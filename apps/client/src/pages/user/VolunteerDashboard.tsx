import { useState } from 'react'
import { useMyVolunteer, useMyShifts, useCommunityActions } from '@repaw/api-client'
import { Loading } from '@repaw/ui'

export default function VolunteerDashboard() {
  const { data: volunteer, isLoading } = useMyVolunteer()
  const { data: shifts } = useMyShifts()
  const { logShiftHours } = useCommunityActions()
  const [hours, setHours] = useState<Record<number, string>>({})
  const [busy, setBusy] = useState<number | null>(null)

  if (isLoading) return <Loading />

  if (!volunteer) {
    return (
      <div className="text-center py-12">
        <h1 className="font-serif text-3xl font-bold text-repaw-dark">Volunteer Dashboard</h1>
        <p className="mt-3 text-repaw-text/80">You haven't applied to volunteer yet.</p>
      </div>
    )
  }

  async function handleLogHours(shiftId: number) {
    const value = Number(hours[shiftId])
    if (!Number.isFinite(value) || value < 0) return
    setBusy(shiftId)
    try {
      await logShiftHours.mutateAsync({ id: shiftId, hours: value })
    } finally {
      setBusy(null)
    }
  }

  return (
    <div className="space-y-6">
      <div className="flex items-center gap-3">
        <span className="mui-icon text-3xl text-repaw-dark">volunteer_activism</span>
        <div>
          <h1 className="font-serif text-3xl font-bold text-repaw-dark">Volunteer Dashboard</h1>
          <p className="text-repaw-text/80 text-sm">
            Status: <span className="font-medium uppercase">{volunteer.status}</span> · Total hours: <span className="font-medium">{volunteer.total_hours}</span>
          </p>
        </div>
      </div>

      {volunteer.status === 'pending' && (
        <div className="rounded-xl border border-repaw-accent/50 bg-amber-50 px-4 py-3 text-sm text-amber-800">
          Your volunteer application is pending review. Once approved, you'll be able to log hours for assigned shifts.
        </div>
      )}

      <div className="bg-white/70 rounded-3xl p-8 border border-repaw-hover/40 shadow-sm">
        <h2 className="font-serif text-2xl font-bold text-repaw-dark mb-4">My Shifts</h2>
        {!shifts || shifts.length === 0 ? (
          <p className="text-repaw-text/70">No shifts assigned yet. Check back soon!</p>
        ) : (
          <div className="overflow-x-auto rounded-2xl border border-repaw-hover/40">
            <table className="w-full text-left text-sm">
              <thead className="bg-repaw-bg/70 text-repaw-dark">
                <tr>
                  <th className="px-3 py-3 font-semibold">Date</th>
                  <th className="px-3 py-3 font-semibold">Time</th>
                  <th className="px-3 py-3 font-semibold">Activity</th>
                  <th className="px-3 py-3 font-semibold">Hours Logged</th>
                  <th className="px-3 py-3 font-semibold">Log Hours</th>
                </tr>
              </thead>
              <tbody className="divide-y divide-repaw-hover/40">
                {shifts.map((shift) => (
                  <tr key={shift.id} className="hover:bg-repaw-bg/40">
                    <td className="px-3 py-3">{new Date(shift.date + 'T00:00:00').toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' })}</td>
                    <td className="px-3 py-3">{shift.time_slot}</td>
                    <td className="px-3 py-3">{shift.activity ?? '—'}</td>
                    <td className="px-3 py-3">{shift.hours_logged} hrs</td>
                    <td className="px-3 py-3">
                      <div className="flex gap-2 items-center">
                        <input
                          type="number"
                          min="0"
                          max="24"
                          value={hours[shift.id] ?? ''}
                          onChange={(e) => setHours((h) => ({ ...h, [shift.id]: e.target.value }))}
                          placeholder="hrs"
                          className="w-20 rounded-lg border border-repaw-hover bg-repaw-bg px-2 py-1 text-xs text-repaw-text focus:outline-none focus:ring-2 focus:ring-repaw-text"
                        />
                        <button
                          onClick={() => void handleLogHours(shift.id)}
                          disabled={busy === shift.id}
                          className="bg-repaw-text text-repaw-bg rounded-full px-3 py-1 text-xs font-medium uppercase tracking-wide hover:bg-repaw-dark transition-colors disabled:opacity-60"
                        >
                          {busy === shift.id ? '...' : 'Save'}
                        </button>
                      </div>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        )}
      </div>
    </div>
  )
}
