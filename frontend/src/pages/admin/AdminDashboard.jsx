import { useEffect, useState } from 'react'
import { useNavigate } from 'react-router-dom'
import { motion } from 'framer-motion'
import api from '@/lib/axios'

export default function AdminDashboard() {
  const [data, setData] = useState(null)
  const navigate = useNavigate()

  useEffect(() => {
    api.get('/admin/dashboard').then(({ data }) => setData(data.data))
  }, [])

  if (!data) return <div className="flex justify-center py-20"><div className="text-4xl animate-float">👨‍💻</div></div>

  const { estudiante, billetera, racha, estadisticas, progreso_semanal } = data

  return (
    <div className="max-w-2xl mx-auto space-y-5">
      <div className="flex items-center justify-between">
        <h1 className="text-xl font-black text-[#e6edf3]">👨‍💻 Panel de papá</h1>
        <button
          onClick={() => navigate('/admin/validaciones')}
          className="relative bg-[#a3e635] text-[#0d1117] font-bold px-4 py-2 rounded-xl text-sm hover:bg-[#84cc16] transition-all"
        >
          Validar ejercicios
          {estadisticas.pendientes_validacion > 0 && (
            <span className="absolute -top-1.5 -right-1.5 w-5 h-5 bg-[#f85149] text-white text-xs rounded-full flex items-center justify-center font-black">
              {estadisticas.pendientes_validacion}
            </span>
          )}
        </button>
      </div>

      {/* Resumen del estudiante */}
      <motion.div
        className="bg-[#161b22] border border-[#30363d] rounded-2xl p-5"
        initial={{ opacity: 0, y: 20 }}
        animate={{ opacity: 1, y: 0 }}
      >
        <div className="flex items-center gap-3 mb-4">
          <span className="text-4xl">{estudiante.avatar}</span>
          <div>
            <div className="font-bold text-[#e6edf3]">{estudiante.nombre}</div>
            <div className="text-xs text-[#8b949e]">🔥 Racha: {racha.dias_actuales} días • Máx: {racha.dias_maximos} días</div>
          </div>
        </div>

        <div className="grid grid-cols-2 gap-3">
          <div className="bg-[#21262d] rounded-xl p-3">
            <div className="text-xs text-[#8b949e] mb-1">Acumulado total</div>
            <div className="text-xl font-black text-[#a3e635]">${billetera.saldo_total.toLocaleString('es-CO')}</div>
          </div>
          <div className="bg-[#21262d] rounded-xl p-3">
            <div className="text-xs text-[#8b949e] mb-1">Pendiente de pago</div>
            <div className="text-xl font-black text-[#facc15]">${billetera.saldo_pendiente.toLocaleString('es-CO')}</div>
          </div>
        </div>
      </motion.div>

      {/* Estadísticas */}
      <div className="grid grid-cols-2 sm:grid-cols-4 gap-3">
        {[
          { label: 'Módulos OK', value: estadisticas.modulos_completados, icon: '🏆', color: 'text-[#a3e635]' },
          { label: 'En progreso', value: estadisticas.modulos_en_progreso, icon: '▶', color: 'text-[#58a6ff]' },
          { label: 'Ejercicios', value: estadisticas.ejercicios_totales, icon: '✅', color: 'text-[#3fb950]' },
          { label: 'Perfectos', value: estadisticas.ejercicios_perfectos, icon: '⭐', color: 'text-[#facc15]' },
        ].map((s) => (
          <div key={s.label} className="bg-[#161b22] border border-[#30363d] rounded-xl p-3 text-center">
            <div className="text-2xl mb-1">{s.icon}</div>
            <div className={`text-xl font-black ${s.color}`}>{s.value}</div>
            <div className="text-xs text-[#8b949e]">{s.label}</div>
          </div>
        ))}
      </div>

      {/* Registrar pago */}
      {billetera.saldo_pendiente > 0 && <PagoRapido saldo={billetera.saldo_pendiente} onPago={() => api.get('/admin/dashboard').then(({ data }) => setData(data.data))} />}
    </div>
  )
}

function PagoRapido({ saldo, onPago }) {
  const [monto, setMonto] = useState(String(saldo))
  const [enviando, setEnviando] = useState(false)
  const [ok, setOk] = useState(false)

  async function handlePagar() {
    setEnviando(true)
    try {
      await api.post('/admin/pagar', { monto: parseInt(monto) })
      setOk(true)
      onPago()
    } finally {
      setEnviando(false)
    }
  }

  return (
    <div className="bg-[#161b22] border border-[#a3e635]/30 rounded-2xl p-5">
      <h3 className="font-bold text-[#e6edf3] mb-3">💸 Registrar pago</h3>
      {ok ? (
        <div className="text-[#3fb950] text-sm font-semibold">✅ Pago registrado correctamente</div>
      ) : (
        <div className="flex gap-3">
          <input
            type="number"
            value={monto}
            onChange={(e) => setMonto(e.target.value)}
            className="flex-1 bg-[#21262d] border border-[#30363d] rounded-xl px-3 py-2 text-[#e6edf3] focus:outline-none focus:border-[#a3e635]"
          />
          <button
            onClick={handlePagar}
            disabled={enviando || !monto}
            className="bg-[#a3e635] text-[#0d1117] font-bold px-5 py-2 rounded-xl hover:bg-[#84cc16] disabled:opacity-40 transition-all"
          >
            {enviando ? '...' : 'Pagar'}
          </button>
        </div>
      )}
    </div>
  )
}
