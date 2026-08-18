import { useEffect, useState } from 'react'
import { Link } from 'react-router-dom'
import logo from '../assets/logo (1).png'
import { SHELTER } from '../config'

export default function Footer() {
  const [showTop, setShowTop] = useState(false)

  useEffect(() => {
    const onScroll = () => setShowTop(window.scrollY > 300)
    window.addEventListener('scroll', onScroll)
    return () => window.removeEventListener('scroll', onScroll)
  }, [])

  return (
    <>
      <footer className="relative mt-12 bg-repaw-accent rounded-t-[6rem] font-sans overflow-hidden">
        <div className="absolute -top-10 -right-10 opacity-10 pointer-events-none select-none" aria-hidden="true">
          <span className="mui-icon text-[20rem] text-repaw-dark">pets</span>
        </div>
        <div className="absolute bottom-0 -left-16 opacity-10 pointer-events-none select-none" aria-hidden="true">
          <span className="mui-icon text-[16rem] text-repaw-dark">pets</span>
        </div>

        <div className="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 pt-16 pb-10 relative z-10">
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10 lg:gap-8">
            <div className="flex flex-col items-start">
              <Link to="/" className="group" aria-label="rePaw City home">
                <img src={logo} alt="rePaw City" className="h-20 w-auto transition-transform duration-300 group-hover:scale-105" />
              </Link>
              <p className="mt-4 text-repaw-text/80 text-sm leading-relaxed max-w-[220px] text-balance">
                Helping pets find their forever homes across the Philippines.
              </p>
            </div>

            <div>
              <h3 className="font-serif text-xl font-semibold text-repaw-dark mb-5 relative inline-block">
                Quick Links
                <span className="absolute -bottom-1 left-0 w-10 h-[3px] bg-repaw-dark rounded-full" />
              </h3>
              <ul className="space-y-2.5">
                <FooterLink to="/adopt?type=Dog">Adopt a Dog</FooterLink>
                <FooterLink to="/adopt?type=Cat">Adopt a Cat</FooterLink>
                <FooterLink to="/donate">Donate</FooterLink>
                <FooterLink to="/about/success-stories">Success Stories</FooterLink>
                <FooterLink to="/volunteer">Volunteer</FooterLink>
                <FooterLink to="/news">News</FooterLink>
              </ul>
            </div>

            <div className="lg:col-span-1">
              <h3 className="font-serif text-xl font-semibold text-repaw-dark mb-5 relative inline-block text-balance">
                About Us
                <span className="absolute -bottom-1 left-0 w-10 h-[3px] bg-repaw-dark rounded-full" />
              </h3>
              <p className="text-repaw-text/90 text-sm leading-relaxed text-balance">
                Welcome to RePaw City, your go-to pet adoption website based in the Philippines! We are a team of passionate animal lovers who are committed to helping pets find their forever homes.
              </p>
              <p className="text-repaw-text/90 text-sm leading-relaxed text-balance mt-3">
                At RePaw City, we believe that every pet deserves a loving home and a chance to live a happy life. We work tirelessly to connect adoptable pets with loving families who can provide them with the care and attention they need.
              </p>
            </div>

            <div>
              <h3 className="font-serif text-xl font-semibold text-repaw-dark mb-5 relative inline-block">
                Address
                <span className="absolute -bottom-1 left-0 w-10 h-[3px] bg-repaw-dark rounded-full" />
              </h3>
              <div className="flex items-start gap-3">
                <span className="mui-icon text-repaw-dark mt-1 text-lg">place</span>
                <p className="text-repaw-text/90 text-sm leading-relaxed">
                  {SHELTER.address.line1}
                  <br />
                  {SHELTER.address.line2}
                  <br />
                  {SHELTER.address.line3}
                </p>
              </div>
              <a
                href={SHELTER.mapsUrl}
                target="_blank"
                rel="noopener noreferrer"
                className="mt-4 inline-flex items-center gap-2 text-repaw-dark font-semibold text-sm hover:underline underline-offset-4 transition-colors duration-200"
              >
                <span className="mui-icon">map</span>
                View Google Maps
              </a>
            </div>
          </div>

          <div className="border-t border-repaw-dark/20 my-8" />

          <div className="grid grid-cols-1 sm:grid-cols-3 gap-8 items-start">
            <div>
              <h3 className="font-serif text-lg font-semibold text-repaw-dark mb-4">Contact</h3>
              <div className="space-y-2.5">
                <p className="flex items-center gap-3 text-repaw-text/90 text-sm">
                  <span className="mui-icon text-repaw-dark">call</span>
                  +63 923 4897 632
                </p>
                <p className="flex items-center gap-3 text-repaw-text/90 text-sm">
                  <span className="mui-icon text-repaw-dark">mail</span>
                  repawcity@gmail.com
                </p>
              </div>
            </div>

            <div>
              <h3 className="font-serif text-lg font-semibold text-repaw-dark mb-4">Information</h3>
              <ul className="space-y-2.5">
                <FooterLink to="/about/mission">Mission</FooterLink>
                <FooterLink to="/about/faq">Assistance</FooterLink>
                <FooterLink to="/privacy">Privacy Policy</FooterLink>
                <FooterLink to="/terms">Terms of Use</FooterLink>
              </ul>
            </div>

            <div>
              <h3 className="font-serif text-lg font-semibold text-repaw-dark mb-4">Social</h3>
              <div className="flex gap-3">
                {['facebook', 'instagram'].map((icon) => (
                  <a
                    key={icon}
                    href="#"
                    aria-label={icon}
                    className="w-11 h-11 rounded-full bg-repaw-dark text-repaw-accent flex items-center justify-center hover:bg-repaw-text transition-colors duration-300 shadow-md"
                  >
                    <span className="mui-icon text-lg">{icon === 'facebook' ? 'thumb_up' : 'photo_camera'}</span>
                  </a>
                ))}
                <a
                  href="#"
                  aria-label="TikTok"
                  className="w-11 h-11 rounded-full bg-repaw-dark text-repaw-accent flex items-center justify-center hover:bg-repaw-text transition-colors duration-300 shadow-md"
                >
                  <span className="mui-icon text-lg">music_note</span>
                </a>
              </div>
            </div>
          </div>
        </div>

        <div className="bg-repaw-dark text-white/80">
          <div className="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 py-5 flex flex-col sm:flex-row items-center justify-center text-center gap-3">
            <p className="text-sm">
              &copy; {new Date().getFullYear()} RePaw City. All rights reserved.
            </p>
          </div>
        </div>
      </footer>

      {showTop && (
        <button
          onClick={() => window.scrollTo({ top: 0, behavior: 'smooth' })}
          className="fixed bottom-6 right-6 w-12 h-12 rounded-full bg-repaw-dark text-white flex items-center justify-center shadow-lg hover:bg-repaw-text transition-colors duration-300 z-50"
          aria-label="Scroll to top"
        >
          <span className="mui-icon text-lg">arrow_upward</span>
        </button>
      )}
    </>
  )
}

function FooterLink({ to, children }: { to: string; children: React.ReactNode }) {
  return (
    <li>
      <Link
        to={to}
        className="text-repaw-text hover:text-repaw-dark transition-colors duration-200 text-[15px] font-medium inline-flex items-center gap-2 group"
      >
        <span className="w-1.5 h-1.5 bg-repaw-dark rounded-full opacity-40 group-hover:opacity-100 transition-opacity" />
        {children}
      </Link>
    </li>
  )
}
