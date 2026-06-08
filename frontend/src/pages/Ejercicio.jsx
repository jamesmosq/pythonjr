import { useEffect, useState } from 'react'
import { useParams, useNavigate } from 'react-router-dom'
import { motion } from 'framer-motion'
import ReactMarkdown from 'react-markdown'
import remarkGfm from 'remark-gfm'
import api from '@/lib/axios'
import CodigoEjemplo from '@/components/contenido/CodigoEjemplo'
import CelebracionModal from '@/components/gamification/CelebracionModal'
import { useBilleteraStore } from '@/stores/billeteraStore'

export default function Ejercicio() {
  const { id } = useParams()
  const navigate = useNavigate()
  const [ejercicio, setEjercicio] = useState(null)
  const [moduloSlug, setModuloSlug] = useState(null)
  const [loading, setLoading] = useState(true)
  const [respuesta, setRespuesta] = useState('')
  const [enviando, setEnviando] = useState(false)
  const [feedback, setFeedback] = useState(null)
  const [celebracion, setCelebracion] = useState(null)
  const [mostrarPista, setMostrarPista] = useState(false)
  const updateSaldo = useBilleteraStore((s) => s.updateSaldo)

  useEffect(() => {
    api.get(`/ejercicios/${id}`)
      .then(({ data }) => {
        setEjercicio(data.data)
        setModuloSlug(data.data.modulo_slug ?? null)
      })
      .finally(() => setLoading(false))
  }, [id])

  async function handleEnviar() {
    if (!respuesta.trim() && ejercicio.tipo !== 'codigo_libre' && ejercicio.tipo !== 'mini_proyecto') return

    setEnviando(true)
    setFeedback(null)
    try {
      const payload =
        ejercicio.tipo === 'quiz_opcion'
          ? { respuesta: String(respuesta) }
          : { respuesta: respuesta.trim() }

      const { data } = await api.post(`/ejercicios/${id}/intentar`, payload)

      if (ejercicio.tipo === 'codigo_libre' || ejercicio.tipo === 'mini_proyecto') {
        setFeedback({ tipo: 'pendiente', message: data.message })
        return
      }

      if (!data.data.es_correcto) {
        setFeedback({ tipo: 'incorrecto', message: data.message, intento: data.data.intento })
        if (data.data.intento >= 2) setMostrarPista(true)
        return
      }

      // Correcto
      if (data.meta?.billetera_total) updateSaldo(data.meta.billetera_total)
      setCelebracion({
        recompensa: data.meta?.recompensa_ganada ?? 0,
        esPerfecto: data.data.es_perfecto,
        nuevosLogros: data.meta?.nuevos_logros ?? [],
      })
    } catch (err) {
      const msg = err.response?.data?.message ?? 'Error al enviar'
      if (err.response?.status === 422) {
        setFeedback({ tipo: 'info', message: msg })
      } else {
        setFeedback({ tipo: 'error', message: msg })
      }
    } finally {
      setEnviando(false)
    }
  }

  if (loading) {
    return <div className="flex items-center justify-center py-20"><div className="text-4xl animate-float">💻</div></div>
  }

  if (!ejercicio) {
    return (
      <div className="text-center py-20">
        <div className="text-[#8b949e]">Ejercicio no encontrado</div>
        <button onClick={() => navigate('/dashboard')} className="mt-4 text-[#a3e635] underline text-sm">Volver al dashboard</button>
      </div>
    )
  }

  const yaCompletado = ejercicio.es_correcto || ejercicio.validado_por_padre

  return (
    <div className="max-w-2xl mx-auto space-y-4">
      {/* Celebración */}
      <CelebracionModal
        open={!!celebracion}
        onClose={() => { setCelebracion(null); navigate(`/modulos/${moduloSlug}`) }}
        recompensa={celebracion?.recompensa ?? 0}
        esPerfecto={celebracion?.esPerfecto ?? false}
        nuevosLogros={celebracion?.nuevosLogros ?? []}
      />

      {/* Breadcrumb */}
      <div className="flex items-center gap-2 text-xs text-[#8b949e]">
        <button onClick={() => navigate('/dashboard')} className="hover:text-[#a3e635]">Dashboard</button>
        <span>›</span>
        {moduloSlug && (
          <>
            <button onClick={() => navigate(`/modulos/${moduloSlug}`)} className="hover:text-[#a3e635]">Módulo</button>
            <span>›</span>
          </>
        )}
        <span className="text-[#e6edf3] truncate">{ejercicio.titulo}</span>
      </div>

      {/* Card principal */}
      <motion.div
        className="bg-[#161b22] border border-[#30363d] rounded-2xl p-6"
        initial={{ opacity: 0, y: 20 }}
        animate={{ opacity: 1, y: 0 }}
      >
        {/* Header */}
        <div className="flex items-start justify-between gap-3 mb-4">
          <div className="flex-1">
            <div className="flex items-center gap-2 text-xs text-[#8b949e] mb-2">
              <span className="bg-[#21262d] px-2 py-0.5 rounded-md capitalize">
                {ejercicio.tipo?.replace('_', ' ')}
              </span>
              {!ejercicio.es_obligatorio && (
                <span className="text-[#484f58]">• Opcional</span>
              )}
            </div>
            <h1 className="text-lg font-bold text-[#e6edf3]">{ejercicio.titulo}</h1>
          </div>
          <div className="text-right flex-shrink-0">
            <div className="text-[#a3e635] font-black text-lg">
              +${ejercicio.recompensa_ejercicio?.toLocaleString('es-CO')}
            </div>
            {ejercicio.recompensa_perfecto > ejercicio.recompensa_ejercicio && (
              <div className="text-xs text-[#facc15]">
                ⭐ ${ejercicio.recompensa_perfecto?.toLocaleString('es-CO')} si es perfecto
              </div>
            )}
          </div>
        </div>

        {/* Enunciado */}
        <div className="md mb-4">
          <ReactMarkdown
            remarkPlugins={[remarkGfm]}
            components={{
              code({ node, inline, className, children }) {
                const match = /language-(\w+)/.exec(className || '')
                const code = String(children).replace(/\n$/, '')
                if (!inline && (match || code.includes('\n'))) {
                  const lines = code.split('\n').length
                  return <CodigoEjemplo codigo={code} lenguaje={match?.[1] ?? 'python'} altura={Math.min(lines * 22 + 40, 300)} />
                }
                return <code className={className}>{children}</code>
              },
            }}
          >
            {ejercicio.enunciado}
          </ReactMarkdown>
        </div>

        {/* Ya completado */}
        {yaCompletado && (
          <div className="bg-[#3fb950]/10 border border-[#3fb950]/30 rounded-xl p-4 text-center">
            <div className="text-2xl mb-1">✅</div>
            <div className="text-[#3fb950] font-bold">¡Ejercicio completado!</div>
            {ejercicio.validado_por_padre && !ejercicio.es_correcto && (
              <div className="text-xs text-[#8b949e] mt-1">Esperando validación de papá</div>
            )}
          </div>
        )}

        {/* Respuesta */}
        {!yaCompletado && (
          <>
            {/* Quiz de opción múltiple */}
            {ejercicio.tipo === 'quiz_opcion' && ejercicio.opciones && (
              <div className="space-y-2 mb-4">
                {ejercicio.opciones.map((op) => (
                  <button
                    key={op.id}
                    onClick={() => setRespuesta(String(op.id))}
                    className={`w-full text-left px-4 py-3 rounded-xl border transition-all text-sm ${
                      respuesta === String(op.id)
                        ? 'border-[#a3e635] bg-[#a3e635]/10 text-[#a3e635]'
                        : 'border-[#30363d] bg-[#21262d] text-[#c9d1d9] hover:border-[#484f58]'
                    }`}
                  >
                    {op.texto}
                  </button>
                ))}
              </div>
            )}

            {/* Quiz de texto */}
            {ejercicio.tipo === 'quiz_texto' && (
              <div className="mb-4">
                <input
                  type="text"
                  value={respuesta}
                  onChange={(e) => setRespuesta(e.target.value)}
                  onKeyDown={(e) => e.key === 'Enter' && handleEnviar()}
                  placeholder="Escribe tu respuesta aquí..."
                  className="w-full bg-[#21262d] border border-[#30363d] rounded-xl px-4 py-3 text-[#e6edf3] placeholder-[#484f58] focus:outline-none focus:border-[#a3e635] transition-colors font-mono"
                  autoFocus
                />
              </div>
            )}

            {/* Código libre / mini proyecto */}
            {(ejercicio.tipo === 'codigo_libre' || ejercicio.tipo === 'mini_proyecto') && (
              <div className="mb-4 space-y-3">
                {ejercicio.codigo_base && (
                  <div>
                    <div className="text-xs text-[#8b949e] mb-2">📝 Código base (escríbelo en VS Code):</div>
                    <CodigoEjemplo codigo={ejercicio.codigo_base} altura={120} />
                  </div>
                )}
                <div className="bg-[#a855f7]/10 border border-[#a855f7]/30 rounded-xl p-4 text-sm text-[#c084fc]">
                  <p className="font-semibold mb-1">¿Cómo entregar?</p>
                  <p className="text-[#8b949e]">
                    1. Abre VS Code en tu PC<br />
                    2. Escribe y ejecuta el programa<br />
                    3. Muéstrale el resultado a papá<br />
                    4. Pega aquí lo que imprimió tu programa (opcional)
                  </p>
                </div>
                <textarea
                  value={respuesta}
                  onChange={(e) => setRespuesta(e.target.value)}
                  placeholder="Pega aquí la salida de tu programa (opcional)..."
                  rows={4}
                  className="w-full bg-[#21262d] border border-[#30363d] rounded-xl px-4 py-3 text-[#e6edf3] placeholder-[#484f58] focus:outline-none focus:border-[#a855f7] transition-colors font-mono text-sm resize-none"
                />
              </div>
            )}

            {/* Feedback */}
            {feedback && (
              <motion.div
                initial={{ opacity: 0, y: -10 }}
                animate={{ opacity: 1, y: 0 }}
                className={`rounded-xl p-3 mb-4 text-sm ${
                  feedback.tipo === 'incorrecto'
                    ? 'bg-[#f85149]/10 border border-[#f85149]/30 text-[#f85149]'
                    : feedback.tipo === 'pendiente'
                    ? 'bg-[#a855f7]/10 border border-[#a855f7]/30 text-[#c084fc]'
                    : 'bg-[#58a6ff]/10 border border-[#58a6ff]/30 text-[#58a6ff]'
                }`}
              >
                {feedback.message}
              </motion.div>
            )}

            {/* Pista */}
            {mostrarPista && ejercicio.pista && (
              <motion.div
                initial={{ opacity: 0, height: 0 }}
                animate={{ opacity: 1, height: 'auto' }}
                className="bg-[#d29922]/10 border border-[#d29922]/30 rounded-xl p-3 mb-4 text-sm text-[#d29922]"
              >
                💡 <strong>Pista:</strong> {ejercicio.pista}
              </motion.div>
            )}

            {!mostrarPista && ejercicio.pista && ejercicio.intento >= 1 && (
              <button
                onClick={() => setMostrarPista(true)}
                className="text-xs text-[#484f58] hover:text-[#d29922] mb-4 block transition-colors"
              >
                💡 Ver pista
              </button>
            )}

            {/* Botón enviar */}
            <button
              onClick={handleEnviar}
              disabled={enviando || (!respuesta.trim() && ejercicio.tipo !== 'codigo_libre' && ejercicio.tipo !== 'mini_proyecto')}
              className="w-full py-3 rounded-xl font-bold text-[#0d1117] bg-[#a3e635] hover:bg-[#84cc16] disabled:opacity-40 disabled:cursor-not-allowed transition-all hover:scale-[1.02] active:scale-[0.98]"
            >
              {enviando ? 'Verificando...' :
               ejercicio.tipo === 'codigo_libre' || ejercicio.tipo === 'mini_proyecto'
                 ? '📤 Enviar al papá para revisar'
                 : '✓ Verificar respuesta'}
            </button>
          </>
        )}
      </motion.div>

      {/* Volver */}
      {moduloSlug && (
        <button
          onClick={() => navigate(`/modulos/${moduloSlug}`)}
          className="text-sm text-[#8b949e] hover:text-[#a3e635] transition-colors"
        >
          ← Volver al módulo
        </button>
      )}
    </div>
  )
}
