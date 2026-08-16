import { Link } from 'react-router-dom'
import type { Pet } from '../types'
import { resolveMedia } from '../api/client'

export default function PetCard({ pet }: { pet: Pet }) {
  return (
    <Link
      to={`/adopt/${pet.id}`}
      className="group bg-white/70 rounded-3xl overflow-hidden shadow-sm hover:shadow-xl transition-shadow duration-300 border border-repaw-hover/40 block"
    >
      <div className="aspect-square overflow-hidden bg-repaw-bg/60">
        <img
          src={resolveMedia(pet.image_url)}
          alt={pet.name}
          className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
        />
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
