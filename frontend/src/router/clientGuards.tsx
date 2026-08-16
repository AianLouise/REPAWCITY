import { Navigate, Outlet, useLocation } from 'react-router-dom'
import type { ReactNode } from 'react'
import { useAuthStore } from '../store/authStore'
import { ADMIN_URL } from '../config'

/**
 * Guards for the public client app (repawcity.com).
 * Admin users are locked out of the client app entirely — they are redirected
 * to the separate admin portal and never see client pages.
 */

export function AdminLockout({ children }: { children: ReactNode }) {
  const user = useAuthStore((s) => s.user)

  if (user?.user_type === '1') {
    window.location.assign(ADMIN_URL)
    return null
  }
  return children
}

export function ProtectedRoute() {
  const user = useAuthStore((s) => s.user)
  const location = useLocation()

  if (!user) {
    return <Navigate to="/login" replace state={{ from: location.pathname }} />
  }
  return <Outlet />
}

export function GuestRoute() {
  const user = useAuthStore((s) => s.user)

  if (user) {
    return <Navigate to="/" replace />
  }
  return <Outlet />
}