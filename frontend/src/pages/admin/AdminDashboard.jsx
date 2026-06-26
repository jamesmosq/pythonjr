import { useEffect, useState } from 'react'
import { useNavigate } from 'react-router-dom'
import { motion } from 'framer-motion'
import api from '@/lib/axios'
import { useAuthStore } from '@/stores/authStore'

const tipoColor = {
  modulo_base:         'text-[#a3e635]',
  ejercicio:           'text-[#3fb950]',
  perfecto:            'text-[#facc15]',
  racha_3d:            'text-orange-400',
  racha_7d:            'text-orange-500',
  velocidad_modulo:    'text-[#58a6ff]',
  velocidad_nivel:     'text-[#a855f7]',
  desafio_dia:         'text-[#c084fc]',
  bono_sorpresa:       'text-[#f472b6]',
  meta_semanal:        'text-[#34d399]',
  hackathon_base:      'text-[#facc15]',
  hackathon_velocidad: 'text-[#facc15]',
  hackathon_perfecto:  'text-[#facc15]',
  pago_padre:          'text-[#f85149]',
}

const estadoColor = {
  completado:  'bg-[#3fb950]/20 text-[#3fb950] border-[#3fb950]/30',
  en_progreso: 'bg-[#a3e635]/20 text-[#a3e635] border-[#a3e635]/30',
  disponible:  'bg-[#58a6ff]/20 text-[#58a6ff] border-[#58a6ff]/30',
  bloqueado:   'bg-[#21262d] text-[#484f58] border-[#30363d]',
}

function MiniBar({ valor, max, color = 'bg-[#a3e635]' }) {
  const pct = max > 0 ? Math.min(100, (valor / max) * 100) : 0
  return (
    <div className="h-1.5 bg-[#21262d] rounded-full overflow-hidden">
      <motion.div
        className={`h-full rounded-full ${color}`}
        initial={{ width: 0 }}
        animate={{ width: `${pct}%` }}
        transition={{ duration: 0.8, ease: 'easeOut' }}
      />
    </div>
  )
}

function GraficoSemana({ datos }) {
  const max = Math.max(...datos.map((d) => d.ejercicios), 1)
  return (
    <div className="flex items-end gap-1.5 h-16">
      {datos.map((d, i) => (
        <div key={i} className="flex-1 flex flex-col items-center gap-1">
          <div className="w-full relative flex flex-col justify-end" style={{ height: 52 }}>
            <motion.div
              className={`w-full rounded-t-md ${d.ejercicios > 0 ? 'bg-[#a3e635]' : 'bg-[#21262d]'}`}
              initial={{ height: 0 }}
              animate={{ height: `${d.ejercicios > 0 ? Math.max(8, (d.ejercicios / max) * 52) : 4}px` }}
              transition={{ delay: i * 0.06, duration: 0.5 }}
              title={`${d.ejercicios} ejercicios`}
            />
          </div>
          <span className="text-[9px] text-[#484f58] capitalize">{d.dia}</span>
        </div>
      ))}
    </div>
  )
}

