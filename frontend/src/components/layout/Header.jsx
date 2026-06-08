import { useAuthStore } from '@/stores/authStore'
import { useBilleteraStore } from '@/stores/billeteraStore'
import { useNavigate } from 'react-router-dom'

export default function Header({ racha = 0, xp = 0, xpMax = 100 }) {
  const user = useAuthStore((s) => s.user)
  const logout = useAuthStore((s) => s.logout)
  const saldo = useBilleteraStore((s) => s.saldo_total)
  const navigate = useNavigate()

  const xpPct = Math.min((xp / xpMax) * 100, 100)

  async function handleLogout() {
    await logout()
    navigate('/login')
  }

  return (
    <header className="sticky top-0 z-40 border-b border-[#30363d] bg-[#0d1117]/95 backdrop-blur">
      <div className="flex items-center justify-between px-4 py-3 max-w-5xl mx-auto">
        {/* Logo */}
        <button
          onClick={() => navigate('/dashboard')}
          className="flex items-center gap-2 font-bold text-lg text-[#a3e635] hover:opacity-80 transition-opacity"
        >
          <span className="text-2xl">🐍</span>
          <span className="hidden sm:inline">PythonJr</span>
        </button>

        {/* Centro — XP bar + racha */}
        <div className="flex items-center gap-4 flex-1 mx-4 max-w-sm">
          {/* XP Bar */}
          <div className="flex-1">
            <div className="flex items-center justify-between mb-1">
              <span className="text-xs text-[#8b949e]">XP</span>
              <span className="text-xs text-[#a3e635] font-mono">{xp}/{xpMax}</span>
            </div>
            <div className="h-2 bg-[#21262d] rounded-full overflow-hidden">
              <div
                className="h-full bg-gradient-to-r from-[#84cc16] to-[#a3e635] rounded-full transition-all duration-700"
                style={{ width: `${xpPct}%` }}
              />
            </div>
          </div>

          {/* Racha */}
          {racha > 0 && (
            <div className="flex items-center gap-1 bg-[#161b22] border border-[#30363d] rounded-lg px-2 py-1">
              <span className="text-orange-400 text-base">🔥</span>
              <span className="text-sm font-bold text-[#e6edf3]">{racha}</span>
            </div>
          )}
        </div>

        {/* Derecha — billetera + avatar */}
        <div className="flex items-center gap-3">
          <button
            onClick={() => navigate('/billetera')}
            className="flex items-center gap-1.5 bg-[#161b22] border border-[#30363d] rounded-lg px-3 py-1.5 hover:border-[#a3e635] transition-colors"
          >
            <span className="text-[#a3e635] text-sm">💰</span>
            <span className="text-sm font-bold text-[#e6edf3]">
              ${saldo.toLocaleString('es-CO')}
            </span>
          </button>

          <button
            onClick={handleLogout}
            title="Cerrar sesión"
            className="text-xl hover:opacity-70 transition-opacity"
          >
            {user?.avatar ?? '👤'}
          </button>
        </div>
      </div>
    </header>
  )
}
