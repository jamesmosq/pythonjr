import { Outlet, useNavigate } from 'react-router-dom'
import { useAuthStore } from '@/stores/authStore'

export default function SuperAdminLayout() {
  const user    = useAuthStore((s) => s.user)
  const logout  = useAuthStore((s) => s.logout)
  const navigate = useNavigate()

  async function handleLogout() {
    await logout()
    navigate('/acceso')
  }

  return (
    <div className="min-h-screen bg-[#0d1117] flex flex-col">
      <header className="sticky top-0 z-40 border-b border-[#30363d] bg-[#0d1117]/95 backdrop-blur">
        <div className="flex items-center justify-between px-4 py-3 max-w-6xl mx-auto">
          <div className="flex items-center gap-3">
            <span className="text-2xl">🐍</span>
            <div>
              <span className="font-black text-[#e6edf3] text-lg">PythonJr</span>
              <span className="text-xs text-[#484f58] ml-2">Panel de plataforma</span>
            </div>
          </div>
          <div className="flex items-center gap-3">
            <span className="text-sm text-[#8b949e]">{user?.name}</span>
            <button
              onClick={handleLogout}
              title="Cerrar sesión"
              className="text-xl hover:opacity-70 transition-opacity"
            >
              {user?.avatar ?? '🦸'}
            </button>
          </div>
        </div>
      </header>
      <main className="flex-1 max-w-6xl w-full mx-auto px-4 py-6">
        <Outlet />
      </main>
    </div>
  )
}
