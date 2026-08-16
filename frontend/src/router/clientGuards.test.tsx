import { describe, it, expect, beforeEach, afterEach, vi } from 'vitest'
import { render, screen, cleanup } from '@testing-library/react'
import { MemoryRouter, Route, Routes } from 'react-router-dom'
import { ProtectedRoute, GuestRoute, AdminLockout } from '../router/clientGuards'
import { useAuthStore } from '../store/authStore'

function renderClientGuards(initialPath: string) {
  return render(
    <MemoryRouter initialEntries={[initialPath]}>
      <Routes>
        <Route path="/login" element={<div>LoginPage</div>} />
        <Route path="/" element={<div>Home</div>} />

        <Route element={<ProtectedRoute />}>
          <Route path="/profile" element={<div>ProfilePage</div>} />
        </Route>

        <Route element={<GuestRoute />}>
          <Route path="/register" element={<div>RegisterPage</div>} />
        </Route>
      </Routes>
    </MemoryRouter>,
  )
}

const regularUser = { id: 2, fname: 'Juan', lname: 'Cruz', email: 'j@test.com', user_type: '2', created_at: '' }
const adminUser = { id: 1, fname: 'A', lname: 'B', email: 'admin@gmail.com', user_type: '1', created_at: '' }

describe('client route guards', () => {
  beforeEach(() => {
    useAuthStore.setState({ user: null, token: null })
    vi.stubGlobal('window', { ...window, location: { ...window.location, assign: vi.fn() } })
  })
  afterEach(() => {
    cleanup()
    vi.unstubAllGlobals()
  })

  it('ProtectedRoute redirects unauthenticated users to login', () => {
    renderClientGuards('/profile')
    expect(screen.getByText('LoginPage')).toBeInTheDocument()
  })

  it('ProtectedRoute allows authenticated regular users', () => {
    useAuthStore.setState({ user: regularUser, token: 'abc' })
    renderClientGuards('/profile')
    expect(screen.getByText('ProfilePage')).toBeInTheDocument()
  })

  it('GuestRoute redirects logged-in users away from auth pages', () => {
    useAuthStore.setState({ user: regularUser, token: 'abc' })
    renderClientGuards('/register')
    expect(screen.getByText('Home')).toBeInTheDocument()
  })

  it('GuestRoute allows anonymous users', () => {
    renderClientGuards('/register')
    expect(screen.getByText('RegisterPage')).toBeInTheDocument()
  })

  it('AdminLockout renders children for regular users and anonymous users', () => {
    render(
      <AdminLockout>
        <div>ClientContent</div>
      </AdminLockout>,
    )
    expect(screen.getByText('ClientContent')).toBeInTheDocument()
  })

  it('AdminLockout redirects admins to the admin portal', () => {
    useAuthStore.setState({ user: adminUser, token: 'abc' })
    render(
      <AdminLockout>
        <div>ClientContent</div>
      </AdminLockout>,
    )
    expect(window.location.assign).toHaveBeenCalled()
  })
})