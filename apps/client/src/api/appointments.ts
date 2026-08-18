import { api } from '@repaw/api-client'
import type { Appointment, AppointmentType, SlotsResponse, TimeSlot } from '@repaw/api-client'

export interface BookingPayload {
  appointment_type: AppointmentType
  pet_id?: number | null
  appointment_date: string
  time_slot: TimeSlot
  first_name: string
  middle_name: string
  last_name: string
  mobile_number: string
  home_address: string
  email_address: string
}

export const appointmentsApi = {
  async slots(date: string): Promise<SlotsResponse> {
    const res = await api.get<SlotsResponse>('/appointments/slots', {
      params: { date },
    })
    return res.data
  },

  async store(payload: BookingPayload): Promise<Appointment> {
    const res = await api.post<{ data: Appointment }>('/appointments', payload)
    return res.data.data
  },

  async my(): Promise<Appointment[]> {
    const res = await api.get<{ data: Appointment[] }>('/appointments/my')
    return res.data.data
  },
}
