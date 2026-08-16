import { Navigate, Outlet, useLocation } from 'react-router-dom'
import { useAuthStore } from '../store/authStore'
import { CLIENT_URL } from '../config'

/**
 * Guards for the admin app (admin.repawcity.com).
 * Only users with user_type === '1' (admin) may enter; everyone else is
 * redirected to the public client app.
 */

export function AdminRoute() {
  const user = useAuthStore((s) => s.user)
  const location = useLocation()

  if (!user) {
    return <Navigate to="/admin/login" replace state={{ from: location.pathname }} />
  }
  if (user.user_type !== '1') {
    window.location.assign(CLIENT_URL)
    return null
  }
  return <Outlet />
}

export function GuestRoute() {
  const user = useAuthStore((s) => s.user)

  if (user) {
    if (user.user_type !== '1') {
      window.location.assign(CLIENT_URL)
      return null
    }
    return <Navigate to="/" replace />
  }
  return <Outlet />
}