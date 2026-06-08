import { useEffect, useState } from 'react'
import { motion } from 'framer-motion'
import api from '@/lib/axios'
import LogroBadge from '@/components/gamification/LogroBadge'

const tipoLabel = {
  progreso: 'Progreso',
  perfeccion: 'Perfección',
  constancia: 'Constancia',
  velocidad: 'Velocidad',
  especial: 'Especial',
}

export default function Logros() {
  const [data, setData] = useState(null)
  const [loading, setLoading] = useState(true)

  useEffect(() => {
    api.get('/logros')
      .then(({ data }) => setData(data.data))
      .finally(() => setLoading(false))
  }, [])

  const grupos = data?.logros?.reduce((acc, l) => {
    const tipo = l.tipo ?? 'especial'
    if (!acc[tipo]) acc[tipo] = []
    acc[tipo].push(l)
    return acc
  }, {}) ?? {}

  return (
    <div className="max-w-lg mx-auto space-y-5">
      <motion.div
        className="flex items-center justify-between"
        initial={{ opacity: 0, y: -10 }}
        animate={{ opacity: 1, y: 0 }}
      >
        <h1 className="text-xl font-black text-[#e6edf3]">🏅 Mis logros</h1>
        {data && (
          <div className="text-sm text-[#8b949e]">
            <span className="text-[#a3e635] font-bold">{data.obtenidos}</span>/{data.total}
          </div>
        )}
      </motion.div>

      {/* Barra de progreso general */}
      {data && (
        <div className="bg-[#161b22] border border-[#30363d] rounded-xl p-4">
          <div className="flex justify-between text-xs text-[#8b949e] mb-2">
            <span>Logros desbloqueados</span>
            <span>{Math.round((data.obtenidos / data.total) * 100)}%</span>
          </div>
          <div className="h-2 bg-[#21262d] rounded-full overflow-hidden">
            <motion.div
              className="h-full bg-gradient-to-r from-[#a855f7] to-[#c084fc] rounded-full"
              initial={{ width: 0 }}
              animate={{ width: `${(data.obtenidos / data.total) * 100}%` }}
              transition={{ duration: 1, delay: 0.3 }}
            />
          </div>
        </div>
      )}

      {loading ? (
        <div className="grid grid-cols-4 gap-3">
          {[...Array(12)].map((_, i) => (
            <div key={i} className="h-16 rounded-xl animate-shimmer" />
          ))}
        </div>
      ) : (
        Object.entries(grupos).map(([tipo, logros]) => (
          <motion.div
            key={tipo}
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
          >
            <h2 className="text-xs font-semibold text-[#484f58] uppercase tracking-wide mb-3">
              {tipoLabel[tipo] ?? tipo}
            </h2>
            <div className="grid grid-cols-4 gap-3">
              {logros.map((l) => (
                <div key={l.slug} className="flex flex-col items-center gap-1">
                  <LogroBadge logro={l} />
                  <span className="text-xs text-[#8b949e] text-center leading-tight line-clamp-2">
                    {l.desbloqueado ? l.nombre : '???'}
                  </span>
                </div>
              ))}
            </div>
          </motion.div>
        ))
      )}
    </div>
  )
}
