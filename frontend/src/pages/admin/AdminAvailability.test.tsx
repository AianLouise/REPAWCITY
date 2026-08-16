import { describe, it, expect, vi, beforeEach, afterEach } from 'vitest'
import { render, screen, cleanup } from '@testing-library/react'
import userEvent from '@testing-library/user-event'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import AdminAvailability from './AdminAvailability'

vi.mock('../../hooks/useSchedule', () => ({
  useAdminSchedules: () => ({
    data: [{ id: 1, date: '2026-08-20', is_open: false, morning_capacity: 4, afternoon_capacity: 6, reason: 'Shelter event' }],
    isLoading: false,
  }),
  useScheduleActions: () => ({
    update: { mutateAsync: vi.fn().mockResolvedValue(undefined) },
  }),
}))

function renderAvailability() {
  const queryClient = new QueryClient({
    defaultOptions: { queries: { retry: false } },
  })
  return render(
    <QueryClientProvider client={queryClient}>
      <AdminAvailability />
    </QueryClientProvider>,
  )
}

function currentMonthLabel(): string {
  const now = new Date()
  return now.toLocaleDateString('en-US', { month: 'long', year: 'numeric' })
}

describe('AdminAvailability', () => {
  beforeEach(() => {
    vi.clearAllMocks()
  })
  afterEach(cleanup)

  it('renders the availability calendar heading', () => {
    renderAvailability()
    expect(screen.getByText('Shelter Availability')).toBeInTheDocument()
    expect(screen.getByText(currentMonthLabel())).toBeInTheDocument()
  })

  it('shows a closed day marker with capacity info', () => {
    renderAvailability()
    expect(screen.getByText('20')).toBeInTheDocument()
    expect(screen.getByText('closed')).toBeInTheDocument()
  })

  it('opens the day editor modal when clicking an open day', async () => {
    const user = userEvent.setup()
    renderAvailability()
    const todayDay = new Date().getDate()
    const futureDay = String(todayDay + 5)
    const dayButton = screen.getByText(futureDay)
    await user.click(dayButton)
    expect(screen.getByText('Shelter is open for visits')).toBeInTheDocument()
    expect(screen.getByText('Morning capacity')).toBeInTheDocument()
    expect(screen.getByText('Afternoon capacity')).toBeInTheDocument()
  })
})
