import { useState } from 'react'
import { Link } from 'react-router-dom'
import { PageHero } from '../../components/Shared'
import { useCommunityActions } from '../../hooks/useCommunity'

export default function Donate() {
  const { storeDonation } = useCommunityActions()
  const [type, setType] = useState<'cash' | 'in_kind'>('cash')
  const [form, setForm] = useState({
    donor_name: '',
    donor_email: '',
    amount: '',
    item_description: '',
    notes: '',
  })
  const [submitting, setSubmitting] = useState(false)
  const [success, setSuccess] = useState(false)
  const [error, setError] = useState<string | null>(null)

  async function handleSubmit() {
    setError(null)
    setSuccess(false)
    if (!form.donor_name.trim() || !form.donor_email.trim()) {
      setError('Please provide your name and email.')
      return
    }
    if (type === 'cash' && !form.amount.trim()) {
      setError('Please enter the amount you are donating.')
      return
    }
    if (type === 'in_kind' && !form.item_description.trim()) {
      setError('Please describe the items you are donating.')
      return
    }

    setSubmitting(true)
    try {
      await storeDonation.mutateAsync({
        donor_name: form.donor_name,
        donor_email: form.donor_email,
        type,
        amount: type === 'cash' ? Number(form.amount) : null,
        item_description: type === 'in_kind' ? form.item_description : null,
        date: new Date().toISOString().slice(0, 10),
        notes: form.notes || null,
      })
      setSuccess(true)
      setForm({ donor_name: '', donor_email: '', amount: '', item_description: '', notes: '' })
    } catch (e) {
      const err = e as { response?: { data?: { message?: string } } }
      setError(err.response?.data?.message ?? 'Failed to record your donation. Please try again.')
    } finally {
      setSubmitting(false)
    }
  }

  return (
    <div>
      <PageHero title="Give a little, help a lot." subtitle="Donate to support pets in need and help us give every animal a happy, healthy life." />

      <section className="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 pt-16 lg:pt-20 pb-20">
        <div className="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center mb-16">
          <div className="flex gap-4 justify-center lg:justify-start">
            <img src="/images/donDog.jpeg" alt="Dog in need" className="w-1/2 rounded-3xl object-cover aspect-[4/5] shadow-sm" />
            <img src="/images/donCat.jpeg" alt="Cat in need" className="w-1/2 rounded-3xl object-cover aspect-[4/5] shadow-sm" />
          </div>
          <div>
            <h2 className="font-serif text-3xl font-bold text-repaw-dark mb-4">Donate</h2>
            <p className="text-repaw-text/90 leading-relaxed">
              Welcome to our pet donation page, where you have the opportunity to make a positive impact
              on the lives of pets in need. At our organization, we are passionate about ensuring that
              every pet has access to the care and support they need to live a happy, healthy life.
              Unfortunately, many pets find themselves in difficult situations, whether they are homeless,
              sick, or in need of medical care that their owners cannot afford. That's where your donation
              can make a real difference.
            </p>
            <p className="mt-4 text-repaw-text/90 leading-relaxed">
              Through your generosity, we are able to provide essential resources to pets in need,
              including food, shelter, medical care, and other vital services. Every donation, no matter
              the size, makes a difference in the lives of pets and their families. Thank you for
              considering a donation to support our mission.
            </p>
          </div>
        </div>

        <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
          <div className="bg-white/70 rounded-3xl p-8 border border-repaw-hover/40 shadow-sm text-center">
            <span className="mui-icon text-4xl text-repaw-dark mb-4 block">account_balance</span>
            <h3 className="font-serif text-xl font-semibold text-repaw-dark mb-3">Bank Transfer</h3>
            <hr className="border-repaw-hover/50 mb-5" />
            <img src="/images/qrcode_bank.png" alt="Bank Transfer QR Code" className="mx-auto w-40 h-40 object-contain mb-5" />
            <p className="text-sm text-repaw-text/80">
              <strong>Account Number:</strong>
            </p>
            <p className="text-repaw-dark font-medium">0036-4007-0350</p>
            <p className="mt-3 text-sm text-repaw-text/80">
              <strong>Account Name:</strong>
            </p>
            <p className="text-repaw-dark font-medium">Repaw City</p>
          </div>

          <div className="bg-white/70 rounded-3xl p-8 border border-repaw-hover/40 shadow-sm text-center">
            <span className="mui-icon text-4xl text-repaw-dark mb-4 block">smartphone</span>
            <h3 className="font-serif text-xl font-semibold text-repaw-dark mb-3">GCash Transfer</h3>
            <hr className="border-repaw-hover/50 mb-5" />
            <img src="/images/qrcode_gcash.png" alt="GCash Transfer QR Code" className="mx-auto w-40 h-40 object-contain mb-5" />
            <p className="text-sm text-repaw-text/80">
              <strong>Account Number:</strong>
            </p>
            <p className="text-repaw-dark font-medium">0912-345-6789</p>
            <p className="mt-3 text-sm text-repaw-text/80">
              <strong>Account Name:</strong>
            </p>
            <p className="text-repaw-dark font-medium">Repaw City</p>
          </div>

          <div className="bg-white/70 rounded-3xl p-8 border border-repaw-hover/40 shadow-sm text-center flex flex-col">
            <span className="mui-icon text-4xl text-repaw-dark mb-4 block">payments</span>
            <h3 className="font-serif text-xl font-semibold text-repaw-dark mb-3">Cash</h3>
            <hr className="border-repaw-hover/50 mb-5" />
            <p className="text-sm text-repaw-text/80 leading-relaxed">
              Please{' '}
              <Link to="/about/contact" className="text-repaw-dark font-medium underline underline-offset-2 hover:text-repaw-text">
                let us know
              </Link>{' '}
              when would be a good time for you to drop by the shelter. We'll be very pleased to meet you and show some of our pets that we're helping!
            </p>
          </div>
        </div>

        <div className="mt-16 max-w-3xl mx-auto bg-white/70 rounded-3xl p-8 border border-repaw-hover/40 shadow-sm">
          <h2 className="font-serif text-3xl font-bold text-repaw-dark mb-2 text-center">Record a Donation</h2>
          <p className="text-center text-repaw-text/80 text-sm mb-8">
            Let us know about your donation so we can thank you and keep track of our support.
          </p>

          {success && (
            <div className="mb-4 rounded-xl border border-green-400/40 bg-green-50 px-4 py-3 text-sm text-green-800">
              Thank you for your generous donation! Your support makes a real difference.
            </div>
          )}
          {error && (
            <div className="mb-4 rounded-xl border border-repaw-danger/40 bg-red-50 px-4 py-3 text-sm text-repaw-danger">{error}</div>
          )}

          <div className="flex gap-4 mb-6">
            <button
              onClick={() => setType('cash')}
              className={`flex-1 rounded-full px-6 py-3 text-sm font-medium uppercase tracking-wide transition-colors ${type === 'cash' ? 'bg-repaw-text text-repaw-bg' : 'bg-repaw-hover/60 text-repaw-text hover:bg-repaw-hover'}`}
            >
              Cash
            </button>
            <button
              onClick={() => setType('in_kind')}
              className={`flex-1 rounded-full px-6 py-3 text-sm font-medium uppercase tracking-wide transition-colors ${type === 'in_kind' ? 'bg-repaw-text text-repaw-bg' : 'bg-repaw-hover/60 text-repaw-text hover:bg-repaw-hover'}`}
            >
              In-kind (items)
            </button>
          </div>

          <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label htmlFor="donor_name" className="block text-sm font-medium text-repaw-dark mb-1.5">Your Name</label>
              <input
                id="donor_name"
                type="text"
                value={form.donor_name}
                onChange={(e) => setForm((f) => ({ ...f, donor_name: e.target.value }))}
                className="w-full rounded-xl border border-repaw-hover bg-repaw-bg px-4 py-2.5 text-repaw-text focus:outline-none focus:ring-2 focus:ring-repaw-text"
              />
            </div>
            <div>
              <label htmlFor="donor_email" className="block text-sm font-medium text-repaw-dark mb-1.5">Your Email</label>
              <input
                id="donor_email"
                type="email"
                value={form.donor_email}
                onChange={(e) => setForm((f) => ({ ...f, donor_email: e.target.value }))}
                className="w-full rounded-xl border border-repaw-hover bg-repaw-bg px-4 py-2.5 text-repaw-text focus:outline-none focus:ring-2 focus:ring-repaw-text"
              />
            </div>
          </div>

          {type === 'cash' ? (
            <div className="mt-4">
              <label htmlFor="donor_amount" className="block text-sm font-medium text-repaw-dark mb-1.5">Amount (PHP)</label>
              <input
                id="donor_amount"
                type="number"
                min="0"
                step="0.01"
                value={form.amount}
                onChange={(e) => setForm((f) => ({ ...f, amount: e.target.value }))}
                className="w-full rounded-xl border border-repaw-hover bg-repaw-bg px-4 py-2.5 text-repaw-text focus:outline-none focus:ring-2 focus:ring-repaw-text"
              />
            </div>
          ) : (
            <div className="mt-4">
              <label htmlFor="donor_items" className="block text-sm font-medium text-repaw-dark mb-1.5">Items Donated</label>
              <input
                id="donor_items"
                type="text"
                value={form.item_description}
                onChange={(e) => setForm((f) => ({ ...f, item_description: e.target.value }))}
                placeholder="e.g. 5 bags of dog food, old blankets"
                className="w-full rounded-xl border border-repaw-hover bg-repaw-bg px-4 py-2.5 text-repaw-text focus:outline-none focus:ring-2 focus:ring-repaw-text"
              />
            </div>
          )}

          <div className="mt-4">
            <label htmlFor="donor_notes" className="block text-sm font-medium text-repaw-dark mb-1.5">Notes (optional)</label>
            <input
              id="donor_notes"
              type="text"
              value={form.notes}
              onChange={(e) => setForm((f) => ({ ...f, notes: e.target.value }))}
              className="w-full rounded-xl border border-repaw-hover bg-repaw-bg px-4 py-2.5 text-repaw-text focus:outline-none focus:ring-2 focus:ring-repaw-text"
            />
          </div>

          <button
            onClick={() => void handleSubmit()}
            disabled={submitting}
            className="mt-6 w-full bg-repaw-text text-repaw-bg rounded-full px-6 py-3 text-[15px] font-medium uppercase tracking-wide hover:bg-repaw-dark transition-colors duration-300 disabled:opacity-60"
          >
            {submitting ? 'Submitting...' : 'Submit Donation'}
          </button>
        </div>
      </section>
    </div>
  )
}
