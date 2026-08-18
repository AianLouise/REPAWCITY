import axios from 'axios'
import { useAuthStore } from '@repaw/auth'

export const API_URL = import.meta.env.VITE_API_URL ?? ''

export const api = axios.create({
  baseURL: `${API_URL}/api`,
  headers: { Accept: 'application/json' },
})

/**
 * Resolve a backend-relative URL (e.g. /storage/pets/x.jpg) to an absolute
 * URL, prefixing the configured API origin when one is set.
 */
export function resolveMedia(path: string): string {
  if (/^https?:\/\//.test(path)) return path
  return `${API_URL}${path}`
}

api.interceptors.request.use((config) => {
  const token = useAuthStore.getState().token
  if (token) {
    config.headers.Authorization = `Bearer ${token}`
  }
  return config
})

api.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response?.status === 401) {
      useAuthStore.getState().logout()
    }
    return Promise.reject(error)
  },
)
