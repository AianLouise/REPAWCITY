import { useState } from 'react'
import { useAdminActions } from '../../hooks/useAdmin'
import type { Pet } from '../../types'
import { dogBreeds, catBreeds, weights, ages } from './petOptions'

interface PetFormProps {
  mode: 'create' | 'edit'
  pet?: Pet
  onDone?: () => void
}

export default function PetForm({ mode, pet, onDone }: PetFormProps) {
  const actions = useAdminActions()
  const [form, setForm] = useState({
    name: pet?.name ?? '',
    type: pet?.type ?? '',
    breed: pet?.breed ?? '',
    sex: pet?.sex ?? '',
    weight: pet?.weight ?? '',
    age: pet?.age ?? '',
    date: pet?.date ?? '',
    intake_date: pet?.intake_date ?? '',
    microchip: pet?.microchip ?? '',
    about: pet?.about ?? '',
  })
  const [intakeNotes, setIntakeNotes] = useState(pet?.intake_notes ?? '')
  const [image, setImage] = useState<File | null>(null)
  const [notice, setNotice] = useState<string | null>(null)
  const [error, setError] = useState<string | null>(null)

  const set = <K extends keyof typeof form>(key: K, value: string) => setForm((f) => ({ ...f, [key]: value }))

  const breeds = form.type === 'Cat' ? catBreeds : form.type === 'Dog' ? dogBreeds : []

  async function handleSubmit(e: React.FormEvent) {
    e.preventDefault()
    setNotice(null)
    setError(null)

    const fd = new FormData()
    Object.entries(form).forEach(([k, v]) => fd.append(k, v))
    fd.append('intake_notes', intakeNotes)
    if (image) fd.append('image', image)

    try {
      if (mode === 'create') {
        await actions.storePet.mutateAsync(fd)
        setNotice('Pets added successfully')
        setForm({ name: '', type: '', breed: '', sex: '', weight: '', age: '', date: '', intake_date: '', microchip: '', about: '' })
        setIntakeNotes('')
        setImage(null)
      } else if (pet) {
        await actions.updatePet.mutateAsync({ id: pet.id, formData: fd })
        setNotice('Data updated successfully')
      }
      onDone?.()
    } catch (e) {
      const err = e as { response?: { data?: { message?: string; errors?: Record<string, string[]> } } }
      setError(err.response?.data?.errors?.image?.[0] ?? err.response?.data?.message ?? 'Failed to save pet')
    }
  }

  const inputCls =
    'w-full rounded-xl border border-repaw-hover bg-repaw-bg px-4 py-2.5 text-repaw-text focus:outline-none focus:ring-2 focus:ring-repaw-text'

  return (
    <div className="bg-white/70 rounded-3xl p-8 border border-repaw-hover/40 shadow-sm">
      <div className="flex items-center gap-3 mb-6">
        <span className="mui-icon text-3xl text-repaw-dark">pets</span>
        <h1 className="font-serif text-3xl font-bold text-repaw-dark">Pet Form</h1>
      </div>

      {notice && <div className="mb-4 rounded-xl border border-green-400/40 bg-green-50 px-4 py-3 text-sm text-green-800">{notice}</div>}
      {error && <div className="mb-4 rounded-xl border border-repaw-danger/40 bg-red-50 px-4 py-3 text-sm text-repaw-danger">{error}</div>}

      <form onSubmit={handleSubmit} className="space-y-5">
        <div className="grid grid-cols-1 sm:grid-cols-2 gap-5">
          <div>
            <label className="block text-sm font-medium text-repaw-dark mb-1.5">Pet Name:</label>
            <input type="text" required value={form.name} onChange={(e) => set('name', e.target.value)} className={inputCls} />
          </div>
          <div>
            <label className="block text-sm font-medium text-repaw-dark mb-1.5">Pet Type:</label>
            <select required value={form.type} onChange={(e) => set('type', e.target.value)} className={inputCls}>
              <option value="">Select Type</option>
              <option value="Dog">Dog</option>
              <option value="Cat">Cat</option>
            </select>
          </div>
          <div>
            <label className="block text-sm font-medium text-repaw-dark mb-1.5">Breed:</label>
            <select value={form.breed} onChange={(e) => set('breed', e.target.value)} className={inputCls}>
              <option value="">Select Breed</option>
              {breeds.map((b) => (
                <option key={b} value={b}>
                  {b}
                </option>
              ))}
            </select>
          </div>
          <div>
            <label className="block text-sm font-medium text-repaw-dark mb-1.5">Sex:</label>
            <select required value={form.sex} onChange={(e) => set('sex', e.target.value)} className={inputCls}>
              <option value="">Select Sex</option>
              <option value="Male">Male</option>
              <option value="Female">Female</option>
            </select>
          </div>
          <div>
            <label className="block text-sm font-medium text-repaw-dark mb-1.5">Weight:</label>
            <select required value={form.weight} onChange={(e) => set('weight', e.target.value)} className={inputCls}>
              <option value="">Select Weight</option>
              {weights.map((w) => (
                <option key={w} value={w}>
                  {w}
                </option>
              ))}
            </select>
          </div>
          <div>
            <label className="block text-sm font-medium text-repaw-dark mb-1.5">Age:</label>
            <select required value={form.age} onChange={(e) => set('age', e.target.value)} className={inputCls}>
              <option value="">Select Age</option>
              {ages.map((a) => (
                <option key={a} value={a}>
                  {a}
                </option>
              ))}
            </select>
          </div>
          <div>
            <label className="block text-sm font-medium text-repaw-dark mb-1.5">Date of Rescue:</label>
            <input type="date" required value={form.date} onChange={(e) => set('date', e.target.value)} className={inputCls} />
          </div>
          <div>
            <label className="block text-sm font-medium text-repaw-dark mb-1.5">Intake Date:</label>
            <input type="date" value={form.intake_date} onChange={(e) => set('intake_date', e.target.value)} className={inputCls} />
          </div>
          <div>
            <label className="block text-sm font-medium text-repaw-dark mb-1.5">Microchip:</label>
            <input type="text" value={form.microchip} onChange={(e) => set('microchip', e.target.value)} placeholder="e.g. 982000123456789" className={inputCls} />
          </div>
          <div>
            <label className="block text-sm font-medium text-repaw-dark mb-1.5">Intake Notes:</label>
            <input type="text" value={intakeNotes} onChange={(e) => setIntakeNotes(e.target.value)} placeholder="Where/how this pet was found" className={inputCls} />
          </div>
          <div className="sm:col-span-2">
            <label className="block text-sm font-medium text-repaw-dark mb-1.5">About:</label>
            <textarea required value={form.about} onChange={(e) => set('about', e.target.value)} rows={4} className={inputCls} />
          </div>
          <div className="sm:col-span-2">
            <label className="block text-sm font-medium text-repaw-dark mb-1.5">
              Image: {mode === 'edit' && <span className="text-xs text-repaw-text/60">(leave blank to keep current)</span>}
            </label>
            <input
              type="file"
              accept="image/jpeg,image/png"
              required={mode === 'create'}
              onChange={(e) => setImage(e.target.files?.[0] ?? null)}
              className={`${inputCls} file:mr-4 file:rounded-lg file:border-0 file:bg-repaw-text file:px-4 file:py-2 file:text-repaw-bg`}
            />
          </div>
        </div>

        <button
          type="submit"
          disabled={actions.storePet.isPending || actions.updatePet.isPending}
          className="inline-flex items-center gap-2 bg-repaw-text text-repaw-bg rounded-full px-8 py-3 text-[15px] font-medium uppercase tracking-wide hover:bg-repaw-dark transition-colors duration-300 disabled:opacity-60"
        >
          <span className="mui-icon">save</span> {mode === 'create' ? 'Submit' : 'Update'}
        </button>
      </form>
    </div>
  )
}
