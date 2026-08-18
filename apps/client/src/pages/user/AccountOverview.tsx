import { Link } from 'react-router-dom'
import { useUserDashboard } from '../../hooks/useAccount'
import { Loading } from '@repaw/ui'
import { resolveMedia } from '@repaw/api-client'

export default function AccountOverview() {
  const { data, isLoading } = useUserDashboard()

  if (isLoading) return <Loading />

  return (
    <div className="space-y-8">
      <div>
        <h1 className="font-serif text-3xl font-bold text-repaw-dark">
          Welcome back, {data?.user.fname ?? ''}!
        </h1>
        <p className="text-repaw-text/80 mt-1">Here's what's happening with your adoption journey.</p>
      </div>

      <div className="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div className="bg-white/70 rounded-3xl p-6 border border-repaw-hover/40 shadow-sm">
          <p className="text-sm text-repaw-text/70">Appointments</p>
          <p className="font-serif text-3xl font-bold text-repaw-dark mt-1">{data?.stats.appointments ?? 0}</p>
        </div>
        <div className="bg-white/70 rounded-3xl p-6 border border-repaw-hover/40 shadow-sm">
          <p className="text-sm text-repaw-text/70">Applications</p>
          <p className="font-serif text-3xl font-bold text-repaw-dark mt-1">{data?.stats.applications ?? 0}</p>
        </div>
        <div className="bg-white/70 rounded-3xl p-6 border border-repaw-hover/40 shadow-sm">
          <p className="text-sm text-repaw-text/70">Saved Pets</p>
          <p className="font-serif text-3xl font-bold text-repaw-dark mt-1">{data?.stats.favorites ?? 0}</p>
        </div>
      </div>

      <div className="bg-white/70 rounded-3xl p-8 border border-repaw-hover/40 shadow-sm">
        <div className="flex items-center justify-between mb-4">
          <h2 className="font-serif text-2xl font-bold text-repaw-dark">Upcoming Appointments</h2>
          <Link to="/account/appointments" className="text-sm text-repaw-dark font-medium hover:text-repaw-text">View all</Link>
        </div>
        {data?.upcoming_appointments && data.upcoming_appointments.length > 0 ? (
          <div className="space-y-3">
            {data.upcoming_appointments.slice(0, 3).map((appt) => (
              <div key={appt.id} className="flex items-center gap-4 flex-wrap bg-repaw-bg/50 rounded-2xl p-4">
                <div>
                  <p className="font-semibold text-repaw-dark">
                    {new Date(appt.appointment_date + 'T00:00:00').toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}
                  </p>
                  <p className="text-sm text-repaw-text/80">{appt.time_slot} · {appt.appointment_type}</p>
                </div>
                <span className={`ml-auto rounded-full px-3 py-1 text-xs font-medium uppercase ${appt.status === 'Accepted' ? 'bg-green-100 text-green-800' : appt.status === 'Cancelled' ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-800'}`}>
                  {appt.status}
                </span>
              </div>
            ))}
          </div>
        ) : (
          <p className="text-repaw-text/70">No upcoming appointments. <Link to="/book" className="text-repaw-dark underline underline-offset-2 hover:text-repaw-text">Book one now</Link>.</p>
        )}
      </div>

      <div className="bg-white/70 rounded-3xl p-8 border border-repaw-hover/40 shadow-sm">
        <div className="flex items-center justify-between mb-4">
          <h2 className="font-serif text-2xl font-bold text-repaw-dark">Active Applications</h2>
          <Link to="/account/applications" className="text-sm text-repaw-dark font-medium hover:text-repaw-text">View all</Link>
        </div>
        {data?.active_applications && data.active_applications.length > 0 ? (
          <div className="space-y-3">
            {data.active_applications.map((app) => (
              <div key={app.id} className="flex items-center gap-4 bg-repaw-bg/50 rounded-2xl p-4">
                <img src={resolveMedia(app.pet.thumb_url)} alt="" className="h-14 w-14 rounded-xl object-cover" />
                <div>
                  <p className="font-semibold text-repaw-dark">Adoption application for {app.pet.name}</p>
                  <p className="text-sm text-repaw-text/80 capitalize">{app.status.replace('_', ' ')}</p>
                </div>
              </div>
            ))}
          </div>
        ) : (
          <p className="text-repaw-text/70">No active applications. <Link to="/adopt" className="text-repaw-dark underline underline-offset-2 hover:text-repaw-text">Find a pet to apply for</Link>.</p>
        )}
      </div>
    </div>
  )
}
