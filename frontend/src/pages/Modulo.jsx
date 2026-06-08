import { useEffect, useState } from 'react'
import { useParams, useNavigate } from 'react-router-dom'
import { motion } from 'framer-motion'
import { useProgresoStore } from '@/stores/progresoStore'
import TeoriaBloque from '@/components/contenido/TeoriaBloque'

const tipoIcon = {
  teoria: '📖',
  ejemplo_codigo: '💻',
  video: '▶️',
  tip: '💡',
}

const ejercicioIcon = {
  quiz_opcion: '❓',
  quiz_texto: '✏️',
  codigo_libre: '💻',
  mini_proyecto: '🏗️',
  desafio_dia: '⚡',
}

export default function Modulo() {
  const { slug } = useParams()
  const navigate = useNavigate()
  const { fetchModulo, loading } = useProgresoStore()
  const [data, setData] = useState(null)
  const [seccionActiva, setSeccionActiva] = useState({ tipo: 'leccion', index: 0 })

  useEffect(() => {
    fetchModulo(slug).then((d) => {
      if (d) setData(d)
    })
  }, [slug])

  if (loading || !data) {
    return (
      <div className="flex items-center justify-center py-20">
        <div className="text-4xl animate-float">📖</div>
      </div>
    )
  }

  const { modulo, lecciones, ejercicios } = data
  const leccionActiva = seccionActiva.tipo === 'leccion' ? lecciones[seccionActiva.index] : null
  const ejercicioActivo = seccionActiva.tipo === 'ejercicio' ? ejercicios[seccionActiva.index] : null

  const totalItems = lecciones.length + ejercicios.length
  const completados = ejercicios.filter((e) => e.es_correcto || e.validado_por_padre).length

  return (
    <div className="flex gap-4 min-h-[calc(100vh-80px)]">
      {/* Sidebar */}
      <aside className="w-56 flex-shrink-0 hidden sm:block">
        <div className="bg-[#161b22] border border-[#30363d] rounded-2xl p-3 sticky top-24">
          {/* Info módulo */}
          <div className="mb-4 px-1">
            <div className="text-2xl mb-1">{modulo.icono}</div>
            <div className="text-sm font-bold text-[#e6edf3] leading-tight">{modulo.titulo}</div>
            <div className="text-xs text-[#8b949e] mt-1">
              {completados}/{ejercicios.filter((e) => e.es_obligatorio).length} ejercicios
            </div>
            <div className="h-1.5 bg-[#21262d] rounded-full mt-2">
              <div
                className="h-full bg-[#a3e635] rounded-full transition-all"
                style={{ width: `${(completados / Math.max(ejercicios.filter(e=>e.es_obligatorio).length, 1)) * 100}%` }}
              />
            </div>
          </div>

          {/* Lecciones */}
          <div className="text-xs text-[#484f58] uppercase font-semibold mb-2 px-1">Lecciones</div>
          <div className="space-y-1 mb-3">
            {lecciones.map((l, i) => (
              <button
                key={l.id}
                onClick={() => setSeccionActiva({ tipo: 'leccion', index: i })}
                className={`w-full text-left flex items-center gap-2 px-2 py-2 rounded-lg text-xs transition-colors ${
                  seccionActiva.tipo === 'leccion' && seccionActiva.index === i
                    ? 'bg-[#a3e635]/20 text-[#a3e635]'
                    : 'text-[#8b949e] hover:bg-[#21262d] hover:text-[#e6edf3]'
                }`}
              >
                <span>{tipoIcon[l.tipo] ?? '📄'}</span>
                <span className="truncate">{l.titulo}</span>
              </button>
            ))}
          </div>

          {/* Ejercicios */}
          <div className="text-xs text-[#484f58] uppercase font-semibold mb-2 px-1">Ejercicios</div>
          <div className="space-y-1">
            {ejercicios.map((ej, i) => (
              <button
                key={ej.id}
                onClick={() => navigate(`/ejercicios/${ej.id}`)}
                className={`w-full text-left flex items-center gap-2 px-2 py-2 rounded-lg text-xs transition-colors ${
                  seccionActiva.tipo === 'ejercicio' && seccionActiva.index === i
                    ? 'bg-[#a3e635]/20 text-[#a3e635]'
                    : 'text-[#8b949e] hover:bg-[#21262d] hover:text-[#e6edf3]'
                }`}
              >
                <span>
                  {ej.es_correcto || ej.validado_por_padre ? '✅' : ejercicioIcon[ej.tipo] ?? '❓'}
                </span>
                <span className="truncate flex-1">{ej.titulo}</span>
                {!ej.es_obligatorio && <span className="text-[#484f58] text-xs">opt</span>}
              </button>
            ))}
          </div>
        </div>
      </aside>

      {/* Contenido principal */}
      <div className="flex-1 min-w-0">
        <motion.div
          key={`${seccionActiva.tipo}-${seccionActiva.index}`}
          initial={{ opacity: 0, x: 20 }}
          animate={{ opacity: 1, x: 0 }}
          transition={{ duration: 0.25 }}
        >
          {/* Breadcrumb */}
          <div className="flex items-center gap-2 text-xs text-[#8b949e] mb-4">
            <button onClick={() => navigate('/dashboard')} className="hover:text-[#a3e635]">Dashboard</button>
            <span>›</span>
            <span className="text-[#e6edf3]">{modulo.titulo}</span>
          </div>

          {leccionActiva && <TeoriaBloque leccion={leccionActiva} />}

          {/* Navegación entre lecciones */}
          <div className="flex justify-between gap-3 mt-4">
            <button
              onClick={() => {
                const prev = seccionActiva.index - 1
                if (prev >= 0) setSeccionActiva({ tipo: 'leccion', index: prev })
              }}
              disabled={seccionActiva.index === 0 || seccionActiva.tipo !== 'leccion'}
              className="flex items-center gap-2 px-4 py-2 rounded-xl border border-[#30363d] text-[#8b949e] hover:border-[#484f58] disabled:opacity-30 transition-colors text-sm"
            >
              ← Anterior
            </button>

            {seccionActiva.tipo === 'leccion' && seccionActiva.index < lecciones.length - 1 ? (
              <button
                onClick={() => setSeccionActiva({ tipo: 'leccion', index: seccionActiva.index + 1 })}
                className="flex items-center gap-2 px-4 py-2 rounded-xl bg-[#a3e635] text-[#0d1117] font-bold hover:bg-[#84cc16] transition-all text-sm"
              >
                Siguiente →
              </button>
            ) : ejercicios.length > 0 ? (
              <button
                onClick={() => navigate(`/ejercicios/${ejercicios[0].id}`)}
                className="flex items-center gap-2 px-4 py-2 rounded-xl bg-[#a855f7] text-white font-bold hover:bg-[#9333ea] transition-all text-sm"
              >
                Ir a ejercicios →
              </button>
            ) : null}
          </div>
        </motion.div>
      </div>
    </div>
  )
}
