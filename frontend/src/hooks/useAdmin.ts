import { useMutation, useQuery, useQueryClient } from '@tanstack/react-query'
import { adminApi } from '../api/admin'
import { petsApi } from '../api/pets'
import { newsApi } from '../api/news'
import type { PetStatus } from '../types'

export function useDashboard(date?: string) {
  return useQuery({
    queryKey: ['admin-dashboard', date ?? 'today'],
    queryFn: () => adminApi.dashboard(date),
  })
}

export function useDailyAppointments(date: string, timeSlot: string) {
  return useQuery({
    queryKey: ['admin-daily', date, timeSlot],
    queryFn: () => adminApi.daily(date, timeSlot),
    enabled: !!date,
  })
}

export function useAllPets() {
  return useQuery({
    queryKey: ['admin-pets'],
    queryFn: () => petsApi.list({ per_page: 100, include_unavailable: true }),
  })
}

export function useAllNews() {
  return useQuery({
    queryKey: ['admin-news'],
    queryFn: () => newsApi.list(false),
  })
}

export function useAdminUsers() {
  return useQuery({
    queryKey: ['admin-users'],
    queryFn: adminApi.users,
  })
}

export function useAdminActions() {
  const queryClient = useQueryClient()

  const invalidate = () => {
    void queryClient.invalidateQueries({ queryKey: ['admin-dashboard'] })
    void queryClient.invalidateQueries({ queryKey: ['admin-daily'] })
    void queryClient.invalidateQueries({ queryKey: ['admin-pets'] })
    void queryClient.invalidateQueries({ queryKey: ['admin-news'] })
    void queryClient.invalidateQueries({ queryKey: ['admin-users'] })
    void queryClient.invalidateQueries({ queryKey: ['pets'] })
    void queryClient.invalidateQueries({ queryKey: ['news'] })
  }

  const updateStatus = useMutation({
    mutationFn: ({ id, status }: { id: number; status: 'Accepted' | 'Cancelled' }) => adminApi.updateAppointmentStatus(id, status),
    onSuccess: invalidate,
  })

  const storePet = useMutation({ mutationFn: adminApi.storePet, onSuccess: invalidate })
  const updatePet = useMutation({ mutationFn: ({ id, formData }: { id: number; formData: FormData }) => adminApi.updatePet(id, formData), onSuccess: invalidate })
  const deletePet = useMutation({ mutationFn: adminApi.deletePet, onSuccess: invalidate })
  const setPetStatus = useMutation({ mutationFn: ({ id, status }: { id: number; status: PetStatus }) => adminApi.setPetStatus(id, status), onSuccess: invalidate })
  const setFeaturedPet = useMutation({
    mutationFn: (ids: [number, number, number, number]) => adminApi.setFeaturedPet(...ids),
    onSuccess: invalidate,
  })

  const storeNews = useMutation({ mutationFn: adminApi.storeNews, onSuccess: invalidate })
  const updateNews = useMutation({ mutationFn: ({ id, data }: { id: number; data: { title?: string; details?: string } }) => adminApi.updateNews(id, data), onSuccess: invalidate })
  const deleteNews = useMutation({ mutationFn: adminApi.deleteNews, onSuccess: invalidate })
  const featureNews = useMutation({ mutationFn: adminApi.featureNews, onSuccess: invalidate })

  const updateUser = useMutation({ mutationFn: ({ id, data }: { id: number; data: Parameters<typeof adminApi.updateUser>[1] }) => adminApi.updateUser(id, data), onSuccess: invalidate })
  const deleteUser = useMutation({ mutationFn: adminApi.deleteUser, onSuccess: invalidate })
  const updateUserRole = useMutation({ mutationFn: ({ id, userType }: { id: number; userType: '1' | '2' }) => adminApi.updateUserRole(id, userType), onSuccess: invalidate })

  return {
    updateStatus,
    storePet,
    updatePet,
    deletePet,
    setPetStatus,
    setFeaturedPet,
    storeNews,
    updateNews,
    deleteNews,
    featureNews,
    updateUser,
    deleteUser,
    updateUserRole,
  }
}
