import { describe, it, expect, vi, beforeEach, afterEach } from 'vitest'
import { render, screen, cleanup } from '@testing-library/react'
import userEvent from '@testing-library/user-event'
import { MemoryRouter, Routes, Route } from 'react-router-dom'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import ApplyForAdoption from './ApplyForAdoption'
import { useAuthStore } from '@repaw/auth'

const pet = {
  id: 5,
  name: 'Cookie',
  type: 'Dog' as const,
  breed: 'Labrador',
  sex: 'Male' as const,
  weight: '10-20 lbs',
  age: '2 years',
  date: '2023-01-01',
  about: 'Friendly',
  image: 'a.jpg',
  image_url: '/storage/pets/a.jpg',
  is_featured: 0,
  status: 'available' as const,
}

vi.mock('../../hooks/useContent', () => ({
  usePet: () => ({ data: pet, isLoading: false, isError: false }),
}))

const storeMutation = vi.fn().mockResolvedValue({ id: 1 })
vi.mock('@repaw/api-client', () => ({
  useApplicationActions: () => ({
    store: { mutateAsync: storeMutation },
  }),
  resolveMedia: (p: string) => p,
}))

function renderPage() {
  const queryClient = new QueryClient({
    defaultOptions: { queries: { retry: false } },
  })
  return render(
    <QueryClientProvider client={queryClient}>
      <MemoryRouter initialEntries={['/adopt/5/apply']}>
        <Routes>
          <Route path="/adopt/:id/apply" element={<ApplyForAdoption />} />
          <Route path="/adopt/:id" element={<div>PetProfilePage</div>} />
        </Routes>
      </MemoryRouter>
    </QueryClientProvider>,
  )
}

describe('ApplyForAdoption', () => {
  beforeEach(() => {
    useAuthStore.setState({
      user: { id: 2, fname: 'Juan', lname: 'Cruz', email: 'j@test.com', user_type: '2', created_at: '' },
      token: 'tok',
    })
    storeMutation.mockClear()
  })
  afterEach(cleanup)

  it('renders the pet application form', () => {
    renderPage()
    expect(screen.getByText('Apply to Adopt Cookie')).toBeInTheDocument()
    expect(screen.getByText('What is your housing situation?')).toBeInTheDocument()
    expect(screen.getByText('Why do you want to adopt this pet?')).toBeInTheDocument()
  })

  it('requires all fields before submitting', async () => {
    const user = userEvent.setup()
    renderPage()
    await user.click(screen.getByText('Submit Application'))
    expect(storeMutation).not.toHaveBeenCalled()
    expect(screen.getByText('Please describe your housing situation.')).toBeInTheDocument()
  })

  it('submits the application with all answers', async () => {
    const user = userEvent.setup()
    renderPage()

    const textareas = screen.getAllByRole('textbox') as HTMLTextAreaElement[]
    const [housing, otherPets, experience, why] = textareas
    await user.type(housing, 'House with a yard')
    await user.type(otherPets, 'One cat')
    await user.type(experience, 'Grew up with dogs')
    await user.type(why, 'Great temperament')

    await user.click(screen.getByText('Submit Application'))

    expect(storeMutation).toHaveBeenCalledWith({
      pet_id: 5,
      answers: {
        housing: 'House with a yard',
        other_pets: 'One cat',
        experience: 'Grew up with dogs',
        why_this_pet: 'Great temperament',
      },
    })
  })
})
