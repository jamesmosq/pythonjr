import { create } from 'zustand'
import { persist } from 'zustand/middleware'
import api from '@/lib/axios'

export const useAuthStore = create(
  persist(
    (set, get) => ({
      user: null,
      token: null,
      loading: false,
      initialized: false,

      login: async (email, password) => {
        set({ loading: true })
        try {
          const { data } = await api.post('/auth/login', { email, password })
          api.defaults.headers.common['Authorization'] = `Bearer ${data.token}`
          set({ user: data.data, token: data.token, loading: false })
          return { ok: true, role: data.data.role }
        } catch (err) {
          set({ loading: false })
          return { ok: false, message: err.response?.data?.message ?? 'Error al iniciar sesión' }
        }
      },

      logout: async () => {
        try {
          await api.post('/auth/logout')
        } finally {
          delete api.defaults.headers.common['Authorization']
          set({ user: null, token: null })
        }
      },

      fetchMe: async () => {
        const token = get().token
        if (!token) {
          set({ initialized: true })
          return
        }
        api.defaults.headers.common['Authorization'] = `Bearer ${token}`
        try {
          const { data } = await api.get('/auth/me')
          set({ user: data.data, initialized: true })
        } catch {
          delete api.defaults.headers.common['Authorization']
          set({ user: null, token: null, initialized: true })
        }
      },

      updateProfile: (userData) => {
        set({ user: { ...get().user, ...userData } })
      },

      isAdmin: () => ['admin', 'superadmin'].includes(get().user?.role),
    }),
    {
      name: 'pythonjr-auth',
      partialize: (s) => ({ user: s.user, token: s.token }),
    }
  )
)
