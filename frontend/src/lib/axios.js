import axios from 'axios'

const api = axios.create({
  baseURL: '/api',
  withCredentials: true,
  headers: {
    Accept: 'application/json',
    'Content-Type': 'application/json',
  },
})

// Interceptor: limpiar auth en 401 (soft navigation via zustand)
api.interceptors.response.use(
  (res) => res,
  (err) => {
    const path = window.location.pathname
    if (err.response?.status === 401 && !path.startsWith('/login') && !path.startsWith('/registro')) {
      import('@/stores/authStore').then(({ useAuthStore }) => {
        delete api.defaults.headers.common['Authorization']
        useAuthStore.setState({ user: null, token: null })
      })
    }
    return Promise.reject(err)
  }
)

export default api
