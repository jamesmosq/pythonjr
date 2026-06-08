import { create } from 'zustand'
import api from '@/lib/axios'

export const useProgresoStore = create((set) => ({
  dashboard: null,
  modulos: [],
  moduloActual: null,
  loading: false,

  fetchDashboard: async () => {
    set({ loading: true })
    try {
      const { data } = await api.get('/dashboard')
      set({ dashboard: data.data, loading: false })
    } catch {
      set({ loading: false })
    }
  },

  fetchModulos: async () => {
    const { data } = await api.get('/modulos')
    set({ modulos: data.data })
  },

  fetchModulo: async (slug) => {
    set({ loading: true })
    try {
      const { data } = await api.get(`/modulos/${slug}`)
      set({ moduloActual: data.data, loading: false })
      return data.data
    } catch {
      set({ loading: false })
      return null
    }
  },
}))
