import { create } from 'zustand'
import api from '@/lib/axios'

export const useBilleteraStore = create((set) => ({
  saldo_total: 0,
  saldo_pendiente: 0,
  saldo_pagado: 0,
  transacciones: [],
  loading: false,

  fetch: async () => {
    set({ loading: true })
    try {
      const { data } = await api.get('/billetera')
      set({ ...data.data, loading: false })
    } catch {
      set({ loading: false })
    }
  },

  updateSaldo: (saldo_total) => set({ saldo_total }),
}))
