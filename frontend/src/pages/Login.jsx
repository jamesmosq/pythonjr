import { useState } from 'react'
import { useNavigate } from 'react-router-dom'
import { motion, AnimatePresence } from 'framer-motion'
import { useAuthStore } from '@/stores/authStore'

export default function Login() {
  const [modo, setModo] = useState('estudiante') // 'estudiante' | 'papa'
  const [email, setEmail] = useState('')
  const [password, setPassword] = useState('')
  const [error, setError] = useState('')
  const login   = useAuthStore((s) => s.login)
  const logout  = useAuthStore((s) => s.logout)
  const loading = useAuthStore((s) => s.loading)
  const navigate = useNavigate()

  const esAdmin = modo === 'papa'
  const accentColor = esAdmin ? '#a855f7' : '#a3e635'
  const accentHover = esAdmin ? '#9333ea' : '#84cc16'

  async function handleSubmit(e) {
    e.preventDefault()
    setError('')
    const result = await login(email, password)
    if (!result.ok) {
      setError(result.message)
      return
    }

    const rolRecibido   = result.role
    const esRolAdmin    = (r) => r === 'admin' || r === 'superadmin'

    if (esAdmin && !esRolAdmin(rolRecibido)) {
      await logout()
      setError('Esas credenciales son de estudiante. Selecciona "Soy estudiante" para entrar.')
      return
    }
    if (!esAdmin && esRolAdmin(rolRecibido)) {
      await logout()
      setError('Esas credenciales son de administrador. Selecciona "Soy papá" para entrar.')
      return
    }

    navigate(esRolAdmin(rolRecibido) ? '/admin/dashboard' : '/dashboard', { replace: true })
  }

  function cambiarModo(nuevoModo) {
    setModo(nuevoModo)
    setEmail('')
    setPassword('')
    setError('')
  }

  return (
    <div className="min-h-screen bg-[#0d1117] flex items-center justify-center p-4">
      <motion.div
        className="w-full max-w-sm"
        initial={{ opacity: 0, y: 30 }}
        animate={{ opacity: 1, y: 0 }}
        transition={{ duration: 0.5 }}
      >
        {/* Logo */}
        <div className="text-center mb-6">
          <AnimatePresence mode="wait">
            <motion.div
              key={modo}
              className="text-7xl mb-3 inline-block"
              initial={{ scale: 0.5, rotate: -20, opacity: 0 }}
              animate={{ scale: 1, rotate: 0, opacity: 1 }}
              exit={{ scale: 0.5, rotate: 20, opacity: 0 }}
              transition={{ duration: 0.3 }}
            >
              {esAdmin ? '👨‍💻' : '🐍'}
            </motion.div>
          </AnimatePresence>
          <h1 className="text-3xl font-black" style={{ color: accentColor }}>PythonJr</h1>
          <AnimatePresence mode="wait">
            <motion.p
              key={modo}
              className="text-[#8b949e] text-sm mt-1"
              initial={{ opacity: 0, y: 5 }}
              animate={{ opacity: 1, y: 0 }}
              exit={{ opacity: 0, y: -5 }}
              transition={{ duration: 0.2 }}
            >
              {esAdmin ? 'Panel de administración · Papá' : 'Tu aventura de programación'}
            </motion.p>
          </AnimatePresence>
        </div>

        {/* Selector de modo */}
        <div className="flex bg-[#161b22] border border-[#30363d] rounded-2xl p-1 mb-4">
          <button
            type="button"
            onClick={() => cambiarModo('estudiante')}
            className={`flex-1 flex items-center justify-center gap-1.5 py-2.5 rounded-xl text-sm font-bold transition-all ${
              !esAdmin
                ? 'bg-[#a3e635] text-[#0d1117] shadow-lg'
                : 'text-[#8b949e] hover:text-[#e6edf3]'
            }`}
          >
            🐍 Soy estudiante
          </button>
          <button
            type="button"
            onClick={() => cambiarModo('papa')}
            className={`flex-1 flex items-center justify-center gap-1.5 py-2.5 rounded-xl text-sm font-bold transition-all ${
              esAdmin
                ? 'bg-[#a855f7] text-white shadow-lg'
                : 'text-[#8b949e] hover:text-[#e6edf3]'
            }`}
          >
            👨‍💻 Soy papá
          </button>
        </div>

        {/* Card */}
        <AnimatePresence mode="wait">
          <motion.div
            key={modo}
            className="rounded-2xl p-6"
            style={{
              backgroundColor: '#161b22',
              border: `1px solid ${esAdmin ? '#a855f740' : '#30363d'}`,
            }}
            initial={{ opacity: 0, x: esAdmin ? 20 : -20 }}
            animate={{ opacity: 1, x: 0 }}
            exit={{ opacity: 0, x: esAdmin ? -20 : 20 }}
            transition={{ duration: 0.25 }}
          >
            <h2 className="text-base font-bold text-[#e6edf3] mb-5">
              {esAdmin ? '🔐 Acceso de administrador' : '🚀 ¡Vamos a programar!'}
            </h2>

            <form onSubmit={handleSubmit} className="space-y-4">
              <div>
                <label className="block text-sm text-[#8b949e] mb-1.5">Email</label>
                <input
                  type="email"
                  value={email}
                  onChange={(e) => setEmail(e.target.value)}
                  required
                  autoFocus
                  className="w-full bg-[#21262d] border border-[#30363d] rounded-lg px-3 py-2.5 text-[#e6edf3] placeholder-[#484f58] focus:outline-none transition-colors"
                  onFocus={(e) => (e.target.style.borderColor = accentColor)}
                  onBlur={(e) => (e.target.style.borderColor = '#30363d')}
                  placeholder="tu@email.com"
                />
              </div>

              <div>
                <label className="block text-sm text-[#8b949e] mb-1.5">Contraseña</label>
                <input
                  type="password"
                  value={password}
                  onChange={(e) => setPassword(e.target.value)}
                  required
                  className="w-full bg-[#21262d] border border-[#30363d] rounded-lg px-3 py-2.5 text-[#e6edf3] placeholder-[#484f58] focus:outline-none transition-colors"
                  onFocus={(e) => (e.target.style.borderColor = accentColor)}
                  onBlur={(e) => (e.target.style.borderColor = '#30363d')}
                  placeholder="••••••••"
                />
              </div>

              {error && (
                <div className="bg-[#f85149]/10 border border-[#f85149]/30 rounded-lg px-3 py-2 text-sm text-[#f85149]">
                  {error}
                </div>
              )}

              <button
                type="submit"
                disabled={loading}
                className="w-full py-3 rounded-xl font-black disabled:opacity-50 disabled:cursor-not-allowed transition-all hover:scale-[1.02] active:scale-[0.98]"
                style={{
                  backgroundColor: accentColor,
                  color: esAdmin ? '#ffffff' : '#0d1117',
                }}
                onMouseEnter={(e) => { if (!loading) e.currentTarget.style.backgroundColor = accentHover }}
                onMouseLeave={(e) => { e.currentTarget.style.backgroundColor = accentColor }}
              >
                {loading
                  ? 'Entrando...'
                  : esAdmin
                  ? 'Entrar al panel 🔐'
                  : '¡Entrar a programar! 🚀'}
              </button>
            </form>
          </motion.div>
        </AnimatePresence>

        <p className="text-center text-xs text-[#484f58] mt-4">
          {esAdmin
            ? 'El sistema detectará tu rol automáticamente al iniciar sesión'
            : '¿Eres papá? Cambia al modo administrador arriba'}
        </p>
      </motion.div>
    </div>
  )
}
