import { api } from './client'
import type { Volunteer, VolunteerShift } from '../types'

export interface VolunteerApplyPayload {
  availability?: string[]
  skills?: string
  interests?: string
}

export const communityApi = {
  async applyVolunteer(payload: VolunteerApplyPayload): Promise<Volunteer> {
    const res = await api.post<{ volunteer: Volunteer }>('/volunteers/apply', payload)
    return res.data.volunteer
  },

  async myVolunteer(): Promise<Volunteer | null> {
    const res = await api.get<{ data: Volunteer | null }>('/volunteers/my')
    return res.data.data
  },

  async myShifts(): Promise<VolunteerShift[]> {
    const res = await api.get<{ data: VolunteerShift[] }>('/volunteers/shifts')
    return res.data.data
  },

  async logShiftHours(id: number, hours: number): Promise<void> {
    await api.put(`/volunteers/shifts/${id}/hours`, { hours_logged: hours })
  },

  async adminVolunteers(): Promise<Volunteer[]> {
    const res = await api.get<{ data: Volunteer[] }>('/admin/volunteers')
    return res.data.data
  },

  async updateVolunteerStatus(id: number, status: string): Promise<void> {
    await api.put(`/admin/volunteers/${id}/status`, { status })
  },

  async assignShift(volunteerId: number, data: { date: string; time_slot: string; activity?: string }): Promise<void> {
    await api.post(`/admin/volunteers/${volunteerId}/shifts`, data)
  },

  async updateShift(id: number, data: { hours_logged?: number; activity?: string }): Promise<void> {
    await api.put(`/admin/volunteers/shifts/${id}`, data)
  },
}
