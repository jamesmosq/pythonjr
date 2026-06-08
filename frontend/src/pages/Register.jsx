import { useState } from 'react'
import { useNavigate, useParams } from 'react-router-dom'
import { motion } from 'framer-motion'
import { useAuthStore } from '@/stores/authStore'
import api, { initCsrf } from '@/lib/axios'

const AVATARES = ['🧑‍🎓', '🧑‍💻', '🦊', '🐱', '🐼', '🦁', '🐸', '🤖','😎','🐐']

export default function Register() {
  const { token } = useParams()
  const navigate = useNavigate()

  const [name, setName] = useState('')
  const [email, setEmail] = useState('')
  const [password, setPassword] = useState('')
  const [passwordConfirmation, setPasswordConfirmation] = useState('')
  const [avatar, setAvatar] = useState('🧑‍🎓')
  const [loading, setLoading] = useState(false)
  const [error, setError] = useState('')
  const [fieldErrors, setFieldErrors] = useState({})

  async function handleSubmit(e) {
    e.preventDefault()
    setError('')
    setFieldErrors({})
    setLoading(true)

    try {
      await initCsrf()
      const { data } = await api.post('/auth/registro', {
        token,
        name,
        email,
        password,
        password_confirmation: passwordConfirmation,
        avatar,
      })

      // Guardar usuario en el store y redirigir
      useAuthStore.setState({ user: data.data })
      navigate('/dashboard', { replace: true })
    } catch (err) {
      if (err.response?.status === 403) {
        setError('Esta URL de registro no es válida.')
      } else if (err.response?.status === 422) {
        const errs = err.response.data?.errors ?? {}
        setFieldErrors(errs)
        setError(err.response.data?.message ?? 'Revisa los campos.')
      } else {
        setError('Error al registrarse. Intenta de nuevo.')
      }
    } finally {
      setLoading(false)
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
        <div className="text-center mb-6">
          <motion.div
            className="text-7xl mb-3 inline-block"
            animate={{ rotate: [0, 5, -5, 0] }}
            transition={{ duration: 2, repeat: Infinity, repeatDelay: 3 }}
          >
            🐍
          </motion.div>
          <h1 className="text-3xl font-black text-[#a3e635]">PythonJr</h1>
          <p className="text-[#8b949e] text-sm mt-1">Crea tu cuenta de estudiante</p>
        </div>

        {/* Card */}
        <div className="bg-[#161b22] border border-[#30363d] rounded-2xl p-6">
          <h2 className="text-base font-bold text-[#e6edf3] mb-5">🎮 Únete a la aventura</h2>

          <form onSubmit={handleSubmit} className="space-y-4">
            {/* Avatar picker */}
            <div>
              <label className="block text-sm text-[#8b949e] mb-2">Elige tu avatar</label>
              <div className="flex gap-2 flex-wrap">
                {AVATARES.map((av) => (
                  <button
                    key={av}
                    type="button"
                    onClick={() => setAvatar(av)}
                    className={`text-2xl p-1.5 rounded-xl transition-all ${
                      avatar === av
                        ? 'bg-[#a3e635]/20 ring-2 ring-[#a3e635] scale-110'
                        : 'bg-[#21262d] hover:bg-[#30363d]'
                    }`}
                  >
                    {av}
                  </button>
                ))}
              </div>
            </div>

            {/* Nombre */}
            <div>
              <label className="block text-sm text-[#8b949e] mb-1.5">Tu nombre</label>
              <input
                type="text"
                value={name}
                onChange={(e) => setName(e.target.value)}
                required
                autoFocus
                className="w-full bg-[#21262d] border border-[#30363d] rounded-lg px-3 py-2.5 text-[#e6edf3] placeholder-[#484f58] focus:outline-none focus:border-[#a3e635] transition-colors"
                placeholder="¿Cómo te llamas?"
              />
              {fieldErrors.name && (
                <p className="text-xs text-[#f85149] mt-1">{fieldErrors.name[0]}</p>
              )}
            </div>

            {/* Email */}
            <div>
              <label className="block text-sm text-[#8b949e] mb-1.5">Email</label>
              <input
                type="email"
                value={email}
                onChange={(e) => setEmail(e.target.value)}
                required
                className="w-full bg-[#21262d] border border-[#30363d] rounded-lg px-3 py-2.5 text-[#e6edf3] placeholder-[#484f58] focus:outline-none focus:border-[#a3e635] transition-colors"
                placeholder="tu@email.com"
              />
              {fieldErrors.email && (
                <p className="text-xs text-[#f85149] mt-1">{fieldErrors.email[0]}</p>
              )}
            </div>

            {/* Password */}
            <div>
              <label className="block text-sm text-[#8b949e] mb-1.5">Contraseña</label>
              <input
                type="password"
                value={password}
                onChange={(e) => setPassword(e.target.value)}
                required
                minLength={6}
                className="w-full bg-[#21262d] border border-[#30363d] rounded-lg px-3 py-2.5 text-[#e6edf3] placeholder-[#484f58] focus:outline-none focus:border-[#a3e635] transition-colors"
                placeholder="Mínimo 6 caracteres"
              />
              {fieldErrors.password && (
                <p className="text-xs text-[#f85149] mt-1">{fieldErrors.password[0]}</p>
              )}
            </div>

            {/* Confirmar password */}
            <div>
              <label className="block text-sm text-[#8b949e] mb-1.5">Confirmar contraseña</label>
              <input
                type="password"
                value={passwordConfirmation}
                onChange={(e) => setPasswordConfirmation(e.target.value)}
                required
                className="w-full bg-[#21262d] border border-[#30363d] rounded-lg px-3 py-2.5 text-[#e6edf3] placeholder-[#484f58] focus:outline-none focus:border-[#a3e635] transition-colors"
                placeholder="Repite tu contraseña"
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
              className="w-full py-3 rounded-xl font-black text-[#0d1117] bg-[#a3e635] hover:bg-[#84cc16] disabled:opacity-50 disabled:cursor-not-allowed transition-all hover:scale-[1.02] active:scale-[0.98]"
            >
              {loading ? 'Creando cuenta...' : '¡Crear mi cuenta! 🚀'}
            </button>
          </form>
        </div>

        <p className="text-center text-xs text-[#484f58] mt-4">
          ¿Ya tienes cuenta?{' '}
          <a href="/login" className="text-[#a3e635] hover:underline">
            Inicia sesión
          </a>
        </p>
      </motion.div>
    </div>
  )
}
