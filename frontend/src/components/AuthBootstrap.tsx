import { useEffect } from 'react'
import { useAuthStore } from '../store/authStore'
import { authApi } from '../api/auth'

export default function AuthBootstrap() {
  const { user, token, setUser, logout } = useAuthStore()

  useEffect(() => {
    if (!token || user) return
    authApi
      .me()
      .then(setUser)
      .catch(() => logout())
  }, [token, user, setUser, logout])

  return null
}