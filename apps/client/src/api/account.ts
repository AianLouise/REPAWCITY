import { api } from '@repaw/api-client'
import type { Appointment, Pet } from '@repaw/api-client'

export interface UserDashboard {
  user: {
    fname: string
    lname: string
    email: string
  }
  upcoming_appointments: Appointment[]
  active_applications: {
    id: number
    pet: Pet
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

  async checkFavorite(petId: number): Promise<{ favorite: boolean }> {
    const res = await api.get<{ favorite: boolean }>(`/favorites/${petId}`)
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
