import { useState } from 'react'
import { PageHero } from '../../components/Shared'
import { SHELTER, SHELTER_ADDRESS_INLINE } from '../../config'

export function Mission() {
  return (
    <div>
      <PageHero title="Our Mission" subtitle="What drives us, where we're headed, and the goals that guide every rescue." />
      <section className="max-w-3xl mx-auto px-6 sm:px-8 lg:px-12 pt-16 lg:pt-20 pb-20">
        <div className="bg-white/70 rounded-3xl p-8 sm:p-10 border border-repaw-hover/40 shadow-sm space-y-10">
          <div>
            <h2 className="font-serif text-2xl font-bold text-repaw-dark mb-3">Mission</h2>
            <p className="text-repaw-text/90 leading-relaxed">
              The mission of our pet shelter is to provide a safe, nurturing, and loving environment for animals in need. We are dedicated to rescuing and rehabilitating abandoned, abused, and neglected pets, and finding them forever homes. Our primary focus is on promoting animal welfare, responsible pet ownership, and reducing the number of homeless animals through adoption, education, and community outreach.
            </p>
          </div>
          <div>
            <h2 className="font-serif text-2xl font-bold text-repaw-dark mb-3">Vision</h2>
            <p className="text-repaw-text/90 leading-relaxed">
              Our vision is to create a world where every animal has a loving and caring home. We strive to be a leading advocate for animal welfare in our community, promoting compassion, empathy, and respect for all living beings. We envision a society where every pet is treated with kindness and provided with the care they deserve, resulting in a decrease in the number of animals suffering from neglect or homelessness.
            </p>
          </div>
          <div>
            <h2 className="font-serif text-2xl font-bold text-repaw-dark mb-3">Goals</h2>
            <ol className="list-decimal list-inside space-y-3 text-repaw-text/90 leading-relaxed">
              <li>
                <strong>Rescue and Rehabilitation:</strong> Our foremost goal is to rescue animals in need, provide them with necessary medical care, and rehabilitate them both physically and emotionally. We aim to give them a second chance at life and prepare them for successful adoptions.
              </li>
              <li>
                <strong>Adoption and Placement:</strong> We aim to find permanent, loving homes for our rescued animals through responsible adoption processes. Our goal is to match each animal with the most suitable family, ensuring a positive and lasting bond.
              </li>
              <li>
                <strong>Education and Outreach:</strong> We are committed to educating the community about responsible pet ownership, animal welfare, and the importance of spaying/neutering. Through workshops, seminars, and outreach programs, we strive to raise awareness and promote humane treatment of animals.
              </li>
              <li>
                <strong>Advocacy and Legislation:</strong> We strive to be a voice for animals in our community and beyond. We actively advocate for stronger animal protection laws, policies, and regulations to ensure the well-being of all animals.
              </li>
              <li>
                <strong>Volunteer and Staff Development:</strong> We value our dedicated volunteers and staff members and provide them with ongoing training and support. By fostering a positive work environment and encouraging personal growth, we can enhance our organization's effectiveness and ability to serve animals in need.
              </li>
            </ol>
            <p className="mt-4 text-repaw-text/90 leading-relaxed">
              These goals collectively contribute to our mission and vision, guiding our efforts to make a meaningful difference in the lives of animals and the community we serve.
            </p>
          </div>
        </div>
      </section>
    </div>
  )
}

