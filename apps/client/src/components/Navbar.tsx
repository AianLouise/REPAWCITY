import { useState } from 'react'
import { Link, NavLink, useNavigate } from 'react-router-dom'
import { useAuthStore } from '@repaw/auth'
import { authApi } from '@repaw/api-client'
import { logo } from '@repaw/ui'
import { ADMIN_URL } from '@repaw/config'
import { useNotifications } from '../hooks/useNotifications'

const primaryLinks = [
  { label: 'Home', to: '/' },
  { label: 'Adopt', to: '/adopt' },
  { label: 'Donate', to: '/donate' },
  { label: 'News', to: '/news' },
  { label: 'Volunteer', to: '/volunteer' },
]

const aboutLinks = [
  { label: 'Success Stories', to: '/about/success-stories' },
  { label: 'FAQ', to: '/about/faq' },
  { label: 'Contact', to: '/about/contact' },
]

function navLinkClass({ isActive }: { isActive: boolean }) {
  return [
    'relative text-repaw-text font-medium text-[15px] uppercase tracking-wide px-4 h-20 flex items-center transition-colors duration-300 focus:outline-none',
    'after:content-[\'\'] after:absolute after:left-4 after:right-4 after:bottom-5 after:h-[2px] after:bg-repaw-dark after:scale-x-0 after:transition-transform after:duration-300 hover:text-repaw-dark',
    isActive ? 'text-repaw-dark after:scale-x-100' : 'hover:after:scale-x-100',
  ]
    .filter(Boolean)
    .join(' ')
}

