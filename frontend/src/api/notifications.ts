import { api } from './client'

export interface AppNotification {
  id: string
  data: {
    type: string
    title: string
    message: string
    appointment_id?: number
    application_id?: number
    pet_name?: string
    appointment_type?: string
    appointment_date?: string
    time_slot?: string
    status?: string
  }
  read_at: string | null
  created_at: string
}

export interface NotificationsResponse {
  data: AppNotification[]
  unread_count: number
  meta: {
    current_page: number
    last_page: number
    total: number
  }
}

export const notificationsApi = {
  async index(): Promise<NotificationsResponse> {
    const res = await api.get<NotificationsResponse>('/notifications')
    return res.data
  },

  async markRead(id: string): Promise<void> {
    await api.post(`/notifications/${id}/read`)
  },

  async markAllRead(): Promise<void> {
    await api.post('/notifications/read-all')
  },
}
