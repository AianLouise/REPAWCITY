import { useState } from 'react'
import { useMyAppointments, useAppointmentMessage } from '../../hooks/useUser'
import { Loading, PageHero, Empty } from '../../components/Shared'
import { format } from 'date-fns'

export default function Notifications() {
  const { data: appointments, isLoading } = useMyAppointments()
  const [selectedId, setSelectedId] = useState<number | null>(null)

  const selected = appointments?.find((a) => a.id === selectedId)

  return (
    <div>
      <PageHero title="Appointment Notifications" subtitle="View the status and messages for your bookings." />
      <section className="max-w-4xl mx-auto px-6 sm:px-8 lg:px-12 pt-16 lg:pt-20 pb-20">
        {isLoading ? (
          <Loading />
        ) : appointments && appointments.length > 0 ? (
          <div className="space-y-6">
            <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
              {appointments.map((appt) => (
                <button
                  key={appt.id}
                  onClick={() => setSelectedId(appt.id)}
                  className={`text-left bg-white/70 rounded-2xl p-5 border shadow-sm hover:shadow-md transition-all duration-300 ${
                    selectedId === appt.id ? 'border-repaw-text ring-2 ring-repaw-text' : 'border-repaw-hover/40'
                  }`}
                >
                  <div className="flex items-center justify-between gap-2">
                    <h3 className="font-serif text-lg font-semibold text-repaw-dark">
                      {appt.appointment_type} Appointment
                    </h3>
                    <StatusBadge status={appt.status} />
                  </div>
                  <p className="mt-2 text-sm text-repaw-text/80">
                    {format(new Date(appt.appointment_date + 'T00:00:00'), 'MMMM dd, yyyy')} · {appt.time_slot}
                  </p>
                </button>
              ))}
            </div>

            {selected && <MessageCard appointmentId={selected.id} />}
          </div>
        ) : (
          <Empty message="You have no appointments yet. Book an appointment to get started!" />
        )}
      </section>
    </div>
  )
}

function MessageCard({ appointmentId }: { appointmentId: number }) {
  const { data, isLoading } = useAppointmentMessage(appointmentId)

  return (
    <div className="bg-white/70 rounded-3xl p-8 border border-repaw-hover/40 shadow-sm">
      <h2 className="font-serif text-2xl font-bold text-repaw-dark mb-4">Message</h2>
      {isLoading ? (
        <Loading />
      ) : (
        <div className="whitespace-pre-line text-repaw-text/90 leading-relaxed">{data?.message ?? 'No message found for this appointment.'}</div>
      )}
    </div>
  )
}

function StatusBadge({ status }: { status: string }) {
  const styles: Record<string, string> = {
    Pending: 'bg-repaw-accent text-repaw-dark',
    Accepted: 'bg-green-600 text-white',
    Cancelled: 'bg-repaw-danger text-white',
  }
  return (
    <span className={`inline-block rounded-full px-3 py-1 text-xs font-medium ${styles[status] ?? 'bg-repaw-hover/60 text-repaw-dark'}`}>
      {status}
    </span>
  )
}