export default function Navbar() {
  const { user, logout } = useAuthStore()
  const navigate = useNavigate()
  const [mobileOpen, setMobileOpen] = useState(false)
  const [aboutOpen, setAboutOpen] = useState(false)
  const [profileOpen, setProfileOpen] = useState(false)

  const { data: notificationsData } = useNotifications()
  const unreadCount = notificationsData?.unread_count ?? 0

  const isAdmin = user?.user_type === '1'

  async function handleLogout() {
    if (!confirm('Are you sure you want to log out?')) return
    try {
      await authApi.logout()
    } catch {
      // token already invalid; clear locally regardless
    }
    logout()
    navigate('/')
  }

  return (
    <nav className="sticky top-0 z-50 bg-repaw-bg border-b border-repaw-hover/40 shadow-sm h-20 font-sans">
      <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div className="flex justify-between items-center h-20">
          <Link to="/" className="flex items-center group" aria-label="rePaw City home">
            <img src={logo} alt="rePaw City" className="h-14 w-auto transition-transform duration-300 group-hover:scale-105" />
          </Link>

          <div className="hidden lg:flex items-center">
            {primaryLinks.map((link) => (
              <NavLink key={link.to} to={link.to} className={navLinkClass}>
                {link.label}
              </NavLink>
            ))}

            <div className="relative group">
              <button
                className="relative text-repaw-text font-medium text-[15px] uppercase tracking-wide px-4 h-20 flex items-center gap-1 focus:outline-none hover:text-repaw-dark transition-colors duration-300"
                aria-haspopup="true"
                aria-expanded="false"
              >
                About Us
                <span className="mui-icon text-[20px] transition-transform duration-300 group-hover:rotate-180">keyboard_arrow_down</span>
              </button>
              <div className="hidden group-hover:block group-focus-within:block absolute top-full left-0 bg-white/95 rounded-xl shadow-xl min-w-[210px] py-2 z-50 border border-repaw-hover/40">
                {aboutLinks.map((link) => (
                  <NavLink
                    key={link.to}
                    to={link.to}
                    className="block text-repaw-text text-[15px] py-2.5 px-5 transition-colors duration-200 hover:bg-repaw-hover hover:text-repaw-dark"
                  >
                    {link.label}
                  </NavLink>
                ))}
              </div>
            </div>

            {user ? (
              <>
                {isAdmin && (
                  <a href={ADMIN_URL} className={navLinkClass({ isActive: false })}>
                    Admin
                  </a>
                )}
                <div className="relative group">
                  <button
                    className="relative text-repaw-text font-medium text-[15px] uppercase tracking-wide px-4 h-20 flex items-center gap-1 focus:outline-none hover:text-repaw-dark transition-colors duration-300"
                    aria-haspopup="true"
                    aria-expanded="false"
                  >
                    Profile
                    <span className="mui-icon text-[20px] transition-transform duration-300 group-hover:rotate-180">keyboard_arrow_down</span>
                  </button>
                  <div className="hidden group-hover:block group-focus-within:block absolute top-full left-0 bg-white/95 rounded-xl shadow-xl min-w-[210px] py-2 z-50 border border-repaw-hover/40">
                    {!isAdmin && (
                      <Link to="/account/profile" className="block text-repaw-text text-[15px] py-2.5 px-5 transition-colors duration-200 hover:bg-repaw-hover hover:text-repaw-dark">
                        Edit Profile
                      </Link>
                    )}
                    {!isAdmin && (
                      <Link to="/account" className="block text-repaw-text text-[15px] py-2.5 px-5 transition-colors duration-200 hover:bg-repaw-hover hover:text-repaw-dark">
                        My Dashboard
                      </Link>
                    )}
                    {!isAdmin && (
                      <Link to="/account/change-password" className="block text-repaw-text text-[15px] py-2.5 px-5 transition-colors duration-200 hover:bg-repaw-hover hover:text-repaw-dark">
                        Change Password
                      </Link>
                    )}
                    {!isAdmin && (
                      <Link to="/account/notifications" className="block text-repaw-text text-[15px] py-2.5 px-5 transition-colors duration-200 hover:bg-repaw-hover hover:text-repaw-dark">
                        Notifications
                        {unreadCount > 0 && (
                          <span className="ml-2 inline-flex items-center justify-center h-5 min-w-5 rounded-full bg-repaw-danger text-white text-[11px] font-bold px-1.5">
                            {unreadCount}
                          </span>
                        )}
                      </Link>
                    )}
                    {!isAdmin && (
                      <Link to="/account/applications" className="block text-repaw-text text-[15px] py-2.5 px-5 transition-colors duration-200 hover:bg-repaw-hover hover:text-repaw-dark">
                        My Applications
                      </Link>
                    )}
                    {!isAdmin && (
                      <Link to="/account/volunteer" className="block text-repaw-text text-[15px] py-2.5 px-5 transition-colors duration-200 hover:bg-repaw-hover hover:text-repaw-dark">
                        Volunteer Dashboard
                      </Link>
                    )}
                    <button
                      onClick={handleLogout}
                      className="block w-full text-left text-repaw-danger text-[15px] py-2.5 px-5 transition-colors duration-200 hover:bg-repaw-hover"
                    >
                      Logout
                    </button>
                  </div>
                </div>
              </>
            ) : (
              <>
                <NavLink to="/login" className={navLinkClass}>
                  Log In
                </NavLink>
                <NavLink
                  to="/register"
                  className="ml-2 bg-repaw-text text-repaw-bg rounded-full px-6 py-2.5 text-[15px] font-medium uppercase tracking-wide hover:bg-repaw-dark transition-colors duration-300"
                >
                  Sign Up
                </NavLink>
              </>
            )}
          </div>

          <div className="lg:hidden flex items-center">
            <button
              onClick={() => setMobileOpen((o) => !o)}
              className="text-repaw-text hover:text-repaw-dark p-2 focus:outline-none"
              aria-label="Open menu"
              aria-expanded={mobileOpen}
            >
              <span className="mui-icon text-3xl">{mobileOpen ? 'close' : 'menu'}</span>
            </button>
          </div>
        </div>
      </div>

      {mobileOpen && (
        <div className="lg:hidden bg-repaw-bg border-t border-repaw-hover/40">
          <div className="px-4 pt-3 pb-6 space-y-1 max-h-[80vh] overflow-y-auto">
            {primaryLinks.map((link) => (
              <NavLink
                key={link.to}
                to={link.to}
                onClick={() => setMobileOpen(false)}
                className="block py-3 px-3 rounded-lg text-repaw-text text-lg uppercase tracking-wide hover:bg-repaw-hover"
              >
                {link.label}
              </NavLink>
            ))}

            <button
              onClick={() => setAboutOpen((o) => !o)}
              className="w-full flex justify-between items-center py-3 px-3 rounded-lg text-repaw-text text-lg uppercase tracking-wide hover:bg-repaw-hover"
              aria-expanded={aboutOpen}
            >
              About Us
              <span className={`mui-icon transition-transform ${aboutOpen ? 'rotate-180' : ''}`}>keyboard_arrow_down</span>
            </button>
            {aboutOpen && (
              <div className="pl-4 space-y-1">
                {aboutLinks.map((link) => (
                  <NavLink
                    key={link.to}
                    to={link.to}
                    onClick={() => setMobileOpen(false)}
                    className="block py-2 px-3 rounded-lg text-repaw-text hover:bg-repaw-hover"
                  >
                    {link.label}
                  </NavLink>
                ))}
              </div>
            )}

            {user ? (
              <>
                <button
                  onClick={() => setProfileOpen((o) => !o)}
                  className="w-full flex justify-between items-center py-3 px-3 rounded-lg text-repaw-text text-lg uppercase tracking-wide hover:bg-repaw-hover"
                  aria-expanded={profileOpen}
                >
                  Profile
                  <span className={`mui-icon transition-transform ${profileOpen ? 'rotate-180' : ''}`}>keyboard_arrow_down</span>
                </button>
                {profileOpen && (
                  <div className="pl-4 space-y-1">
                    {!isAdmin && (
                      <Link to="/account" onClick={() => setMobileOpen(false)} className="block py-2 px-3 rounded-lg text-repaw-text hover:bg-repaw-hover">
                        My Dashboard
                      </Link>
                    )}
                    {!isAdmin && (
                      <Link to="/account/profile" onClick={() => setMobileOpen(false)} className="block py-2 px-3 rounded-lg text-repaw-text hover:bg-repaw-hover">
                        Edit Profile
                      </Link>
                    )}
                    {!isAdmin && (
                      <Link to="/account/change-password" onClick={() => setMobileOpen(false)} className="block py-2 px-3 rounded-lg text-repaw-text hover:bg-repaw-hover">
                        Change Password
                      </Link>
                    )}
                    {!isAdmin && (
                      <Link to="/account/notifications" onClick={() => setMobileOpen(false)} className="block py-2 px-3 rounded-lg text-repaw-text hover:bg-repaw-hover">
                        Notifications
                        {unreadCount > 0 && (
                          <span className="ml-2 inline-flex items-center justify-center h-5 min-w-5 rounded-full bg-repaw-danger text-white text-[11px] font-bold px-1.5">
                            {unreadCount}
                          </span>
                        )}
                      </Link>
                    )}
                    {!isAdmin && (
                      <Link to="/account/applications" onClick={() => setMobileOpen(false)} className="block py-2 px-3 rounded-lg text-repaw-text hover:bg-repaw-hover">
                        My Applications
                      </Link>
                    )}
                    {!isAdmin && (
                      <Link to="/account/volunteer" onClick={() => setMobileOpen(false)} className="block py-2 px-3 rounded-lg text-repaw-text hover:bg-repaw-hover">
                        Volunteer Dashboard
                      </Link>
                    )}
                    {isAdmin && (
                      <a href={ADMIN_URL} onClick={() => setMobileOpen(false)} className="block py-2 px-3 rounded-lg text-repaw-text hover:bg-repaw-hover">
                        Admin Dashboard
                      </a>
                    )}
                  </div>
                )}
                <button
                  onClick={() => {
                    setMobileOpen(false)
                    void handleLogout()
                  }}
                  className="block w-full text-left py-3 px-3 mt-1 rounded-lg text-repaw-danger text-lg uppercase tracking-wide hover:bg-red-50"
                >
                  Logout
                </button>
              </>
            ) : (
              <>
                <NavLink to="/login" onClick={() => setMobileOpen(false)} className="block py-3 px-3 rounded-lg text-repaw-text text-lg uppercase tracking-wide hover:bg-repaw-hover">
                  Log In
                </NavLink>
                <NavLink to="/register" onClick={() => setMobileOpen(false)} className="block py-3 mt-2 text-center bg-repaw-text text-repaw-bg text-lg uppercase tracking-wide rounded-full hover:bg-repaw-dark">
                  Sign Up
                </NavLink>
              </>
            )}
          </div>
        </div>
      )}
    </nav>
  )
}
