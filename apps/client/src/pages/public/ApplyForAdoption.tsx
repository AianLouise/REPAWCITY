import { useState } from 'react'
import { useNavigate, useParams } from 'react-router-dom'
import { usePet } from '../../hooks/useContent'
import { useApplicationActions } from '@repaw/api-client'
import { Loading } from '@repaw/ui'
import { resolveMedia } from '@repaw/api-client'

export default function ApplyForAdoption() {
  const { id } = useParams()
  const petId = Number(id)
  const navigate = useNavigate()
  const { data: pet, isLoading, isError } = usePet(petId)
  const { store } = useApplicationActions()

  const [form, setForm] = useState({
    housing: '',
    other_pets: '',
    experience: '',
    why_this_pet: '',
  })
  const [errors, setErrors] = useState<Record<string, string>>({})
  const [apiError, setApiError] = useState<string | null>(null)
  const [submitting, setSubmitting] = useState(false)

  if (isLoading) return <Loading />

  if (isError || !pet) {
    return (
      <div className="max-w-3xl mx-auto px-6 py-20 text-center">
        <h1 className="font-serif text-3xl font-bold text-repaw-dark">Pet Not Found</h1>
      </div>
    )
  }

  const bookable = pet.status === 'available' || pet.status === 'on_hold'
  const activePet = pet

  function set(key: keyof typeof form, value: string) {
    setForm((f) => ({ ...f, [key]: value }))
    setErrors((e) => ({ ...e, [key]: '' }))
  }

  async function handleSubmit() {
    setApiError(null)
    const nextErrors: Record<string, string> = {}
    if (!form.housing.trim()) nextErrors.housing = 'Please describe your housing situation.'
    if (!form.other_pets.trim()) nextErrors.other_pets = 'Please tell us about any other pets.'
    if (!form.experience.trim()) nextErrors.experience = 'Please share your pet experience.'
    if (!form.why_this_pet.trim()) nextErrors.why_this_pet = 'Please tell us why you want this pet.'
    setErrors(nextErrors)
    if (Object.keys(nextErrors).length > 0) return

    setSubmitting(true)
    try {
      await store.mutateAsync({
        pet_id: activePet.id,
        answers: {
          housing: form.housing,
          other_pets: form.other_pets,
          experience: form.experience,
          why_this_pet: form.why_this_pet,
        },
      })
      navigate('/adopt/' + activePet.id, { state: { applied: true } })
    } catch (e) {
      const err = e as { response?: { status?: number; data?: { message?: string } } }
      setApiError(err.response?.data?.message ?? 'Failed to submit your application. Please try again.')
    } finally {
      setSubmitting(false)
    }
  }

  if (!bookable) {
    return (
      <div className="max-w-3xl mx-auto px-6 py-20 text-center">
        <h1 className="font-serif text-3xl font-bold text-repaw-dark">Not Available</h1>
        <p className="mt-3 text-repaw-text/80">This pet is no longer available for adoption.</p>
      </div>
    )
  }

  const field = (key: keyof typeof form, label: string, placeholder: string) => (
    <div>
      <label className="block text-sm font-medium text-repaw-dark mb-1.5">
        {label} <span className="text-repaw-danger">*</span>
      </label>
      <textarea
        value={form[key]}
        onChange={(e) => set(key, e.target.value)}
        rows={3}
        placeholder={placeholder}
        className="w-full rounded-xl border border-repaw-hover bg-repaw-bg px-4 py-2.5 text-repaw-text focus:outline-none focus:ring-2 focus:ring-repaw-text"
      />
      {errors[key] && <p className="mt-1 text-xs text-repaw-danger">{errors[key]}</p>}
    </div>
  )

  return (
    <div className="max-w-3xl mx-auto px-6 py-12">
      <button onClick={() => navigate(-1)} className="inline-flex items-center gap-2 text-repaw-dark font-medium hover:text-repaw-text transition-colors mb-8">
        <span className="mui-icon">arrow_back</span> Back
      </button>

      <div className="flex items-center gap-5 mb-8">
        <img src={resolveMedia(pet.image_url)} alt={pet.name} className="h-20 w-20 rounded-2xl object-cover border border-repaw-hover/40" />
        <div>
          <h1 className="font-serif text-3xl font-bold text-repaw-dark">Apply to Adopt {pet.name}</h1>
          <p className="text-repaw-text/80 mt-1">
            {pet.breed} · {pet.type} · {pet.sex} · {pet.age}
          </p>
        </div>
      </div>

      <div className="bg-white/70 rounded-3xl p-8 border border-repaw-hover/40 shadow-sm space-y-5">
        <p className="text-sm text-repaw-text/80">
          Please answer honestly. Our team reviews every application to make sure each pet finds the best home.
        </p>

        {apiError && (
          <div className="rounded-xl border border-repaw-danger/40 bg-red-50 px-4 py-3 text-sm text-repaw-danger">{apiError}</div>
        )}

        {field('housing', 'What is your housing situation?', 'e.g. Owned home with a fenced yard')}
        {field('other_pets', 'Tell us about any other pets you have.', 'e.g. One 3-year-old cat, fully vaccinated')}
        {field('experience', 'What is your experience with pets?', 'e.g. I grew up with dogs and currently walk a neighbor\u2019s dog')}
        {field('why_this_pet', 'Why do you want to adopt this pet?', 'e.g. Looking for a calm companion for my family')}

        <div className="flex justify-end gap-4 pt-2">
          <button onClick={() => navigate(-1)} className="bg-repaw-hover text-repaw-dark rounded-full px-8 py-3 text-[15px] font-medium uppercase tracking-wide hover:bg-repaw-hover/70 transition-colors">
            Cancel
          </button>
          <button
            onClick={() => void handleSubmit()}
            disabled={submitting}
            className="bg-repaw-text text-repaw-bg rounded-full px-8 py-3 text-[15px] font-medium uppercase tracking-wide hover:bg-repaw-dark transition-colors disabled:opacity-60"
          >
            {submitting ? 'Submitting...' : 'Submit Application'}
          </button>
        </div>
      </div>
    </div>
  )
}
