import { useMutation, useQuery, useQueryClient } from '@tanstack/react-query'
import { applicationsApi, type ApplicationPayload } from '../api/applications'

export function useMyApplications() {
  return useQuery({
    queryKey: ['my-applications'],
    queryFn: applicationsApi.my,
  })
}

export function useAllApplications(status?: string) {
  return useQuery({
    queryKey: ['admin-applications', status ?? 'all'],
    queryFn: () => applicationsApi.all(),
  })
}

export function useApplicationActions() {
  const queryClient = useQueryClient()

  const invalidate = () => {
    void queryClient.invalidateQueries({ queryKey: ['my-applications'] })
    void queryClient.invalidateQueries({ queryKey: ['admin-applications'] })
    void queryClient.invalidateQueries({ queryKey: ['pets'] })
    void queryClient.invalidateQueries({ queryKey: ['pet'] })
  }

  const store = useMutation({
    mutationFn: (payload: ApplicationPayload) => applicationsApi.store(payload),
    onSuccess: invalidate,
  })
  const cancel = useMutation({ mutationFn: applicationsApi.cancel, onSuccess: invalidate })
  const updateStatus = useMutation({
    mutationFn: ({ id, status, notes }: { id: number; status: string; notes?: string | null }) =>
      applicationsApi.updateStatus(id, status, notes),
    onSuccess: invalidate,
  })

  return { store, cancel, updateStatus }
}
