import { api } from './client'

export interface ReportMonth {
  month: string
  label: string
  appointments: number
  applications: number
  adoptions: number
  volunteer_hours: number
}

export interface ReportData {
  months: number
  series: ReportMonth[]
  totals: {
    appointments: number
    applications: number
    adoptions: number
    volunteer_hours: number
  }
  top_pets_by_appointments: { pet_id: number; name: string; appointments: number }[]
  top_pets_by_applications: { pet_id: number; name: string; applications: number }[]
}

export const reportsApi = {
  async index(months = 12): Promise<ReportData> {
    const res = await api.get<ReportData>('/admin/reports', { params: { months } })
    return res.data
  },
}
