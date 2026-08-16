import { Outlet } from 'react-router-dom'
import Navbar from './Navbar'
import Footer from './Footer'

export default function Layout() {
  return (
    <div className="min-h-screen bg-repaw-bg text-repaw-text font-sans antialiased">
      <Navbar />
      <main id="top">
        <Outlet />
      </main>
      <Footer />
    </div>
  )
}
