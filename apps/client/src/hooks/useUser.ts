import { useMutation, useQuery, useQueryClient } from '@tanstack/react-query'
import { appointmentsApi } from '../api/appointments'
import { api } from '@repaw/api-client'
import { useAuthStore } from '@repaw/auth'
import type { Appointment } from '@repaw/api-client'

export function useMyAppointments() {
  return useQuery({
    queryKey: ['my-appointments'],
    queryFn: appointmentsApi.my,
  })
}

export function useUpdateProfile() {
  const setUser = useAuthStore((s) => s.setUser)
  const queryClient = useQueryClient()

  return useMutation({
    mutationFn: async (data: { fname: string; lname: string; email: string }) => {
      const res = await api.put<{ data: { fname: string; lname: string; email: string; user_type: string; id: number; created_at: string } }>(
        '/user/profile',
        data,
      )
      return res.data.data
    },
    onSuccess: (user) => {
      setUser(user as never)
      void queryClient.invalidateQueries({ queryKey: ['user'] })
    },
  })
}

export function useChangePassword() {
  return useMutation({
    mutationFn: async (data: { old_password: string; new_password: string; new_password_confirmation: string }) => {
      const res = await api.put<{ message: string }>('/user/password', data)
      return res.data
    },
  })
}

export function useAppointmentMessage(id: number) {
  return useQuery({
    queryKey: ['appointment-message', id],
    queryFn: async () => {
      const res = await api.get<Appointment>(`/appointments/${id}/message`)
      return res.data
    },
    enabled: Number.isFinite(id) && id > 0,
  })
}
