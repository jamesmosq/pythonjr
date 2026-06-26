import { useState } from 'react'
import { useNavigate } from 'react-router-dom'
import { motion } from 'framer-motion'
import { useAuthStore } from '@/stores/authStore'

export default function SuperAdminLogin() {
  const [email, setEmail]       = useState('')
  const [password, setPassword] = useState('')
  const [error, setError]       = useState('')
  const login   = useAuthStore((s) => s.login)
  const logout  = useAuthStore((s) => s.logout)
  const loading = useAuthStore((s) => s.loading)
  const navigate = useNavigate()

  async function handleSubmit(e) {
    e.preventDefault()
    setError('')

    const result = await login(email, password)

    if (!result.ok) {
      setError(result.message)
      return
    }

    if (result.role !== 'superadmin') {
      await logout()
      setError('Acceso restringido.')
      return
    }

    navigate('/admin/dashboard', { replace: true })
  }

  return (
    <div className="min-h-screen bg-[#0d1117] flex items-center justify-center p-4">
      <motion.div
        className="w-full max-w-xs"
        initial={{ opacity: 0, y: 20 }}
        animate={{ opacity: 1, y: 0 }}
        transition={{ duration: 0.4 }}
      >
        <div className="text-center mb-8">
          <div className="text-5xl mb-3">🐍</div>
          <h1 className="text-2xl font-black text-[#e6edf3]">PythonJr</h1>
          <p className="text-[#484f58] text-xs mt-1 tracking-widest uppercase">Panel de control</p>
        </div>

        <form onSubmit={handleSubmit} className="space-y-4">
          <div>
            <input
              type="email"
              value={email}
              onChange={(e) => setEmail(e.target.value)}
              required
              autoFocus
              className="w-full bg-[#161b22] border border-[#30363d] rounded-lg px-3 py-2.5 text-[#e6edf3] placeholder-[#484f58] focus:outline-none focus:border-[#58a6ff] transition-colors text-sm"
              placeholder="Email"
            />
          </div>

          <div>
            <input
              type="password"
              value={password}
              onChange={(e) => setPassword(e.target.value)}
              required
              className="w-full bg-[#161b22] border border-[#30363d] rounded-lg px-3 py-2.5 text-[#e6edf3] placeholder-[#484f58] focus:outline-none focus:border-[#58a6ff] transition-colors text-sm"
              placeholder="Contraseña"
            />
          </div>

          {error && (
            <div className="bg-[#f85149]/10 border border-[#f85149]/30 rounded-lg px-3 py-2 text-xs text-[#f85149]">
              {error}
            </div>
          )}

          <button
            type="submit"
            disabled={loading}
            className="w-full py-2.5 rounded-lg font-bold text-sm bg-[#21262d] border border-[#30363d] text-[#8b949e] hover:border-[#58a6ff] hover:text-[#58a6ff] disabled:opacity-50 disabled:cursor-not-allowed transition-all"
          >
            {loading ? '...' : 'Acceder'}
          </button>
        </form>
      </motion.div>
    </div>
  )
}
