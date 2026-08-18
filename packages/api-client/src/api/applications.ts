import { api } from './client'
import type { AdoptionApplication, ApplicationAnswers } from '../types'

export interface ApplicationPayload {
  pet_id: number
  appointment_id?: number | null
  answers: ApplicationAnswers
}

export const applicationsApi = {
  async store(payload: ApplicationPayload): Promise<AdoptionApplication> {
    const res = await api.post<{ data: AdoptionApplication }>('/adoption-applications', payload)
    return res.data.data
  },

  async my(): Promise<AdoptionApplication[]> {
    const res = await api.get<{ data: AdoptionApplication[] }>('/adoption-applications/my')
    return res.data.data
  },

  async cancel(id: number): Promise<AdoptionApplication> {
    const res = await api.post<{ application: AdoptionApplication }>(`/adoption-applications/${id}/cancel`)
    return res.data.application
  },

  async all(): Promise<AdoptionApplication[]> {
    const res = await api.get<{ data: AdoptionApplication[] }>('/admin/adoption-applications')
    return res.data.data
  },

  async updateStatus(id: number, status: string, notes?: string | null): Promise<AdoptionApplication> {
    const res = await api.put<{ application: AdoptionApplication }>(`/admin/adoption-applications/${id}/status`, {
      status,
      notes,
    })
    return res.data.application
  },
}