export function SuccessStories() {
  return (
    <div>
      <PageHero title="From Strays to Stars" subtitle="Heartwarming stories of shelter pets finding forever homes with the help of rePaw City!" />
      <section className="max-w-5xl mx-auto px-6 sm:px-8 lg:px-12 pt-16 lg:pt-20 pb-20 space-y-16">
        <div className="grid grid-cols-1 lg:grid-cols-2 gap-10 items-center">
          <div className="rounded-3xl overflow-hidden border border-repaw-hover/40 shadow-sm">
            <img src="/images/img1.jpg" alt="Lucky" className="w-full aspect-[4/3] object-cover" />
          </div>
          <div className="space-y-6">
            <div className="bg-white/70 rounded-3xl p-7 border border-repaw-hover/40 shadow-sm">
              <h2 className="font-serif text-2xl font-bold text-repaw-dark mb-3">"The Lucky Stray"</h2>
              <p className="text-repaw-text/90 leading-relaxed">
                In a bustling city, there was a stray dog named Lucky. Lucky was always on the streets, searching for food and shelter. One rainy day, he stumbled upon a pet shelter where the staff took him in, providing him with food, a bath, and a comfortable bed. They took care of him until he was back in good condition. Lucky slowly adjusted to his new surroundings, grateful for the kindness shown by the staff.
              </p>
            </div>
            <div className="bg-white/70 rounded-3xl p-7 border border-repaw-hover/40 shadow-sm">
              <h2 className="font-serif text-2xl font-bold text-repaw-dark mb-3">Lucky</h2>
              <p className="text-repaw-text/90 leading-relaxed">
                He started to play with other dogs out there, making a lot of pawfriends! As time passed, he found a loving family who adopted him, offering a forever home. Now, Lucky spends his days playing with his new family, spreading happiness wherever he goes.
              </p>
            </div>
          </div>
        </div>

        <div className="grid grid-cols-1 lg:grid-cols-3 gap-8 items-center">
          <div className="lg:col-span-1 rounded-3xl overflow-hidden border border-repaw-hover/40 shadow-sm">
            <img src="/images/img2.jpg" alt="Bella" className="w-full aspect-square object-cover" />
            <h2 className="font-serif text-xl font-bold text-repaw-dark text-center py-3">Bella</h2>
          </div>
          <div className="lg:col-span-2 bg-white/70 rounded-3xl p-7 border border-repaw-hover/40 shadow-sm">
            <h2 className="font-serif text-2xl font-bold text-repaw-dark mb-3">"Rebuilding Trust"</h2>
            <p className="text-repaw-text/90 leading-relaxed">
              Bella, a once-beloved cat, found herself in a pet shelter after her owner passed away. Confused and heartbroken, Bella became wary of humans. The shelter staff understood her trauma and patiently worked to rebuild her trust. Through gentle interactions, soft-spoken words, and consistent care, Bella began to open up. Slowly, Bella began to trust humans again, purring with delight whenever approached. Then, a kind-hearted woman named Emily visited the shelter and instantly fell in love with Bella's gentle nature. Adopting her, Emily provided Bella with a forever home filled with warmth and affection. Bella now spends her days curled up on Emily's lap, grateful for the second chance at a happy life.
            </p>
          </div>
        </div>

        <div className="grid grid-cols-1 lg:grid-cols-3 gap-8 items-center">
          <div className="lg:col-span-2 bg-white/70 rounded-3xl p-7 border border-repaw-hover/40 shadow-sm">
            <h2 className="font-serif text-2xl font-bold text-repaw-dark mb-3">"From Fear to Friendship"</h2>
            <p className="text-repaw-text/90 leading-relaxed">
              Meet Max, a timid and frightened dog rescued from an abusive situation. Upon arriving at the pet shelter, Max was terrified of everything and everyone around him. The patient shelter staff worked tirelessly to help him overcome his fears, introducing him to friendly dogs and providing a safe space for healing. Over time, Max's trust in humans grew, his tail wagging in delight upon their approach. One day, a loving couple visited the shelter and instantly connected with Max's gentle eyes.
            </p>
            <p className="mt-4 text-repaw-text/90 leading-relaxed">
              They made the decision to adopt him, promising the love and care he deserved. Today, Max is a happy and confident dog, enjoying long walks and endless belly rubs with his new family.
            </p>
          </div>
          <div className="lg:col-span-1 rounded-3xl overflow-hidden border border-repaw-hover/40 shadow-sm">
            <img src="/images/img3.jpg" alt="Max" className="w-full aspect-square object-cover" />
            <h2 className="font-serif text-xl font-bold text-repaw-dark text-center py-3">Max</h2>
          </div>
        </div>
      </section>
    </div>
  )
}

