import { useEffect, useState, type ReactNode } from 'react'
import { Link } from 'react-router-dom'
import logo from './logo (1).png'

const slides = ['/images/bg1.jpg', '/images/bg2.jpg', '/images/bg3.jpg']

export default function AuthLayout({ children }: { children: ReactNode }) {
  const [slide, setSlide] = useState(0)

  useEffect(() => {
    const id = setInterval(() => setSlide((s) => (s + 1) % slides.length), 4000)
    return () => clearInterval(id)
  }, [])

  return (
    <div className="min-h-screen grid grid-cols-1 lg:grid-cols-2 font-sans antialiased">
      <div className="relative hidden lg:block overflow-hidden bg-repaw-dark">
        <div className="absolute inset-0">
          {slides.map((src, i) => (
            <img
              key={src}
              src={src}
              alt={`Slide ${i + 1}`}
              className={`absolute inset-0 w-full h-full object-cover transition-opacity duration-700 ${i === slide ? 'opacity-100' : 'opacity-0'}`}
            />
          ))}
        </div>
        <div className="absolute inset-0 bg-repaw-dark/40" />
        <div className="relative h-full flex flex-col justify-between p-10 text-repaw-bg">
          <Link to="/" className="inline-block w-fit">
            <img src={logo} alt="rePaw City" className="h-16 w-auto" />
          </Link>
          <div>
            <h2 className="font-serif text-3xl font-bold leading-tight">Every pet deserves a forever home.</h2>
            <p className="mt-3 text-repaw-bg/80 max-w-sm">Join rePaw City and continue your journey of helping pets find their forever homes.</p>
          </div>
        </div>
      </div>

      <div className="flex items-center justify-center bg-repaw-bg px-6 py-12">
        <div className="w-full max-w-md">
          <div className="lg:hidden mb-8 flex justify-center">
            <Link to="/">
              <img src={logo} alt="rePaw City" className="h-16 w-auto" />
            </Link>
          </div>
          {children}
        </div>
      </div>
    </div>
  )
}
