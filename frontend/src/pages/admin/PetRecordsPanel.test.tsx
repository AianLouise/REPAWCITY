import { describe, it, expect, vi, afterEach } from 'vitest'
import { render, screen, cleanup, fireEvent, waitFor } from '@testing-library/react'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import PetRecordsPanel from './PetRecordsPanel'

const storeFn = vi.fn().mockResolvedValue({ id: 1 })
const destroyFn = vi.fn().mockResolvedValue(undefined)

vi.mock('../../hooks/usePetRecords', () => ({
  useAdminPetRecords: () => ({
    data: [
      { id: 1, type: 'vaccination', title: 'Rabies shot', details: 'Annual booster', record_date: '2026-08-01', created_by: 'A Admin' },
    ],
    isLoading: false,
  }),
  usePetRecordActions: () => ({
    store: { mutateAsync: storeFn },
    destroy: { mutateAsync: destroyFn },
  }),
}))

function renderPanel() {
  const queryClient = new QueryClient({
    defaultOptions: { queries: { retry: false } },
  })
  return render(
    <QueryClientProvider client={queryClient}>
      <PetRecordsPanel petId={5} />
    </QueryClientProvider>,
  )
}

describe('PetRecordsPanel', () => {
  afterEach(() => {
    cleanup()
    storeFn.mockClear()
    destroyFn.mockClear()
  })

  it('lists existing care records', () => {
    renderPanel()
    expect(screen.getByText('Care Records')).toBeInTheDocument()
    expect(screen.getByText('Rabies shot')).toBeInTheDocument()
    expect(screen.getByText('Annual booster')).toBeInTheDocument()
    expect(screen.getByText(/by A Admin/)).toBeInTheDocument()
  })

  it('opens the add form and saves a new record', async () => {
    renderPanel()
    fireEvent.click(screen.getByText('+ Add Record'))

    fireEvent.change(screen.getByPlaceholderText('e.g. Rabies booster'), { target: { value: 'Deworming' } })
    fireEvent.change(screen.getByPlaceholderText('Details of the record...'), { target: { value: 'Given oral dewormer.' } })
    fireEvent.change(screen.getByLabelText('Date'), { target: { value: '2026-08-10' } })

    fireEvent.click(screen.getByText('Save'))

    await waitFor(() =>
      expect(storeFn).toHaveBeenCalledWith({
        type: 'vaccination',
        title: 'Deworming',
        details: 'Given oral dewormer.',
        record_date: '2026-08-10',
      }),
    )
  })

  it('validates required fields before saving', () => {
    renderPanel()
    fireEvent.click(screen.getByText('+ Add Record'))
    fireEvent.click(screen.getByText('Save'))
    expect(storeFn).not.toHaveBeenCalled()
    expect(screen.getByText('Please fill in title, details, and date.')).toBeInTheDocument()
  })
})
