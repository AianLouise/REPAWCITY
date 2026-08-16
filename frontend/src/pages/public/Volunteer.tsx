import { Link } from 'react-router-dom'
import { useAuthStore } from '../../store/authStore'

export default function Volunteer() {
  const user = useAuthStore((s) => s.user)

  const cta = user ? (
    <a
      href="/book"
      target="_blank"
      rel="noreferrer"
      className="inline-flex items-center justify-center gap-2 rounded-full bg-repaw-text text-repaw-bg px-7 py-3 text-[15px] font-medium uppercase tracking-wide hover:bg-repaw-dark transition-colors duration-300"
    >
      <span className="mui-icon text-[20px]">volunteer_activism</span> Become a Volunteer
    </a>
  ) : (
    <Link
      to="/login"
      className="inline-flex items-center justify-center gap-2 rounded-full bg-repaw-text text-repaw-bg px-7 py-3 text-[15px] font-medium uppercase tracking-wide hover:bg-repaw-dark transition-colors duration-300"
    >
      <span className="mui-icon text-[20px]">volunteer_activism</span> Become a Volunteer
    </Link>
  )

  return (
    <div>
      <section className="relative overflow-hidden">
        <div className="absolute inset-0 bg-gradient-to-br from-repaw-bg/90 via-repaw-bg/70 to-repaw-accent/40 pointer-events-none" />
        <div className="relative max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 py-16 lg:py-20 text-center">
          <h1 className="font-serif text-4xl sm:text-5xl font-bold text-repaw-dark max-w-3xl mx-auto">
            Join a passionate community of animal lovers and contribute to a meaningful cause.
          </h1>
          <div className="mt-8">{cta}</div>
        </div>
      </section>

      <section className="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 pt-16 lg:pt-20 pb-20">
        <div className="rounded-3xl overflow-hidden border border-repaw-hover/40 shadow-sm mb-12">
          <img src="/images/volunteer/img1.jpg" alt="Volunteers with pets" className="w-full h-72 object-cover" />
        </div>

        <div className="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-12">
          <div className="rounded-3xl overflow-hidden border border-repaw-hover/40 shadow-sm">
            <img src="/images/volunteer/img2.jpg" alt="Volunteer activity" className="w-full h-56 object-cover" />
          </div>
          <div className="rounded-3xl overflow-hidden border border-repaw-hover/40 shadow-sm">
            <img src="/images/volunteer/img3.jpg" alt="Volunteer activity" className="w-full h-56 object-cover" />
          </div>
        </div>

        <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
          <div className="bg-white/70 rounded-3xl p-8 border border-repaw-hover/40 shadow-sm">
            <h2 className="font-serif text-2xl font-bold text-repaw-dark mb-4">Volunteer Requirements</h2>
            <ul className="space-y-3 text-repaw-text/90">
              <li className="flex gap-2">
                <span className="mui-icon text-repaw-dark">check_circle</span> Compassion and respect for animals, with a commitment to their well-being.
              </li>
              <li className="flex gap-2">
                <span className="mui-icon text-repaw-dark">check_circle</span> Availability to commit to a regular schedule or specific event dates.
              </li>
              <li className="flex gap-2">
                <span className="mui-icon text-repaw-dark">check_circle</span> No age limit.
              </li>
              <li className="flex gap-2">
                <span className="mui-icon text-repaw-dark">check_circle</span> Want to learn and grow.
              </li>
            </ul>
          </div>

          <div className="bg-white/70 rounded-3xl p-8 border border-repaw-hover/40 shadow-sm">
            <h2 className="font-serif text-2xl font-bold text-repaw-dark mb-4">Volunteer Opportunities</h2>
            <ul className="space-y-3 text-repaw-text/90">
              <li className="flex gap-2">
                <span className="mui-icon text-repaw-dark">pets</span> Support adoption events and assist potential adopters in meeting our animals.
              </li>
              <li className="flex gap-2">
                <span className="mui-icon text-repaw-dark">pets</span> Help with socializing, grooming, and exercising the animals in preparation for adoption.
              </li>
              <li className="flex gap-2">
                <span className="mui-icon text-repaw-dark">pets</span> Assist with feeding, cleaning, and providing enrichment activities for the animals.
              </li>
            </ul>
          </div>

          <div className="bg-white/70 rounded-3xl p-8 border border-repaw-hover/40 shadow-sm">
            <h2 className="font-serif text-2xl font-bold text-repaw-dark mb-4">Benefits</h2>
            <ul className="space-y-3 text-repaw-text/90">
              <li className="flex gap-2">
                <span className="mui-icon text-repaw-dark">favorite</span> Gain valuable experience working with animals and developing essential skills.
              </li>
              <li className="flex gap-2">
                <span className="mui-icon text-repaw-dark">favorite</span> Join a passionate community of animal lovers and contribute to a meaningful cause.
              </li>
              <li className="flex gap-2">
                <span className="mui-icon text-repaw-dark">favorite</span> Personal fulfillment and the joy of seeing animals thrive in their new homes.
              </li>
            </ul>
          </div>
        </div>

        <div className="text-center mt-12">{cta}</div>
      </section>
    </div>
  )
}
