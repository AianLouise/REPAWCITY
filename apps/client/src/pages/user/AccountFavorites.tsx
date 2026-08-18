import { Link } from 'react-router-dom'
import { useFavorites } from '../../hooks/useAccount'
import { Loading } from '@repaw/ui'
import PetCard from '../../components/PetCard'

export default function AccountFavorites() {
  const { data, isLoading } = useFavorites()

  if (isLoading) return <Loading />

  return (
    <div className="space-y-6">
      <div className="flex items-center gap-3">
        <span className="mui-icon text-3xl text-repaw-dark">favorite</span>
        <h1 className="font-serif text-3xl font-bold text-repaw-dark">Favorite Pets</h1>
      </div>

      {!data || data.length === 0 ? (
        <div className="bg-white/70 rounded-3xl p-8 border border-repaw-hover/40 shadow-sm text-center">
          <p className="text-repaw-text/80 mb-4">You haven't saved any pets yet.</p>
          <Link to="/adopt" className="inline-block bg-repaw-text text-repaw-bg rounded-full px-6 py-2.5 text-sm font-medium uppercase tracking-wide hover:bg-repaw-dark transition-colors">
            Browse Pets
          </Link>
        </div>
      ) : (
        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
          {data.map((pet) => (
            <PetCard key={pet.id} pet={pet} />
          ))}
        </div>
      )}
    </div>
  )
}
