import { api } from './client'
import type { PetRecord, PetRecordType } from '../types'

export const petRecordsApi = {
  async public(petId: number): Promise<PetRecord[]> {
    const res = await api.get<{ data: PetRecord[] }>(`/pets/${petId}/records`)
    return res.data.data
  },

  async adminList(petId: number): Promise<PetRecord[]> {
    const res = await api.get<{ data: PetRecord[] }>(`/admin/pets/${petId}/records`)
    return res.data.data
  },

  async store(petId: number, data: { type: PetRecordType; title: string; details: string; record_date: string }): Promise<PetRecord> {
    const res = await api.post<{ record: PetRecord }>(`/admin/pets/${petId}/records`, data)
    return res.data.record
  },

  async destroy(petId: number, recordId: number): Promise<void> {
    await api.delete(`/admin/pets/${petId}/records/${recordId}`)
  },
}
