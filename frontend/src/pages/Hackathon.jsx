import { useEffect, useState, useCallback } from 'react'
import { useNavigate } from 'react-router-dom'
import { motion, AnimatePresence } from 'framer-motion'
import api from '@/lib/axios'
import CelebracionModal from '@/components/gamification/CelebracionModal'
import { useBilleteraStore } from '@/stores/billeteraStore'
import ReactMarkdown from 'react-markdown'
import remarkGfm from 'remark-gfm'
import CodigoEjemplo from '@/components/contenido/CodigoEjemplo'

function Countdown({ endsAt }) {
  const [segundos, setSegundos] = useState(null)

  useEffect(() => {
    if (!endsAt) return
    const calc = () => Math.max(0, Math.floor((new Date(endsAt) - Date.now()) / 1000))
    setSegundos(calc())
    const interval = setInterval(() => setSegundos(calc()), 1000)
    return () => clearInterval(interval)
  }, [endsAt])

  if (segundos === null) return null

  const h = Math.floor(segundos / 3600)
  const m = Math.floor((segundos % 3600) / 60)
  const s = segundos % 60

  const urgente = segundos < 600

  return (
    <div className={`font-black text-2xl tabular-nums ${urgente ? 'text-[#f85149] animate-pulse' : 'text-[#facc15]'}`}>
      {h > 0 && `${h}h `}{String(m).padStart(2, '0')}:{String(s).padStart(2, '0')}
    </div>
  )
}

