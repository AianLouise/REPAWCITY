import { api } from './client'
import type { AuthResponse, User } from '@repaw/auth'

export const authApi = {
  async register(data: {
    fname: string
    lname: string
    email: string
    password: string
    password_confirmation: string
  }): Promise<AuthResponse> {
    const res = await api.post<AuthResponse>('/register', data)
    return res.data
  },

  async login(data: { email: string; password: string }): Promise<AuthResponse> {
    const res = await api.post<AuthResponse>('/login', data)
    return res.data
  },

  async logout(): Promise<void> {
    await api.post('/logout')
  },

  async me(): Promise<User> {
    const res = await api.get<{ data: User }>('/user')
    return res.data.data
  },
}
