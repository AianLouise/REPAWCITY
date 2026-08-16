import { Link } from 'react-router-dom'
import PetCard from '../../components/PetCard'
import { Empty, Loading } from '../../components/Shared'
import { useFeaturedPets } from '../../hooks/useContent'

export default function Home() {
  const { data, isLoading } = useFeaturedPets()

  return (
    <div>
      <section className="relative overflow-hidden">
        <div className="absolute inset-0 bg-gradient-to-br from-repaw-bg/90 via-repaw-bg/70 to-repaw-accent/40 pointer-events-none" />
        <div className="relative max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 py-20 lg:py-28">
          <div className="max-w-3xl">
            <span className="inline-flex items-center gap-2 rounded-full bg-repaw-accent/80 px-4 py-1.5 text-sm font-medium text-repaw-dark mb-6">
              <span className="mui-icon text-[18px]">pets</span> Adopt · Donate · Volunteer
            </span>
            <h1 className="font-serif text-4xl sm:text-5xl lg:text-6xl font-bold text-repaw-dark leading-tight">
              Every pet deserves a<br />
              <span className="text-repaw-text">forever home.</span>
            </h1>
            <p className="mt-6 text-lg text-repaw-text/90 leading-relaxed max-w-xl">
              rePaw City connects rescuable dogs and cats across the Philippines with loving families. Browse adoptable pets, support our mission, or lend a hand as a volunteer.
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

            <dl className="mt-12 grid grid-cols-3 gap-6 max-w-md">
              <Stat value="500+" label="Pets Rehomed" />
              <Stat value="120" label="Active Volunteers" />
              <Stat value="15" label="Partner Shelters" />
            </dl>
          </div>
        </div>
      </section>

      <section className="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 py-16">
        <div className="text-center mb-12">
          <h2 className="font-serif text-3xl sm:text-4xl font-bold text-repaw-dark">How You Can Help</h2>
          <p className="mt-3 text-repaw-text/80 max-w-2xl mx-auto">Three simple ways to make a life-changing difference for an animal in need.</p>
        </div>
        <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
          <HelpCard to="/adopt" icon="home" title="Adopt" text="Give a rescue dog or cat the loving home they've been waiting for." accent="bg-repaw-accent text-repaw-dark" />
          <HelpCard to="/donate" icon="volunteer_activism" title="Donate" text="Fund food, medical care, and shelter for pets on their road to recovery." accent="bg-repaw-text text-repaw-bg" />
          <HelpCard to="/volunteer" icon="group" title="Volunteer" text="Lend your time and skills to walk, feed, and care for our furry friends." accent="bg-repaw-hover text-repaw-dark" />
        </div>
      </section>

      <section className="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 pb-16">
        <div className="text-center mb-12">
          <h2 className="font-serif text-3xl sm:text-4xl font-bold text-repaw-dark">Featured Pets</h2>
          <p className="mt-3 text-repaw-text/80 max-w-2xl mx-auto">Meet some of our pets who are ready for their forever homes.</p>
        </div>
        {isLoading ? (
          <Loading />
        ) : data && data.data.length > 0 ? (
          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            {data.data.map((pet) => (
              <PetCard key={pet.id} pet={pet} />
            ))}
          </div>
        ) : (
          <Empty message="No featured pets available right now." />
        )}
      </section>

      <section className="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 pb-20">
        <div className="relative overflow-hidden rounded-[3rem] bg-repaw-dark text-repaw-bg px-8 py-14 lg:px-16 text-center">
          <h2 className="font-serif text-3xl sm:text-4xl font-bold relative z-10">Ready to meet your new best friend?</h2>
          <p className="mt-4 text-repaw-bg/80 max-w-2xl mx-auto relative z-10">Browse our adoptable pets today and start your journey toward a fuller, happier home.</p>
          <Link
            to="/adopt"
            className="mt-8 relative z-10 inline-flex items-center justify-center gap-2 rounded-full bg-repaw-accent text-repaw-dark px-7 py-3 text-[15px] font-medium uppercase tracking-wide hover:bg-repaw-dark hover:text-repaw-accent transition-colors duration-300"
          >
            <span className="mui-icon text-[20px]">pets</span> Start Adopting
          </Link>
        </div>
      </section>
    </div>
  )
}

function Stat({ value, label }: { value: string; label: string }) {
  return (
    <div>
      <dt className="font-serif text-3xl font-bold text-repaw-dark">{value}</dt>
      <dd className="text-sm text-repaw-text/80 mt-1">{label}</dd>
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
