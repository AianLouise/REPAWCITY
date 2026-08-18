import { api } from './client'
import type { Paginated, Pet } from '../types'

export interface PetFilters {
  type?: string
  sex?: string
  weight?: string
  age?: string
  featured?: boolean
  q?: string
  per_page?: number
  page?: number
  status?: string
  include_unavailable?: boolean
}

export const petsApi = {
  async list(filters: PetFilters = {}): Promise<Paginated<Pet>> {
    const res = await api.get<Paginated<Pet>>('/pets', { params: filters })
    return res.data
  },

  async show(id: number): Promise<Pet> {
    const res = await api.get<{ data: Pet }>(`/pets/${id}`)
    return res.data.data
  },
}
