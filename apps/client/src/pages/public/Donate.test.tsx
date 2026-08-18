import { describe, it, expect, afterEach } from 'vitest'
import { render, screen, cleanup } from '@testing-library/react'
import { MemoryRouter } from 'react-router-dom'
import Donate from './Donate'

function renderDonate() {
  return render(
    <MemoryRouter>
      <Donate />
    </MemoryRouter>,
  )
}

describe('Donate', () => {
  afterEach(() => {
    cleanup()
  })

  it('renders the donation methods', () => {
    renderDonate()
    expect(screen.getByText('Bank Transfer')).toBeInTheDocument()
    expect(screen.getByText('GCash Transfer')).toBeInTheDocument()
    expect(screen.getByText('Cash')).toBeInTheDocument()
  })

  it('no longer shows the record-a-donation form', () => {
    renderDonate()
    expect(screen.queryByText('Record a Donation')).not.toBeInTheDocument()
    expect(screen.queryByText('Submit Donation')).not.toBeInTheDocument()
  })
})
