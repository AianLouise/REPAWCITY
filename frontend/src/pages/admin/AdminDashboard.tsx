import { useState } from 'react'
import { useDashboard, useDailyAppointments, useAdminActions } from '../../hooks/useAdmin'
import { Loading } from '../../components/Shared'
import { format } from 'date-fns'
import type { TimeSlot } from '../../types'

export default function AdminDashboard() {
  const [date, setDate] = useState(() => format(new Date(), 'yyyy-MM-dd'))
  const { data, isLoading } = useDashboard(date)
  const actions = useAdminActions()

  return (
    <div className="space-y-8">
      <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <StatCard icon="event_available" value={data?.counts.total} label="Total Appointments" />
        <StatCard icon="pets" value={data?.counts.adopt} label="Adopt" />
        <StatCard icon="volunteer_activism" value={data?.counts.donate} label="Donate" />
        <StatCard icon="visibility" value={data?.counts.visit} label="Visit" />
        <StatCard icon="groups" value={data?.counts.volunteer} label="Volunteer" />
      </div>

      <div className="bg-white/70 rounded-3xl p-6 border border-repaw-hover/40 shadow-sm">
        <div className="flex items-end gap-4">
          <div className="flex-1">
            <label className="block text-sm font-medium text-repaw-dark mb-1.5">Select Date:</label>
            <input
              type="date"
              value={date}
              onChange={(e) => setDate(e.target.value)}
              className="w-full rounded-xl border border-repaw-hover bg-repaw-bg px-4 py-2.5 text-repaw-text focus:outline-none focus:ring-2 focus:ring-repaw-text"
            />
          </div>
        </div>
        <div className="text-center mt-4">
          <div className="font-serif text-2xl font-bold text-repaw-dark">
            {format(new Date(date + 'T00:00:00'), 'MMMM d, yyyy')}
          </div>
        </div>
      </div>

      {isLoading ? (
        <Loading />
      ) : (
        <>
          <SessionTable
            title="Morning Session"
            date={date}
            timeSlot="Morning Session"
            actions={actions}
          />
          <SessionTable
            title="Afternoon Session"
            date={date}
            timeSlot="Afternoon Session"
            actions={actions}
          />
        </>
      )}
    </div>
  )
}

function StatCard({ icon, value, label }: { icon: string; value?: number; label: string }) {
  return (
    <div className="bg-white/70 rounded-3xl p-6 border border-repaw-hover/40 shadow-sm flex items-center gap-4">
      <span className="mui-icon text-4xl text-repaw-dark">{icon}</span>
      <div>
        <div className="font-serif text-3xl font-bold text-repaw-dark">{value ?? '—'}</div>
        <div className="text-sm text-repaw-text/80">{label}</div>
      </div>
    </div>
  )
}

function SessionTable({
  title,
  date,
  timeSlot,
  actions,
}: {
  title: string
  date: string
  timeSlot: TimeSlot
  actions: ReturnType<typeof useAdminActions>
}) {
  const { data: rows, isLoading } = useDailyAppointments(date, timeSlot)

  return (
    <div className="bg-white/70 rounded-3xl p-6 border border-repaw-hover/40 shadow-sm">
      <div className="font-serif text-xl font-semibold text-repaw-dark mb-4">{title}</div>
      <div className="overflow-x-auto rounded-2xl border border-repaw-hover/40">
        <table className="w-full text-left text-sm">
          <thead className="bg-repaw-bg/70 text-repaw-dark">
            <tr>
              <th className="px-4 py-3 font-semibold">Type</th>
              <th className="px-4 py-3 font-semibold">Name</th>
              <th className="px-4 py-3 font-semibold">Mobile #</th>
              <th className="px-4 py-3 font-semibold">Address</th>
              <th className="px-4 py-3 font-semibold">Email</th>
              <th className="px-4 py-3 font-semibold">Status</th>
            </tr>
          </thead>
          <tbody className="divide-y divide-repaw-hover/40">
            {isLoading ? (
              <tr>
                <td colSpan={6} className="px-4 py-6 text-center text-repaw-text/70">
                  Loading...
                </td>
              </tr>
            ) : rows && rows.length > 0 ? (
              rows.map((row) => {
                const fullName = [row.first_name, row.middle_name, row.last_name].filter(Boolean).join(' ')
                return (
                  <tr key={row.id}>
                    <td className="px-4 py-3">{row.appointment_type}</td>
                    <td className="px-4 py-3 font-medium text-repaw-dark">{fullName}</td>
                    <td className="px-4 py-3">{row.mobile_number}</td>
                    <td className="px-4 py-3">{row.home_address}</td>
                    <td className="px-4 py-3">{row.email_address}</td>
                    <td className="px-4 py-3">
                      {row.status === 'Pending' ? (
                        <>
                          <button
                            onClick={() => actions.updateStatus.mutate({ id: row.id, status: 'Accepted' })}
                            disabled={actions.updateStatus.isPending}
                            className="inline-flex items-center gap-1 bg-repaw-text text-repaw-bg rounded-full px-4 py-1.5 text-xs font-medium uppercase tracking-wide hover:bg-repaw-dark transition-colors mr-2"
                          >
                            <span className="mui-icon text-[16px]">check_circle</span>Accept
                          </button>
                          <button
                            onClick={() => actions.updateStatus.mutate({ id: row.id, status: 'Cancelled' })}
                            disabled={actions.updateStatus.isPending}
                            className="inline-flex items-center gap-1 bg-repaw-danger text-white rounded-full px-4 py-1.5 text-xs font-medium uppercase tracking-wide hover:opacity-90 transition-opacity"
                          >
                            <span className="mui-icon text-[16px]">cancel</span>Cancel
                          </button>
                        </>
                      ) : (
                        row.status
                      )}
                    </td>
                  </tr>
                )
              })
            ) : (
              <tr>
                <td colSpan={6} className="px-4 py-6 text-center text-repaw-text/70">
                  No appointments available
                </td>
              </tr>
            )}
          </tbody>
        </table>
      </div>
    </div>
  )
}
