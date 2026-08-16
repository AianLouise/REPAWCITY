import { useQuery } from '@tanstack/react-query'
import { reportsApi } from '../api/reports'

export function useReports(months = 12) {
  return useQuery({
    queryKey: ['reports', months],
    queryFn: () => reportsApi.index(months),
  })
}
