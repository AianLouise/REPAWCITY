import { api } from './client'
import type { Appointment, Pet } from '../types'

export interface UserDashboard {
  user: {
    fname: string
    lname: string
    email: string
  }
  upcoming_appointments: Appointment[]
  active_applications: {
    id: number
    pet: { id: number; name: string; type: string; image_url: string; thumb_url: string }
    status: string
  }[]
  favorite_pet_ids: number[]
  stats: {
    appointments: number
    applications: number
    favorites: number
  }
}

export const accountApi = {
  async dashboard(): Promise<UserDashboard> {
    const res = await api.get<UserDashboard>('/dashboard')
    return res.data
  },

  async toggleFavorite(petId: number): Promise<{ favorite: boolean; pet_id: number }> {
    const res = await api.post<{ favorite: boolean; pet_id: number }>(`/favorites/${petId}`)
    return res.data
  },

  async favorites(): Promise<Pet[]> {
    const res = await api.get<{ data: Pet[] }>('/favorites')
    return res.data.data
  },
}
