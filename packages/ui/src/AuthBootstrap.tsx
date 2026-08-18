import { useEffect } from 'react'
import { useAuthStore } from '@repaw/auth'
import { authApi } from '@repaw/api-client'

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