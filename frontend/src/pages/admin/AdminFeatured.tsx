import { useState } from 'react'
import { useAllPets, useAdminActions } from '../../hooks/useAdmin'
import { resolveMedia } from '../../api/client'

export default function AdminFeatured() {
  const { data, isLoading } = useAllPets()
  const actions = useAdminActions()
  const [slots, setSlots] = useState<[string, string, string, string]>(['', '', '', ''])
  const [notice, setNotice] = useState<string | null>(null)
  const [error, setError] = useState<string | null>(null)

  const inputCls =
    'w-full rounded-xl border border-repaw-hover bg-repaw-bg px-4 py-2.5 text-repaw-text focus:outline-none focus:ring-2 focus:ring-repaw-text'

  async function handleSubmit(e: React.FormEvent) {
    e.preventDefault()
    setNotice(null)
    setError(null)
    const ids = slots.map(Number)
    if (ids.some((n) => !Number.isInteger(n) || n <= 0)) {
      setError('Please enter valid pet IDs for all four featured slots.')
      return
    }
    try {
      await actions.setFeaturedPet.mutateAsync([ids[0], ids[1], ids[2], ids[3]])
      setNotice('Records updated successfully')
    } catch {
      setError('Failed to update featured pets. Please check the pet IDs.')
    }
  }

  return (
    <div className="max-w-5xl mx-auto space-y-8">
      <div className="bg-white/70 rounded-3xl p-8 border border-repaw-hover/40 shadow-sm">
        <div className="flex items-center gap-3 mb-6">
          <span className="mui-icon text-3xl text-repaw-dark">stars</span>
          <h1 className="font-serif text-3xl font-bold text-repaw-dark">Pets List</h1>
        </div>

        <div className="overflow-x-auto rounded-2xl border border-repaw-hover/40">
          <table className="w-full text-left text-sm">
            <thead className="bg-repaw-bg/70 text-repaw-dark">
              <tr>
                <th className="px-4 py-3 font-semibold">ID</th>
                <th className="px-4 py-3 font-semibold">Image</th>
                <th className="px-4 py-3 font-semibold">Name</th>
                <th className="px-4 py-3 font-semibold">Is Featured</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-repaw-hover/40">
              {isLoading ? (
                <tr>
                  <td colSpan={4} className="px-4 py-6 text-center text-repaw-text/70">Loading...</td>
                </tr>
              ) : data && data.data.length > 0 ? (
                data.data.map((pet) => (
                  <tr key={pet.id} className="hover:bg-repaw-bg/40">
                    <td className="px-4 py-3">{pet.id}</td>
                    <td className="px-4 py-3">
                      <img src={resolveMedia(pet.image_url)} alt="" className="h-12 w-12 rounded-lg object-cover" />
                    </td>
                    <td className="px-4 py-3 font-medium text-repaw-dark">{pet.name}</td>
                    <td className="px-4 py-3">{pet.is_featured}</td>
                  </tr>
                ))
              ) : (
                <tr>
                  <td colSpan={4} className="px-4 py-6 text-center text-repaw-text/70">No data available</td>
                </tr>
              )}
            </tbody>
          </table>
        </div>
      </div>

      <div className="bg-white/70 rounded-3xl p-8 border border-repaw-hover/40 shadow-sm">
        <h4 className="text-center font-serif text-xl font-semibold text-repaw-dark mt-2 mb-6">
          Select IDs to set as Featured Images
        </h4>

        {notice && <div className="mb-4 rounded-xl border border-green-400/40 bg-green-50 px-4 py-3 text-sm text-green-800">{notice}</div>}
        {error && <div className="mb-4 rounded-xl border border-repaw-danger/40 bg-red-50 px-4 py-3 text-sm text-repaw-danger">{error}</div>}

        <form onSubmit={handleSubmit} className="max-w-md mx-auto grid grid-cols-1 sm:grid-cols-2 gap-5">
          {[1, 2, 3, 4].map((slot) => (
            <div key={slot}>
              <label className="block text-sm font-medium text-repaw-dark mb-1.5">Featured Image {slot}:</label>
              <input
                type="number"
                min={1}
                value={slots[slot - 1]}
                onChange={(e) =>
                  setSlots((s) => {
                    const copy = [...s] as typeof s
                    copy[slot - 1] = e.target.value
                    return copy
                  })
                }
                className={inputCls}
              />
            </div>
          ))}
          <div className="sm:col-span-2">
            <button
              type="submit"
              disabled={actions.setFeaturedPet.isPending}
              className="inline-flex items-center gap-2 w-full justify-center bg-repaw-text text-repaw-bg rounded-full px-8 py-3 text-[15px] font-medium uppercase tracking-wide hover:bg-repaw-dark transition-colors duration-300 disabled:opacity-60"
            >
              <span className="mui-icon">stars</span> Set as Featured Images
            </button>
          </div>
        </form>
      </div>
    </div>
  )
}
