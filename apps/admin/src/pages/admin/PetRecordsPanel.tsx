import { useState } from 'react'
import { useAdminPetRecords, usePetRecordActions } from '@repaw/api-client'
import type { PetRecordType } from '@repaw/api-client'
import { MdDelete, MdVaccines, MdMedicalServices, MdContentCut, MdHome, MdNote } from 'react-icons/md'
import type { IconType } from 'react-icons'

const TYPE_LABEL: Record<string, string> = {
  vaccination: 'Vaccination',
  vet_visit: 'Vet Visit',
  grooming: 'Grooming',
  intake: 'Intake',
  note: 'Note',
}

const TYPE_ICON: Record<string, IconType> = {
  vaccination: MdVaccines,
  vet_visit: MdMedicalServices,
  grooming: MdContentCut,
  intake: MdHome,
  note: MdNote,
}

export default function PetRecordsPanel({ petId }: { petId: number }) {
  const { data, isLoading } = useAdminPetRecords(petId)
  const { store, destroy } = usePetRecordActions(petId)

  const [showForm, setShowForm] = useState(false)
  const [form, setForm] = useState({ type: 'vaccination' as PetRecordType, title: '', details: '', record_date: '' })
  const [saving, setSaving] = useState(false)
  const [error, setError] = useState<string | null>(null)

  const records = data ?? []

  async function handleAdd() {
    setError(null)
    if (!form.title.trim() || !form.details.trim() || !form.record_date) {
      setError('Please fill in title, details, and date.')
      return
    }
    setSaving(true)
    try {
      await store.mutateAsync({
        type: form.type,
        title: form.title.trim(),
        details: form.details.trim(),
        record_date: form.record_date,
      })
      setShowForm(false)
      setForm({ type: 'vaccination', title: '', details: '', record_date: '' })
    } catch {
      setError('Failed to save record.')
    } finally {
      setSaving(false)
    }
  }

  async function handleDelete(recordId: number) {
    if (!confirm('Delete this care record?')) return
    await destroy.mutateAsync(recordId)
  }

  return (
    <div className="mt-8 bg-white/70 rounded-3xl p-6 border border-repaw-hover/40 shadow-sm">
      <div className="flex items-center justify-between mb-4">
        <h2 className="font-serif text-xl font-bold text-repaw-dark">Care Records</h2>
        <button
          onClick={() => setShowForm((s) => !s)}
          className="bg-repaw-text text-repaw-bg rounded-full px-4 py-2 text-xs font-medium uppercase tracking-wide hover:bg-repaw-dark transition-colors"
        >
          + Add Record
        </button>
      </div>

      {error && (
        <div className="mb-4 rounded-xl border border-repaw-danger/40 bg-red-50 px-4 py-3 text-sm text-repaw-danger">{error}</div>
      )}

      {showForm && (
        <div className="mb-6 bg-repaw-bg/60 rounded-2xl p-4 space-y-3">
          <div className="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div>
              <label className="block text-xs font-medium text-repaw-dark mb-1">Type</label>
              <select
                value={form.type}
                onChange={(e) => setForm((f) => ({ ...f, type: e.target.value as PetRecordType }))}
                className="w-full rounded-lg border border-repaw-hover bg-white/70 px-3 py-2 text-sm text-repaw-text focus:outline-none focus:ring-2 focus:ring-repaw-text"
              >
                {Object.entries(TYPE_LABEL).map(([value, label]) => (
                  <option key={value} value={value}>{label}</option>
                ))}
              </select>
            </div>
            <div>
              <label htmlFor="record-date" className="block text-xs font-medium text-repaw-dark mb-1">Date</label>
              <input
                id="record-date"
                type="date"
                value={form.record_date}
                onChange={(e) => setForm((f) => ({ ...f, record_date: e.target.value }))}
                className="w-full rounded-lg border border-repaw-hover bg-white/70 px-3 py-2 text-sm text-repaw-text focus:outline-none focus:ring-2 focus:ring-repaw-text"
              />
            </div>
            <div>
              <label className="block text-xs font-medium text-repaw-dark mb-1">Title</label>
              <input
                type="text"
                value={form.title}
                onChange={(e) => setForm((f) => ({ ...f, title: e.target.value }))}
                placeholder="e.g. Rabies booster"
                className="w-full rounded-lg border border-repaw-hover bg-white/70 px-3 py-2 text-sm text-repaw-text focus:outline-none focus:ring-2 focus:ring-repaw-text"
              />
            </div>
          </div>
          <div>
            <label className="block text-xs font-medium text-repaw-dark mb-1">Details</label>
            <textarea
              value={form.details}
              onChange={(e) => setForm((f) => ({ ...f, details: e.target.value }))}
              rows={2}
              placeholder="Details of the record..."
              className="w-full rounded-lg border border-repaw-hover bg-white/70 px-3 py-2 text-sm text-repaw-text focus:outline-none focus:ring-2 focus:ring-repaw-text"
            />
          </div>
          <div className="flex justify-end gap-2">
            <button onClick={() => setShowForm(false)} className="bg-repaw-hover text-repaw-dark rounded-full px-4 py-2 text-xs font-medium uppercase tracking-wide hover:bg-repaw-hover/70 transition-colors">
              Cancel
            </button>
            <button onClick={() => void handleAdd()} disabled={saving} className="bg-repaw-text text-repaw-bg rounded-full px-4 py-2 text-xs font-medium uppercase tracking-wide hover:bg-repaw-dark transition-colors disabled:opacity-60">
              {saving ? '...' : 'Save'}
            </button>
          </div>
        </div>
      )}

      {isLoading ? (
        <p className="text-repaw-text/70 text-sm">Loading records...</p>
      ) : records.length === 0 ? (
        <p className="text-repaw-text/70 text-sm">No care records yet.</p>
      ) : (
        <div className="space-y-3">
          {records.map((r) => (
            <div key={r.id} className="flex items-start gap-3 bg-repaw-bg/50 rounded-2xl p-4">
              {(() => { const Icon = TYPE_ICON[r.type] ?? MdNote; return <Icon size={20} className="text-repaw-dark mt-0.5" /> })()}
              <div className="flex-1 min-w-0">
                <div className="flex items-center justify-between gap-2 flex-wrap">
                  <p className="font-semibold text-repaw-dark text-sm capitalize">{r.title}</p>
                  <span className="text-xs text-repaw-text/60">{TYPE_LABEL[r.type]}</span>
                </div>
                <p className="text-sm text-repaw-text/80 mt-0.5">{r.details}</p>
                <p className="text-xs text-repaw-text/50 mt-1">
                  {new Date(r.record_date + 'T00:00:00').toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}
                  {r.created_by ? ` · by ${r.created_by}` : ''}
                </p>
              </div>
              <button
                onClick={() => void handleDelete(r.id)}
                className="shrink-0 text-repaw-danger hover:opacity-80 transition-opacity"
                aria-label="Delete record"
                title="Delete record"
              >
                <MdDelete />
              </button>
            </div>
          ))}
        </div>
      )}
    </div>
  )
}
