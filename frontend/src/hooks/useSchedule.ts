import { useMutation, useQuery, useQueryClient } from '@tanstack/react-query'
import { schedulesApi } from '../api/schedules'

export function useUpcomingSchedule() {
  return useQuery({
    queryKey: ['schedules'],
    queryFn: schedulesApi.upcoming,
  })
}

export function useAdminSchedules(from: string, to: string) {
  return useQuery({
    queryKey: ['admin-schedules', from, to],
    queryFn: () => schedulesApi.adminList(from, to),
    enabled: !!from && !!to,
  })
}

export function useScheduleActions() {
  const queryClient = useQueryClient()

  const update = useMutation({
    mutationFn: schedulesApi.update,
    onSuccess: () => {
      void queryClient.invalidateQueries({ queryKey: ['admin-schedules'] })
      void queryClient.invalidateQueries({ queryKey: ['schedules'] })
      void queryClient.invalidateQueries({ queryKey: ['slots'] })
    },
  })

  return { update }
}
