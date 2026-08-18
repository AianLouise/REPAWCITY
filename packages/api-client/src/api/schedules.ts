import { api } from './client'
import type { AdminSchedule, ScheduleDay } from '../types'

export const schedulesApi = {
  async upcoming(): Promise<ScheduleDay[]> {
    const res = await api.get<{ data: ScheduleDay[] }>('/schedules')
    return res.data.data
  },

  async adminList(from: string, to: string): Promise<AdminSchedule[]> {
    const res = await api.get<{ data: AdminSchedule[] }>('/admin/schedules', {
      params: { from, to },
    })
    return res.data.data
  },

  async update(data: {
    date: string
    is_open: boolean
    morning_capacity: number
    afternoon_capacity: number
    reason?: string | null
  }): Promise<void> {
    await api.put('/admin/schedules', data)
  },
}
