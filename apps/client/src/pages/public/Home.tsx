import { Link } from 'react-router-dom'
import PetCard from '../../components/PetCard'
import { Empty, Loading } from '@repaw/ui'
import { useFeaturedPets, usePets } from '../../hooks/useContent'
import { SHELTER } from '@repaw/config'

const adoptionSteps = [
  {
    icon: 'event_available',
    title: 'Book a visit',
    text: 'Choose an open date and a morning or afternoon session on our booking page.',
  },
  {
    icon: 'how_to_reg',
    title: 'Meet & apply',
    text: 'Spend time with the pet at the shelter, then submit an adoption application for review.',
  },
  {
    icon: 'favorite',
    title: 'Take them home',
    text: 'Once approved, complete the handover and bring your new companion home.',
  },
]

export default function Home() {
  const { data: featured, isLoading: loadingFeatured } = useFeaturedPets()
  const { data: allPets } = usePets({ per_page: 1 })

  const availableCount = allPets?.meta.total

  return (
    <div>
      <section className="relative overflow-hidden">
        <div className="absolute inset-0 bg-gradient-to-br from-repaw-bg/90 via-repaw-bg/70 to-repaw-accent/40 pointer-events-none" />
        <div className="relative max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 py-20 lg:py-28">
          <div className="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">
            <div className="max-w-3xl">
              <h1 className="font-serif text-4xl sm:text-5xl lg:text-6xl font-bold text-repaw-dark leading-tight">
                Meet the rescues<br />
                <span className="text-repaw-text">of {SHELTER.address.line2.replace(' City, Metro Manila', '').replace(', Metro Manila', '')}.</span>
              </h1>
              <p className="mt-6 text-lg text-repaw-text/90 leading-relaxed max-w-xl">
                rePaw City is a shelter in {SHELTER.address.line2} helping stray dogs and cats get healthy, vaccinated, and adopted. Our pets are seen by appointment only.
              </p>
              <div className="mt-9 flex flex-wrap gap-4">
                <Link
                  to="/adopt"
                  className="inline-flex items-center justify-center gap-2 rounded-full bg-repaw-text text-repaw-bg px-7 py-3 text-[15px] font-medium uppercase tracking-wide hover:bg-repaw-dark transition-colors duration-300"
                >
                  <span className="mui-icon text-[20px]">pets</span> Adopt a Pet
                </Link>
                <Link
                  to="/donate"
                  className="inline-flex items-center justify-center gap-2 rounded-full bg-repaw-accent text-repaw-dark px-7 py-3 text-[15px] font-medium uppercase tracking-wide hover:bg-repaw-dark hover:text-repaw-accent transition-colors duration-300"
                >
                  <span className="mui-icon text-[20px]">favorite</span> Donate
                </Link>
              </div>

              {availableCount !== undefined && (
                <dl className="mt-7 inline-flex items-center gap-3 rounded-full bg-white/70 border border-repaw-hover/40 px-5 py-3 shadow-sm">
                  <dt className="sr-only">Available pets</dt>
                  <dd className="font-serif text-2xl font-bold text-repaw-dark leading-none">{availableCount}</dd>
                  <dd className="text-sm text-repaw-text/80 leading-snug whitespace-nowrap">
                    pets ready for adoption right now
                  </dd>
                </dl>
              )}

              <ul className="mt-5 flex flex-wrap gap-x-6 gap-y-2 text-sm text-repaw-text/80">
                <li className="inline-flex items-center gap-2">
                  <span className="mui-icon text-[18px] text-repaw-text">vaccines</span> Vaccinated &amp; vet-checked
                </li>
                <li className="inline-flex items-center gap-2">
                  <span className="mui-icon text-[18px] text-repaw-text">event_available</span> Seen by appointment only
                </li>
                <li className="inline-flex items-center gap-2">
                  <span className="mui-icon text-[18px] text-repaw-text">place</span> Based in {SHELTER.address.line2}
                </li>
              </ul>
            </div>

            <div className="relative hidden lg:block">
              <img
                src="https://images.unsplash.com/photo-1548199973-03cce0bbc87b?q=80&w=1200&auto=format&fit=crop"
                alt="Rescued dogs at rePaw City shelter"
                className="w-full h-[420px] xl:h-[460px] object-cover rounded-[3rem] shadow-lg border-4 border-white/70"
              />
              <div className="absolute -bottom-6 -left-6 bg-white/90 backdrop-blur rounded-2xl border border-repaw-hover/40 px-5 py-4 shadow-lg">
                <p className="font-serif text-2xl font-bold text-repaw-dark leading-none">{SHELTER.address.line2}</p>
                <p className="mt-1 text-sm text-repaw-text/80">rescues rehomed with love</p>
              </div>
            </div>

            <div className="lg:hidden">
              <img
                src="https://images.unsplash.com/photo-1548199973-03cce0bbc87b?q=80&w=1200&auto=format&fit=crop"
                alt="Rescued dogs at rePaw City shelter"
                className="w-full aspect-[16/10] object-cover rounded-[2rem] shadow-lg border-4 border-white/70"
              />
            </div>
          </div>
        </div>
      </section>

      <section className="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 py-16">
        <div className="text-center mb-12">
          <h2 className="font-serif text-3xl sm:text-4xl font-bold text-repaw-dark">How adoption works</h2>
          <p className="mt-3 text-repaw-text/80 max-w-2xl mx-auto">Three steps from your first visit to taking a pet home.</p>
        </div>
        <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
          {adoptionSteps.map((step, i) => (
            <div key={step.title} className="relative bg-white/70 rounded-3xl p-8 border border-repaw-hover/40 shadow-sm">
              <span className="absolute top-6 right-7 font-serif text-5xl font-bold text-repaw-hover/50" aria-hidden="true">
                {i + 1}
              </span>
              <div className="w-14 h-14 rounded-2xl bg-repaw-accent text-repaw-dark flex items-center justify-center mb-5">
                <span className="mui-icon text-3xl">{step.icon}</span>
              </div>
              <h3 className="font-serif text-xl font-semibold text-repaw-dark mb-2">{step.title}</h3>
              <p className="text-repaw-text/80 text-sm leading-relaxed">{step.text}</p>
            </div>
          ))}
        </div>
        <p className="text-center mt-8">
          <Link to="/adopt" className="inline-flex items-center gap-1 text-repaw-dark font-medium uppercase tracking-wide text-sm hover:text-repaw-text transition-colors">
            Browse available pets <span className="mui-icon text-[18px]">arrow_forward</span>
          </Link>
        </p>
      </section>

      <section className="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 py-16">
        <div className="text-center mb-12">
          <h2 className="font-serif text-3xl sm:text-4xl font-bold text-repaw-dark">How You Can Help</h2>
          <p className="mt-3 text-repaw-text/80 max-w-2xl mx-auto">Three ways to support the rescues at our shelter.</p>
        </div>
        <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
          <HelpCard to="/adopt" icon="home" title="Adopt" text="Meet our rescued dogs and cats and bring one home from the shelter." accent="bg-repaw-accent text-repaw-dark" />
          <HelpCard to="/donate" icon="volunteer_activism" title="Donate" text="Your support covers food, medicine, and vet care for our rescues." accent="bg-repaw-text text-repaw-bg" />
          <HelpCard to="/volunteer" icon="group" title="Volunteer" text="Help us walk, feed, and care for the animals at the shelter." accent="bg-repaw-hover text-repaw-dark" />
        </div>
      </section>

      <section className="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 pb-16">
        <div className="text-center mb-12">
          <h2 className="font-serif text-3xl sm:text-4xl font-bold text-repaw-dark">Featured Pets</h2>
          <p className="mt-3 text-repaw-text/80 max-w-2xl mx-auto">A few of the rescues currently looking for adopters.</p>
        </div>
        {loadingFeatured ? (
          <Loading />
        ) : featured && featured.data.length > 0 ? (
          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            {featured.data.map((pet) => (
              <PetCard key={pet.id} pet={pet} />
            ))}
          </div>
        ) : (
          <Empty message="No featured pets available right now." />
        )}
      </section>

      <section className="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 pb-20">
        <div className="relative overflow-hidden rounded-[3rem] bg-repaw-dark text-repaw-bg px-8 py-14 lg:px-16 text-center">
          <h2 className="font-serif text-3xl sm:text-4xl font-bold relative z-10">Come meet our rescues.</h2>
          <p className="mt-4 text-repaw-bg/80 max-w-2xl mx-auto relative z-10">Book a morning or afternoon visit at our shelter in {SHELTER.address.line2}.</p>
          <Link
            to="/adopt"
            className="mt-8 relative z-10 inline-flex items-center justify-center gap-2 rounded-full bg-repaw-accent text-repaw-dark px-7 py-3 text-[15px] font-medium uppercase tracking-wide hover:bg-repaw-dark hover:text-repaw-accent transition-colors duration-300"
          >
            <span className="mui-icon text-[20px]">pets</span> See Available Pets
          </Link>
        </div>
      </section>
    </div>
  )
}

function HelpCard({ to, icon, title, text, accent }: { to: string; icon: string; title: string; text: string; accent: string }) {
  return (
    <Link
      to={to}
      className="group bg-white/70 rounded-3xl p-8 shadow-sm hover:shadow-xl transition-shadow duration-300 border border-repaw-hover/40 block"
    >
      <div className={`w-14 h-14 rounded-2xl flex items-center justify-center mb-5 transition-transform duration-300 group-hover:-translate-y-1 ${accent}`}>
        <span className="mui-icon text-3xl">{icon}</span>
      </div>
      <h3 className="font-serif text-xl font-semibold text-repaw-dark mb-2">{title}</h3>
      <p className="text-repaw-text/80 text-sm leading-relaxed">{text}</p>
    </Link>
  )
}
