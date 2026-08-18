import { useState } from 'react'
import { Link } from 'react-router-dom'
import { useDashboard, useDailyAppointments, useAdminActions } from '../../hooks/useAdmin'
import { useAllPets, useAllNews } from '../../hooks/useAdmin'
import { useAllApplications } from '@repaw/api-client'
import { useAdminVolunteers } from '@repaw/api-client'
import { useAdminUsers } from '../../hooks/useAdmin'
import { Loading } from '@repaw/ui'
import { format } from 'date-fns'
import type { TimeSlot } from '@repaw/api-client'
import {
  MdEventAvailable,
  MdPets,
  MdVolunteerActivism,
  MdGroups,
  MdCheckCircle,
  MdCancel,
  MdHowToReg,
  MdNewspaper,
  MdArrowForward,
  MdDashboard,
} from 'react-icons/md'
import type { IconType } from 'react-icons'

function getGreeting() {
  const hour = new Date().getHours()
  if (hour < 12) return 'Good morning'
  if (hour < 17) return 'Good afternoon'
  return 'Good evening'
}

export default function AdminDashboard() {
  const [date, setDate] = useState(() => format(new Date(), 'yyyy-MM-dd'))
  const { data, isLoading: dashboardLoading } = useDashboard(date)
  const { data: petsData } = useAllPets()
  const { data: newsData } = useAllNews()
  const { data: applicationsData } = useAllApplications()
  const { data: volunteersData } = useAdminVolunteers()
  const { data: usersData } = useAdminUsers()
  const actions = useAdminActions()

  const petCount = petsData?.data?.length ?? 0
  const newsCount = newsData?.data?.length ?? 0
  const applicationCount = applicationsData?.length ?? 0
  const volunteerCount = volunteersData?.length ?? 0
  const userCount = usersData?.length ?? 0

  return (
    <div className="space-y-8">
      {/* Header */}
      <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
        <div>
          <p className="text-repaw-text/70 text-sm">{getGreeting()}, admin</p>
          <h1 className="font-serif text-3xl font-bold text-repaw-dark">Dashboard Overview</h1>
        </div>
        <div className="text-sm text-repaw-text/60">
          {format(new Date(), 'MMM d, yyyy')} · {format(new Date(), 'h:mm a')}
        </div>
      </div>

      {/* Key Metrics */}
      <div>
        <div className="flex items-center gap-2 mb-4">
          <h2 className="font-serif text-lg font-bold text-repaw-dark">Key Metrics</h2>
          <span className="text-sm text-repaw-text/50">Real-time business overview</span>
        </div>
        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
          <StatCard
            icon={MdEventAvailable}
            iconColor="text-emerald-500"
            label="Total Appointments"
            value={data?.counts.total}
          />
          <StatCard
            icon={MdPets}
            iconColor="text-orange-500"
            label="Adopt Appointments"
            value={data?.counts.adopt}
          />
          <StatCard
            icon={MdVolunteerActivism}
            iconColor="text-blue-500"
            label="Total Volunteers"
            value={volunteerCount}
          />
          <StatCard
            icon={MdGroups}
            iconColor="text-purple-500"
            label="Total Users"
            value={userCount}
          />
        </div>
      </div>

      {/* Module Analytics */}
      <div>
        <div className="flex items-center gap-2 mb-4">
          <h2 className="font-serif text-lg font-bold text-repaw-dark">Module Analytics</h2>
          <span className="text-sm text-repaw-text/50">Current totals across modules</span>
        </div>
        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
          <ModuleCard
            icon={MdHowToReg}
            iconColor="text-blue-500"
            title="Adoption Applications"
            link="/applications"
            stats={[
              { label: 'Total Applications', value: applicationCount },
            ]}
          />
          <ModuleCard
            icon={MdPets}
            iconColor="text-orange-500"
            title="Pet Management"
            link="/pets/manage"
            stats={[
              { label: 'Total Pets', value: petCount },
            ]}
          />
          <ModuleCard
            icon={MdNewspaper}
            iconColor="text-emerald-500"
            title="News Management"
            link="/news/manage"
            stats={[
              { label: 'Total News', value: newsCount },
            ]}
          />
          <ModuleCard
            icon={MdVolunteerActivism}
            iconColor="text-purple-500"
            title="Volunteer Management"
            link="/volunteers"
            stats={[
              { label: 'Total Volunteers', value: volunteerCount },
            ]}
          />
          <ModuleCard
            icon={MdGroups}
            iconColor="text-indigo-500"
            title="User Management"
            link="/users"
            stats={[
              { label: 'Total Users', value: userCount },
            ]}
          />
          <ModuleCard
            icon={MdDashboard}
            iconColor="text-rose-500"
            title="Reports & Analytics"
            link="/reports"
            stats={[]}
          />
        </div>
      </div>

      {/* Date Picker & Session Tables */}
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

      {dashboardLoading ? (
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

function StatCard({
  icon,
  iconColor,
  value,
  label,
}: {
  icon: IconType
  iconColor: string
  value?: number
  label: string
}) {
  const Icon = icon
  return (
    <div className="bg-white/70 rounded-2xl p-5 border border-repaw-hover/40 shadow-sm">
      <div className="flex items-center justify-between mb-3">
        <span className="text-sm text-repaw-text/70">{label}</span>
        <Icon size={20} className={iconColor} />
      </div>
      <div className="font-serif text-3xl font-bold text-repaw-dark">{value ?? '—'}</div>
    </div>
  )
}

function ModuleCard({
  icon,
  iconColor,
  title,
  link,
  stats,
}: {
  icon: IconType
  iconColor: string
  title: string
  link: string
  stats: { label: string; value: number }[]
}) {
  const Icon = icon
  return (
    <Link
      to={link}
      className="bg-white/70 rounded-2xl p-5 border border-repaw-hover/40 shadow-sm hover:shadow-md transition-shadow group block"
    >
      <div className="flex items-center justify-between mb-4">
        <div className="flex items-center gap-3">
          <Icon size={22} className={iconColor} />
          <span className="font-serif font-bold text-repaw-dark">{title}</span>
        </div>
        <MdArrowForward size={18} className="text-repaw-text/40 group-hover:text-repaw-dark transition-colors" />
      </div>
      {stats.length > 0 && (
        <div className="space-y-2">
          {stats.map((stat) => (
            <div key={stat.label} className="flex items-center justify-between text-sm">
              <span className="text-repaw-text/70">{stat.label}</span>
              <span className="font-semibold text-repaw-dark">{stat.value}</span>
            </div>
          ))}
        </div>
      )}
    </Link>
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
                            <MdCheckCircle size={16} />Accept
                          </button>
                          <button
                            onClick={() => actions.updateStatus.mutate({ id: row.id, status: 'Cancelled' })}
                            disabled={actions.updateStatus.isPending}
                            className="inline-flex items-center gap-1 bg-repaw-danger text-white rounded-full px-4 py-1.5 text-xs font-medium uppercase tracking-wide hover:opacity-90 transition-opacity"
                          >
                            <MdCancel size={16} />Cancel
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
