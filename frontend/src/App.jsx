import { BrowserRouter, Routes, Route, Navigate } from 'react-router-dom'
import { Suspense, lazy, useEffect } from 'react'
import { useAuthStore } from '@/stores/authStore'
import AppLayout from '@/components/layout/AppLayout'

const Login = lazy(() => import('@/pages/Login'))
const Dashboard = lazy(() => import('@/pages/Dashboard'))
const Modulo = lazy(() => import('@/pages/Modulo'))
const Ejercicio = lazy(() => import('@/pages/Ejercicio'))
const Billetera = lazy(() => import('@/pages/Billetera'))
const Logros = lazy(() => import('@/pages/Logros'))
const AdminDashboard = lazy(() => import('@/pages/admin/AdminDashboard'))
const Validaciones = lazy(() => import('@/pages/admin/Validaciones'))

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
  return children
}

export default function App() {
  const fetchMe = useAuthStore((s) => s.fetchMe)

  useEffect(() => { fetchMe() }, [])

  return (
    <BrowserRouter>
      <Suspense fallback={<PageLoader />}>
        <Routes>
          <Route path="/login" element={<Login />} />

          <Route element={<RequireAuth><AppLayout /></RequireAuth>}>
            <Route path="/dashboard" element={<Dashboard />} />
            <Route path="/modulos/:slug" element={<Modulo />} />
            <Route path="/ejercicios/:id" element={<Ejercicio />} />
            <Route path="/billetera" element={<Billetera />} />
            <Route path="/logros" element={<Logros />} />
          </Route>

          <Route element={<RequireAuth adminOnly><AppLayout /></RequireAuth>}>
            <Route path="/admin/dashboard" element={<AdminDashboard />} />
            <Route path="/admin/validaciones" element={<Validaciones />} />
          </Route>

          <Route path="/" element={<Navigate to="/dashboard" replace />} />
          <Route path="*" element={<Navigate to="/dashboard" replace />} />
        </Routes>
      </Suspense>
    </BrowserRouter>
  )
}
