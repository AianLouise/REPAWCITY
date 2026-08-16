import { Link } from 'react-router-dom'
import type { Pet } from '../types'
import { resolveMedia } from '../api/client'
import FavoriteButton from './FavoriteButton'

const STATUS_LABEL: Record<Pet['status'], string> = {
  available: 'Available',
  on_hold: 'On Hold',
  adopted: 'Adopted',
  deceased: 'No Longer Available',
}

export default function PetCard({ pet }: { pet: Pet }) {
  const unavailable = pet.status === 'adopted' || pet.status === 'deceased'

  return (
    <Link
      to={`/adopt/${pet.id}`}
      className="group bg-white/70 rounded-3xl overflow-hidden shadow-sm hover:shadow-xl transition-shadow duration-300 border border-repaw-hover/40 block"
    >
      <div className="aspect-square overflow-hidden bg-repaw-bg/60 relative">
        <img
          src={resolveMedia(pet.thumb_url)}
          alt={pet.name}
          className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
        />
        <FavoriteButton petId={pet.id} />
        {pet.status !== 'available' && (
          <span className="absolute top-3 left-3 rounded-full bg-repaw-dark/85 text-repaw-bg text-xs font-medium uppercase tracking-wide px-3 py-1">
            {STATUS_LABEL[pet.status]}
          </span>
        )}
        {unavailable && <div className="absolute inset-0 bg-repaw-dark/35" />}
      </div>
      <div className="p-5">
        <h4 className="font-serif text-xl font-semibold text-repaw-dark">{pet.name}</h4>
        <div className="mt-2 flex items-center gap-3 text-sm text-repaw-text/80">
          <span className="inline-flex items-center gap-1">
            <span className="mui-icon text-[18px]">{pet.sex === 'Male' ? 'male' : 'female'}</span>
            {pet.sex}
          </span>
          <span className="w-px h-4 bg-repaw-hover" />
          <span className="inline-flex items-center gap-1">
            <span className="mui-icon text-[18px]">cake</span>
            {pet.age}
          </span>
        </div>
      </div>
    </Link>
  )
}
