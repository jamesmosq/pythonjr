import { useState } from 'react'
import { useNavigate } from 'react-router-dom'
import { motion } from 'framer-motion'
import { useAuthStore } from '@/stores/authStore'

export default function Login() {
  const [email, setEmail] = useState('')
  const [password, setPassword] = useState('')
  const [error, setError] = useState('')
  const login = useAuthStore((s) => s.login)
  const loading = useAuthStore((s) => s.loading)
  const navigate = useNavigate()

  async function handleSubmit(e) {
    e.preventDefault()
    setError('')
    const result = await login(email, password)
    if (result.ok) {
      navigate('/dashboard', { replace: true })
    } else {
      setError(result.message)
    }
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
        <div className="text-center mb-8">
          <motion.div
            className="text-7xl mb-3 inline-block animate-float"
            animate={{ rotate: [0, 5, -5, 0] }}
            transition={{ duration: 2, repeat: Infinity, repeatDelay: 3 }}
          >
            🐍
          </motion.div>
          <h1 className="text-3xl font-black text-[#a3e635]">PythonJr</h1>
          <p className="text-[#8b949e] text-sm mt-1">Tu aventura de programación</p>
        </div>

        {/* Card */}
        <div className="bg-[#161b22] border border-[#30363d] rounded-2xl p-6">
          <h2 className="text-lg font-bold text-[#e6edf3] mb-5">Iniciar sesión</h2>

          <form onSubmit={handleSubmit} className="space-y-4">
            <div>
              <label className="block text-sm text-[#8b949e] mb-1.5">Email</label>
              <input
                type="email"
                value={email}
                onChange={(e) => setEmail(e.target.value)}
                required
                autoFocus
                className="w-full bg-[#21262d] border border-[#30363d] rounded-lg px-3 py-2.5 text-[#e6edf3] placeholder-[#484f58] focus:outline-none focus:border-[#a3e635] transition-colors"
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
                className="w-full bg-[#21262d] border border-[#30363d] rounded-lg px-3 py-2.5 text-[#e6edf3] placeholder-[#484f58] focus:outline-none focus:border-[#a3e635] transition-colors"
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
              className="w-full py-3 rounded-xl font-bold text-[#0d1117] bg-[#a3e635] hover:bg-[#84cc16] disabled:opacity-50 disabled:cursor-not-allowed transition-all hover:scale-[1.02] active:scale-[0.98]"
            >
              {loading ? 'Entrando...' : '¡Entrar a programar! 🚀'}
            </button>
          </form>
        </div>

        <p className="text-center text-xs text-[#484f58] mt-4">
          ¿Eres papá? Entra con tu cuenta de administrador
        </p>
      </motion.div>
    </div>
  )
}
