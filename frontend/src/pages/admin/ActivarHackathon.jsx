import { useEffect, useState } from 'react'
import { useNavigate } from 'react-router-dom'
import { motion, AnimatePresence } from 'framer-motion'
import api from '@/lib/axios'

const TIPOS = [
  { valor: 'sprint',   icono: '⚡', label: 'Sprint',   desc: '5 ejercicios · ~2 horas' },
  { valor: 'maraton',  icono: '🏃', label: 'Maratón',  desc: '10 ejercicios · fin de semana' },
  { valor: 'maestro',  icono: '👨‍💻', label: 'Maestro',  desc: '8 ejercicios · sin límite' },
  { valor: 'tematico', icono: '🎯', label: 'Temático', desc: '6 ejercicios · tema específico' },
]

const LIMITE_HORAS = {
  sprint: 2,
  maraton: 72,
  maestro: null,
  tematico: 4,
}

export default function ActivarHackathon() {
  const navigate = useNavigate()
  const [tipo, setTipo] = useState('sprint')
  const [nombre, setNombre] = useState('')
  const [recompensa, setRecompensa] = useState('60000')
  const [horasCustom, setHorasCustom] = useState('')
  const [multVelocidad, setMultVelocidad] = useState('1.3')
  const [multPerfecto, setMultPerfecto] = useState('1.5')
  const [activando, setActivando] = useState(false)
  const [resultado, setResultado] = useState(null)
  const [error, setError] = useState(null)
  const [historial, setHistorial] = useState([])

  useEffect(() => {
    api.get('/admin/hackathon').then(({ data }) => setHistorial(data.data)).catch(() => {})
    const tipo_ = TIPOS[0]
    setNombre(`Hackathon ${tipo_.label} ${new Date().toLocaleDateString('es-CO')}`)
  }, [])

  useEffect(() => {
    setNombre(`Hackathon ${TIPOS.find((t) => t.valor === tipo)?.label ?? ''} ${new Date().toLocaleDateString('es-CO')}`)
  }, [tipo])

  async function activar() {
    setActivando(true)
    setError(null)
    try {
      const horas = horasCustom ? parseInt(horasCustom) : LIMITE_HORAS[tipo]
      const { data } = await api.post('/admin/hackathon/activar', {
        nombre,
        tipo,
        recompensa_base: parseInt(recompensa),
        tiempo_limite_horas: horas,
        multiplicador_velocidad: parseFloat(multVelocidad),
        multiplicador_perfecto: parseFloat(multPerfecto),
      })
      setResultado(data)
      api.get('/admin/hackathon').then(({ data }) => setHistorial(data.data)).catch(() => {})
    } catch (err) {
      setError(err.response?.data?.message ?? 'Error al activar el hackathon')
    } finally {
      setActivando(false)
    }
  }

  async function cancelar(id) {
    await api.delete(`/admin/hackathon/${id}`)
    setHistorial((prev) => prev.map((h) => h.id === id ? { ...h, estado: 'cancelado' } : h))
  }

  return (
    <div className="max-w-lg mx-auto space-y-5">
      <div className="flex items-center justify-between">
        <h1 className="text-xl font-black text-[#e6edf3]">🏆 Hackathon</h1>
        <button onClick={() => navigate('/admin/dashboard')} className="text-sm text-[#8b949e] hover:text-[#a3e635]">
          ← Volver
        </button>
      </div>

      <AnimatePresence>
        {resultado && (
          <motion.div
            className="bg-[#3fb950]/10 border border-[#3fb950]/40 rounded-2xl p-4"
            initial={{ opacity: 0, y: -10 }}
            animate={{ opacity: 1, y: 0 }}
            exit={{ opacity: 0 }}
          >
            <div className="text-[#3fb950] font-bold">✅ {resultado.message}</div>
            <div className="text-xs text-[#8b949e] mt-1">
              {resultado.data?.ejercicios_count} ejercicios generados
            </div>
          </motion.div>
        )}
        {error && (
          <motion.div
            className="bg-[#f85149]/10 border border-[#f85149]/40 rounded-2xl p-4 text-[#f85149] text-sm"
            initial={{ opacity: 0 }}
            animate={{ opacity: 1 }}
          >
            {error}
          </motion.div>
        )}
      </AnimatePresence>

      {/* Tipo */}
      <div className="grid grid-cols-2 gap-3">
        {TIPOS.map((t) => (
          <button
            key={t.valor}
            onClick={() => setTipo(t.valor)}
            className={`p-4 rounded-2xl border text-left transition-all ${
              tipo === t.valor
                ? 'border-[#facc15] bg-[#facc15]/10'
                : 'border-[#30363d] bg-[#161b22] hover:border-[#484f58]'
            }`}
          >
            <div className="text-2xl mb-1">{t.icono}</div>
            <div className={`font-bold text-sm ${tipo === t.valor ? 'text-[#facc15]' : 'text-[#e6edf3]'}`}>{t.label}</div>
            <div className="text-xs text-[#8b949e]">{t.desc}</div>
          </button>
        ))}
      </div>

      {/* Nombre */}
      <div>
        <label className="text-xs text-[#8b949e] block mb-1">Nombre del hackathon</label>
        <input
          value={nombre}
          onChange={(e) => setNombre(e.target.value)}
          className="w-full bg-[#21262d] border border-[#30363d] rounded-xl px-3 py-2.5 text-[#e6edf3] focus:outline-none focus:border-[#facc15] text-sm"
        />
      </div>

      {/* Recompensa */}
      <div className="grid grid-cols-2 gap-3">
        <div>
          <label className="text-xs text-[#8b949e] block mb-1">Recompensa base (COP)</label>
          <input
            type="number"
            value={recompensa}
            onChange={(e) => setRecompensa(e.target.value)}
            className="w-full bg-[#21262d] border border-[#30363d] rounded-xl px-3 py-2 text-[#e6edf3] focus:outline-none focus:border-[#facc15] text-sm"
          />
        </div>
        <div>
          <label className="text-xs text-[#8b949e] block mb-1">Tiempo límite (horas, opcional)</label>
          <input
            type="number"
            value={horasCustom}
            onChange={(e) => setHorasCustom(e.target.value)}
            placeholder={LIMITE_HORAS[tipo] ? `${LIMITE_HORAS[tipo]}h por defecto` : 'Sin límite'}
            className="w-full bg-[#21262d] border border-[#30363d] rounded-xl px-3 py-2 text-[#e6edf3] placeholder-[#484f58] focus:outline-none focus:border-[#facc15] text-sm"
          />
        </div>
      </div>

      {/* Multiplicadores */}
      <div className="grid grid-cols-2 gap-3">
        <div>
          <label className="text-xs text-[#8b949e] block mb-1">⚡ Mult. velocidad (x)</label>
          <input
            type="number"
            step="0.1"
            value={multVelocidad}
            onChange={(e) => setMultVelocidad(e.target.value)}
            className="w-full bg-[#21262d] border border-[#30363d] rounded-xl px-3 py-2 text-[#e6edf3] focus:outline-none focus:border-[#facc15] text-sm"
          />
        </div>
        <div>
          <label className="text-xs text-[#8b949e] block mb-1">⭐ Mult. perfecto (x)</label>
          <input
            type="number"
            step="0.1"
            value={multPerfecto}
            onChange={(e) => setMultPerfecto(e.target.value)}
            className="w-full bg-[#21262d] border border-[#30363d] rounded-xl px-3 py-2 text-[#e6edf3] focus:outline-none focus:border-[#facc15] text-sm"
          />
        </div>
      </div>

      <button
        onClick={activar}
        disabled={activando || !nombre || !recompensa}
        className="w-full py-3.5 bg-[#facc15] text-[#0d1117] font-black rounded-xl hover:bg-yellow-300 disabled:opacity-40 transition-all hover:scale-[1.02] active:scale-[0.98]"
      >
        {activando ? 'Generando ejercicios...' : '🚀 Activar hackathon'}
      </button>

      {/* Historial */}
      {historial.length > 0 && (
        <div className="space-y-2">
          <div className="text-xs text-[#484f58] uppercase tracking-wide">Historial</div>
          {historial.map((h) => (
            <div key={h.id} className="bg-[#161b22] border border-[#30363d] rounded-xl px-4 py-3 flex items-center justify-between">
              <div>
                <div className="text-sm font-medium text-[#e6edf3]">{h.nombre}</div>
                <div className="text-xs text-[#8b949e]">
                  {h.ejercicios_count} ejercicios · ${h.recompensa_base?.toLocaleString('es-CO')}
                </div>
              </div>
              <div className="flex items-center gap-2">
                <span className={`text-xs font-bold px-2 py-0.5 rounded-full ${
                  h.estado === 'activo'      ? 'bg-[#3fb950]/20 text-[#3fb950]' :
                  h.estado === 'finalizado'  ? 'bg-[#58a6ff]/20 text-[#58a6ff]' :
                  h.estado === 'cancelado'   ? 'bg-[#f85149]/20 text-[#f85149]' :
                  'bg-[#21262d] text-[#8b949e]'
                }`}>
                  {h.estado}
                </span>
                {h.estado === 'activo' && (
                  <button onClick={() => cancelar(h.id)} className="text-xs text-[#f85149] hover:underline">
                    Cancelar
                  </button>
                )}
              </div>
            </div>
          ))}
        </div>
      )}
    </div>
  )
}
