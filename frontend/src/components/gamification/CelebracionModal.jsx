import { motion, AnimatePresence } from 'framer-motion'
import { useEffect, useState } from 'react'

function Confetti() {
  const pieces = Array.from({ length: 30 }, (_, i) => i)
  const colors = ['#a3e635', '#a855f7', '#facc15', '#3b82f6', '#f43f5e']

  return (
    <div className="pointer-events-none fixed inset-0 overflow-hidden z-50">
      {pieces.map((i) => (
        <motion.div
          key={i}
          className="absolute w-2 h-2 rounded-sm"
          style={{
            background: colors[i % colors.length],
            left: `${Math.random() * 100}%`,
            top: '-10px',
          }}
          animate={{
            y: ['0vh', '110vh'],
            x: [0, (Math.random() - 0.5) * 200],
            rotate: [0, Math.random() * 720],
            opacity: [1, 1, 0],
          }}
          transition={{
            duration: 2 + Math.random() * 1.5,
            delay: Math.random() * 0.8,
            ease: 'easeIn',
          }}
        />
      ))}
    </div>
  )
}

export default function CelebracionModal({ open, onClose, tipo = 'correcto', recompensa = 0, esPerfecto = false, nuevosLogros = [] }) {
  const [showConfetti, setShowConfetti] = useState(false)

  useEffect(() => {
    if (open) {
      setShowConfetti(true)
      const t = setTimeout(() => setShowConfetti(false), 3000)
      return () => clearTimeout(t)
    }
  }, [open])

  const config = {
    correcto: { emoji: '🎉', titulo: '¡Correcto!', color: '#3fb950', bg: 'from-green-900/40' },
    perfecto: { emoji: '⭐', titulo: '¡PERFECTO!', color: '#facc15', bg: 'from-yellow-900/40' },
    modulo: { emoji: '🏆', titulo: '¡Módulo completado!', color: '#a3e635', bg: 'from-lime-900/40' },
    logro: { emoji: '🏅', titulo: '¡Nuevo logro!', color: '#a855f7', bg: 'from-purple-900/40' },
  }

  const cfg = config[esPerfecto ? 'perfecto' : tipo] ?? config.correcto

  return (
    <AnimatePresence>
      {open && (
        <>
          {showConfetti && <Confetti />}
          <motion.div
            className="fixed inset-0 z-50 flex items-center justify-center p-4"
            initial={{ opacity: 0 }}
            animate={{ opacity: 1 }}
            exit={{ opacity: 0 }}
          >
            <div className="absolute inset-0 bg-black/70" onClick={onClose} />
            <motion.div
              className={`relative z-10 bg-gradient-to-b ${cfg.bg} to-[#161b22] border border-[#30363d] rounded-2xl p-8 max-w-sm w-full text-center shadow-2xl`}
              initial={{ scale: 0.5, y: 50 }}
              animate={{ scale: 1, y: 0 }}
              exit={{ scale: 0.8, opacity: 0 }}
              transition={{ type: 'spring', stiffness: 400, damping: 25 }}
            >
              <motion.div
                className="text-7xl mb-4"
                animate={{ rotate: [0, -10, 10, -10, 10, 0], scale: [1, 1.2, 1] }}
                transition={{ duration: 0.6, delay: 0.2 }}
              >
                {cfg.emoji}
              </motion.div>

              <h2 className="text-2xl font-black mb-2" style={{ color: cfg.color }}>
                {cfg.titulo}
              </h2>

              {recompensa > 0 && (
                <motion.div
                  className="text-3xl font-black text-[#a3e635] mb-4"
                  initial={{ scale: 0 }}
                  animate={{ scale: 1 }}
                  transition={{ type: 'spring', delay: 0.3 }}
                >
                  +${recompensa.toLocaleString('es-CO')}
                </motion.div>
              )}

              {nuevosLogros.length > 0 && (
                <div className="mb-4 space-y-2">
                  {nuevosLogros.map((l) => (
                    <motion.div
                      key={l.slug}
                      className="flex items-center gap-2 bg-[#a855f7]/20 border border-[#a855f7]/40 rounded-lg px-3 py-2"
                      initial={{ x: -20, opacity: 0 }}
                      animate={{ x: 0, opacity: 1 }}
                      transition={{ delay: 0.5 }}
                    >
                      <span className="text-xl">{l.icono}</span>
                      <div className="text-left">
                        <div className="text-sm font-bold text-[#c084fc]">¡Nuevo logro!</div>
                        <div className="text-xs text-[#8b949e]">{l.nombre}</div>
                      </div>
                    </motion.div>
                  ))}
                </div>
              )}

              <button
                onClick={onClose}
                className="w-full py-3 rounded-xl font-bold text-[#0d1117] transition-transform hover:scale-105 active:scale-95"
                style={{ background: cfg.color }}
              >
                ¡Genial! 💪
              </button>
            </motion.div>
          </motion.div>
        </>
      )}
    </AnimatePresence>
  )
}
