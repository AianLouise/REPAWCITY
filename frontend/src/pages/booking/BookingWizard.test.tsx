import { describe, it, expect, vi, beforeEach, afterEach } from 'vitest'
import { render, screen, cleanup } from '@testing-library/react'
import userEvent from '@testing-library/user-event'
import { MemoryRouter } from 'react-router-dom'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import BookingWizard from './BookingWizard'
import { useAuthStore } from '../../store/authStore'

vi.mock('../../hooks/useBooking', () => ({
  useSlots: () => ({ data: { date: '2026-08-20', booked: [] } }),
}))

function renderWizard() {
  const queryClient = new QueryClient({
    defaultOptions: { queries: { retry: false } },
  })
  return render(
    <QueryClientProvider client={queryClient}>
      <MemoryRouter>
        <BookingWizard />
      </MemoryRouter>
    </QueryClientProvider>,
  )
}

describe('BookingWizard', () => {
  beforeEach(() => {
    useAuthStore.setState({
      user: { id: 2, fname: 'Juan', lname: 'Cruz', email: 'j@test.com', user_type: '2', created_at: '' },
      token: 'tok',
    })
  })
  afterEach(cleanup)

  it('shows the intro step initially', () => {
    renderWizard()
    expect(screen.getByText('SET UP AN APPOINTMENT ONLINE')).toBeInTheDocument()
    expect(screen.getByText('Get Started')).toBeInTheDocument()
  })

  it('moves from intro to appointment type step', async () => {
    const user = userEvent.setup()
    renderWizard()
    await user.click(screen.getByText('Get Started'))
    expect(screen.getByText('APPOINTMENT TYPE')).toBeInTheDocument()
  })

  it('requires selecting a type before advancing to date step', async () => {
    const user = userEvent.setup()
    renderWizard()
    await user.click(screen.getByText('Get Started'))

    const next = screen.getByText('Next')
    expect((next as HTMLButtonElement).disabled).toBe(true)

    await user.selectOptions(screen.getByRole('combobox'), 'Adopt')
    expect((screen.getByText('Next') as HTMLButtonElement).disabled).toBe(false)

    await user.click(screen.getByText('Next'))
    expect(screen.getByText('CHOOSE APPOINTMENT DATE')).toBeInTheDocument()
  })

  it('disables Next on the date step until date and slot are chosen', async () => {
    const user = userEvent.setup()
    renderWizard()
    await user.click(screen.getByText('Get Started'))
    await user.selectOptions(screen.getByRole('combobox'), 'Donate')
    await user.click(screen.getByText('Next'))

    const nextDay = new Date()
    nextDay.setDate(nextDay.getDate() + 1)
    const key = `${nextDay.getFullYear()}-${String(nextDay.getMonth() + 1).padStart(2, '0')}-${String(nextDay.getDate()).padStart(2, '0')}`

    // Date and Session inputs in the date step
    const dateInput = document.querySelector('input[type="date"]') as HTMLInputElement
    await user.clear(dateInput)
    await user.type(dateInput, key)

    const sessionSelect = document.querySelector('select') as HTMLSelectElement
    await user.selectOptions(sessionSelect, 'Morning Session')

    expect((screen.getByText('Next') as HTMLButtonElement).disabled).toBe(false)
  })
})
