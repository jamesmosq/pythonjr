import { useEffect } from 'react'
import { useNavigate } from 'react-router-dom'
import { motion } from 'framer-motion'
import { useProgresoStore } from '@/stores/progresoStore'
import { useBilleteraStore } from '@/stores/billeteraStore'
import { useAuthStore } from '@/stores/authStore'
import api from '@/lib/axios'

const estadoColor = {
  completado: 'border-[#3fb950] bg-[#3fb950]/10 text-[#3fb950]',
  en_progreso: 'border-[#a3e635] bg-[#a3e635]/10 text-[#a3e635]',
  disponible: 'border-[#58a6ff] bg-[#58a6ff]/10 text-[#58a6ff]',
  bloqueado: 'border-[#30363d] bg-[#21262d] text-[#484f58]',
}

const estadoIcon = {
  completado: '✓',
  en_progreso: '▶',
  disponible: '○',
  bloqueado: '🔒',
}

function ModuloCard({ modulo, onClick }) {
  const est = modulo.estado ?? 'bloqueado'
  const bloqueado = est === 'bloqueado'

  return (
    <motion.button
      onClick={() => !bloqueado && onClick(modulo.slug)}
      disabled={bloqueado}
      className={`w-full text-left p-4 rounded-xl border transition-all ${estadoColor[est]} ${
        bloqueado ? 'cursor-not-allowed opacity-60' : 'hover:scale-[1.02] active:scale-[0.98]'
      }`}
      whileHover={bloqueado ? {} : { y: -2 }}
    >
      <div className="flex items-center gap-3">
        <span className="text-2xl">{modulo.icono}</span>
        <div className="flex-1 min-w-0">
          <div className="font-semibold text-sm truncate">{modulo.titulo}</div>
          <div className="text-xs opacity-70 mt-0.5">
            Nivel {modulo.nivel} • ${(modulo.recompensa_base ?? 0).toLocaleString('es-CO')}
          </div>
        </div>
        <span className="text-lg flex-shrink-0">{estadoIcon[est]}</span>
      </div>
    </motion.button>
  )
}

