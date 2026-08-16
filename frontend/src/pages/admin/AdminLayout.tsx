import { Link, NavLink, Outlet, useNavigate } from 'react-router-dom'
import logo from '../../assets/logo (1).png'
import { useAuthStore } from '../../store/authStore'
import { authApi } from '../../api/auth'
import { CLIENT_URL } from '../../config'

const navGroups: { group: string; links: { to: string; label: string; icon: string; end?: boolean }[] }[] = [
  {
    group: 'Overview',
    links: [
      { to: '/', label: 'Dashboard', icon: 'dashboard', end: true },
      { to: '/availability', label: 'Availability', icon: 'calendar_month' },
      { to: '/applications', label: 'Adoption Applications', icon: 'how_to_reg' },
      { to: '/reports', label: 'Reports', icon: 'insights' },
    ],
  },
  {
    group: 'Community',
    links: [
      { to: '/donations', label: 'Donations', icon: 'volunteer_activism' },
      { to: '/volunteers', label: 'Volunteers', icon: 'group' },
    ],
  },
  {
    group: 'Pets',
    links: [
      { to: '/pets/add', label: 'Add Pets', icon: 'pets' },
      { to: '/pets/manage', label: 'Manage Pets', icon: 'pets' },
      { to: '/pets/featured', label: 'Modify Featured Image', icon: 'stars' },
    ],
  },
  {
    group: 'News',
    links: [
      { to: '/news/add', label: 'Add News', icon: 'newspaper' },
      { to: '/news/manage', label: 'Manage News', icon: 'newspaper' },
    ],
  },
  {
    group: 'Accounts',
    links: [{ to: '/users', label: 'Manage Users', icon: 'group' }],
  },
]

export default function AdminLayout() {
  const { logout } = useAuthStore()
  const navigate = useNavigate()

  async function handleLogout() {
    if (!confirm('Are you sure you want to log out?')) return
    try {
      await authApi.logout()
    } catch {
      // ignore
    }
    logout()
    navigate('/')
  }

  return (
    <div className="min-h-screen bg-repaw-bg text-repaw-text font-sans antialiased">
      <nav className="sticky top-0 z-50 flex items-center justify-between bg-repaw-dark px-6 h-16 shadow-md">
        <Link to={CLIENT_URL} target="_blank" className="flex items-center">
          <img src={logo} alt="rePaw City" className="h-10 w-auto" />
        </Link>
        <button
          onClick={handleLogout}
          className="inline-flex items-center gap-2 rounded-full bg-repaw-accent px-5 py-2 text-sm font-medium text-repaw-dark hover:bg-repaw-text hover:text-repaw-bg transition-colors"
        >
          <span className="mui-icon">logout</span> Logout
        </button>
      </nav>

      <div className="flex min-h-[calc(100vh-4rem)]">
        <aside className="w-60 shrink-0 bg-white/60 border-r border-repaw-hover/40 p-4 hidden md:block">
          <nav className="flex flex-col gap-5">
            {navGroups.map((g) => (
              <div key={g.group}>
                <p className="px-4 mb-1 text-xs font-semibold uppercase tracking-wider text-repaw-text/50">{g.group}</p>
                {g.links.map((link) => (
                  <NavLink
                    key={link.to}
                    to={link.to}
                    end={link.end}
                    className={({ isActive }) =>
                      `flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm font-medium ${
                        isActive ? 'bg-repaw-text text-repaw-bg' : 'text-repaw-text hover:bg-repaw-hover/60 transition-colors'
                      }`
                    }
                  >
                    <span className="mui-icon text-[20px]">{link.icon}</span>
                    {link.label}
                  </NavLink>
                ))}
              </div>
            ))}
          </nav>
        </aside>

        <main className="flex-1 p-6 sm:p-10 space-y-8">
          <Outlet />
        </main>
      </div>
    </div>
  )
}
