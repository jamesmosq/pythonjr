import { useEffect, useState } from 'react'
import { motion } from 'framer-motion'
import api from '@/lib/axios'
import { useAuthStore } from '@/stores/authStore'

const AVATARES_ADMIN = ['👨‍💻', '👩‍💻', '🧑‍💻', '👨‍🏫', '👩‍🏫', '👨‍👦', '👩‍👦', '🦸', '💪', '🧠']

const descripcionLabel = {
  bono_racha_3d:              'Bono racha 3 días (COP)',
  bono_racha_7d:              'Bono racha 7 días (COP)',
  multiplicador_finde_activo: 'Fin de semana 2x activo',
  multiplicador_finde_factor: 'Factor multiplicador',
  meta_semanal_activa:        'Meta semanal activa',
  meta_semanal_ejercicios:    'Ejercicios para meta semanal',
  meta_semanal_recompensa:    'Recompensa meta semanal (COP)',
}

export default function ConfiguracionAdmin() {
  const adminUser     = useAuthStore((s) => s.user)
  const updateProfile = useAuthStore((s) => s.updateProfile)

  // Perfil
  const [perfilNombre,   setPerfilNombre]   = useState('')
  const [perfilEmail,    setPerfilEmail]    = useState('')
  const [perfilAvatar,   setPerfilAvatar]   = useState('')
  const [perfilPassword, setPerfilPassword] = useState('')
  const [guardandoPerfil, setGuardandoPerfil] = useState(false)
  const [okPerfil, setOkPerfil] = useState(false)
  const [errorPerfil, setErrorPerfil] = useState('')

  // Config plataforma
  const [config, setConfig] = useState(null)
  const [local, setLocal]   = useState({})
  const [guardando, setGuardando] = useState(false)
  const [ok, setOk] = useState(false)

  // Bono sorpresa
  const [monto, setMonto] = useState('')
  const [mensaje, setMensaje] = useState('')
  const [enviandoBono, setEnviandoBono] = useState(false)
  const [bonos, setBonos] = useState([])
  const [estudiantes, setEstudiantes] = useState([])
  const [estudianteId, setEstudianteId] = useState(null)

  useEffect(() => {
    if (adminUser) {
      setPerfilNombre(adminUser.name)
      setPerfilEmail(adminUser.email)
      setPerfilAvatar(adminUser.avatar ?? '👨‍💻')
    }
    api.get('/admin/configuracion').then(({ data }) => {
      setConfig(data.data)
      const vals = {}
      Object.entries(data.data).forEach(([k, v]) => { vals[k] = v.valor })
      setLocal(vals)
    })
    api.get('/admin/estudiantes').then(({ data }) => {
      setEstudiantes(data.data)
      if (data.data.length > 0) setEstudianteId(data.data[0].id)
    })
    api.get('/admin/bono-sorpresa/historial').then(({ data }) => setBonos(data.data)).catch(() => {})
  }, [])

  async function guardarPerfil() {
    setGuardandoPerfil(true)
    setErrorPerfil('')
    setOkPerfil(false)
    try {
      const payload = { name: perfilNombre, email: perfilEmail, avatar: perfilAvatar }
      if (perfilPassword) {
        payload.password = perfilPassword
        payload.password_confirmation = perfilPassword
      }
      const { data } = await api.put('/admin/perfil', payload)
      updateProfile(data.data)
      setOkPerfil(true)
      setPerfilPassword('')
      setTimeout(() => setOkPerfil(false), 3000)
    } catch (err) {
      setErrorPerfil(err.response?.data?.message ?? 'Error al guardar')
    } finally {
      setGuardandoPerfil(false)
    }
  }

  async function guardar() {
    setGuardando(true)
    setOk(false)
    try {
      await api.put('/admin/configuracion', { configuracion: local })
      setOk(true)
      setTimeout(() => setOk(false), 3000)
    } finally {
      setGuardando(false)
    }
  }

  async function enviarBono() {
    if (!monto || !estudianteId) return
    setEnviandoBono(true)
    try {
      await api.post('/admin/bono-sorpresa', { user_id: estudianteId, monto: parseInt(monto), mensaje })
      setBonos((prev) => [{ monto: parseInt(monto), mensaje, created_at: new Date().toISOString() }, ...prev])
      setMonto('')
      setMensaje('')
    } finally {
      setEnviandoBono(false)
    }
  }

  function cambiar(clave, valor) {
    setLocal((prev) => ({ ...prev, [clave]: valor }))
  }

  if (!config) return (
    <div className="flex justify-center py-20"><div className="text-4xl animate-float">⚙️</div></div>
  )

  const boolClaves  = Object.entries(config).filter(([, v]) => v.tipo === 'boolean')
  const numberClaves = Object.entries(config).filter(([, v]) => v.tipo !== 'boolean')

  const estudianteSeleccionado = estudiantes.find((e) => e.id === estudianteId)

  return (
    <div className="max-w-lg mx-auto space-y-5">
      <h1 className="text-xl font-black text-[#e6edf3]">⚙️ Configuración</h1>

      {/* Mi perfil */}
      <div className="bg-[#161b22] border border-[#58a6ff]/30 rounded-2xl p-5 space-y-4">
        <h2 className="text-sm font-semibold text-[#8b949e] uppercase tracking-wide">👤 Mi perfil</h2>

        {/* Avatar */}
        <div>
          <label className="block text-xs text-[#8b949e] mb-2">Avatar</label>
          <div className="flex gap-2 flex-wrap">
            {AVATARES_ADMIN.map((av) => (
              <button
                key={av}
                type="button"
                onClick={() => setPerfilAvatar(av)}
                className={`text-2xl p-1.5 rounded-xl transition-all ${
                  perfilAvatar === av
                    ? 'bg-[#58a6ff]/20 ring-2 ring-[#58a6ff] scale-110'
                    : 'bg-[#21262d] hover:bg-[#30363d]'
                }`}
              >
                {av}
              </button>
            ))}
          </div>
        </div>

        {/* Nombre */}
        <div className="grid grid-cols-2 gap-3">
          <div>
            <label className="block text-xs text-[#8b949e] mb-1.5">Nombre (Papá / Mamá / ...)</label>
            <input
              type="text"
              value={perfilNombre}
              onChange={(e) => setPerfilNombre(e.target.value)}
              className="w-full bg-[#21262d] border border-[#30363d] rounded-xl px-3 py-2 text-[#e6edf3] focus:outline-none focus:border-[#58a6ff] text-sm"
              placeholder="Papá"
            />
          </div>
          <div>
            <label className="block text-xs text-[#8b949e] mb-1.5">Email</label>
            <input
              type="email"
              value={perfilEmail}
              onChange={(e) => setPerfilEmail(e.target.value)}
              className="w-full bg-[#21262d] border border-[#30363d] rounded-xl px-3 py-2 text-[#e6edf3] focus:outline-none focus:border-[#58a6ff] text-sm"
            />
          </div>
        </div>

        {/* Nueva contraseña (opcional) */}
        <div>
          <label className="block text-xs text-[#8b949e] mb-1.5">Nueva contraseña (dejar vacío para no cambiar)</label>
          <input
            type="password"
            value={perfilPassword}
            onChange={(e) => setPerfilPassword(e.target.value)}
            className="w-full bg-[#21262d] border border-[#30363d] rounded-xl px-3 py-2 text-[#e6edf3] placeholder-[#484f58] focus:outline-none focus:border-[#58a6ff] text-sm"
            placeholder="••••••••"
          />
        </div>

        {errorPerfil && (
          <p className="text-xs text-[#f85149]">{errorPerfil}</p>
        )}

        <button
          onClick={guardarPerfil}
          disabled={guardandoPerfil}
          className="w-full py-2.5 bg-[#58a6ff] text-[#0d1117] font-bold rounded-xl hover:bg-blue-400 disabled:opacity-40 transition-all text-sm"
        >
          {guardandoPerfil ? 'Guardando...' : okPerfil ? '✅ Perfil actualizado' : 'Guardar perfil'}
        </button>
      </div>

      {/* Switches booleanos */}
      <div className="bg-[#161b22] border border-[#30363d] rounded-2xl p-5 space-y-4">
        <h2 className="text-sm font-semibold text-[#8b949e] uppercase tracking-wide">Activadores</h2>
        {boolClaves.map(([clave, meta]) => (
          <div key={clave} className="flex items-center justify-between">
            <div>
              <div className="text-sm text-[#e6edf3] font-medium">{descripcionLabel[clave] ?? clave}</div>
              <div className="text-xs text-[#484f58]">{meta.descripcion}</div>
            </div>
            <button
              onClick={() => cambiar(clave, !local[clave])}
              className={`w-12 h-6 rounded-full transition-all relative ${local[clave] ? 'bg-[#a3e635]' : 'bg-[#30363d]'}`}
            >
              <motion.div
                className="absolute top-1 w-4 h-4 bg-white rounded-full shadow"
                animate={{ left: local[clave] ? '1.5rem' : '0.25rem' }}
                transition={{ type: 'spring', stiffness: 500, damping: 30 }}
              />
            </button>
          </div>
        ))}
      </div>

      {/* Valores numéricos */}
      <div className="bg-[#161b22] border border-[#30363d] rounded-2xl p-5 space-y-3">
        <h2 className="text-sm font-semibold text-[#8b949e] uppercase tracking-wide">Valores</h2>
        {numberClaves.map(([clave, meta]) => (
          <div key={clave}>
            <label className="text-xs text-[#8b949e] block mb-1">{descripcionLabel[clave] ?? clave}</label>
            <input
              type="number"
              value={local[clave] ?? ''}
              onChange={(e) => cambiar(clave, e.target.value)}
              className="w-full bg-[#21262d] border border-[#30363d] rounded-xl px-3 py-2 text-[#e6edf3] focus:outline-none focus:border-[#a3e635] text-sm"
            />
          </div>
        ))}
      </div>

      <button
        onClick={guardar}
        disabled={guardando}
        className="w-full py-3 bg-[#a3e635] text-[#0d1117] font-bold rounded-xl hover:bg-[#84cc16] disabled:opacity-40 transition-all"
      >
        {guardando ? 'Guardando...' : ok ? '✅ Guardado' : 'Guardar cambios'}
      </button>

      {/* Bono sorpresa */}
      <div className="bg-[#161b22] border border-[#a855f7]/30 rounded-2xl p-5 space-y-3">
        <h2 className="text-sm font-semibold text-[#8b949e] uppercase tracking-wide">
          🎁 Bono sorpresa{estudianteSeleccionado ? ` para ${estudianteSeleccionado.avatar} ${estudianteSeleccionado.nombre}` : ''}
        </h2>

        {/* Selector de estudiante si hay más de uno */}
        {estudiantes.length > 1 && (
          <div className="flex gap-2">
            {estudiantes.map((est) => (
              <button
                key={est.id}
                type="button"
                onClick={() => setEstudianteId(est.id)}
                className={`flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-sm font-medium transition-all ${
                  estudianteId === est.id
                    ? 'bg-[#a855f7] text-white'
                    : 'bg-[#21262d] text-[#8b949e] hover:text-[#e6edf3]'
                }`}
              >
                <span>{est.avatar}</span> {est.nombre}
              </button>
            ))}
          </div>
        )}

        <input
          type="number"
          value={monto}
          onChange={(e) => setMonto(e.target.value)}
          placeholder="Monto en COP (ej: 15000)"
          className="w-full bg-[#21262d] border border-[#30363d] rounded-xl px-3 py-2 text-[#e6edf3] focus:outline-none focus:border-[#a855f7] text-sm"
        />
        <textarea
          value={mensaje}
          onChange={(e) => setMensaje(e.target.value)}
          placeholder={`Mensaje para ${estudianteSeleccionado?.nombre ?? 'el estudiante'} (opcional) — ej: ¡Qué semana tan buena! 🔥`}
          rows={2}
          className="w-full bg-[#21262d] border border-[#30363d] rounded-xl px-3 py-2 text-[#e6edf3] placeholder-[#484f58] focus:outline-none focus:border-[#a855f7] text-sm resize-none"
        />
        <button
          onClick={enviarBono}
          disabled={enviandoBono || !monto}
          className="w-full py-2.5 bg-[#a855f7] text-white font-bold rounded-xl hover:bg-[#9333ea] disabled:opacity-40 transition-all text-sm"
        >
          {enviandoBono ? 'Enviando...' : '🎁 Enviar bono'}
        </button>
        {bonos.length > 0 && (
          <div className="space-y-2 pt-2">
            <div className="text-xs text-[#484f58] uppercase tracking-wide">Últimos bonos enviados</div>
            {bonos.slice(0, 3).map((b, i) => (
              <div key={i} className="flex justify-between text-xs text-[#8b949e]">
                <span className="truncate">{b.mensaje || 'Sin mensaje'}</span>
                <span className="text-[#a3e635] font-bold ml-2">+${b.monto?.toLocaleString('es-CO')}</span>
              </div>
            ))}
          </div>
        )}
      </div>
    </div>
  )
}