export default function Dashboard() {
  const navigate = useNavigate()
  const user = useAuthStore((s) => s.user)
  const { dashboard, loading, fetchDashboard, modulos, fetchModulos } = useProgresoStore()
  const fetchBilletera = useBilleteraStore((s) => s.fetch)

  useEffect(() => {
    fetchDashboard()
    fetchModulos()
    fetchBilletera()
  }, [])

  if (loading && !dashboard) {
    return (
      <div className="flex items-center justify-center py-20">
        <div className="text-4xl animate-float">🐍</div>
      </div>
    )
  }

  const racha = dashboard?.racha
  const desafio = dashboard?.desafio_hoy
  const moduloActual = dashboard?.modulo_actual

  return (
    <div className="space-y-6">
      {/* Saludo */}
      <motion.div
        className="bg-[#161b22] border border-[#30363d] rounded-2xl p-5"
        initial={{ opacity: 0, y: 20 }}
        animate={{ opacity: 1, y: 0 }}
      >
        <div className="flex items-start justify-between">
          <div>
            <h1 className="text-xl font-bold text-[#e6edf3]">
              ¡Hola, {user?.name ?? 'Programador'}! 👋
            </h1>
            {racha?.dias_actuales > 0 ? (
              <p className="text-sm text-[#8b949e] mt-1">
                🔥 Llevas <span className="text-orange-400 font-bold">{racha.dias_actuales} días</span> seguidos. ¡Sigue así!
              </p>
            ) : (
              <p className="text-sm text-[#8b949e] mt-1">¡Completa un ejercicio para empezar tu racha! 🔥</p>
            )}
          </div>
          <span className="text-4xl animate-float">{user?.avatar ?? '🧑‍💻'}</span>
        </div>

        {/* Stats rápidas */}
        <div className="grid grid-cols-3 gap-3 mt-4">
          <div className="bg-[#21262d] rounded-xl p-3 text-center">
            <div className="text-lg font-black text-[#a3e635]">
              ${(dashboard?.billetera?.saldo_total ?? 0).toLocaleString('es-CO')}
            </div>
            <div className="text-xs text-[#8b949e]">Acumulado</div>
          </div>
          <div className="bg-[#21262d] rounded-xl p-3 text-center">
            <div className="text-lg font-black text-orange-400">{racha?.dias_actuales ?? 0}</div>
            <div className="text-xs text-[#8b949e]">Días racha</div>
          </div>
          <div className="bg-[#21262d] rounded-xl p-3 text-center">
            <div className="text-lg font-black text-[#58a6ff]">{dashboard?.ejercicios_hoy ?? 0}</div>
            <div className="text-xs text-[#8b949e]">Hoy</div>
          </div>
        </div>
      </motion.div>

      {/* Desafío del día */}
      {desafio && !desafio.completado && (
        <motion.div
          className="bg-gradient-to-r from-[#a855f7]/20 to-[#7c3aed]/10 border border-[#a855f7]/40 rounded-2xl p-5"
          initial={{ opacity: 0, x: -20 }}
          animate={{ opacity: 1, x: 0 }}
          transition={{ delay: 0.1 }}
        >
          <div className="flex items-center justify-between">
            <div>
              <div className="flex items-center gap-2 text-[#c084fc] text-sm font-semibold mb-1">
                <span>⚡</span> DESAFÍO DE HOY
              </div>
              <div className="font-bold text-[#e6edf3]">{desafio.titulo}</div>
              <div className="text-[#a3e635] font-black mt-1">+${desafio.recompensa.toLocaleString('es-CO')}</div>
            </div>
            <button
              onClick={() => navigate(`/desafio`)}
              className="bg-[#a855f7] hover:bg-[#9333ea] text-white font-bold px-4 py-2 rounded-xl transition-all hover:scale-105 active:scale-95 text-sm"
            >
              Hacer →
            </button>
          </div>
        </motion.div>
      )}

      {/* Continúa donde ibas */}
      {moduloActual && (
        <motion.div
          className="bg-[#161b22] border border-[#a3e635]/30 rounded-2xl p-5"
          initial={{ opacity: 0, y: 20 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ delay: 0.15 }}
        >
          <div className="text-xs text-[#8b949e] mb-2 font-semibold uppercase tracking-wide">
            📍 Continúa donde ibas
          </div>
          <div className="flex items-center justify-between">
            <div className="flex items-center gap-3">
              <span className="text-3xl">{moduloActual.icono}</span>
              <div>
                <div className="font-bold text-[#e6edf3]">{moduloActual.titulo}</div>
                <div className="text-xs text-[#8b949e] capitalize">{moduloActual.estado?.replace('_', ' ')}</div>
              </div>
            </div>
            <button
              onClick={() => navigate(`/modulos/${moduloActual.slug}`)}
              className="bg-[#a3e635] hover:bg-[#84cc16] text-[#0d1117] font-bold px-4 py-2 rounded-xl transition-all hover:scale-105 active:scale-95 text-sm"
            >
              Continuar →
            </button>
          </div>
        </motion.div>
      )}

      {/* Todos los módulos */}
      <motion.div
        initial={{ opacity: 0, y: 20 }}
        animate={{ opacity: 1, y: 0 }}
        transition={{ delay: 0.2 }}
      >
        <h2 className="text-sm font-semibold text-[#8b949e] uppercase tracking-wide mb-3">
          🏆 Módulos del curso
        </h2>
        <div className="grid grid-cols-1 sm:grid-cols-2 gap-3">
          {modulos.map((mod, i) => (
            <motion.div
              key={mod.slug}
              initial={{ opacity: 0, y: 10 }}
              animate={{ opacity: 1, y: 0 }}
              transition={{ delay: 0.1 + i * 0.05 }}
            >
              <ModuloCard modulo={mod} onClick={(slug) => navigate(`/modulos/${slug}`)} />
            </motion.div>
          ))}
        </div>
      </motion.div>

      {/* Navegación rápida */}
      <div className="grid grid-cols-2 gap-3 pb-4">
        <button
          onClick={() => navigate('/billetera')}
          className="bg-[#161b22] border border-[#30363d] rounded-xl p-4 text-left hover:border-[#a3e635] transition-colors"
        >
          <div className="text-2xl mb-1">💰</div>
          <div className="text-sm font-semibold text-[#e6edf3]">Mi billetera</div>
          <div className="text-xs text-[#8b949e]">Ver ganancias</div>
        </button>
        <button
          onClick={() => navigate('/logros')}
          className="bg-[#161b22] border border-[#30363d] rounded-xl p-4 text-left hover:border-[#a855f7] transition-colors"
        >
          <div className="text-2xl mb-1">🏅</div>
          <div className="text-sm font-semibold text-[#e6edf3]">Mis logros</div>
          <div className="text-xs text-[#8b949e]">Ver badges</div>
        </button>
      </div>
    </div>
  )
}
