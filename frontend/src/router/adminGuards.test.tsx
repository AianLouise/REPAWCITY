import { describe, it, expect, beforeEach, afterEach, vi } from 'vitest'
import { render, screen, cleanup } from '@testing-library/react'
import { MemoryRouter, Route, Routes } from 'react-router-dom'
import { AdminRoute, GuestRoute } from '../router/adminGuards'
import { useAuthStore } from '../store/authStore'

function renderAdminGuards(initialPath: string) {
  return render(
    <MemoryRouter initialEntries={[initialPath]}>
      <Routes>
        <Route path="/admin/login" element={<div>AdminLoginPage</div>} />
        <Route path="/" element={<div>AdminDashboard</div>} />

        <Route element={<AdminRoute />}>
          <Route path="/pets/manage" element={<div>ManagePets</div>} />
        </Route>

        <Route element={<GuestRoute />}>
          <Route path="/login-route" element={<div>LoginRoute</div>} />
        </Route>
      </Routes>
    </MemoryRouter>,
  )
}

const regularUser = { id: 2, fname: 'Juan', lname: 'Cruz', email: 'j@test.com', user_type: '2', created_at: '' }
const adminUser = { id: 1, fname: 'A', lname: 'B', email: 'admin@gmail.com', user_type: '1', created_at: '' }

describe('admin route guards', () => {
  beforeEach(() => {
    useAuthStore.setState({ user: null, token: null })
    vi.stubGlobal('window', { ...window, location: { ...window.location, assign: vi.fn() } })
  })
  afterEach(() => {
    cleanup()
    vi.unstubAllGlobals()
  })

  it('AdminRoute redirects unauthenticated users to admin login', () => {
    renderAdminGuards('/pets/manage')
    expect(screen.getByText('AdminLoginPage')).toBeInTheDocument()
  })

  it('AdminRoute allows admins', () => {
    useAuthStore.setState({ user: adminUser, token: 'abc' })
    renderAdminGuards('/pets/manage')
    expect(screen.getByText('ManagePets')).toBeInTheDocument()
  })

  it('AdminRoute sends regular users back to the client site', () => {
    useAuthStore.setState({ user: regularUser, token: 'abc' })
    renderAdminGuards('/pets/manage')
    expect(window.location.assign).toHaveBeenCalled()
  })

  it('GuestRoute sends regular users back to the client site', () => {
    useAuthStore.setState({ user: regularUser, token: 'abc' })
    renderAdminGuards('/login-route')
    expect(window.location.assign).toHaveBeenCalled()
  })

  it('GuestRoute redirects logged-in admins to the dashboard', () => {
    useAuthStore.setState({ user: adminUser, token: 'abc' })
    renderAdminGuards('/login-route')
    expect(screen.getByText('AdminDashboard')).toBeInTheDocument()
  })

  it('GuestRoute allows anonymous users on the login page', () => {
    renderAdminGuards('/login-route')
    expect(screen.getByText('LoginRoute')).toBeInTheDocument()
  })
})