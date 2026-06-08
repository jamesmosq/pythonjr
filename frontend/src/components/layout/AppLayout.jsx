import { Outlet } from 'react-router-dom'
import Header from './Header'
import { useProgresoStore } from '@/stores/progresoStore'

export default function AppLayout() {
  const dashboard = useProgresoStore((s) => s.dashboard)

  return (
    <div className="min-h-screen bg-[#0d1117] flex flex-col">
      <Header
        racha={dashboard?.racha?.dias_actuales ?? 0}
        xp={dashboard?.billetera?.saldo_total ?? 0}
        xpMax={500000}
      />
      <main className="flex-1 max-w-5xl w-full mx-auto px-4 py-6">
        <Outlet />
      </main>
    </div>
  )
}