const faqs = [
  {
    q: 'What is the purpose of rePaw City?',
    a: 'rePaw City is dedicated to providing a safe and caring environment for abandoned, neglected, or surrendered pets. Our main goal is to rehabilitate and rehome these animals, ensuring they find loving and permanent homes.',
  },
  {
    q: 'How can I adopt a pet from your shelter?',
    a: 'To adopt a pet from our shelter, please book an appointment first or check our website for available animals.',
  },
  {
    q: 'Do you have a policy for screening potential adopters?',
    a: 'Yes, we have a screening process to ensure that our animals are placed in suitable and loving homes. The process may involve an application, an interview, reference checks, and sometimes a home visit. We aim to match the needs and personalities of our animals with the lifestyle and capabilities of potential adopters.',
  },
  {
    q: 'Can I surrender my pet to your shelter?',
    a: 'Yes, we accept owner surrenders, but we encourage you to contact us in advance to discuss your situation. Surrendering a pet is a serious decision, and we want to ensure we have the necessary resources to accommodate your pet\'s needs.',
  },
  {
    q: 'How can I support rePaw City?',
    a: 'There are several ways to support our shelter: you can donate, volunteer, or spread the word about our cause. Visit our Donate page to contribute or reach out to us about volunteering opportunities.',
  },
]

export function FAQ() {
  const [open, setOpen] = useState<number | null>(0)

  return (
    <div>
      <PageHero title="Frequently Asked Questions" subtitle="Answers to the questions we hear most often from adopters, donors, and volunteers." />
      <section className="max-w-3xl mx-auto px-6 sm:px-8 lg:px-12 pt-16 lg:pt-20 pb-20">
        <div className="space-y-4">
          {faqs.map((item, i) => (
            <div key={i} className="bg-white/70 rounded-2xl border border-repaw-hover/40 shadow-sm overflow-hidden">
              <button
                onClick={() => setOpen(open === i ? null : i)}
                className="w-full flex items-center justify-between gap-4 cursor-pointer px-6 py-5 font-serif text-lg font-semibold text-repaw-dark text-left"
              >
                {item.q}
                <span className={`mui-icon text-2xl transition-transform ${open === i ? 'rotate-180' : ''}`}>expand_more</span>
              </button>
              {open === i && <div className="px-6 pb-5 text-repaw-text/90 leading-relaxed">{item.a}</div>}
            </div>
          ))}
        </div>
      </section>
    </div>
  )
}

export function Contact() {
  return (
    <div>
      <PageHero title="Contact Us" subtitle="Have a question, want to help, or ready to meet a pet? We'd love to hear from you." />
      <section className="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 pt-16 lg:pt-20 pb-20">
        <div className="grid grid-cols-1 lg:grid-cols-2 gap-10">
          <div className="bg-white/70 rounded-3xl p-8 sm:p-10 border border-repaw-hover/40 shadow-sm">
            <p className="text-repaw-text/90 leading-relaxed mb-6">
              We're thrilled that you're interested in connecting with us. If you have questions or feedback, don't hesitate to reach out. Our team is ready to assist you and provide the information you need. We look forward to connecting with you!
            </p>
            <hr className="border-repaw-hover/50 mb-6" />
            <div className="space-y-4">
              <p className="flex items-center gap-3 text-repaw-text/90">
                <span className="mui-icon text-repaw-dark text-2xl">call</span>
                <a href={`tel:${SHELTER.phone.replace(/\s/g, '')}`} className="hover:text-repaw-dark transition-colors">{SHELTER.phone}</a>
              </p>
              <p className="flex items-center gap-3 text-repaw-text/90">
                <span className="mui-icon text-repaw-dark text-2xl">mail</span>
                <a href={`mailto:${SHELTER.email}`} className="hover:text-repaw-dark transition-colors">{SHELTER.email}</a>
              </p>
              <p className="flex items-start gap-3 text-repaw-text/90">
                <span className="mui-icon text-repaw-dark text-2xl mt-0.5">place</span>
                <span>{SHELTER_ADDRESS_INLINE}</span>
              </p>
            </div>
          </div>
          <div className="rounded-3xl overflow-hidden border border-repaw-hover/40 shadow-sm min-h-[320px]">
            <iframe
              title="rePaw City location"
              src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d619.8446481048363!2d120.4906345!3d14.862239!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3396f20a82a1a269%3A0x0!2s14%C2%B051&#39;44.1%22N%20120%C2%B029&#39;26.3%22E!5e0!3m2!1sen!2sus!4v1626317845211!5m2!1sen!2sus"
              width="100%"
              height="100%"
              style={{ border: 0, minHeight: 320 }}
              allowFullScreen
              loading="lazy"
            />
          </div>
        </div>
      </section>
    </div>
  )
}