export default function Hackathon() {
  const navigate = useNavigate()
  const [hackathon, setHackathon] = useState(null)
  const [loading, setLoading] = useState(true)
  const [ejercicioActual, setEjercicioActual] = useState(0)
  const [respuesta, setRespuesta] = useState('')
  const [enviando, setEnviando] = useState(false)
  const [feedback, setFeedback] = useState(null)
  const [celebracion, setCelebracion] = useState(null)
  const [mostrarPista, setMostrarPista] = useState(false)
  const updateSaldo = useBilleteraStore((s) => s.updateSaldo)

  const cargar = useCallback(() => {
    api.get('/hackathon/activo')
      .then(({ data }) => setHackathon(data.data))
      .finally(() => setLoading(false))
  }, [])

  useEffect(() => { cargar() }, [cargar])

  async function handleEnviar() {
    const ej = hackathon.ejercicios[ejercicioActual]?.ejercicio
    if (!ej) return

    setEnviando(true)
    setFeedback(null)
    try {
      const payload = { respuesta, hackathon_id: hackathon.id }
      const { data } = await api.post(`/ejercicios/${ej.id}/intentar`, payload)

      if (ej.tipo === 'mini_proyecto' || ej.tipo === 'codigo_libre') {
        setFeedback({ tipo: 'pendiente', message: data.message })
        return
      }

      if (!data.data.es_correcto) {
        setFeedback({ tipo: 'incorrecto', message: data.message })
        if (data.data.intento >= 2) setMostrarPista(true)
        return
      }

      if (data.meta?.billetera_total) updateSaldo(data.meta.billetera_total)
      setCelebracion({
        recompensa: data.meta?.recompensa_ganada ?? 0,
        esPerfecto: data.data.es_perfecto,
        nuevosLogros: data.meta?.nuevos_logros ?? [],
      })
    } catch (err) {
      setFeedback({ tipo: 'error', message: err.response?.data?.message ?? 'Error al enviar' })
    } finally {
      setEnviando(false)
    }
  }

  function onCierreModal() {
    setCelebracion(null)
    setRespuesta('')
    setFeedback(null)
    setMostrarPista(false)
    cargar()
    // Avanzar al siguiente ejercicio no completado
    if (hackathon) {
      const siguiente = hackathon.ejercicios.findIndex((e, i) => i > ejercicioActual && !e.completado)
      if (siguiente !== -1) setEjercicioActual(siguiente)
    }
  }

  if (loading) return (
    <div className="flex items-center justify-center py-20">
      <div className="text-4xl animate-float">🏆</div>
    </div>
  )

  if (!hackathon) return (
    <div className="max-w-lg mx-auto text-center py-20 space-y-4">
      <div className="text-6xl">😴</div>
      <div className="text-[#e6edf3] font-bold text-xl">No hay hackathon activo</div>
      <div className="text-[#8b949e] text-sm">Cuando papá active uno, aparecerá aquí.</div>
      <button onClick={() => navigate('/dashboard')} className="text-[#a3e635] underline text-sm">Volver al dashboard</button>
    </div>
  )

  if (hackathon.finalizado) return (
    <div className="max-w-lg mx-auto space-y-5">
      <motion.div
        className="bg-gradient-to-b from-[#facc15]/20 to-[#161b22] border border-[#facc15]/40 rounded-2xl p-6 text-center space-y-4"
        initial={{ opacity: 0, scale: 0.9 }}
        animate={{ opacity: 1, scale: 1 }}
      >
        <div className="text-6xl">🏆</div>
        <h1 className="text-2xl font-black text-[#facc15]">¡Hackathon completado!</h1>
        <div className="text-[#e6edf3] font-bold text-lg">{hackathon.nombre}</div>
        <div className="text-3xl font-black text-[#a3e635]">
          +${hackathon.recompensa_ganada.toLocaleString('es-CO')}
        </div>
        {hackathon.reporte_ia && (
          <div className="bg-[#21262d] rounded-xl p-4 text-left text-sm text-[#c9d1d9] leading-relaxed">
            <div className="text-xs text-[#8b949e] mb-2 font-semibold uppercase tracking-wide">📋 Reporte de tu tutor IA</div>
            {hackathon.reporte_ia}
          </div>
        )}
        <button onClick={() => navigate('/dashboard')} className="w-full py-3 bg-[#a3e635] text-[#0d1117] font-bold rounded-xl hover:bg-[#84cc16] transition-all">
          Volver al dashboard
        </button>
      </motion.div>
    </div>
  )

  const totalEjercicios = hackathon.ejercicios.length
  const completados = hackathon.completados
  const ejItem = hackathon.ejercicios[ejercicioActual]
  const ej = ejItem?.ejercicio

  return (
    <div className="max-w-2xl mx-auto space-y-4">
      <CelebracionModal
        open={!!celebracion}
        onClose={onCierreModal}
        recompensa={celebracion?.recompensa ?? 0}
        esPerfecto={celebracion?.esPerfecto ?? false}
        nuevosLogros={celebracion?.nuevosLogros ?? []}
      />

      {/* Header del hackathon */}
      <motion.div
        className="bg-gradient-to-r from-[#facc15]/15 to-[#161b22] border border-[#facc15]/40 rounded-2xl p-4"
        initial={{ opacity: 0, y: -10 }}
        animate={{ opacity: 1, y: 0 }}
      >
        <div className="flex items-center justify-between">
          <div>
            <div className="text-xs text-[#facc15] font-black uppercase tracking-wide mb-1">🏆 Hackathon</div>
            <div className="font-bold text-[#e6edf3]">{hackathon.nombre}</div>
            <div className="text-xs text-[#8b949e] mt-0.5">
              {completados}/{totalEjercicios} ejercicios •{' '}
              <span className="text-[#a3e635]">+${hackathon.recompensa_base.toLocaleString('es-CO')}</span>
            </div>
          </div>
          <div className="text-right">
            {hackathon.ends_at && <Countdown endsAt={hackathon.ends_at} />}
          </div>
        </div>
        <div className="mt-3 h-1.5 bg-[#21262d] rounded-full overflow-hidden">
          <motion.div
            className="h-full bg-gradient-to-r from-[#facc15] to-[#f59e0b]"
            animate={{ width: `${(completados / totalEjercicios) * 100}%` }}
            transition={{ duration: 0.5 }}
          />
        </div>
      </motion.div>

      {/* Selector de ejercicios */}
      <div className="flex gap-2 overflow-x-auto pb-1">
        {hackathon.ejercicios.map((item, i) => (
          <button
            key={i}
            onClick={() => { setEjercicioActual(i); setRespuesta(''); setFeedback(null); setMostrarPista(false) }}
            className={`flex-shrink-0 w-10 h-10 rounded-xl font-bold text-sm transition-all ${
              item.completado
                ? 'bg-[#3fb950] text-[#0d1117]'
                : i === ejercicioActual
                ? 'bg-[#facc15] text-[#0d1117]'
                : 'bg-[#21262d] text-[#8b949e] border border-[#30363d]'
            }`}
          >
            {item.completado ? '✓' : i + 1}
          </button>
        ))}
      </div>

      {/* Ejercicio actual */}
      {ej && (
        <motion.div
          key={ejercicioActual}
          className="bg-[#161b22] border border-[#30363d] rounded-2xl p-6 space-y-4"
          initial={{ opacity: 0, x: 20 }}
          animate={{ opacity: 1, x: 0 }}
        >
          <div className="flex items-start justify-between gap-3">
            <div>
              <div className="text-xs text-[#8b949e] capitalize mb-1 bg-[#21262d] inline-block px-2 py-0.5 rounded-md">
                {ej.tipo?.replace('_', ' ')}
              </div>
              <h2 className="font-bold text-[#e6edf3] text-lg">{ej.titulo}</h2>
            </div>
            <div className="text-[#a3e635] font-black text-lg flex-shrink-0">
              +${ej.recompensa_ejercicio?.toLocaleString('es-CO')}
            </div>
          </div>

          <div className="md text-sm">
            <ReactMarkdown
              remarkPlugins={[remarkGfm]}
              components={{
                code({ node, inline, className, children }) {
                  const match = /language-(\w+)/.exec(className || '')
                  const code = String(children).replace(/\n$/, '')
                  if (!inline && (match || code.includes('\n'))) {
                    return <CodigoEjemplo codigo={code} lenguaje={match?.[1] ?? 'bash'} altura={Math.min(code.split('\n').length * 22 + 40, 250)} />
                  }
                  return <code className={className}>{children}</code>
                },
              }}
            >
              {ej.enunciado}
            </ReactMarkdown>
          </div>

          {ejItem.completado ? (
            <div className="bg-[#3fb950]/10 border border-[#3fb950]/30 rounded-xl p-3 text-center text-[#3fb950] font-bold">
              ✅ Completado
            </div>
          ) : (
            <>
              {ej.tipo === 'quiz_opcion' && (
                <div className="space-y-2">
                  {ej.opciones?.map((op) => (
                    <button
                      key={op.id}
                      onClick={() => setRespuesta(String(op.id))}
                      className={`w-full text-left px-4 py-3 rounded-xl border transition-all text-sm ${
                        respuesta === String(op.id)
                          ? 'border-[#facc15] bg-[#facc15]/10 text-[#facc15]'
                          : 'border-[#30363d] bg-[#21262d] text-[#c9d1d9] hover:border-[#484f58]'
                      }`}
                    >
                      {op.texto}
                    </button>
                  ))}
                </div>
              )}

              {ej.tipo === 'quiz_texto' && (
                <input
                  type="text"
                  value={respuesta}
                  onChange={(e) => setRespuesta(e.target.value)}
                  onKeyDown={(e) => e.key === 'Enter' && handleEnviar()}
                  placeholder="Escribe tu respuesta..."
                  className="w-full bg-[#21262d] border border-[#30363d] rounded-xl px-4 py-3 text-[#e6edf3] placeholder-[#484f58] focus:outline-none focus:border-[#facc15] font-mono"
                  autoFocus
                />
              )}

              {(ej.tipo === 'terminal_git' || ej.tipo === 'codigo_libre' || ej.tipo === 'mini_proyecto') && (
                <textarea
                  value={respuesta}
                  onChange={(e) => setRespuesta(e.target.value)}
                  placeholder={ej.tipo === 'terminal_git' ? 'Pega aquí el output de tu terminal...' : 'Pega aquí tu respuesta...'}
                  rows={5}
                  className="w-full bg-[#21262d] border border-[#30363d] rounded-xl px-4 py-3 text-[#e6edf3] placeholder-[#484f58] focus:outline-none focus:border-[#facc15] font-mono text-sm resize-none"
                />
              )}

              <AnimatePresence>
                {feedback && (
                  <motion.div
                    initial={{ opacity: 0, y: -8 }}
                    animate={{ opacity: 1, y: 0 }}
                    exit={{ opacity: 0 }}
                    className={`rounded-xl p-3 text-sm ${
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
              </AnimatePresence>

              {mostrarPista && ej.pista && (
                <div className="bg-[#d29922]/10 border border-[#d29922]/30 rounded-xl p-3 text-sm text-[#d29922]">
                  💡 <strong>Pista:</strong> {ej.pista}
                </div>
              )}

              <button
                onClick={handleEnviar}
                disabled={enviando || (!respuesta.trim() && ej.tipo !== 'mini_proyecto')}
                className="w-full py-3 rounded-xl font-bold text-[#0d1117] bg-[#facc15] hover:bg-yellow-300 disabled:opacity-40 transition-all hover:scale-[1.02] active:scale-[0.98]"
              >
                {enviando
                  ? 'Verificando...'
                  : ej.tipo === 'terminal_git'
                  ? '🤖 Que la IA evalúe'
                  : ej.tipo === 'mini_proyecto'
                  ? '📤 Enviar a papá'
                  : '✓ Verificar'}
              </button>
            </>
          )}
        </motion.div>
      )}
    </div>
  )
}