export default function AdminDashboard() {
  const adminUser    = useAuthStore((s) => s.user)
  const [data, setData]                 = useState(null)
  const [estudiantes, setEstudiantes]   = useState([])
  const [estudianteId, setEstudianteId] = useState(null)
  const navigate = useNavigate()

  useEffect(() => {
    api.get('/admin/estudiantes').then(({ data }) => {
      setEstudiantes(data.data)
      if (data.data.length > 0) setEstudianteId(data.data[0].id)
    })
  }, [])

  useEffect(() => {
    if (!estudianteId) return
    api.get(`/admin/dashboard?estudiante_id=${estudianteId}`).then(({ data }) => setData(data.data))
  }, [estudianteId])

  // Destructure with defaults so the header renders safely before data loads
  const {
    estudiante        = {},
    billetera         = {},
    racha             = {},
    estadisticas      = {},
    progreso_modulos  = [],
    actividad_reciente = [],
    progreso_semanal  = [],
    historial_ingresos = [],
  } = data ?? {}

  const diasDesdeInicio = estadisticas.primer_actividad
    ? Math.ceil((Date.now() - new Date(estadisticas.primer_actividad)) / 86400000)
    : 0

  return (
    <div className="max-w-2xl mx-auto space-y-4">

      {/* Header — admin + acciones (siempre visible) */}
      <div className="flex items-center justify-between">
        <div className="flex items-center gap-2.5">
          <span className="text-2xl">{adminUser?.avatar ?? '👨‍💻'}</span>
          <div>
            <h1 className="text-base font-black text-[#e6edf3] leading-tight">
              Hola, {adminUser?.name ?? 'Admin'} 👋
            </h1>
            <p className="text-[10px] text-[#484f58]">Panel de seguimiento</p>
          </div>
        </div>
        <div className="flex items-center gap-2">
          <button
            onClick={() => navigate('/admin/configuracion')}
            className="text-[#484f58] hover:text-[#8b949e] transition-colors p-1"
            title="Configuración y perfil"
          >
            ⚙️
          </button>
          <button
            onClick={() => navigate('/admin/validaciones')}
            className="relative bg-[#a3e635] text-[#0d1117] font-bold px-3 py-2 rounded-xl text-sm hover:bg-[#84cc16] transition-all"
          >
            Validar
            {(estadisticas.pendientes_validacion ?? 0) > 0 && (
              <span className="absolute -top-1.5 -right-1.5 w-5 h-5 bg-[#f85149] text-white text-xs rounded-full flex items-center justify-center font-black">
                {estadisticas.pendientes_validacion}
              </span>
            )}
          </button>
        </div>
      </div>

      {/* Selector de estudiante (siempre visible cuando hay datos de lista) */}
      {estudiantes.length > 1 ? (
        <div className="flex gap-2">
          {estudiantes.map((est) => (
            <button
              key={est.id}
              onClick={() => { setData(null); setEstudianteId(est.id) }}
              className={`flex items-center gap-1.5 px-3 py-2 rounded-xl text-sm font-medium transition-all border ${
                estudianteId === est.id
                  ? 'bg-[#a3e635]/10 border-[#a3e635] text-[#a3e635]'
                  : 'bg-[#161b22] border-[#30363d] text-[#8b949e] hover:border-[#484f58]'
              }`}
            >
              <span>{est.avatar}</span>
              <span>{est.nombre}</span>
            </button>
          ))}
        </div>
      ) : estudiantes.length === 1 ? (
        <div className="flex items-center gap-3 bg-[#161b22] border border-[#30363d] rounded-xl px-4 py-2.5">
          <span className="text-2xl">{estudiantes[0].avatar}</span>
          <div>
            <div className="text-sm font-bold text-[#e6edf3]">{estudiantes[0].nombre}</div>
            {data && (
              <div className="text-[10px] text-[#8b949e]">
                🔥 {racha.dias_actuales} días · máx {racha.dias_maximos}
                {diasDesdeInicio > 0 && ` · ${diasDesdeInicio} días estudiando`}
              </div>
            )}
          </div>
        </div>
      ) : null}

      {/* Spinner mientras carga datos de estudiante */}
      {!data && (
        <div className="flex justify-center py-12">
          <div className="text-4xl animate-float">📊</div>
        </div>
      )}

      {/* ── Secciones de datos — solo cuando data está disponible ── */}
      {data && (
        <>
          {/* Dinero — 5 tarjetas */}
          <div className="grid grid-cols-2 sm:grid-cols-5 gap-2">
            {[
              { label: 'Total acumulado', value: billetera.saldo_total,          color: 'text-[#a3e635]' },
              { label: 'Pendiente pago',  value: billetera.saldo_pendiente,      color: 'text-[#facc15]' },
              { label: 'Ya pagado',       value: billetera.saldo_pagado,         color: 'text-[#3fb950]' },
              { label: 'Esta semana',     value: billetera.ingresos_esta_semana, color: 'text-[#58a6ff]' },
              { label: 'Este mes',        value: billetera.ingresos_este_mes,    color: 'text-[#c084fc]' },
            ].map((s) => (
              <div key={s.label} className="bg-[#161b22] border border-[#30363d] rounded-xl p-3 text-center">
                <div className={`text-base font-black ${s.color}`}>
                  ${(s.value ?? 0).toLocaleString('es-CO')}
                </div>
                <div className="text-[10px] text-[#8b949e] leading-tight mt-0.5">{s.label}</div>
              </div>
            ))}
          </div>

          {/* Métricas de aprendizaje */}
          <div className="grid grid-cols-2 sm:grid-cols-4 gap-2">
            {[
              { label: 'Módulos OK',       value: estadisticas.modulos_completados,        icon: '🏆', color: 'text-[#a3e635]' },
              { label: 'Ejercicios',        value: estadisticas.ejercicios_totales,         icon: '✅', color: 'text-[#3fb950]' },
              { label: '% Perfectos',       value: `${estadisticas.porcentaje_perfecto}%`,  icon: '⭐', color: 'text-[#facc15]' },
              { label: 'Días activos/mes',  value: estadisticas.dias_activos_mes,           icon: '📅', color: 'text-[#58a6ff]' },
            ].map((s) => (
              <div key={s.label} className="bg-[#161b22] border border-[#30363d] rounded-xl p-3 text-center">
                <div className="text-xl mb-0.5">{s.icon}</div>
                <div className={`text-lg font-black ${s.color}`}>{s.value}</div>
                <div className="text-[10px] text-[#8b949e]">{s.label}</div>
              </div>
            ))}
          </div>

          {/* Gráfico semanal */}
          <div className="bg-[#161b22] border border-[#30363d] rounded-2xl p-4">
            <div className="flex items-center justify-between mb-3">
              <h2 className="text-sm font-semibold text-[#8b949e] uppercase tracking-wide">📊 Esta semana</h2>
              <span className="text-xs text-[#484f58]">
                promedio {estadisticas.promedio_por_dia} ej/día activo
              </span>
            </div>
            <GraficoSemana datos={progreso_semanal} />
            <div className="flex justify-between mt-2">
              {progreso_semanal.map((d, i) => (
                <div key={i} className="flex-1 text-center">
                  {d.ejercicios > 0 && (
                    <span className="text-[10px] font-bold text-[#a3e635]">{d.ejercicios}</span>
                  )}
                </div>
              ))}
            </div>
          </div>

          {/* Progreso por módulo */}
          <div className="bg-[#161b22] border border-[#30363d] rounded-2xl p-4 space-y-3">
            <h2 className="text-sm font-semibold text-[#8b949e] uppercase tracking-wide">📚 Progreso por módulo</h2>
            {progreso_modulos.map((mod, i) => (
              <motion.div
                key={mod.slug}
                initial={{ opacity: 0, x: -10 }}
                animate={{ opacity: 1, x: 0 }}
                transition={{ delay: i * 0.04 }}
                className="space-y-1"
              >
                <div className="flex items-center justify-between">
                  <div className="flex items-center gap-2">
                    <span className="text-base">{mod.icono}</span>
                    <span className="text-sm text-[#c9d1d9] truncate max-w-[180px]">{mod.titulo}</span>
                  </div>
                  <div className="flex items-center gap-2 flex-shrink-0">
                    <span className="text-xs text-[#484f58]">
                      {mod.completados}/{mod.total_ejercicios}
                    </span>
                    <span className={`text-[10px] font-bold px-1.5 py-0.5 rounded-full border ${estadoColor[mod.estado]}`}>
                      {mod.estado.replace('_', ' ')}
                    </span>
                  </div>
                </div>
                <MiniBar
                  valor={mod.completados}
                  max={mod.total_ejercicios}
                  color={mod.estado === 'completado' ? 'bg-[#3fb950]' : mod.estado === 'en_progreso' ? 'bg-[#a3e635]' : 'bg-[#484f58]'}
                />
              </motion.div>
            ))}
          </div>

          {/* Actividad reciente */}
          <div className="bg-[#161b22] border border-[#30363d] rounded-2xl p-4 space-y-2">
            <h2 className="text-sm font-semibold text-[#8b949e] uppercase tracking-wide mb-3">🕐 Actividad reciente</h2>
            {actividad_reciente.length === 0 ? (
              <p className="text-sm text-[#484f58] text-center py-4">Sin actividad aún</p>
            ) : (
              actividad_reciente.map((a, i) => (
                <div key={i} className="flex items-center justify-between py-1.5 border-b border-[#21262d] last:border-0">
                  <div className="flex items-center gap-2 min-w-0">
                    <span className="text-base flex-shrink-0">
                      {a.es_perfecto ? '⭐' : a.es_correcto ? '✅' : '❌'}
                    </span>
                    <div className="min-w-0">
                      <div className="text-sm text-[#c9d1d9] truncate">{a.ejercicio_titulo}</div>
                      <div className="text-[10px] text-[#484f58] flex items-center gap-1">
                        {a.modulo_titulo}
                        {a.validado_por_ia && <span className="text-[#a855f7]">· IA</span>}
                        {a.intento > 1 && <span>· {a.intento} intentos</span>}
                      </div>
                    </div>
                  </div>
                  <div className="text-right flex-shrink-0 ml-2">
                    {a.recompensa > 0 && (
                      <div className="text-xs font-bold text-[#a3e635]">+${a.recompensa.toLocaleString('es-CO')}</div>
                    )}
                    <div className="text-[10px] text-[#484f58]">
                      {new Date(a.completado_at).toLocaleDateString('es-CO', { day: '2-digit', month: 'short' })}
                    </div>
                  </div>
                </div>
              ))
            )}
          </div>

          {/* Historial de ingresos */}
          <div className="bg-[#161b22] border border-[#30363d] rounded-2xl p-4 space-y-2">
            <h2 className="text-sm font-semibold text-[#8b949e] uppercase tracking-wide mb-3">💰 Historial de ingresos</h2>
            {historial_ingresos.length === 0 ? (
              <p className="text-sm text-[#484f58] text-center py-4">Sin transacciones aún</p>
            ) : (
              historial_ingresos.map((t, i) => (
                <div key={i} className="flex items-center justify-between py-1.5 border-b border-[#21262d] last:border-0">
                  <div className="min-w-0">
                    <div className="text-sm text-[#c9d1d9] truncate">{t.descripcion}</div>
                    <div className="text-[10px] text-[#484f58]">
                      {new Date(t.created_at).toLocaleDateString('es-CO', { day: '2-digit', month: 'short', hour: '2-digit', minute: '2-digit' })}
                    </div>
                  </div>
                  <div className={`font-black text-sm flex-shrink-0 ml-2 ${t.monto > 0 ? (tipoColor[t.tipo] ?? 'text-[#3fb950]') : 'text-[#f85149]'}`}>
                    {t.monto > 0 ? '+' : ''}${Math.abs(t.monto).toLocaleString('es-CO')}
                  </div>
                </div>
              ))
            )}
          </div>

          {/* Pago rápido */}
          {(billetera.saldo_pendiente ?? 0) > 0 && (
            <PagoRapido
              saldo={billetera.saldo_pendiente}
              nombre={estudiante.nombre}
              estudianteId={estudianteId}
              onPago={() => api.get(`/admin/dashboard?estudiante_id=${estudianteId}`).then(({ data }) => setData(data.data))}
            />
          )}

          {/* Acciones */}
          <div className="grid grid-cols-2 gap-3 pb-4">
            <button
              onClick={() => navigate('/admin/hackathon')}
              className="bg-[#facc15]/10 border border-[#facc15]/30 rounded-2xl p-4 text-left hover:border-[#facc15] transition-colors"
            >
              <div className="text-2xl mb-1">🏆</div>
              <div className="text-sm font-bold text-[#facc15]">Hackathon</div>
              <div className="text-xs text-[#8b949e]">Activar desafío especial</div>
            </button>
            <button
              onClick={() => navigate('/admin/configuracion')}
              className="bg-[#161b22] border border-[#30363d] rounded-2xl p-4 text-left hover:border-[#a3e635] transition-colors"
            >
              <div className="text-2xl mb-1">⚙️</div>
              <div className="text-sm font-bold text-[#e6edf3]">Configuración</div>
              <div className="text-xs text-[#8b949e]">Recompensas y bonos</div>
            </button>
          </div>
        </>
      )}
    </div>
  )
}

