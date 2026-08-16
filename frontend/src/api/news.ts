import { api } from './client'
import type { NewsArticle, Paginated } from '../types'

export const newsApi = {
  async list(featured = false): Promise<Paginated<NewsArticle>> {
    const res = await api.get<Paginated<NewsArticle>>('/news', {
      params: featured ? { featured: 1 } : {},
    })
    return res.data
  },

  async show(id: number): Promise<NewsArticle> {
    const res = await api.get<{ data: NewsArticle }>(`/news/${id}`)
    return res.data.data
  },
}
