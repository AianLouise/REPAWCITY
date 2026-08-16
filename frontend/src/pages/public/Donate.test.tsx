import { describe, it, expect, vi, afterEach } from 'vitest'
import { render, screen, cleanup, fireEvent, waitFor } from '@testing-library/react'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import { MemoryRouter } from 'react-router-dom'
import Donate from './Donate'

const storeDonation = vi.fn().mockResolvedValue(undefined)

vi.mock('../../hooks/useCommunity', () => ({
  useCommunityActions: () => ({ storeDonation: { mutateAsync: storeDonation } }),
}))

function renderDonate() {
  const queryClient = new QueryClient({
    defaultOptions: { queries: { retry: false } },
  })
  return render(
    <QueryClientProvider client={queryClient}>
      <MemoryRouter>
        <Donate />
      </MemoryRouter>
    </QueryClientProvider>,
  )
}

describe('Donate', () => {
  afterEach(() => {
    cleanup()
  })

  it('renders the donation form', () => {
    renderDonate()
    expect(screen.getByText('Record a Donation')).toBeInTheDocument()
    expect(screen.getByText('In-kind (items)')).toBeInTheDocument()
  })

  it('requires name and email', () => {
    renderDonate()
    fireEvent.click(screen.getByText('Submit Donation'))
    expect(storeDonation).not.toHaveBeenCalled()
    expect(screen.getByText('Please provide your name and email.')).toBeInTheDocument()
  })

  it('records a cash donation', async () => {
    renderDonate()

    fireEvent.change(screen.getByLabelText('Your Name'), { target: { value: 'Jane Doe' } })
    fireEvent.change(screen.getByLabelText('Your Email'), { target: { value: 'jane@test.com' } })
    fireEvent.change(screen.getByLabelText('Amount (PHP)'), { target: { value: '250' } })

    fireEvent.click(screen.getByText('Submit Donation'))

    expect(storeDonation).toHaveBeenCalledWith({
      donor_name: 'Jane Doe',
      donor_email: 'jane@test.com',
      type: 'cash',
      amount: 250,
      item_description: null,
      date: expect.any(String),
      notes: null,
    })

    await waitFor(() =>
      expect(screen.getByText('Thank you for your generous donation! Your support makes a real difference.')).toBeInTheDocument(),
    )
  })
})
