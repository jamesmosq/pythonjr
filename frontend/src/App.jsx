import { BrowserRouter, Routes, Route, Navigate } from 'react-router-dom'
import { Suspense, lazy, useEffect } from 'react'
import { useAuthStore } from '@/stores/authStore'
import AppLayout from '@/components/layout/AppLayout'

const Login = lazy(() => import('@/pages/Login'))
const Register = lazy(() => import('@/pages/Register'))
const Dashboard = lazy(() => import('@/pages/Dashboard'))
const Modulo = lazy(() => import('@/pages/Modulo'))
const Ejercicio = lazy(() => import('@/pages/Ejercicio'))
const Billetera = lazy(() => import('@/pages/Billetera'))
const Logros = lazy(() => import('@/pages/Logros'))
const AdminDashboard = lazy(() => import('@/pages/admin/AdminDashboard'))
const Validaciones = lazy(() => import('@/pages/admin/Validaciones'))
const ConfiguracionAdmin = lazy(() => import('@/pages/admin/ConfiguracionAdmin'))
const ActivarHackathon = lazy(() => import('@/pages/admin/ActivarHackathon'))
const Hackathon = lazy(() => import('@/pages/Hackathon'))

function PageLoader() {
  return (
    <div className="min-h-screen bg-[#0d1117] flex items-center justify-center">
      <div className="text-4xl animate-float">🐍</div>
    </div>
  )
}

function RequireAuth({ children, adminOnly = false }) {
  const user = useAuthStore((s) => s.user)
  if (!user) return <Navigate to="/login" replace />
  if (adminOnly && user.role !== 'admin') return <Navigate to="/dashboard" replace />
  // Admin no tiene acceso al área de estudiante
  if (!adminOnly && user.role === 'admin') return <Navigate to="/admin/dashboard" replace />
  return children
}

function RootRedirect() {
  const user = useAuthStore((s) => s.user)
  if (!user) return <Navigate to="/login" replace />
  return <Navigate to={user.role === 'admin' ? '/admin/dashboard' : '/dashboard'} replace />
}

export default function App() {
  const fetchMe = useAuthStore((s) => s.fetchMe)

  useEffect(() => { fetchMe() }, [])

  return (
    <BrowserRouter>
      <Suspense fallback={<PageLoader />}>
        <Routes>
          <Route path="/login" element={<Login />} />
          <Route path="/registro/:token" element={<Register />} />

          <Route element={<RequireAuth><AppLayout /></RequireAuth>}>
            <Route path="/dashboard" element={<Dashboard />} />
            <Route path="/modulos/:slug" element={<Modulo />} />
            <Route path="/ejercicios/:id" element={<Ejercicio />} />
            <Route path="/billetera" element={<Billetera />} />
            <Route path="/logros" element={<Logros />} />
            <Route path="/hackathon" element={<Hackathon />} />
          </Route>

          <Route element={<RequireAuth adminOnly><AppLayout /></RequireAuth>}>
            <Route path="/admin/dashboard" element={<AdminDashboard />} />
            <Route path="/admin/validaciones" element={<Validaciones />} />
            <Route path="/admin/configuracion" element={<ConfiguracionAdmin />} />
            <Route path="/admin/hackathon" element={<ActivarHackathon />} />
          </Route>

          <Route path="/" element={<RootRedirect />} />
          <Route path="*" element={<RootRedirect />} />
        </Routes>
      </Suspense>
    </BrowserRouter>
  )
}
