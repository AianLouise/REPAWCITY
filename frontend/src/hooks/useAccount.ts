import { useMutation, useQuery, useQueryClient } from '@tanstack/react-query'
import { accountApi } from '../api/account'

export function useUserDashboard() {
  return useQuery({
    queryKey: ['user-dashboard'],
    queryFn: accountApi.dashboard,
  })
}

export function useFavorites() {
  return useQuery({
    queryKey: ['favorites'],
    queryFn: accountApi.favorites,
  })
}

export function useFavoriteActions() {
  const queryClient = useQueryClient()

  const toggle = useMutation({
    mutationFn: (petId: number) => accountApi.toggleFavorite(petId),
    onSuccess: (res) => {
      void queryClient.invalidateQueries({ queryKey: ['favorites'] })
      void queryClient.invalidateQueries({ queryKey: ['user-dashboard'] })
      // keep per-pet favorite state fresh
      void queryClient.setQueryData(['pet-favorite', res.pet_id], res.favorite)
      void queryClient.invalidateQueries({ queryKey: ['pet-favorite'] })
    },
  })

  return { toggle }
}

export function usePetFavorite(petId: number) {
  return useQuery({
    queryKey: ['pet-favorite', petId],
    queryFn: async () => {
      const pets = await accountApi.favorites()
      return pets.some((p) => p.id === petId)
    },
    enabled: Number.isFinite(petId) && petId > 0,
  })
}
