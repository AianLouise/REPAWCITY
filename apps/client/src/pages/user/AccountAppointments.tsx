import { useQuery } from '@tanstack/react-query'
import { appointmentsApi } from '../../api/appointments'
import { Loading } from '@repaw/ui'
import { resolveMedia } from '@repaw/api-client'

export default function AccountAppointments() {
  const { data, isLoading } = useQuery({
    queryKey: ['my-appointments'],
    queryFn: appointmentsApi.my,
  })

  if (isLoading) return <Loading />

  return (
    <div className="space-y-6">
      <div className="flex items-center gap-3">
        <span className="mui-icon text-3xl text-repaw-dark">event</span>
        <h1 className="font-serif text-3xl font-bold text-repaw-dark">My Appointments</h1>
      </div>

      {!data || data.length === 0 ? (
        <div className="bg-white/70 rounded-3xl p-8 border border-repaw-hover/40 shadow-sm text-center">
          <p className="text-repaw-text/80">You have no appointments yet.</p>
        </div>
      ) : (
        <div className="space-y-4">
          {data.map((appt) => (
            <div key={appt.id} className="bg-white/70 rounded-3xl p-6 border border-repaw-hover/40 shadow-sm flex items-center gap-5 flex-wrap">
              <div className="min-w-[160px]">
                <p className="font-serif text-xl font-bold text-repaw-dark">
                  {new Date(appt.appointment_date + 'T00:00:00').toLocaleDateString('en-US', { weekday: 'short', month: 'short', day: 'numeric', year: 'numeric' })}
                </p>
                <p className="text-sm text-repaw-text/80">{appt.time_slot}</p>
              </div>
              <div className="flex-1 min-w-[140px]">
                <p className="font-semibold text-repaw-dark">{appt.appointment_type}</p>
                {appt.pet && (
                  <p className="text-sm text-repaw-text/80 inline-flex items-center gap-2">
                    <img src={resolveMedia(appt.pet.image_url)} alt="" className="h-6 w-6 rounded object-cover" />
                    {appt.pet.name}
                  </p>
                )}
              </div>
              <span className={`rounded-full px-3 py-1 text-xs font-medium uppercase ${
                appt.status === 'Accepted' ? 'bg-green-100 text-green-800'
                : appt.status === 'Cancelled' ? 'bg-red-100 text-red-700'
                : 'bg-amber-100 text-amber-800'
              }`}>
                {appt.status}
              </span>
            </div>
          ))}
        </div>
      )}
    </div>
  )
}
