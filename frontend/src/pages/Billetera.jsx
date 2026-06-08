import { useEffect } from 'react'
import { motion } from 'framer-motion'
import { useBilleteraStore } from '@/stores/billeteraStore'

const tipoColor = {
  modulo_base: 'text-[#a3e635]',
  ejercicio: 'text-[#3fb950]',
  perfecto: 'text-[#facc15]',
  racha_3d: 'text-orange-400',
  racha_7d: 'text-orange-500',
  velocidad_modulo: 'text-[#58a6ff]',
  velocidad_nivel: 'text-[#a855f7]',
  desafio_dia: 'text-[#c084fc]',
  pago_padre: 'text-[#f85149]',
}

const tipoIcon = {
  modulo_base: '🏆',
  ejercicio: '✅',
  perfecto: '⭐',
  racha_3d: '🔥',
  racha_7d: '🔥🔥',
  velocidad_modulo: '⚡',
  velocidad_nivel: '⚡⚡',
  desafio_dia: '🎯',
  pago_padre: '💸',
}

export default function Billetera() {
  const { saldo_total, saldo_pendiente, saldo_pagado, transacciones, loading, fetch } = useBilleteraStore()

  useEffect(() => { fetch() }, [])

  return (
    <div className="max-w-lg mx-auto space-y-5">
      <motion.h1
        className="text-xl font-black text-[#e6edf3]"
        initial={{ opacity: 0, y: -10 }}
        animate={{ opacity: 1, y: 0 }}
      >
        💰 Mi billetera
      </motion.h1>

      {/* Resumen */}
      <motion.div
        className="grid grid-cols-3 gap-3"
        initial={{ opacity: 0, y: 20 }}
        animate={{ opacity: 1, y: 0 }}
      >
        <div className="bg-[#161b22] border border-[#30363d] rounded-2xl p-4 text-center">
          <div className="text-xs text-[#8b949e] mb-1">Acumulado</div>
          <div className="text-lg font-black text-[#a3e635]">
            ${saldo_total.toLocaleString('es-CO')}
          </div>
        </div>
        <div className="bg-[#161b22] border border-[#30363d] rounded-2xl p-4 text-center">
          <div className="text-xs text-[#8b949e] mb-1">Pendiente</div>
          <div className="text-lg font-black text-[#facc15]">
            ${saldo_pendiente.toLocaleString('es-CO')}
          </div>
        </div>
        <div className="bg-[#161b22] border border-[#30363d] rounded-2xl p-4 text-center">
          <div className="text-xs text-[#8b949e] mb-1">Pagado</div>
          <div className="text-lg font-black text-[#3fb950]">
            ${saldo_pagado.toLocaleString('es-CO')}
          </div>
        </div>
      </motion.div>

      {/* Historial */}
      <div>
        <h2 className="text-sm font-semibold text-[#8b949e] uppercase tracking-wide mb-3">
          Historial de ganancias
        </h2>
        {loading ? (
          <div className="space-y-2">
            {[...Array(5)].map((_, i) => (
              <div key={i} className="h-14 rounded-xl animate-shimmer" />
            ))}
          </div>
        ) : transacciones.length === 0 ? (
          <div className="text-center py-12 text-[#484f58]">
            <div className="text-4xl mb-3">💰</div>
            <p>Aún no tienes ganancias.</p>
            <p className="text-sm">¡Completa ejercicios para ganar!</p>
          </div>
        ) : (
          <div className="space-y-2">
            {transacciones.map((t, i) => (
              <motion.div
                key={t.id}
                className="bg-[#161b22] border border-[#30363d] rounded-xl px-4 py-3 flex items-center justify-between"
                initial={{ opacity: 0, x: -10 }}
                animate={{ opacity: 1, x: 0 }}
                transition={{ delay: i * 0.03 }}
              >
                <div className="flex items-center gap-3">
                  <span className="text-xl">{tipoIcon[t.tipo] ?? '💵'}</span>
                  <div>
                    <div className="text-sm text-[#e6edf3]">{t.descripcion}</div>
                    <div className="text-xs text-[#484f58]">
                      {new Date(t.created_at).toLocaleDateString('es-CO', { day: '2-digit', month: 'short' })}
                    </div>
                  </div>
                </div>
                <div className={`font-black ${t.monto > 0 ? (tipoColor[t.tipo] ?? 'text-[#3fb950]') : 'text-[#f85149]'}`}>
                  {t.monto > 0 ? '+' : ''}${Math.abs(t.monto).toLocaleString('es-CO')}
                </div>
              </motion.div>
            ))}
          </div>
        )}
      </div>
    </div>
  )
}
