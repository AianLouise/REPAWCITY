import { useState } from 'react'
import { useReports } from '../../hooks/useReports'
import { Loading } from '../../components/Shared'

function BarChart({ data, color }: { data: { label: string; value: number }[]; color: string }) {
  const max = Math.max(...data.map((d) => d.value), 1)
  return (
    <div className="flex items-end gap-1.5 h-40">
      {data.map((d) => {
        const h = Math.round((d.value / max) * 160) || 2
        return (
          <div key={d.label} className="flex-1 flex flex-col items-center justify-end gap-1 group" title={`${d.label}: ${d.value}`}>
            <span className="text-[10px] text-repaw-text/70 font-medium">{d.value || ''}</span>
            <div className="w-full rounded-t-md transition-colors" style={{ height: `${h}px`, backgroundColor: color }} />
            <span className="text-[9px] text-repaw-text/50 truncate w-full text-center">{d.label.split(' ')[0]}</span>
          </div>
        )
      })}
    </div>
  )
}

export default function AdminReports() {
  const [months, setMonths] = useState(12)
  const { data, isLoading } = useReports(months)

  if (isLoading) return <Loading />

  return (
    <div className="space-y-6">
      <div className="flex items-center justify-between flex-wrap gap-3">
        <div className="flex items-center gap-3">
          <span className="mui-icon text-3xl text-repaw-dark">insights</span>
          <h1 className="font-serif text-3xl font-bold text-repaw-dark">Reports & Analytics</h1>
        </div>
        <div className="flex gap-2">
          {[3, 6, 12].map((m) => (
            <button
              key={m}
              onClick={() => setMonths(m)}
              className={`rounded-full px-4 py-2 text-xs font-medium uppercase tracking-wide transition-colors ${months === m ? 'bg-repaw-text text-repaw-bg' : 'bg-repaw-hover/60 text-repaw-text hover:bg-repaw-hover'}`}
            >
              {m} mo
            </button>
          ))}
        </div>
      </div>

      <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        <StatCard label="Appointments" value={data?.totals.appointments ?? 0} icon="event" />
        <StatCard label="Applications" value={data?.totals.applications ?? 0} icon="how_to_reg" />
        <StatCard label="Adoptions" value={data?.totals.adoptions ?? 0} icon="favorite" />
        <StatCard label="Cash Donations" value={`₱ ${Number(data?.totals.donations_cash ?? 0).toLocaleString()}`} icon="volunteer_activism" />
        <StatCard label="Volunteer Hours" value={data?.totals.volunteer_hours ?? 0} icon="schedule" />
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <ChartCard title="Appointments per month">
          <BarChart
            color="#4a2c17"
            data={(data?.series ?? []).map((s) => ({ label: s.label, value: s.appointments }))}
          />
        </ChartCard>

        <ChartCard title="Adoption applications vs adoptions">
          <div className="space-y-4">
            <div>
              <p className="text-xs text-repaw-text/60 mb-2">Applications</p>
              <BarChart color="#d6bca8" data={(data?.series ?? []).map((s) => ({ label: s.label, value: s.applications }))} />
            </div>
            <div>
              <p className="text-xs text-repaw-text/60 mb-2">Adoptions</p>
              <BarChart color="#fad046" data={(data?.series ?? []).map((s) => ({ label: s.label, value: s.adoptions }))} />
            </div>
          </div>
        </ChartCard>

        <ChartCard title="Donations (cash ₱)">
          <BarChart
            color="#2f7d4f"
            data={(data?.series ?? []).map((s) => ({ label: s.label, value: s.donations_cash }))}
          />
        </ChartCard>

        <ChartCard title="Volunteer hours per month">
          <BarChart
            color="#7a5aa8"
            data={(data?.series ?? []).map((s) => ({ label: s.label, value: s.volunteer_hours }))}
          />
        </ChartCard>
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div className="bg-white/70 rounded-3xl p-8 border border-repaw-hover/40 shadow-sm">
          <h2 className="font-serif text-2xl font-bold text-repaw-dark mb-4">Top Pets by Appointments</h2>
          <div className="space-y-3">
            {(data?.top_pets_by_appointments ?? []).length === 0 && (
              <p className="text-repaw-text/70">No appointment data yet.</p>
            )}
            {(data?.top_pets_by_appointments ?? []).map((p, i) => (
              <div key={p.pet_id} className="flex items-center gap-3">
                <span className="w-6 h-6 rounded-full bg-repaw-hover text-repaw-text text-xs font-bold flex items-center justify-center">{i + 1}</span>
                <span className="font-medium text-repaw-dark flex-1">{p.name}</span>
                <span className="text-sm text-repaw-text/80">{p.appointments} appt</span>
              </div>
            ))}
          </div>
        </div>

        <div className="bg-white/70 rounded-3xl p-8 border border-repaw-hover/40 shadow-sm">
          <h2 className="font-serif text-2xl font-bold text-repaw-dark mb-4">Top Pets by Applications</h2>
          <div className="space-y-3">
            {(data?.top_pets_by_applications ?? []).length === 0 && (
              <p className="text-repaw-text/70">No application data yet.</p>
            )}
            {(data?.top_pets_by_applications ?? []).map((p, i) => (
              <div key={p.pet_id} className="flex items-center gap-3">
                <span className="w-6 h-6 rounded-full bg-repaw-hover text-repaw-text text-xs font-bold flex items-center justify-center">{i + 1}</span>
                <span className="font-medium text-repaw-dark flex-1">{p.name}</span>
                <span className="text-sm text-repaw-text/80">{p.applications} apps</span>
              </div>
            ))}
          </div>
        </div>
      </div>
    </div>
  )
}

function StatCard({ label, value, icon }: { label: string; value: number | string; icon: string }) {
  return (
    <div className="bg-white/70 rounded-3xl p-6 border border-repaw-hover/40 shadow-sm">
      <span className="mui-icon text-2xl text-repaw-dark block mb-2">{icon}</span>
      <p className="text-sm text-repaw-text/70">{label}</p>
      <p className="font-serif text-2xl font-bold text-repaw-dark mt-1">{value}</p>
    </div>
  )
}

function ChartCard({ title, children }: { title: string; children: React.ReactNode }) {
  return (
    <div className="bg-white/70 rounded-3xl p-8 border border-repaw-hover/40 shadow-sm">
      <h2 className="font-serif text-2xl font-bold text-repaw-dark mb-6">{title}</h2>
      {children}
    </div>
  )
}
