import { BrowserRouter, Routes, Route, Navigate } from 'react-router-dom'
import { Suspense, lazy, useEffect } from 'react'
import { useAuthStore } from '@/stores/authStore'
import AppLayout from '@/components/layout/AppLayout'
import SuperAdminLayout from '@/components/layout/SuperAdminLayout'

const Login               = lazy(() => import('@/pages/Login'))
const Register            = lazy(() => import('@/pages/Register'))
const Dashboard           = lazy(() => import('@/pages/Dashboard'))
const Modulo              = lazy(() => import('@/pages/Modulo'))
const Ejercicio           = lazy(() => import('@/pages/Ejercicio'))
const Billetera           = lazy(() => import('@/pages/Billetera'))
const Logros              = lazy(() => import('@/pages/Logros'))
const AdminDashboard      = lazy(() => import('@/pages/admin/AdminDashboard'))
const Validaciones        = lazy(() => import('@/pages/admin/Validaciones'))
const ConfiguracionAdmin  = lazy(() => import('@/pages/admin/ConfiguracionAdmin'))
const ActivarHackathon    = lazy(() => import('@/pages/admin/ActivarHackathon'))
const Hackathon           = lazy(() => import('@/pages/Hackathon'))
const SuperAdminLogin     = lazy(() => import('@/pages/SuperAdminLogin'))
const SuperAdminDashboard = lazy(() => import('@/pages/superadmin/SuperAdminDashboard'))

function PageLoader() {
  return (
    <div className="min-h-screen bg-[#0d1117] flex items-center justify-center">
      <div className="text-4xl animate-float">🐍</div>
    </div>
  )
}

// Guarda para rutas de estudiante
function RequireEstudiante({ children }) {
  const user = useAuthStore((s) => s.user)
  if (!user) return <Navigate to="/login" replace />
  if (user.role === 'superadmin') return <Navigate to="/superadmin/dashboard" replace />
  if (user.role === 'admin')      return <Navigate to="/admin/dashboard" replace />
  return children
}

// Guarda para rutas de admin (papá)
function RequireAdmin({ children }) {
  const user = useAuthStore((s) => s.user)
  if (!user) return <Navigate to="/login" replace />
  if (user.role === 'superadmin') return <Navigate to="/superadmin/dashboard" replace />
  if (user.role !== 'admin')      return <Navigate to="/dashboard" replace />
  return children
}

// Guarda para rutas exclusivas de superadmin
function RequireSuperAdmin({ children }) {
  const user = useAuthStore((s) => s.user)
  if (!user)                      return <Navigate to="/acceso" replace />
  if (user.role !== 'superadmin') return <Navigate to="/login" replace />
  return children
}

function RootRedirect() {
  const user = useAuthStore((s) => s.user)
  if (!user) return <Navigate to="/login" replace />
  if (user.role === 'superadmin') return <Navigate to="/superadmin/dashboard" replace />
  if (user.role === 'admin')      return <Navigate to="/admin/dashboard" replace />
  return <Navigate to="/dashboard" replace />
}

export default function App() {
  const fetchMe     = useAuthStore((s) => s.fetchMe)
  const initialized = useAuthStore((s) => s.initialized)

  useEffect(() => { fetchMe() }, [])

  if (!initialized) return <PageLoader />

  return (
    <BrowserRouter>
      <Suspense fallback={<PageLoader />}>
        <Routes>
          {/* Públicas */}
          <Route path="/login"           element={<Login />} />
          <Route path="/acceso"          element={<SuperAdminLogin />} />
          <Route path="/registro/:token" element={<Register />} />

          {/* Rutas de estudiante */}
          <Route element={<RequireEstudiante><AppLayout /></RequireEstudiante>}>
            <Route path="/dashboard"          element={<Dashboard />} />
            <Route path="/modulos/:slug"      element={<Modulo />} />
            <Route path="/ejercicios/:id"     element={<Ejercicio />} />
            <Route path="/billetera"          element={<Billetera />} />
            <Route path="/logros"             element={<Logros />} />
            <Route path="/hackathon"          element={<Hackathon />} />
          </Route>

          {/* Rutas de admin (papá) */}
          <Route element={<RequireAdmin><AppLayout /></RequireAdmin>}>
            <Route path="/admin/dashboard"    element={<AdminDashboard />} />
            <Route path="/admin/validaciones" element={<Validaciones />} />
            <Route path="/admin/configuracion" element={<ConfiguracionAdmin />} />
            <Route path="/admin/hackathon"    element={<ActivarHackathon />} />
          </Route>

          {/* Rutas de superadmin */}
          <Route element={<RequireSuperAdmin><SuperAdminLayout /></RequireSuperAdmin>}>
            <Route path="/superadmin/dashboard" element={<SuperAdminDashboard />} />
          </Route>

          <Route path="/"  element={<RootRedirect />} />
          <Route path="*"  element={<RootRedirect />} />
        </Routes>
      </Suspense>
    </BrowserRouter>
  )
}
