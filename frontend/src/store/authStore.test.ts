import { describe, it, expect } from 'vitest'
import { useAuthStore } from '../store/authStore'

const user = { id: 1, fname: 'Aian', lname: 'Alfaro', email: 'admin@gmail.com', user_type: '1', created_at: '' }

describe('auth store', () => {
  it('starts logged out', () => {
    useAuthStore.setState({ user: null, token: null })
    expect(useAuthStore.getState().user).toBeNull()
    expect(useAuthStore.getState().token).toBeNull()
  })

  it('setAuth stores user and token', () => {
    useAuthStore.getState().setAuth(user, 'tok123')
    expect(useAuthStore.getState().user).toEqual(user)
    expect(useAuthStore.getState().token).toBe('tok123')
  })

  it('setUser updates only user', () => {
    useAuthStore.getState().setAuth(user, 'tok123')
    useAuthStore.getState().setUser({ ...user, fname: 'Aian Louise' })
    expect(useAuthStore.getState().user?.fname).toBe('Aian Louise')
    expect(useAuthStore.getState().token).toBe('tok123')
  })

  it('logout clears user and token', () => {
    useAuthStore.getState().setAuth(user, 'tok123')
    useAuthStore.getState().logout()
    expect(useAuthStore.getState().user).toBeNull()
    expect(useAuthStore.getState().token).toBeNull()
  })
})
