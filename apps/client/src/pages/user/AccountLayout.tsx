import { NavLink, Outlet } from 'react-router-dom'

const links = [
  { to: '/account', label: 'Overview', icon: 'home', end: true },
  { to: '/account/appointments', label: 'My Appointments', icon: 'event' },
  { to: '/account/applications', label: 'My Applications', icon: 'how_to_reg' },
  { to: '/account/favorites', label: 'Favorite Pets', icon: 'favorite' },
  { to: '/account/volunteer', label: 'Volunteer Dashboard', icon: 'volunteer_activism' },
  { to: '/account/profile', label: 'Profile', icon: 'person' },
  { to: '/account/change-password', label: 'Change Password', icon: 'lock' },
  { to: '/account/notifications', label: 'Notifications', icon: 'notifications' },
]

export default function AccountLayout() {
  return (
    <div className="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 py-10 lg:py-14">
      <div className="grid grid-cols-1 lg:grid-cols-[260px_1fr] gap-8 items-start">
        <aside className="bg-white/70 rounded-3xl border border-repaw-hover/40 shadow-sm p-4 lg:sticky lg:top-24">
          <nav className="flex lg:flex-col gap-1 overflow-x-auto lg:overflow-visible">
            {links.map((link) => (
              <NavLink
                key={link.to}
                to={link.to}
                end={link.end}
                className={({ isActive }) =>
                  `flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm font-medium whitespace-nowrap ${
                    isActive ? 'bg-repaw-text text-repaw-bg' : 'text-repaw-text hover:bg-repaw-hover/60 transition-colors'
                  }`
                }
              >
                <span className="mui-icon text-[20px]">{link.icon}</span>
                {link.label}
              </NavLink>
            ))}
          </nav>
        </aside>

        <main className="min-w-0">
          <Outlet />
        </main>
      </div>
    </div>
  )
}
