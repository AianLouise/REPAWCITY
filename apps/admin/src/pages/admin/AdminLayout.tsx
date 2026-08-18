import { useState } from 'react'
import { Link, NavLink, Outlet, useNavigate } from 'react-router-dom'
import { logo } from '@repaw/ui'
import { useAuthStore } from '@repaw/auth'
import { authApi } from '@repaw/api-client'
import { CLIENT_URL } from '@repaw/config'
import {
  MdDashboard,
  MdCalendarMonth,
  MdHowToReg,
  MdInsights,
  MdGroup,
  MdGroups,
  MdPets,
  MdStars,
  MdNewspaper,
  MdMenuOpen,
  MdMenu,
  MdOpenInNew,
  MdLogout,
  MdClose,
} from 'react-icons/md'
import type { IconType } from 'react-icons'

const navGroups: { group: string; links: { to: string; label: string; icon: IconType; end?: boolean }[] }[] = [
  {
    group: 'Overview',
    links: [
      { to: '/', label: 'Dashboard', icon: MdDashboard, end: true },
      { to: '/availability', label: 'Availability', icon: MdCalendarMonth },
      { to: '/applications', label: 'Adoption Applications', icon: MdHowToReg },
      { to: '/reports', label: 'Reports', icon: MdInsights },
    ],
  },
  {
    group: 'Community',
    links: [
      { to: '/volunteers', label: 'Volunteers', icon: MdGroup },
    ],
  },
  {
    group: 'Pets',
    links: [
      { to: '/pets/add', label: 'Add Pets', icon: MdPets },
      { to: '/pets/manage', label: 'Manage Pets', icon: MdPets },
      { to: '/pets/featured', label: 'Modify Featured Image', icon: MdStars },
    ],
  },
  {
    group: 'News',
    links: [
      { to: '/news/add', label: 'Add News', icon: MdNewspaper },
      { to: '/news/manage', label: 'Manage News', icon: MdNewspaper },
    ],
  },
  {
    group: 'Accounts',
    links: [{ to: '/users', label: 'Manage Users', icon: MdGroups }],
  },
]

export default function AdminLayout() {
  const { user, logout } = useAuthStore()
  const navigate = useNavigate()
  const [sidebarOpen, setSidebarOpen] = useState(
    () => typeof window !== 'undefined' && window.innerWidth >= 768,
  )

  const fullName = user ? `${user.fname} ${user.lname}`.trim() : 'Admin'
  const initials = user
    ? `${user.fname?.[0] ?? ''}${user.lname?.[0] ?? ''}`.toUpperCase() || 'A'
    : 'A'

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
      <header className="sticky top-0 z-50 flex items-center gap-3 bg-repaw-bg border-b border-repaw-hover/50 px-4 sm:px-6 h-16 shadow-sm">
        <button
          type="button"
          onClick={() => setSidebarOpen((o) => !o)}
          aria-label={sidebarOpen ? 'Close navigation' : 'Open navigation'}
          aria-expanded={sidebarOpen}
          className="inline-flex items-center justify-center h-10 w-10 rounded-full text-repaw-dark hover:bg-repaw-hover/60 transition-colors"
        >
          {sidebarOpen ? <MdMenuOpen size={24} /> : <MdMenu size={24} />}
        </button>

        <Link to="/" className="flex items-center gap-3">
          <img src={logo} alt="rePaw City" className="h-9 w-auto" />
          <span className="hidden sm:inline-flex items-center text-sm font-semibold uppercase tracking-[0.2em] text-repaw-text/70">
            Admin Portal
          </span>
        </Link>

        <div className="ml-auto flex items-center gap-2 sm:gap-3">
          <a
            href={CLIENT_URL}
            target="_blank"
            rel="noreferrer"
            className="hidden sm:inline-flex items-center gap-2 rounded-full px-4 py-2 text-sm font-medium text-repaw-dark/80 hover:bg-repaw-hover/60 transition-colors"
          >
            <MdOpenInNew size={20} /> View site
          </a>

          <div className="hidden sm:block h-8 w-px bg-repaw-text/15" />

          <div className="hidden sm:flex flex-col items-end leading-tight">
            <span className="text-sm font-medium text-repaw-dark">{fullName}</span>
            {user?.email && <span className="text-xs text-repaw-text/60">{user.email}</span>}
          </div>
          <div className="h-9 w-9 shrink-0 rounded-full bg-repaw-accent text-repaw-dark flex items-center justify-center font-semibold">
            {initials}
          </div>

          <button
            onClick={handleLogout}
            className="inline-flex items-center gap-2 rounded-full bg-repaw-text px-4 sm:px-5 py-2 text-sm font-medium text-repaw-bg hover:bg-repaw-dark transition-colors"
          >
            <MdLogout size={20} />
            <span className="hidden sm:inline">Logout</span>
          </button>
        </div>
      </header>

      {sidebarOpen && (
        <div
          onClick={() => setSidebarOpen(false)}
          className="fixed inset-0 z-40 bg-black/40 md:hidden"
          aria-hidden="true"
        />
      )}

      <div className="flex min-h-[calc(100vh-4rem)]">
        <aside
          className={`fixed md:static z-40 top-16 bottom-0 left-0 w-64 shrink-0 bg-white/60 border-r border-repaw-hover/40 p-4 overflow-y-auto transition-[transform,width] duration-200 md:top-0 md:translate-x-0 ${
            sidebarOpen ? 'translate-x-0 md:w-64' : '-translate-x-full md:w-0 md:overflow-hidden md:border-r-0 md:p-0'
          }`}
        >
          <div className="flex items-center justify-between mb-4 md:hidden">
            <span className="font-serif font-bold text-repaw-dark">Menu</span>
            <button
              type="button"
              onClick={() => setSidebarOpen(false)}
              aria-label="Close navigation"
              className="inline-flex items-center justify-center h-9 w-9 rounded-full text-repaw-text hover:bg-repaw-hover/60 transition-colors"
            >
              <MdClose size={22} />
            </button>
          </div>
          <nav className="flex flex-col gap-5">
            {navGroups.map((g) => (
              <div key={g.group}>
                <p className="px-4 mb-1 text-xs font-semibold uppercase tracking-wider text-repaw-text/50">{g.group}</p>
                {g.links.map((link) => (
                  <NavLink
                    key={link.to}
                    to={link.to}
                    end={link.end}
                    onClick={() => setSidebarOpen(false)}
                    className={({ isActive }) =>
                      `flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm font-medium ${
                        isActive ? 'bg-repaw-text text-repaw-bg' : 'text-repaw-text hover:bg-repaw-hover/60 transition-colors'
                      }`
                    }
                  >
                    <link.icon size={20} />
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
