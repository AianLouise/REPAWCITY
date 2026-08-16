import { useState } from 'react'
import { Link } from 'react-router-dom'
import { useAuthStore } from '../store/authStore'
import { usePetFavorite, useFavoriteActions } from '../hooks/useAccount'

export default function FavoriteButton({ petId }: { petId: number }) {
  const user = useAuthStore((s) => s.user)
  const { data: isFavorite, isLoading } = usePetFavorite(petId)
  const { toggle } = useFavoriteActions()
  const [busy, setBusy] = useState(false)

  if (!user) {
    return (
      <Link
        to="/login"
        onClick={(e) => {
          e.preventDefault()
          e.stopPropagation()
        }}
        className="absolute top-3 right-3 z-10 inline-flex items-center justify-center h-10 w-10 rounded-full bg-white/85 text-repaw-text shadow-sm hover:bg-white transition-colors"
        aria-label="Log in to save this pet"
        title="Log in to save this pet"
      >
        <span className="mui-icon text-[22px]">favorite_border</span>
      </Link>
    )
  }

  async function handleToggle() {
    setBusy(true)
    try {
      await toggle.mutateAsync(petId)
    } finally {
      setBusy(false)
    }
  }

  return (
    <button
      onClick={(e) => {
        e.preventDefault()
        e.stopPropagation()
        void handleToggle()
      }}
      disabled={busy || isLoading}
      className={`absolute top-3 right-3 z-10 inline-flex items-center justify-center h-10 w-10 rounded-full shadow-sm transition-colors disabled:opacity-60 ${
        isFavorite ? 'bg-repaw-danger text-white' : 'bg-white/85 text-repaw-text hover:bg-white'
      }`}
      aria-label={isFavorite ? 'Remove from favorites' : 'Save to favorites'}
      title={isFavorite ? 'Remove from favorites' : 'Save to favorites'}
    >
      <span className="mui-icon text-[22px]">{isFavorite ? 'favorite' : 'favorite_border'}</span>
    </button>
  )
}
