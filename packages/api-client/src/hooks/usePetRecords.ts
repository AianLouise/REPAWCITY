import { useMutation, useQuery, useQueryClient } from '@tanstack/react-query'
import { petRecordsApi } from '../api/petRecords'

export function usePetRecords(petId: number) {
  return useQuery({
    queryKey: ['pet-records', petId],
    queryFn: () => petRecordsApi.public(petId),
    enabled: Number.isFinite(petId) && petId > 0,
  })
}

export function useAdminPetRecords(petId: number) {
  return useQuery({
    queryKey: ['admin-pet-records', petId],
    queryFn: () => petRecordsApi.adminList(petId),
    enabled: Number.isFinite(petId) && petId > 0,
  })
}

export function usePetRecordActions(petId: number) {
  const queryClient = useQueryClient()

  const invalidate = () => {
    void queryClient.invalidateQueries({ queryKey: ['pet-records', petId] })
    void queryClient.invalidateQueries({ queryKey: ['admin-pet-records', petId] })
  }

  const store = useMutation({
    mutationFn: (data: Parameters<typeof petRecordsApi.store>[1]) => petRecordsApi.store(petId, data),
    onSuccess: invalidate,
  })
  const destroy = useMutation({
    mutationFn: (recordId: number) => petRecordsApi.destroy(petId, recordId),
    onSuccess: invalidate,
  })

  return { store, destroy }
}
