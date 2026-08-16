import { useQuery } from '@tanstack/react-query'
import { appointmentsApi } from '../api/appointments'
import type { SlotsResponse } from '../types'

export function useSlots(date: string) {
  return useQuery({
    queryKey: ['slots', date],
    queryFn: () => appointmentsApi.slots(date),
    enabled: !!date,
  })
}

export function useDayAvailability(date: string) {
  const { data } = useSlots(date)
  return data as SlotsResponse | undefined
}
