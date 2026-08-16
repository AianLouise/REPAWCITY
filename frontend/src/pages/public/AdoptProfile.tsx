import { Link, useParams } from 'react-router-dom'
import { useAuthStore } from '../../store/authStore'
import { Loading } from '../../components/Shared'
import { usePet } from '../../hooks/useContent'
import { resolveMedia } from '../../api/client'

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
          <div className="rounded-3xl overflow-hidden border border-repaw-hover/40 shadow-sm bg-white/70 aspect-square">
            <img src={resolveMedia(pet.image_url)} alt={pet.name} className="w-full h-full object-cover" />
          </div>

          <div className="bg-white/70 rounded-3xl p-8 border border-repaw-hover/40 shadow-sm">
            <h1 className="font-serif text-3xl font-bold text-repaw-dark">{pet.name}</h1>

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

            {user ? (
              <Link
                to="/book"
                target="_blank"
                className="mt-8 inline-flex items-center justify-center gap-2 rounded-full bg-repaw-text text-repaw-bg px-7 py-3 text-[15px] font-medium uppercase tracking-wide hover:bg-repaw-dark transition-colors duration-300"
              >
                <span className="mui-icon text-[20px]">call</span>
                Contact us to Meet {pet.name}
              </Link>
            ) : (
              <Link
                to="/login"
                className="mt-8 inline-flex items-center justify-center gap-2 rounded-full bg-repaw-text text-repaw-bg px-7 py-3 text-[15px] font-medium uppercase tracking-wide hover:bg-repaw-dark transition-colors duration-300"
              >
                <span className="mui-icon text-[20px]">call</span>
                Log in to Book an Appointment
              </Link>
            )}
          </div>
        </div>
      </section>
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
