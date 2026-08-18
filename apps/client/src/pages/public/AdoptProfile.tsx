import { Link, useParams } from 'react-router-dom'
import { useAuthStore } from '@repaw/auth'
import { Loading } from '@repaw/ui'
import { usePet } from '../../hooks/useContent'
import { usePetRecords } from '@repaw/api-client'
import { resolveMedia } from '@repaw/api-client'
import FavoriteButton from '../../components/FavoriteButton'

const STATUS_LABEL: Record<string, string> = {
  available: 'Available for Adoption',
  on_hold: 'On Hold',
  adopted: 'Adopted',
  deceased: 'No Longer Available',
}

export default function AdoptProfile() {
  const { id } = useParams()
  const petId = Number(id)
  const user = useAuthStore((s) => s.user)
  const { data: pet, isLoading, isError } = usePet(petId)

  if (isLoading) return <Loading />

  if (isError || !pet) {
    return (
      <div className="max-w-3xl mx-auto px-6 py-20 text-center">
        <h1 className="font-serif text-3xl font-bold text-repaw-dark">Pet Not Found</h1>
        <p className="mt-3 text-repaw-text/80">The requested pet could not be found.</p>
        <Link to="/adopt" className="inline-flex items-center gap-2 text-repaw-dark font-medium hover:text-repaw-text transition-colors mt-8">
          <span className="mui-icon">arrow_back</span> Back to pets
        </Link>
      </div>
    )
  }

  const bookable = pet.status === 'available' || pet.status === 'on_hold'

  return (
    <div>
      <section className="relative overflow-hidden">
        <div className="absolute inset-0 bg-gradient-to-br from-repaw-bg/90 via-repaw-bg/70 to-repaw-accent/40 pointer-events-none" />
        <div className="relative max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 py-16 lg:py-20 text-center">
          <h1 className="font-serif text-4xl sm:text-5xl font-bold text-repaw-dark">Meet your new best friend</h1>
          <p className="mt-4 text-lg text-repaw-text/90 max-w-2xl mx-auto">All of our cats and dogs can be seen by appointment only.</p>
        </div>
      </section>

      <section className="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 pt-16 lg:pt-20 pb-20">
        <Link to="/adopt" className="inline-flex items-center gap-2 text-repaw-dark font-medium hover:text-repaw-text transition-colors mb-8">
          <span className="mui-icon">arrow_back</span> Back to pets
        </Link>

        <div className="grid grid-cols-1 lg:grid-cols-2 gap-10 items-start">
          <div className="rounded-3xl overflow-hidden border border-repaw-hover/40 shadow-sm bg-white/70 aspect-square relative">
            <img src={resolveMedia(pet.image_url)} alt={pet.name} className="w-full h-full object-cover" />
            <FavoriteButton petId={pet.id} />
          </div>

          <div className="bg-white/70 rounded-3xl p-8 border border-repaw-hover/40 shadow-sm">
            <div className="flex items-center gap-3 flex-wrap">
              <h1 className="font-serif text-3xl font-bold text-repaw-dark">{pet.name}</h1>
              {pet.status !== 'available' && (
                <span className="rounded-full bg-repaw-dark/85 text-repaw-bg text-xs font-medium uppercase tracking-wide px-3 py-1">
                  {STATUS_LABEL[pet.status]}
                </span>
              )}
            </div>

            <dl className="mt-6 grid grid-cols-2 gap-y-4 gap-x-6 text-sm">
              <Detail label="Type" value={pet.type} />
              <Detail label="Breed" value={pet.breed} />
              <Detail label="Sex" value={pet.sex} />
              <Detail label="Weight" value={pet.weight} />
              <Detail label="Age" value={pet.age} />
              <Detail label="Date of Rescue" value={pet.date ?? '—'} />
            </dl>

            <h2 className="mt-8 font-serif text-xl font-semibold text-repaw-dark">About {pet.name}:</h2>
            <p className="mt-3 text-repaw-text/90 leading-relaxed whitespace-pre-line">{pet.about}</p>

            {bookable && user ? (
              <div className="mt-8 flex flex-wrap gap-3">
                <Link
                  to={`/adopt/${pet.id}/apply`}
                  className="inline-flex items-center justify-center gap-2 rounded-full bg-repaw-text text-repaw-bg px-7 py-3 text-[15px] font-medium uppercase tracking-wide hover:bg-repaw-dark transition-colors duration-300"
                >
                  <span className="mui-icon text-[20px]">favorite</span>
                  Apply to Adopt {pet.name}
                </Link>
                <Link
                  to={`/book?pet=${pet.id}`}
                  target="_blank"
                  className="inline-flex items-center justify-center gap-2 rounded-full bg-repaw-hover text-repaw-dark px-7 py-3 text-[15px] font-medium uppercase tracking-wide hover:bg-repaw-hover/70 transition-colors duration-300"
                >
                  <span className="mui-icon text-[20px]">call</span>
                  Book a Visit
                </Link>
              </div>
            ) : bookable && !user ? (
              <Link
                to="/login"
                className="mt-8 inline-flex items-center justify-center gap-2 rounded-full bg-repaw-text text-repaw-bg px-7 py-3 text-[15px] font-medium uppercase tracking-wide hover:bg-repaw-dark transition-colors duration-300"
              >
                <span className="mui-icon text-[20px]">call</span>
                Log in to Adopt {pet.name}
              </Link>
            ) : (
              <div className="mt-8 inline-flex items-center gap-2 text-repaw-text/70 text-sm">
                <span className="mui-icon text-[20px]">block</span>
                {pet.status === 'adopted'
                  ? `${pet.name} has found a forever home!`
                  : `${pet.name} is not currently available for booking.`}
              </div>
            )}
          </div>
        </div>

        <CareSection petId={pet.id} petName={pet.name} />
      </section>
    </div>
  )
}

function CareSection({ petId, petName }: { petId: number; petName: string }) {
  const { data: records } = usePetRecords(petId)

  if (!records || records.length === 0) return null

  const icon: Record<string, string> = {
    vaccination: 'vaccines',
    grooming: 'content_cut',
    intake: 'home',
    note: 'note',
  }

  return (
    <div className="mt-16 bg-white/70 rounded-3xl p-8 border border-repaw-hover/40 shadow-sm">
      <h2 className="font-serif text-2xl font-bold text-repaw-dark mb-6">Care History</h2>
      <div className="space-y-4">
        {records.map((r) => (
          <div key={r.id} className="flex items-start gap-4">
            <span className="mui-icon text-2xl text-repaw-dark mt-0.5">{icon[r.type] ?? 'note'}</span>
            <div>
              <p className="font-semibold text-repaw-dark capitalize">{r.title}</p>
              <p className="text-sm text-repaw-text/80 mt-0.5">{r.details}</p>
              <p className="text-xs text-repaw-text/50 mt-1">
                {new Date(r.record_date + 'T00:00:00').toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })}
              </p>
            </div>
          </div>
        ))}
      </div>
      <p className="mt-6 text-xs text-repaw-text/60">
        {petName}'s care records are kept up to date by our shelter team.
      </p>
    </div>
  )
}

function Detail({ label, value }: { label: string; value: string }) {
  return (
    <div>
      <dt className="text-repaw-text/60">{label}</dt>
      <dd className="font-medium text-repaw-dark">{value}</dd>
    </div>
  )
}
