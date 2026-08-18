import { useQuery } from '@tanstack/react-query'
import { appointmentsApi } from '../api/appointments'

export function useSlots(date: string) {
  return useQuery({
    queryKey: ['slots', date],
    queryFn: () => appointmentsApi.slots(date),
    enabled: !!date,
  })
}
