import { useState } from 'react'
import { useAllPets, useAdminActions } from '../../hooks/useAdmin'
import { resolveMedia } from '../../api/client'
import PetForm from './PetForm'
import type { Pet } from '../../types'

export default function AdminManagePets() {
  const { data, isLoading } = useAllPets()
  const actions = useAdminActions()
  const [selected, setSelected] = useState<Pet | null>(null)

  async function handleDelete(pet: Pet) {
    if (!confirm(`Delete "${pet.name}"?`)) return
    await actions.deletePet.mutateAsync(pet.id)
    if (selected?.id === pet.id) setSelected(null)
  }

  return (
    <div className="space-y-8">
      <div className="bg-white/70 rounded-3xl p-8 border border-repaw-hover/40 shadow-sm">
        <div className="flex items-center gap-3 mb-6">
          <span className="mui-icon text-3xl text-repaw-dark">pets</span>
          <h1 className="font-serif text-3xl font-bold text-repaw-dark">Pets List</h1>
        </div>

        <div className="overflow-x-auto rounded-2xl border border-repaw-hover/40 max-h-[400px] overflow-y-auto">
          <table className="w-full text-left text-sm">
            <thead className="bg-repaw-bg/70 text-repaw-dark sticky top-0">
              <tr>
                <th className="px-3 py-3 font-semibold">ID</th>
                <th className="px-3 py-3 font-semibold">Image</th>
                <th className="px-3 py-3 font-semibold">Name</th>
                <th className="px-3 py-3 font-semibold">Type</th>
                <th className="px-3 py-3 font-semibold">Breed</th>
                <th className="px-3 py-3 font-semibold">Sex</th>
                <th className="px-3 py-3 font-semibold">Weight</th>
                <th className="px-3 py-3 font-semibold">Age</th>
                <th className="px-3 py-3 font-semibold">Date of Rescue</th>
                <th className="px-3 py-3 font-semibold">About</th>
                <th className="px-3 py-3 font-semibold">Actions</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-repaw-hover/40">
              {isLoading ? (
                <tr>
                  <td colSpan={11} className="px-3 py-6 text-center text-repaw-text/70">Loading...</td>
                </tr>
              ) : data && data.data.length > 0 ? (
                data.data.map((pet) => (
                  <tr key={pet.id} className="hover:bg-repaw-bg/40 cursor-pointer" onClick={() => setSelected(pet)}>
                    <td className="px-3 py-3">{pet.id}</td>
                    <td className="px-3 py-3">
                      <img src={resolveMedia(pet.image_url)} alt="" className="h-12 w-12 rounded-lg object-cover" />
                    </td>
                    <td className="px-3 py-3 font-medium text-repaw-dark">{pet.name}</td>
                    <td className="px-3 py-3">{pet.type}</td>
                    <td className="px-3 py-3">{pet.breed}</td>
                    <td className="px-3 py-3">{pet.sex}</td>
                    <td className="px-3 py-3">{pet.weight}</td>
                    <td className="px-3 py-3">{pet.age}</td>
                    <td className="px-3 py-3">{pet.date}</td>
                    <td className="px-3 py-3 max-w-xs truncate">{pet.about}</td>
                    <td className="px-3 py-3">
                      <button
                        onClick={(e) => {
                          e.stopPropagation()
                          void handleDelete(pet)
                        }}
                        className="inline-flex items-center gap-1 bg-repaw-danger text-white rounded-full px-3 py-1.5 text-xs font-medium uppercase tracking-wide hover:opacity-90 transition-opacity"
                      >
                        <span className="mui-icon text-[16px]">delete</span>Delete
                      </button>
                    </td>
                  </tr>
                ))
              ) : (
                <tr>
                  <td colSpan={11} className="px-3 py-6 text-center text-repaw-text/70">No data available</td>
                </tr>
              )}
            </tbody>
          </table>
        </div>
      </div>

      {selected && (
        <div className="max-w-3xl mx-auto">
          <PetForm key={selected.id} mode="edit" pet={selected} onDone={() => setSelected(null)} />
        </div>
      )}
    </div>
  )
}
