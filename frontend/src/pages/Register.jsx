import { useState } from 'react'
import { useNavigate, useParams } from 'react-router-dom'
import { motion, AnimatePresence } from 'framer-motion'
import { useAuthStore } from '@/stores/authStore'
import api from '@/lib/axios'

const AVATARES = ['🧑‍🎓', '🧑‍💻', '🦊', '🐱', '🐼', '🦁', '🐸', '🤖', '😎', '🐐']

export default function Register() {
  const { token } = useParams()
  const navigate = useNavigate()

  const [paso, setPaso] = useState(1)

  // Datos del padre
  const [padreName, setPadreName]         = useState('')
  const [padreEmail, setPadreEmail]       = useState('')
  const [padrePassword, setPadrePassword] = useState('')

  // Datos del hijo
  const [hijoName, setHijoName]           = useState('')
  const [hijoEmail, setHijoEmail]         = useState('')
  const [hijoPassword, setHijoPassword]   = useState('')
  const [hijoAvatar, setHijoAvatar]       = useState('🧑‍🎓')

  const [loading, setLoading]     = useState(false)
  const [error, setError]         = useState('')
  const [fieldErrors, setFieldErrors] = useState({})

  function avanzar(e) {
    e.preventDefault()
    setError('')
    if (!padreName || !padreEmail || !padrePassword) {
      setError('Completa todos los campos del papá.')
      return
    }
    if (padrePassword.length < 6) {
      setError('La contraseña del papá debe tener al menos 6 caracteres.')
      return
    }
    setPaso(2)
  }

  async function handleSubmit(e) {
    e.preventDefault()
    setError('')
    setFieldErrors({})
    setLoading(true)

    try {
      const { data } = await api.post('/auth/registro', {
        token,
        padre_name:     padreName,
        padre_email:    padreEmail,
        padre_password: padrePassword,
        hijo_name:      hijoName,
        hijo_email:     hijoEmail,
        hijo_password:  hijoPassword,
        hijo_avatar:    hijoAvatar,
      })

      api.defaults.headers.common['Authorization'] = `Bearer ${data.token}`
      useAuthStore.setState({ user: data.data, token: data.token, initialized: true })
      navigate('/admin/dashboard', { replace: true })
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
          <p className="text-[#8b949e] text-sm mt-1">Registro de nueva familia</p>
        </div>

        {/* Indicador de pasos */}
        <div className="flex items-center gap-2 mb-5">
          {[1, 2].map((n) => (
            <div key={n} className="flex-1 flex items-center gap-2">
              <div
                className={`flex-shrink-0 w-7 h-7 rounded-full flex items-center justify-center text-xs font-black transition-all ${
                  paso >= n
                    ? 'bg-[#a3e635] text-[#0d1117]'
                    : 'bg-[#21262d] text-[#484f58]'
                }`}
              >
                {n}
              </div>
              <span className={`text-xs font-medium ${paso >= n ? 'text-[#a3e635]' : 'text-[#484f58]'}`}>
                {n === 1 ? 'Datos del papá' : 'Datos del hijo'}
              </span>
              {n === 1 && <div className={`flex-1 h-0.5 ${paso >= 2 ? 'bg-[#a3e635]' : 'bg-[#21262d]'}`} />}
            </div>
          ))}
        </div>

        <AnimatePresence mode="wait">
          {paso === 1 ? (
            <motion.div
              key="paso1"
              initial={{ opacity: 0, x: -20 }}
              animate={{ opacity: 1, x: 0 }}
              exit={{ opacity: 0, x: -20 }}
              transition={{ duration: 0.25 }}
              className="bg-[#161b22] border border-[#30363d] rounded-2xl p-6"
            >
              <h2 className="text-base font-bold text-[#e6edf3] mb-5">👨‍💻 Información del papá</h2>

              <form onSubmit={avanzar} className="space-y-4">
                <div>
                  <label className="block text-sm text-[#8b949e] mb-1.5">Tu nombre</label>
                  <input
                    type="text"
                    value={padreName}
                    onChange={(e) => setPadreName(e.target.value)}
                    required
                    autoFocus
                    className="w-full bg-[#21262d] border border-[#30363d] rounded-lg px-3 py-2.5 text-[#e6edf3] placeholder-[#484f58] focus:outline-none focus:border-[#a855f7] transition-colors"
                    placeholder="Tu nombre"
                  />
                </div>

                <div>
                  <label className="block text-sm text-[#8b949e] mb-1.5">Tu email</label>
                  <input
                    type="email"
                    value={padreEmail}
                    onChange={(e) => setPadreEmail(e.target.value)}
                    required
                    className="w-full bg-[#21262d] border border-[#30363d] rounded-lg px-3 py-2.5 text-[#e6edf3] placeholder-[#484f58] focus:outline-none focus:border-[#a855f7] transition-colors"
                    placeholder="tu@email.com"
                  />
                  {fieldErrors.padre_email && (
                    <p className="text-xs text-[#f85149] mt-1">{fieldErrors.padre_email[0]}</p>
                  )}
                </div>

                <div>
                  <label className="block text-sm text-[#8b949e] mb-1.5">Contraseña</label>
                  <input
                    type="password"
                    value={padrePassword}
                    onChange={(e) => setPadrePassword(e.target.value)}
                    required
                    minLength={6}
                    className="w-full bg-[#21262d] border border-[#30363d] rounded-lg px-3 py-2.5 text-[#e6edf3] placeholder-[#484f58] focus:outline-none focus:border-[#a855f7] transition-colors"
                    placeholder="Mínimo 6 caracteres"
                  />
                </div>

                {error && (
                  <div className="bg-[#f85149]/10 border border-[#f85149]/30 rounded-lg px-3 py-2 text-sm text-[#f85149]">
                    {error}
                  </div>
                )}

                <button
                  type="submit"
                  className="w-full py-3 rounded-xl font-black text-white bg-[#a855f7] hover:bg-[#9333ea] transition-all hover:scale-[1.02] active:scale-[0.98]"
                >
                  Continuar →
                </button>
              </form>
            </motion.div>
          ) : (
            <motion.div
              key="paso2"
              initial={{ opacity: 0, x: 20 }}
              animate={{ opacity: 1, x: 0 }}
              exit={{ opacity: 0, x: 20 }}
              transition={{ duration: 0.25 }}
              className="bg-[#161b22] border border-[#30363d] rounded-2xl p-6"
            >
              <h2 className="text-base font-bold text-[#e6edf3] mb-5">🐍 Información del hijo</h2>

              <form onSubmit={handleSubmit} className="space-y-4">
                {/* Avatar picker */}
                <div>
                  <label className="block text-sm text-[#8b949e] mb-2">Avatar del hijo</label>
                  <div className="flex gap-2 flex-wrap">
                    {AVATARES.map((av) => (
                      <button
                        key={av}
                        type="button"
                        onClick={() => setHijoAvatar(av)}
                        className={`text-2xl p-1.5 rounded-xl transition-all ${
                          hijoAvatar === av
                            ? 'bg-[#a3e635]/20 ring-2 ring-[#a3e635] scale-110'
                            : 'bg-[#21262d] hover:bg-[#30363d]'
                        }`}
                      >
                        {av}
                      </button>
                    ))}
                  </div>
                </div>

                <div>
                  <label className="block text-sm text-[#8b949e] mb-1.5">Nombre del hijo</label>
                  <input
                    type="text"
                    value={hijoName}
                    onChange={(e) => setHijoName(e.target.value)}
                    required
                    autoFocus
                    className="w-full bg-[#21262d] border border-[#30363d] rounded-lg px-3 py-2.5 text-[#e6edf3] placeholder-[#484f58] focus:outline-none focus:border-[#a3e635] transition-colors"
                    placeholder="¿Cómo se llama tu hijo?"
                  />
                  {fieldErrors.hijo_name && (
                    <p className="text-xs text-[#f85149] mt-1">{fieldErrors.hijo_name[0]}</p>
                  )}
                </div>

                <div>
                  <label className="block text-sm text-[#8b949e] mb-1.5">Email del hijo</label>
                  <input
                    type="email"
                    value={hijoEmail}
                    onChange={(e) => setHijoEmail(e.target.value)}
                    required
                    className="w-full bg-[#21262d] border border-[#30363d] rounded-lg px-3 py-2.5 text-[#e6edf3] placeholder-[#484f58] focus:outline-none focus:border-[#a3e635] transition-colors"
                    placeholder="email@del.hijo"
                  />
                  {fieldErrors.hijo_email && (
                    <p className="text-xs text-[#f85149] mt-1">{fieldErrors.hijo_email[0]}</p>
                  )}
                </div>

                <div>
                  <label className="block text-sm text-[#8b949e] mb-1.5">Contraseña del hijo</label>
                  <input
                    type="password"
                    value={hijoPassword}
                    onChange={(e) => setHijoPassword(e.target.value)}
                    required
                    minLength={6}
                    className="w-full bg-[#21262d] border border-[#30363d] rounded-lg px-3 py-2.5 text-[#e6edf3] placeholder-[#484f58] focus:outline-none focus:border-[#a3e635] transition-colors"
                    placeholder="Mínimo 6 caracteres"
                  />
                  {fieldErrors.hijo_password && (
                    <p className="text-xs text-[#f85149] mt-1">{fieldErrors.hijo_password[0]}</p>
                  )}
                </div>

                {error && (
                  <div className="bg-[#f85149]/10 border border-[#f85149]/30 rounded-lg px-3 py-2 text-sm text-[#f85149]">
                    {error}
                  </div>
                )}

                <div className="flex gap-3">
                  <button
                    type="button"
                    onClick={() => { setPaso(1); setError('') }}
                    className="flex-1 py-3 rounded-xl font-bold text-[#8b949e] bg-[#21262d] hover:bg-[#30363d] transition-all"
                  >
                    ← Volver
                  </button>
                  <button
                    type="submit"
                    disabled={loading}
                    className="flex-1 py-3 rounded-xl font-black text-[#0d1117] bg-[#a3e635] hover:bg-[#84cc16] disabled:opacity-50 disabled:cursor-not-allowed transition-all hover:scale-[1.02] active:scale-[0.98]"
                  >
                    {loading ? 'Registrando...' : '¡Crear familia! 🚀'}
                  </button>
                </div>
              </form>
            </motion.div>
          )}
        </AnimatePresence>

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
