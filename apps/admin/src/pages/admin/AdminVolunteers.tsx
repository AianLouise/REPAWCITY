import { useState } from 'react'
import { useAdminVolunteers, useCommunityActions } from '@repaw/api-client'
import { Loading } from '@repaw/ui'
import type { Volunteer } from '@repaw/api-client'
import { MdGroup } from 'react-icons/md'

export default function AdminVolunteers() {
  const { data, isLoading } = useAdminVolunteers()
  const { updateVolunteerStatus, assignShift } = useCommunityActions()
  const [busy, setBusy] = useState<number | null>(null)
  const [shiftFor, setShiftFor] = useState<Volunteer | null>(null)
  const [shiftDate, setShiftDate] = useState('')
  const [shiftSlot, setShiftSlot] = useState('Morning Session')
  const [shiftActivity, setShiftActivity] = useState('')
  const [error, setError] = useState<string | null>(null)

  if (isLoading) return <Loading />

  const volunteers = data ?? []

  async function handleStatus(v: Volunteer, status: string) {
    setBusy(v.id)
    try {
      await updateVolunteerStatus.mutateAsync({ id: v.id, status })
    } finally {
      setBusy(null)
    }
  }

  async function handleAssignShift() {
    if (!shiftFor) return
    if (!shiftDate) {
      setError('Please pick a date for the shift.')
      return
    }
    setError(null)
    setBusy(shiftFor.id)
    try {
      await assignShift.mutateAsync({
        volunteerId: shiftFor.id,
        data: { date: shiftDate, time_slot: shiftSlot, activity: shiftActivity || undefined },
      })
      setShiftFor(null)
      setShiftDate('')
      setShiftActivity('')
    } catch {
      setError('Failed to assign shift.')
    } finally {
      setBusy(null)
    }
  }

  return (
    <div className="space-y-6">
      <div className="flex items-center gap-3">
        <MdGroup size={30} className="text-repaw-dark" />
        <h1 className="font-serif text-3xl font-bold text-repaw-dark">Volunteers</h1>
      </div>

      {error && (
        <div className="rounded-xl border border-repaw-danger/40 bg-red-50 px-4 py-3 text-sm text-repaw-danger">{error}</div>
      )}

      <div className="bg-white/70 rounded-3xl p-8 border border-repaw-hover/40 shadow-sm">
        <div className="overflow-x-auto rounded-2xl border border-repaw-hover/40 max-h-[500px] overflow-y-auto">
          <table className="w-full text-left text-sm">
            <thead className="bg-repaw-bg/70 text-repaw-dark sticky top-0">
              <tr>
                <th className="px-3 py-3 font-semibold">Volunteer</th>
                <th className="px-3 py-3 font-semibold">Email</th>
                <th className="px-3 py-3 font-semibold">Availability</th>
                <th className="px-3 py-3 font-semibold">Skills</th>
                <th className="px-3 py-3 font-semibold">Hours</th>
                <th className="px-3 py-3 font-semibold">Status</th>
                <th className="px-3 py-3 font-semibold">Actions</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-repaw-hover/40">
              {volunteers.length > 0 ? (
                volunteers.map((v) => (
                  <tr key={v.id} className="hover:bg-repaw-bg/40">
                    <td className="px-3 py-3 font-medium text-repaw-dark">
                      {v.user?.fname} {v.user?.lname}
                    </td>
                    <td className="px-3 py-3">{v.user?.email}</td>
                    <td className="px-3 py-3 text-xs">{(v.availability ?? []).join(', ') || '—'}</td>
                    <td className="px-3 py-3 max-w-[180px] truncate">{v.skills ?? '—'}</td>
                    <td className="px-3 py-3">{v.total_hours}</td>
                    <td className="px-3 py-3">
                      <span className={`rounded-full px-2.5 py-0.5 text-xs font-medium uppercase ${
                        v.status === 'active' ? 'bg-green-100 text-green-800'
                        : v.status === 'pending' ? 'bg-amber-100 text-amber-800'
                        : 'bg-repaw-hover text-repaw-text'
                      }`}>
                        {v.status}
                      </span>
                    </td>
                    <td className="px-3 py-3">
                      <div className="flex flex-wrap gap-1.5">
                        {v.status === 'pending' && (
                          <button onClick={() => void handleStatus(v, 'active')} disabled={busy === v.id} className="bg-green-100 text-green-800 rounded-full px-3 py-1 text-xs font-medium uppercase tracking-wide hover:bg-green-200 transition-colors disabled:opacity-60">
                            Approve
                          </button>
                        )}
                        {v.status === 'active' && (
                          <button onClick={() => void handleStatus(v, 'inactive')} disabled={busy === v.id} className="bg-repaw-hover text-repaw-text rounded-full px-3 py-1 text-xs font-medium uppercase tracking-wide hover:bg-repaw-hover/70 transition-colors disabled:opacity-60">
                            Deactivate
                          </button>
                        )}
                        <button onClick={() => setShiftFor(v)} className="bg-repaw-text text-repaw-bg rounded-full px-3 py-1 text-xs font-medium uppercase tracking-wide hover:bg-repaw-dark transition-colors">
                          Assign Shift
                        </button>
                      </div>
                    </td>
                  </tr>
                ))
              ) : (
                <tr>
                  <td colSpan={7} className="px-3 py-6 text-center text-repaw-text/70">No volunteer applications yet.</td>
                </tr>
              )}
            </tbody>
          </table>
        </div>
      </div>

      {shiftFor && (
        <div className="fixed inset-0 z-50 flex items-center justify-center bg-repaw-dark/40 p-4" onClick={() => setShiftFor(null)}>
          <div className="bg-white rounded-3xl p-8 border border-repaw-hover/40 shadow-xl w-full max-w-md" onClick={(e) => e.stopPropagation()}>
            <h2 className="font-serif text-2xl font-bold text-repaw-dark mb-1">Assign Shift</h2>
            <p className="text-sm text-repaw-text/80 mb-6">
              {shiftFor.user?.fname} {shiftFor.user?.lname}
            </p>

            <div className="space-y-4">
              <div>
                <label className="block text-sm font-medium text-repaw-dark mb-1.5">Date</label>
                <input
                  type="date"
                  value={shiftDate}
                  onChange={(e) => setShiftDate(e.target.value)}
                  className="w-full rounded-xl border border-repaw-hover bg-repaw-bg px-4 py-2.5 text-repaw-text focus:outline-none focus:ring-2 focus:ring-repaw-text"
                />
              </div>
              <div>
                <label className="block text-sm font-medium text-repaw-dark mb-1.5">Time Slot</label>
                <select
                  value={shiftSlot}
                  onChange={(e) => setShiftSlot(e.target.value)}
                  className="w-full rounded-xl border border-repaw-hover bg-repaw-bg px-4 py-2.5 text-repaw-text focus:outline-none focus:ring-2 focus:ring-repaw-text"
                >
                  <option value="Morning Session">Morning Session</option>
                  <option value="Afternoon Session">Afternoon Session</option>
                </select>
              </div>
              <div>
                <label className="block text-sm font-medium text-repaw-dark mb-1.5">Activity</label>
                <input
                  type="text"
                  value={shiftActivity}
                  onChange={(e) => setShiftActivity(e.target.value)}
                  placeholder="e.g. Kennel cleaning"
                  className="w-full rounded-xl border border-repaw-hover bg-repaw-bg px-4 py-2.5 text-repaw-text focus:outline-none focus:ring-2 focus:ring-repaw-text"
                />
              </div>
            </div>

            <div className="flex justify-end gap-3 mt-8">
              <button onClick={() => setShiftFor(null)} className="bg-repaw-hover text-repaw-dark rounded-full px-6 py-2.5 text-sm font-medium uppercase tracking-wide hover:bg-repaw-hover/70 transition-colors">
                Cancel
              </button>
              <button onClick={() => void handleAssignShift()} disabled={busy === shiftFor.id} className="bg-repaw-text text-repaw-bg rounded-full px-6 py-2.5 text-sm font-medium uppercase tracking-wide hover:bg-repaw-dark transition-colors disabled:opacity-60">
                {busy === shiftFor.id ? '...' : 'Assign'}
              </button>
            </div>
          </div>
        </div>
      )}
    </div>
  )
}
