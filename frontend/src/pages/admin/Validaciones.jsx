import { useEffect, useState } from 'react'
import { motion, AnimatePresence } from 'framer-motion'
import api from '@/lib/axios'

function ValidacionCard({ item, onValidar }) {
  const [feedback, setFeedback] = useState('')
  const [enviando, setEnviando] = useState(null)

  async function validar(aprobar) {
    setEnviando(aprobar ? 'aprobar' : 'rechazar')
    try {
      await api.post(`/admin/validar/${item.id}`, { aprobar, feedback })
      onValidar(item.id)
    } finally {
      setEnviando(null)
    }
  }

  return (
    <motion.div
      layout
      initial={{ opacity: 0, y: 20 }}
      animate={{ opacity: 1, y: 0 }}
      exit={{ opacity: 0, x: 50 }}
      className="bg-[#161b22] border border-[#30363d] rounded-2xl p-5 space-y-4"
    >
      <div className="flex items-start justify-between gap-3">
        <div>
          <div className="font-bold text-[#e6edf3]">{item.ejercicio_titulo}</div>
          <div className="text-xs text-[#8b949e] mt-0.5">
            {item.modulo_titulo} •{' '}
            <span className="capitalize">{item.tipo?.replace('_', ' ')}</span> •{' '}
            {new Date(item.completado_at).toLocaleDateString('es-CO')}
          </div>
        </div>
        <div className="text-[#a3e635] font-black text-sm flex-shrink-0">
          +${item.recompensa?.toLocaleString('es-CO')}
        </div>
      </div>

      {item.respuesta_dada && (
        <div>
          <div className="text-xs text-[#8b949e] mb-1">Respuesta de {item.estudiante}:</div>
          <div className="bg-[#0d1117] border border-[#30363d] rounded-xl p-3 text-sm text-[#c9d1d9] font-mono whitespace-pre-wrap max-h-40 overflow-y-auto">
            {item.respuesta_dada}
          </div>
        </div>
      )}

      <textarea
        value={feedback}
        onChange={(e) => setFeedback(e.target.value)}
        placeholder="Comentario para Santiago (opcional)..."
        rows={2}
        className="w-full bg-[#21262d] border border-[#30363d] rounded-xl px-3 py-2 text-sm text-[#e6edf3] placeholder-[#484f58] focus:outline-none focus:border-[#484f58] resize-none"
      />

      <div className="flex gap-3">
        <button
          onClick={() => validar(true)}
          disabled={!!enviando}
          className="flex-1 py-2.5 rounded-xl font-bold bg-[#3fb950] text-[#0d1117] hover:bg-green-400 disabled:opacity-50 transition-all text-sm"
        >
          {enviando === 'aprobar' ? '...' : '✅ Aprobar'}
        </button>
        <button
          onClick={() => validar(false)}
          disabled={!!enviando}
          className="flex-1 py-2.5 rounded-xl font-bold bg-[#21262d] border border-[#f85149]/50 text-[#f85149] hover:bg-[#f85149]/10 disabled:opacity-50 transition-all text-sm"
        >
          {enviando === 'rechazar' ? '...' : '🔄 Pedir corrección'}
        </button>
      </div>
    </motion.div>
  )
}

export default function Validaciones() {
  const [pendientes, setPendientes] = useState([])
  const [loading, setLoading] = useState(true)

  useEffect(() => {
    api.get('/admin/pendientes')
      .then(({ data }) => setPendientes(data.data))
      .finally(() => setLoading(false))
  }, [])

  function handleValidado(id) {
    setPendientes((prev) => prev.filter((p) => p.id !== id))
  }

  return (
    <div className="max-w-lg mx-auto space-y-5">
      <h1 className="text-xl font-black text-[#e6edf3]">
        📋 Ejercicios pendientes
        {pendientes.length > 0 && (
          <span className="ml-2 bg-[#f85149] text-white text-sm px-2 py-0.5 rounded-full">{pendientes.length}</span>
        )}
      </h1>

      {loading ? (
        <div className="text-center py-20"><div className="text-4xl animate-float">⏳</div></div>
      ) : pendientes.length === 0 ? (
        <div className="text-center py-16 space-y-3">
          <div className="text-5xl">🎉</div>
          <div className="text-[#3fb950] font-bold">¡Todo al día!</div>
          <div className="text-sm text-[#8b949e]">No hay ejercicios pendientes de validar.</div>
        </div>
      ) : (
        <AnimatePresence mode="popLayout">
          {pendientes.map((item) => (
            <ValidacionCard key={item.id} item={item} onValidar={handleValidado} />
          ))}
        </AnimatePresence>
      )}
    </div>
  )
}
