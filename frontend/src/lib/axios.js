import axios from 'axios'

const api = axios.create({
  baseURL: '/api',
  withCredentials: true,
  withXSRFToken: true,
  headers: {
    Accept: 'application/json',
    'Content-Type': 'application/json',
  },
})

// Obtener CSRF cookie antes del primer request autenticado
let csrfInitialized = false

export async function initCsrf() {
  if (csrfInitialized) return
  await axios.get('/sanctum/csrf-cookie', { withCredentials: true })
  csrfInitialized = true
}

export function resetCsrf() {
  csrfInitialized = false
}

// Interceptor: redirigir al login en 401
api.interceptors.response.use(
  (res) => res,
  (err) => {
    if (err.response?.status === 401 && !window.location.pathname.startsWith('/login')) {
      window.location.href = '/login'
    }
    return Promise.reject(err)
  }
)

export default api
