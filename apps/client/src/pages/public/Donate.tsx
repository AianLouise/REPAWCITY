import { Link } from 'react-router-dom'
import { PageHero } from '@repaw/ui'

export default function Donate() {
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
      </section>
    </div>
  )
}
