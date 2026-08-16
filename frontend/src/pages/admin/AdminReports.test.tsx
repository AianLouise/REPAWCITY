import { describe, it, expect, vi, afterEach } from 'vitest'
import { render, screen, cleanup } from '@testing-library/react'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import AdminReports from './AdminReports'

vi.mock('../../hooks/useReports', () => ({
  useReports: () => ({
    data: {
      months: 12,
      totals: { appointments: 5, applications: 3, adoptions: 1, donations_cash: 500, volunteer_hours: 8 },
      series: [
        { month: '2026-08', label: 'Aug 2026', appointments: 5, applications: 3, adoptions: 1, donations_cash: 500, donations_in_kind: 1, volunteer_hours: 8 },
      ],
      top_pets_by_appointments: [{ pet_id: 1, name: 'Cookie', appointments: 3 }],
      top_pets_by_applications: [{ pet_id: 1, name: 'Cookie', applications: 2 }],
    },
    isLoading: false,
  }),
}))

function renderReports() {
  const queryClient = new QueryClient({
    defaultOptions: { queries: { retry: false } },
  })
  return render(
    <QueryClientProvider client={queryClient}>
      <AdminReports />
    </QueryClientProvider>,
  )
}

describe('AdminReports', () => {
  afterEach(cleanup)

  it('renders report headings and totals', () => {
    renderReports()
    expect(screen.getByText('Reports & Analytics')).toBeInTheDocument()
    expect(screen.getByText('Appointments')).toBeInTheDocument()
    expect(screen.getAllByText('Adoptions').length).toBeGreaterThan(0)
    expect(screen.getByText('Top Pets by Appointments')).toBeInTheDocument()
  })

  it('shows top pets with counts', () => {
    renderReports()
    expect(screen.getAllByText('Cookie').length).toBeGreaterThan(0)
    expect(screen.getByText('3 appt')).toBeInTheDocument()
    expect(screen.getByText('2 apps')).toBeInTheDocument()
  })

  it('renders period selector buttons', () => {
    renderReports()
    expect(screen.getByText('3 mo')).toBeInTheDocument()
    expect(screen.getByText('6 mo')).toBeInTheDocument()
    expect(screen.getByText('12 mo')).toBeInTheDocument()
  })
})
