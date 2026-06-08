import { create } from 'zustand'
import { persist } from 'zustand/middleware'
import api, { initCsrf, resetCsrf } from '@/lib/axios'

export const useAuthStore = create(
  persist(
    (set, get) => ({
      user: null,
      loading: false,

      login: async (email, password) => {
        set({ loading: true })
        try {
          await initCsrf()
          const { data } = await api.post('/auth/login', { email, password })
          set({ user: data.data, loading: false })
          return { ok: true }
        } catch (err) {
          set({ loading: false })
          return { ok: false, message: err.response?.data?.message ?? 'Error al iniciar sesión' }
        }
      },

      logout: async () => {
        try {
          await api.post('/auth/logout')
        } finally {
          resetCsrf()
          set({ user: null })
        }
      },

      fetchMe: async () => {
        try {
          const { data } = await api.get('/auth/me')
          set({ user: data.data })
        } catch {
          set({ user: null })
        }
      },

      isAdmin: () => get().user?.role === 'admin',
    }),
    {
      name: 'pythonjr-auth',
      partialize: (s) => ({ user: s.user }),
    }
  )
)
