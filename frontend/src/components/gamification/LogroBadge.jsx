export default function LogroBadge({ logro, size = 'md' }) {
  const sizes = { sm: 'p-2 text-2xl', md: 'p-3 text-3xl', lg: 'p-4 text-5xl' }

  return (
    <div
      className={`relative inline-flex items-center justify-center rounded-xl border transition-all ${sizes[size]} ${
        logro.desbloqueado
          ? 'bg-[#a855f7]/20 border-[#a855f7]/50 shadow-lg shadow-purple-900/20'
          : 'bg-[#21262d] border-[#30363d] opacity-40 grayscale'
      }`}
      title={logro.desbloqueado ? logro.nombre : '???'}
    >
      <span>{logro.icono ?? '🏅'}</span>
      {logro.desbloqueado && (
        <span className="absolute -top-1 -right-1 w-3 h-3 bg-[#a3e635] rounded-full border-2 border-[#0d1117]" />
      )}
    </div>
  )
}
