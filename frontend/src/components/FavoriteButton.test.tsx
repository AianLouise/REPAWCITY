import { describe, it, expect, vi, beforeEach, afterEach } from 'vitest'
import { render, screen, cleanup, fireEvent, waitFor } from '@testing-library/react'
import { MemoryRouter } from 'react-router-dom'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import FavoriteButton from './FavoriteButton'
import { useAuthStore } from '../store/authStore'

const toggleFn = vi.fn().mockResolvedValue({ favorite: true, pet_id: 5 })

vi.mock('../hooks/useAccount', () => ({
  usePetFavorite: () => ({ data: false, isLoading: false }),
  useFavoriteActions: () => ({ toggle: { mutateAsync: toggleFn } }),
}))

function renderButton() {
  const queryClient = new QueryClient({
    defaultOptions: { queries: { retry: false } },
  })
  return render(
    <QueryClientProvider client={queryClient}>
      <MemoryRouter>
        <FavoriteButton petId={5} />
      </MemoryRouter>
    </QueryClientProvider>,
  )
}

describe('FavoriteButton', () => {
  beforeEach(() => {
    useAuthStore.setState({
      user: { id: 2, fname: 'Juan', lname: 'Cruz', email: 'j@test.com', user_type: '2', created_at: '' },
      token: 'tok',
    })
    toggleFn.mockClear()
  })
  afterEach(cleanup)

  it('renders the favorite toggle button for logged-in users', () => {
    renderButton()
    const btn = screen.getByRole('button', { name: /save to favorites/i })
    expect(btn).toBeInTheDocument()
  })

  it('toggles the favorite on click', async () => {
    renderButton()
    fireEvent.click(screen.getByRole('button', { name: /save to favorites/i }))
    await waitFor(() => expect(toggleFn).toHaveBeenCalledWith(5))
  })

  it('shows a login link for anonymous users', () => {
    useAuthStore.setState({ user: null, token: null })
    renderButton()
    expect(screen.getByRole('link', { name: /log in to save this pet/i })).toBeInTheDocument()
  })
})
