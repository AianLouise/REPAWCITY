import { useMutation, useQuery, useQueryClient } from '@tanstack/react-query'
import { communityApi } from '../api/community'

export function useAdminDonations() {
  return useQuery({
    queryKey: ['admin-donations'],
    queryFn: communityApi.adminDonations,
  })
}

export function useMyVolunteer() {
  return useQuery({
    queryKey: ['my-volunteer'],
    queryFn: communityApi.myVolunteer,
  })
}

export function useMyShifts() {
  return useQuery({
    queryKey: ['my-shifts'],
    queryFn: communityApi.myShifts,
  })
}

export function useAdminVolunteers() {
  return useQuery({
    queryKey: ['admin-volunteers'],
    queryFn: communityApi.adminVolunteers,
  })
}

export function useCommunityActions() {
  const queryClient = useQueryClient()

  const invalidate = () => {
    void queryClient.invalidateQueries({ queryKey: ['admin-donations'] })
    void queryClient.invalidateQueries({ queryKey: ['my-volunteer'] })
    void queryClient.invalidateQueries({ queryKey: ['my-shifts'] })
    void queryClient.invalidateQueries({ queryKey: ['admin-volunteers'] })
  }

  const storeDonation = useMutation({
    mutationFn: communityApi.storeDonation,
    onSuccess: invalidate,
  })
  const applyVolunteer = useMutation({
    mutationFn: communityApi.applyVolunteer,
    onSuccess: invalidate,
  })
  const logShiftHours = useMutation({
    mutationFn: ({ id, hours }: { id: number; hours: number }) => communityApi.logShiftHours(id, hours),
    onSuccess: invalidate,
  })
  const updateVolunteerStatus = useMutation({
    mutationFn: ({ id, status }: { id: number; status: string }) => communityApi.updateVolunteerStatus(id, status),
    onSuccess: invalidate,
  })
  const assignShift = useMutation({
    mutationFn: ({ volunteerId, data }: { volunteerId: number; data: { date: string; time_slot: string; activity?: string } }) =>
      communityApi.assignShift(volunteerId, data),
    onSuccess: invalidate,
  })

  return { storeDonation, applyVolunteer, logShiftHours, updateVolunteerStatus, assignShift }
}