function PagoRapido({ saldo, nombre, estudianteId, onPago }) {
  const [monto, setMonto]       = useState(String(saldo))
  const [enviando, setEnviando] = useState(false)
  const [ok, setOk]             = useState(false)

  async function handlePagar() {
    setEnviando(true)
    try {
      await api.post('/admin/pagar', { monto: parseInt(monto), estudiante_id: estudianteId })
      setOk(true)
      onPago()
    } finally {
      setEnviando(false)
    }
  }

  return (
    <div className="bg-[#161b22] border border-[#a3e635]/30 rounded-2xl p-4">
      <h3 className="font-bold text-[#e6edf3] mb-3 text-sm">💸 Registrar pago a {nombre}</h3>
      {ok ? (
        <div className="text-[#3fb950] text-sm font-semibold">✅ Pago registrado</div>
      ) : (
        <div className="flex gap-3">
          <input
            type="number"
            value={monto}
            onChange={(e) => setMonto(e.target.value)}
            className="flex-1 bg-[#21262d] border border-[#30363d] rounded-xl px-3 py-2 text-[#e6edf3] focus:outline-none focus:border-[#a3e635] text-sm"
          />
          <button
            onClick={handlePagar}
            disabled={enviando || !monto}
            className="bg-[#a3e635] text-[#0d1117] font-bold px-5 py-2 rounded-xl hover:bg-[#84cc16] disabled:opacity-40 transition-all text-sm"
          >
            {enviando ? '...' : 'Pagar'}
          </button>
        </div>
      )}
    </div>
  )
}
