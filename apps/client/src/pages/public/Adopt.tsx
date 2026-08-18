import { useMemo, useState } from 'react'
import { Link, useSearchParams } from 'react-router-dom'
import { useAuthStore } from '@repaw/auth'
import PetCard from '../../components/PetCard'
import Pagination from '../../components/Pagination'
import { Loading, PageHero } from '@repaw/ui'
import { usePets } from '../../hooks/useContent'
import type { PetFilters } from '@repaw/api-client'

export default function Adopt() {
  const user = useAuthStore((s) => s.user)
  const [params] = useSearchParams()
  const [page, setPage] = useState(1)
  const [filters, setFilters] = useState<PetFilters>(() => ({
    type: params.get('type') ?? '',
    sex: '',
    weight: '',
    age: '',
  }))

  const query = useMemo(() => ({ ...filters, page }), [filters, page])
  const { data, isLoading } = usePets(query)

  const setFilter = (key: keyof PetFilters, value: string) => {
    setPage(1)
    setFilters((f) => ({ ...f, [key]: value }))
  }

  return (
    <div>
      <PageHero
        title="Find your new best friend"
        subtitle="All of our cats and dogs can be seen by appointment only."
      />

      <section className="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 pt-16 lg:pt-20 pb-20">
        <div className="text-center mb-10">
          <h2 className="font-serif text-3xl sm:text-4xl font-bold text-repaw-dark">Meet Our Pets</h2>
          <p className="mt-3 text-repaw-text/80">Sort and filter to find the perfect match.</p>
        </div>

        <div className="bg-white/70 border border-repaw-hover/40 rounded-3xl p-5 sm:p-6 lg:p-8 shadow-sm mb-12">
          <div className="flex items-center gap-2 mb-4 text-repaw-dark">
            <span className="mui-icon">tune</span>
            <span className="font-medium">Sort by:</span>
          </div>
          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <FilterSelect
              label="Pet Type"
              value={filters.type ?? ''}
              onChange={(v) => setFilter('type', v)}
              options={[
                { value: 'Dog', label: 'Dog' },
                { value: 'Cat', label: 'Cat' },
              ]}
              placeholder="Select Type"
            />
            <FilterSelect
              label="Sex"
              value={filters.sex ?? ''}
              onChange={(v) => setFilter('sex', v)}
              options={[
                { value: 'Male', label: 'Male' },
                { value: 'Female', label: 'Female' },
              ]}
              placeholder="Select Sex"
            />
            <FilterSelect
              label="Weight"
              value={filters.weight ?? ''}
              onChange={(v) => setFilter('weight', v)}
              options={['Less than 5 lbs', '5-10 lbs', '10-20 lbs', '20-50 lbs', 'over 50 lbs'].map((v) => ({ value: v, label: v }))}
              placeholder="Select Weight"
            />
            <FilterSelect
              label="Age"
              value={filters.age ?? ''}
              onChange={(v) => setFilter('age', v)}
              options={['Less than 6 months', '6 months to 5 years', '5 to 10 years', 'over 10 years'].map((v) => ({ value: v, label: v }))}
              placeholder="Select Age"
            />
          </div>
        </div>

        {user ? (
          <div className="text-center mb-12">
            <Link
              to="/book"
              className="inline-flex items-center justify-center gap-2 rounded-full bg-repaw-text text-repaw-bg px-7 py-3 text-[15px] font-medium uppercase tracking-wide hover:bg-repaw-dark transition-colors duration-300"
            >
              <span className="mui-icon text-[20px]">event_available</span> Book Appointment
            </Link>
          </div>
        ) : null}

        {isLoading ? (
          <Loading />
        ) : data && data.data.length > 0 ? (
          <>
            <p className="mb-5 text-sm text-repaw-text/70">
              Showing <span className="font-medium text-repaw-dark">{data.meta.total}</span> pet{data.meta.total !== 1 ? 's' : ''} ready to meet you.
            </p>
            <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
              {data.data.map((pet) => (
                <PetCard key={pet.id} pet={pet} />
              ))}
            </div>
            <Pagination page={page} lastPage={data.meta.last_page} onChange={setPage} />
          </>
        ) : (
          <div className="text-center bg-white/70 rounded-3xl p-12 border border-repaw-hover/40 shadow-sm">
            <span className="mui-icon text-5xl text-repaw-text/40 block mb-4">pets</span>
            <h3 className="font-serif text-2xl font-bold text-repaw-dark">No pets found</h3>
            <p className="mt-2 text-repaw-text/80">Try adjusting your filters, or check back soon — new rescues arrive regularly.</p>
            <button
              onClick={() => setFilters({ type: '', sex: '', weight: '', age: '' })}
              className="mt-6 inline-flex items-center gap-2 bg-repaw-text text-repaw-bg rounded-full px-6 py-2.5 text-sm font-medium uppercase tracking-wide hover:bg-repaw-dark transition-colors"
            >
              <span className="mui-icon">restart_alt</span> Clear Filters
            </button>
          </div>
        )}
      </section>
    </div>
  )
}

function FilterSelect({
  label,
  value,
  onChange,
  options,
  placeholder,
}: {
  label: string
  value: string
  onChange: (value: string) => void
  options: { value: string; label: string }[]
  placeholder: string
}) {
  return (
    <div>
      <label className="block text-sm font-medium text-repaw-dark mb-1.5">{label}</label>
      <select
        value={value}
        onChange={(e) => onChange(e.target.value)}
        className="w-full rounded-xl border border-repaw-hover bg-repaw-bg px-4 py-2.5 text-repaw-text focus:outline-none focus:ring-2 focus:ring-repaw-text"
      >
        <option value="">{placeholder}</option>
        {options.map((o) => (
          <option key={o.value} value={o.value}>
            {o.label}
          </option>
        ))}
      </select>
    </div>
  )
}
