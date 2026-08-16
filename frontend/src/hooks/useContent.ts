import { useQuery } from '@tanstack/react-query'
import { petsApi, type PetFilters } from '../api/pets'
import { newsApi } from '../api/news'

export function usePets(filters: PetFilters = {}) {
  return useQuery({
    queryKey: ['pets', filters],
    queryFn: () => petsApi.list(filters),
  })
}

export function useFeaturedPets() {
  return useQuery({
    queryKey: ['pets', { featured: true }],
    queryFn: () => petsApi.list({ featured: true, per_page: 4 }),
  })
}

export function usePet(id: number) {
  return useQuery({
    queryKey: ['pet', id],
    queryFn: () => petsApi.show(id),
    enabled: Number.isFinite(id) && id > 0,
  })
}

export function useNews(featured = false) {
  return useQuery({
    queryKey: ['news', featured],
    queryFn: () => newsApi.list(featured),
  })
}

export function useNewsArticle(id: number) {
  return useQuery({
    queryKey: ['news-article', id],
    queryFn: () => newsApi.show(id),
    enabled: Number.isFinite(id) && id > 0,
  })
}
