import { useEffect, useState } from 'react'
import api from '@/lib/axios'

function StatCard({ icon, label, value, color = '#a3e635' }) {
  return (
    <div className="bg-[#161b22] border border-[#30363d] rounded-2xl p-5">
      <div className="text-3xl mb-2">{icon}</div>
      <div className="text-2xl font-black" style={{ color }}>{value ?? '—'}</div>
      <div className="text-sm text-[#8b949e] mt-1">{label}</div>
    </div>
  )
}

function Toggle({ activo, onChange, disabled }) {
  return (
    <button
      onClick={onChange}
      disabled={disabled}
      title={activo ? 'Desactivar familia' : 'Activar familia'}
      className={`relative w-11 h-6 rounded-full transition-colors duration-200 focus:outline-none disabled:opacity-50 ${
        activo ? 'bg-[#a3e635]' : 'bg-[#f85149]'
      }`}
    >
      <span
        className={`absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow transition-transform duration-200 ${
          activo ? 'translate-x-5' : 'translate-x-0'
        }`}
      />
    </button>
  )
}

export default function SuperAdminDashboard() {
  const [stats,    setStats]    = useState(null)
  const [familias, setFamilias] = useState([])
  const [modulos,  setModulos]  = useState([])

  const [toggling,     setToggling]     = useState(null)
  const [togglingMod,  setTogglingMod]  = useState(null)
  const [resetInfo,    setResetInfo]    = useState(null)   // { id, password }
  const [resetting,    setResetting]    = useState(null)

  useEffect(() => {
    api.get('/superadmin/stats').then(r => setStats(r.data.data)).catch(() => {})
    api.get('/superadmin/familias').then(r => setFamilias(r.data.data)).catch(() => {})
    api.get('/superadmin/modulos').then(r => setModulos(r.data.data)).catch(() => {})
  }, [])

  async function toggleFamilia(id) {
    setToggling(id)
    try {
      const r = await api.put(`/superadmin/familias/${id}/toggle`)
      setFamilias(prev => prev.map(f => f.id === id ? { ...f, activo: r.data.data.activo } : f))
    } catch {}
    setToggling(null)
  }

  async function resetPassword(id) {
    setResetting(id)
    try {
      const r = await api.post(`/superadmin/familias/${id}/reset-password`)
      setResetInfo({ id, password: r.data.data.nueva_contrasena })
    } catch {}
    setResetting(null)
  }

  async function toggleModulo(id) {
    setTogglingMod(id)
    try {
      const r = await api.put(`/superadmin/modulos/${id}/toggle`)
      setModulos(prev => prev.map(m => m.id === id ? { ...m, activo: r.data.data.activo } : m))
    } catch {}
    setTogglingMod(null)
  }

  return (
    <div className="space-y-8">
      <div>
        <h1 className="text-2xl font-black text-[#e6edf3]">Panel de plataforma</h1>
        <p className="text-sm text-[#8b949e] mt-1">Gestión global de PythonJr</p>
      </div>

      {/* Stats */}
      <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
        <StatCard icon="👨‍👩‍👧" label="Familias registradas"  value={stats?.total_familias}     color="#a3e635" />
        <StatCard icon="🧑‍🎓" label="Estudiantes activos"   value={stats?.total_estudiantes}  color="#38bdf8" />
        <StatCard icon="⚡"   label="Ejercicios esta semana" value={stats?.actividad_semana}    color="#f59e0b" />
        <StatCard icon="🆕"  label="Nuevas familias (mes)"  value={stats?.nuevas_familias_mes} color="#a855f7" />
      </div>

      {/* Familias */}
      <section>
        <h2 className="text-lg font-bold text-[#e6edf3] mb-4">Familias registradas</h2>
        {familias.length === 0 ? (
          <div className="bg-[#161b22] border border-[#30363d] rounded-2xl p-8 text-center text-[#484f58]">
            No hay familias registradas aún.
          </div>
        ) : (
          <div className="space-y-3">
            {familias.map(f => (
              <div
                key={f.id}
                className={`bg-[#161b22] border rounded-2xl p-4 transition-all ${
                  f.activo ? 'border-[#30363d]' : 'border-[#f85149]/30 opacity-70'
                }`}
              >
                <div className="flex items-center justify-between gap-4 flex-wrap">
                  {/* Info familia */}
                  <div className="flex items-center gap-3 min-w-0">
                    <span className="text-2xl flex-shrink-0">{f.avatar ?? '👤'}</span>
                    <div className="min-w-0">
                      <div className="font-bold text-[#e6edf3] flex items-center gap-2">
                        {f.nombre}
                        {!f.activo && (
                          <span className="text-xs bg-[#f85149]/20 text-[#f85149] border border-[#f85149]/30 px-1.5 py-0.5 rounded-full">
                            inactiva
                          </span>
                        )}
                      </div>
                      <div className="text-xs text-[#8b949e]">{f.email}</div>
                      <div className="text-xs text-[#484f58]">Registrada {f.creado}</div>
                    </div>
                  </div>

                  {/* Estudiantes + acciones */}
                  <div className="flex items-center gap-3 flex-wrap justify-end">
                    {/* Estudiantes */}
                    <div className="flex items-center gap-1.5">
                      {f.estudiantes.length === 0 ? (
                        <span className="text-xs text-[#484f58]">Sin estudiantes</span>
                      ) : f.estudiantes.map(e => (
                        <div key={e.id} className="flex items-center gap-1 bg-[#21262d] rounded-lg px-2 py-1">
                          <span className="text-base">{e.avatar ?? '🧑‍🎓'}</span>
                          <span className="text-xs text-[#e6edf3]">{e.nombre}</span>
                        </div>
                      ))}
                    </div>

                    {/* Reset contraseña */}
                    <button
                      onClick={() => resetPassword(f.id)}
                      disabled={resetting === f.id}
                      title="Resetear contraseña del papá"
                      className="flex items-center gap-1.5 bg-[#21262d] border border-[#30363d] rounded-lg px-2.5 py-1.5 text-xs text-[#8b949e] hover:text-[#e6edf3] hover:border-[#a3e635] transition-all disabled:opacity-50"
                    >
                      🔑 {resetting === f.id ? '...' : 'Reset'}
                    </button>

                    {/* Toggle activo */}
                    <Toggle
                      activo={f.activo}
                      disabled={toggling === f.id}
                      onChange={() => toggleFamilia(f.id)}
                    />
                  </div>
                </div>

                {/* Contraseña reseteada — mostrar inline */}
                {resetInfo?.id === f.id && (
                  <div className="mt-3 bg-[#a3e635]/10 border border-[#a3e635]/30 rounded-xl px-4 py-3 flex items-center justify-between gap-3">
                    <div>
                      <div className="text-xs text-[#8b949e] mb-0.5">Nueva contraseña temporal para {f.nombre}:</div>
                      <div className="font-mono font-black text-[#a3e635] text-lg tracking-widest">
                        {resetInfo.password}
                      </div>
                      <div className="text-xs text-[#484f58] mt-0.5">Compártela con el papá — deberá cambiarla.</div>
                    </div>
                    <button
                      onClick={() => setResetInfo(null)}
                      className="text-[#484f58] hover:text-[#8b949e] text-xl flex-shrink-0"
                    >
                      ✕
                    </button>
                  </div>
                )}
              </div>
            ))}
          </div>
        )}
      </section>

      {/* Módulos */}
      <section>
        <h2 className="text-lg font-bold text-[#e6edf3] mb-4">Módulos de la plataforma</h2>
        {modulos.length === 0 ? (
          <div className="bg-[#161b22] border border-[#30363d] rounded-2xl p-8 text-center text-[#484f58]">
            Sin módulos cargados.
          </div>
        ) : (
          <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
            {modulos.map(m => (
              <div
                key={m.id}
                className={`bg-[#161b22] border rounded-2xl p-4 flex items-center justify-between gap-3 transition-colors ${
                  m.activo ? 'border-[#30363d]' : 'border-[#21262d] opacity-60'
                }`}
              >
                <div className="flex items-center gap-3">
                  <span className="text-2xl">{m.icono ?? '📦'}</span>
                  <div>
                    <div className="text-sm font-bold text-[#e6edf3]">{m.titulo}</div>
                    <div className="text-xs text-[#484f58]">Nivel {m.nivel}</div>
                  </div>
                </div>
                <button
                  onClick={() => toggleModulo(m.id)}
                  disabled={togglingMod === m.id}
                  className={`relative w-11 h-6 rounded-full transition-colors duration-200 focus:outline-none disabled:opacity-50 ${
                    m.activo ? 'bg-[#a3e635]' : 'bg-[#30363d]'
                  }`}
                >
                  <span
                    className={`absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow transition-transform duration-200 ${
                      m.activo ? 'translate-x-5' : 'translate-x-0'
                    }`}
                  />
                </button>
              </div>
            ))}
          </div>
        )}
      </section>
    </div>
  )
}
