import { api } from '@repaw/api-client'
import type { Appointment, DashboardResponse, Pet, PetStatus, User } from '@repaw/api-client'

export const adminApi = {
  async dashboard(date?: string): Promise<DashboardResponse> {
    const res = await api.get<DashboardResponse>('/admin/dashboard', { params: date ? { date } : {} })
    return res.data
  },

  async daily(date: string, timeSlot: string): Promise<Appointment[]> {
    const res = await api.get<{ data: Appointment[] }>('/admin/dashboard/daily', {
      params: { date, time_slot: timeSlot },
    })
    return res.data.data
  },

  async updateAppointmentStatus(id: number, status: 'Accepted' | 'Cancelled'): Promise<void> {
    await api.post(`/admin/appointments/${id}/status`, { status })
  },

  async storePet(formData: FormData): Promise<Pet> {
    const res = await api.post<{ data: Pet }>('/admin/pets', formData)
    return res.data.data
  },

  async updatePet(id: number, formData: FormData): Promise<Pet> {
    const res = await api.put<{ data: Pet }>(`/admin/pets/${id}`, formData)
    return res.data.data
  },

  async deletePet(id: number): Promise<void> {
    await api.delete(`/admin/pets/${id}`)
  },

  async setPetStatus(id: number, status: PetStatus): Promise<Pet> {
    const res = await api.post<{ pet: Pet }>(`/admin/pets/${id}/status`, { status })
    return res.data.pet
  },

  async setFeaturedPet(image1: number, image2: number, image3: number, image4: number): Promise<void> {
    await api.post('/admin/pets/featured', {
      featured_image_1: image1,
      featured_image_2: image2,
      featured_image_3: image3,
      featured_image_4: image4,
    })
  },

  async storeNews(formData: FormData): Promise<{ id: number }> {
    const res = await api.post<{ data: { id: number } }>('/admin/news', formData, {
      headers: { 'Content-Type': 'multipart/form-data' },
    })
    return res.data.data
  },

  async updateNews(id: number, data: { title?: string; details?: string }): Promise<void> {
    await api.put(`/admin/news/${id}`, data)
  },

  async deleteNews(id: number): Promise<void> {
    await api.delete(`/admin/news/${id}`)
  },

  async featureNews(id: number): Promise<void> {
    await api.post(`/admin/news/${id}/feature`)
  },

  async users(): Promise<User[]> {
    const res = await api.get<{ data: User[] }>('/admin/users')
    return res.data.data
  },

  async updateUser(id: number, data: { fname?: string; lname?: string; email?: string; password?: string }): Promise<void> {
    await api.put(`/admin/users/${id}`, data)
  },

  async deleteUser(id: number): Promise<void> {
    await api.delete(`/admin/users/${id}`)
  },

  async updateUserRole(id: number, userType: '1' | '2'): Promise<void> {
    await api.post(`/admin/users/${id}/role`, { user_type: userType })
  },
}
