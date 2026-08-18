import { useState } from 'react'
import { Link } from 'react-router-dom'
import { useAuthStore } from '@repaw/auth'
import { useCommunityActions, useMyVolunteer } from '@repaw/api-client'

export default function Volunteer() {
  const user = useAuthStore((s) => s.user)
  const { data: myVolunteer } = useMyVolunteer()
  const { applyVolunteer } = useCommunityActions()

  const [availability, setAvailability] = useState<string[]>([])
  const [skills, setSkills] = useState('')
  const [interests, setInterests] = useState('')
  const [submitting, setSubmitting] = useState(false)
  const [submitted, setSubmitted] = useState(false)
  const [error, setError] = useState<string | null>(null)

  const alreadyApplied = !!myVolunteer

  function toggleAvailability(day: string) {
    setAvailability((a) => (a.includes(day) ? a.filter((d) => d !== day) : [...a, day]))
  }

  async function handleApply() {
    setError(null)
    if (!skills.trim()) {
      setError('Please tell us about your skills.')
      return
    }
    setSubmitting(true)
    try {
      await applyVolunteer.mutateAsync({
        availability,
        skills: skills.trim(),
        interests: interests.trim() || undefined,
      })
      setSubmitted(true)
    } catch (e) {
      const err = e as { response?: { data?: { message?: string } } }
      setError(err.response?.data?.message ?? 'Failed to submit your application.')
    } finally {
      setSubmitting(false)
    }
  }

  const cta = user ? (
    <Link
      to="/book"
      target="_blank"
      rel="noreferrer"
      className="inline-flex items-center justify-center gap-2 rounded-full bg-repaw-text text-repaw-bg px-7 py-3 text-[15px] font-medium uppercase tracking-wide hover:bg-repaw-dark transition-colors duration-300"
    >
      <span className="mui-icon text-[20px]">volunteer_activism</span> Book a Volunteer Visit
    </Link>
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
              <li className="flex gap-3">
                <span className="w-1.5 h-1.5 bg-repaw-dark rounded-full mt-2 shrink-0" /> Support adoption events and assist potential adopters in meeting our animals.
              </li>
              <li className="flex gap-3">
                <span className="w-1.5 h-1.5 bg-repaw-dark rounded-full mt-2 shrink-0" /> Help with socializing, grooming, and exercising the animals in preparation for adoption.
              </li>
              <li className="flex gap-3">
                <span className="w-1.5 h-1.5 bg-repaw-dark rounded-full mt-2 shrink-0" /> Assist with feeding, cleaning, and providing enrichment activities for the animals.
              </li>
            </ul>
          </div>

          <div className="bg-white/70 rounded-3xl p-8 border border-repaw-hover/40 shadow-sm">
            <h2 className="font-serif text-2xl font-bold text-repaw-dark mb-4">Benefits</h2>
            <ul className="space-y-3 text-repaw-text/90">
              <li className="flex gap-3">
                <span className="w-1.5 h-1.5 bg-repaw-dark rounded-full mt-2 shrink-0" /> Gain valuable experience working with animals and developing essential skills.
              </li>
              <li className="flex gap-3">
                <span className="w-1.5 h-1.5 bg-repaw-dark rounded-full mt-2 shrink-0" /> Join a passionate community of animal lovers and contribute to a meaningful cause.
              </li>
              <li className="flex gap-3">
                <span className="w-1.5 h-1.5 bg-repaw-dark rounded-full mt-2 shrink-0" /> Personal fulfillment and the joy of seeing animals thrive in their new homes.
              </li>
            </ul>
          </div>
        </div>

        <div className="mt-16 max-w-3xl mx-auto bg-white/70 rounded-3xl p-8 border border-repaw-hover/40 shadow-sm">
          <h2 className="font-serif text-3xl font-bold text-repaw-dark mb-2 text-center">Apply to Volunteer</h2>
          <p className="text-center text-repaw-text/80 text-sm mb-8">Tell us a little about yourself and how you'd like to help.</p>

          {submitted || alreadyApplied ? (
            <div className="rounded-xl border border-green-400/40 bg-green-50 px-4 py-4 text-sm text-green-800 text-center">
              {alreadyApplied && !submitted
                ? 'You have already submitted a volunteer application. Our team will review it soon!'
                : 'Thank you for applying! Our team will review your application and get in touch.'}
            </div>
          ) : !user ? (
            <div className="text-center">
              <p className="text-repaw-text/80 mb-4">Please log in to submit a volunteer application.</p>
              <Link to="/login" className="inline-block bg-repaw-text text-repaw-bg rounded-full px-6 py-3 text-sm font-medium uppercase tracking-wide hover:bg-repaw-dark transition-colors">
                Log in to Apply
              </Link>
            </div>
          ) : (
            <>
              {error && (
                <div className="mb-4 rounded-xl border border-repaw-danger/40 bg-red-50 px-4 py-3 text-sm text-repaw-danger">{error}</div>
              )}

              <div className="mb-5">
                <label className="block text-sm font-medium text-repaw-dark mb-2">Availability</label>
                <div className="flex flex-wrap gap-2">
                  {['Weekdays Morning', 'Weekdays Afternoon', 'Weekends Morning', 'Weekends Afternoon'].map((day) => (
                    <button
                      key={day}
                      type="button"
                      onClick={() => toggleAvailability(day)}
                      className={`rounded-full px-4 py-2 text-sm font-medium transition-colors ${availability.includes(day) ? 'bg-repaw-text text-repaw-bg' : 'bg-repaw-hover/60 text-repaw-text hover:bg-repaw-hover'}`}
                    >
                      {day}
                    </button>
                  ))}
                </div>
              </div>

              <div className="mb-4">
                <label className="block text-sm font-medium text-repaw-dark mb-1.5">Skills</label>
                <input
                  type="text"
                  value={skills}
                  onChange={(e) => setSkills(e.target.value)}
                  placeholder="e.g. Dog handling, cleaning, photography"
                  className="w-full rounded-xl border border-repaw-hover bg-repaw-bg px-4 py-2.5 text-repaw-text focus:outline-none focus:ring-2 focus:ring-repaw-text"
                />
              </div>

              <div className="mb-6">
                <label className="block text-sm font-medium text-repaw-dark mb-1.5">Interests (optional)</label>
                <input
                  type="text"
                  value={interests}
                  onChange={(e) => setInterests(e.target.value)}
                  placeholder="e.g. Walking dogs, adoption events"
                  className="w-full rounded-xl border border-repaw-hover bg-repaw-bg px-4 py-2.5 text-repaw-text focus:outline-none focus:ring-2 focus:ring-repaw-text"
                />
              </div>

              <button
                onClick={() => void handleApply()}
                disabled={submitting}
                className="w-full bg-repaw-text text-repaw-bg rounded-full px-6 py-3 text-[15px] font-medium uppercase tracking-wide hover:bg-repaw-dark transition-colors duration-300 disabled:opacity-60"
              >
                {submitting ? 'Submitting...' : 'Submit Application'}
              </button>
            </>
          )}
        </div>

        <div className="text-center mt-12">{cta}</div>
      </section>
    </div>
  )
}
