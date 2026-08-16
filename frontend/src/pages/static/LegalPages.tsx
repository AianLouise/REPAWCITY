import { PageHero } from '../../components/Shared'

const references = [
  { text: 'PAWS (n.d). STRAYS: Why are there stray cats and dogs?', href: 'http://pawsphilippines.weebly.com/strays-why-are-there-stray-cats-and-dogs.html' },
  { text: 'Fund, M. (n.d.). The History of the No-Kill Movement.', href: 'https://www.maddiesfund.org/the-history-of-the-no-kill-movement.htm' },
  { text: 'Perez, A.R. (2021) The Mourning After: Dealing with pet euthanasia.', href: 'https://www.gmanetwork.com/news/lifestyle/familyandrelationships/809185/the-mourning-after-dealing-with-pet-euthanasia/story/' },
  { text: 'Smith, J. (2022). Cat playing with a ball [Digital image].', href: 'https://www.cuteanimals.com/cat-image' },
  { text: 'Elnur_ (2022). Happy family with golden retriever dog — Photo.', href: 'https://depositphotos.com/stock-photos/childfree.html' },
  { text: 'Brewer, J. (2018). Himalayan Sheepdog.', href: 'https://wagwalking.com/breed/himalayan-sheepdog' },
  { text: 'David, W. (2019). Bloodhound expert and her dogs have found hundreds of lost pets in the worst conditions.', href: 'https://www.ocregister.com/2019/05/01/bloodhound-expert-and-her-dogshave-found-hundreds-of-lost-pets-in-the-worst-conditions/amp/' },
  { text: 'Ed, M. (2023). How Do Stray Cats Survive Winter? The Cold Truth & Tips on How to Help.', href: 'https://www.hepper.com/how-do-stray-cats-survive-winter/' },
  { text: 'Camille, T. (2023). Animal Shelter Celebrates After it Saved 1,000 Dogs in 3 Months.', href: 'https://www.yahoo.com/lifestyle/animal-shelter-celebrates-saved-1-170653541.html' },
  { text: 'Stuant, W. (2018). Brave Todd got a muzzle of venom after protecting his owner.', href: 'https://www.google.com/amp/s/www.express.co.uk/news/nature/983467/dognews-puppy-hero-saving-owner-rattlesnake-bite/amp/' },
  { text: 'Hayes, E. (2014). Meet the dogs and cats that help heal sick Portland kids (Photos).', href: 'https://www.bizjournals.com/portland/blog/health-care-inc/2014/02/howchanel-parker-and-other-furry.html' },
  { text: 'Ely, T. (2019). VOLUNTEERS NEEDED FOR ELY ANIMAL SHELTER.', href: 'https://elynews.com/2019/07/19/volunteers-needed-for-ely-animal-shelter/' },
  { text: 'Washington, J. (2017). Pet Donations.', href: 'https://www.seminolecountyfl.gov/departments-services/countymanagers-office/animal-services/donations.stml' },
  { text: 'Brulliard, K. (2019). What makes dogs so special and successful? Love.', href: 'https://www.washingtonpost.com/science/2019/09/25/what-makes-dogsso-special-successful-love/' },
  { text: 'Mangulsone, K. (2015). white dog and gray cat hugging each other on grass.', href: 'https://unsplash.com/photos/9gz3wfHr65U' },
  { text: 'Andrew, S. (2021). a dog and a cat laying in the grass.', href: 'https://unsplash.com/photos/ouo1hbizWwo' },
  { text: 'Ward, E. (2018). photo of man hugging tan dog.', href: 'https://unsplash.com/photos/ouo1hbizWwo' },
  { text: 'Nickson, R. (2018). white dogs on red sofa.', href: 'https://unsplash.com/photos/44Ca9zfFXjg' },
  { text: 'Nelson, M. (2017). Four dogs on park.', href: 'https://unsplash.com/photos/aI3EBLvcyu4' },
  { text: 'Matu, Y. (2017). shallow focus photography of white and brown cat.', href: 'https://unsplash.com/photos/GtwiBmtJvaU' },
  { text: 'Leohoho, A. (2020). Woman kissing brown short coated dog.', href: 'https://unsplash.com/photos/58MUPAelJm0' },
  { text: 'Laylor, S. (2020). Golden Retriever waiting at the front door. Adobe Stock Photo.', href: 'https://www.ourcompanions.org/the-difference-between-stay-and-wait/' },
  { text: 'Free Pnging.com (n.d.) Stray Dog Png 5.', href: 'https://freepngimg.com/png/9870-dog-png-5' },
  { text: 'iWitness News (2017) A sad tale — abandoned animals in misery.', href: 'https://www.iwnsvg.com/2017/06/23/a-sad-tale-abandoned-animals-inmisery/' },
  { text: 'The Foundation For Homeless Cats (2020) A Stray Cats Prayer.', href: 'https://www.facebook.com/story.php?story_fbid=3255179077843092&id=180016842026013' },
]

export function References() {
  return (
    <div>
      <PageHero title="References" subtitle="" />
      <section className="max-w-3xl mx-auto px-6 sm:px-8 lg:px-12 pt-16 lg:pt-20 pb-20">
        <div className="bg-white/70 rounded-3xl p-8 sm:p-10 border border-repaw-hover/40 shadow-sm">
          <ul className="space-y-3 text-repaw-text/90">
            {references.map((ref) => (
              <li key={ref.href}>
                <a
                  target="_blank"
                  rel="noreferrer"
                  className="inline-flex items-start gap-2 hover:text-repaw-dark transition-colors"
                  href={ref.href}
                >
                  <span className="mui-icon text-repaw-dark mt-0.5">link</span> {ref.text}
                </a>
              </li>
            ))}
          </ul>
        </div>
      </section>
    </div>
  )
}

export function PrivacyPolicy() {
  return (
    <div>
      <PageHero title="Privacy Policy" subtitle="" />
      <section className="max-w-3xl mx-auto px-6 sm:px-8 lg:px-12 pt-16 lg:pt-20 pb-20">
        <div className="bg-white/70 rounded-3xl p-8 sm:p-10 border border-repaw-hover/40 shadow-sm space-y-8">
          <div>
            <h2 className="font-serif text-xl font-bold text-repaw-dark mb-3">Introduction</h2>
            <p className="text-repaw-text/90 leading-relaxed">
              rePawCity respects your privacy and is committed to complying with this privacy policy ("Privacy Policy"), which describes what information we collect about you, including how we collect it, how we use it, with whom we may share it and what choices you have regarding our use of your information. This Privacy Policy applies to information collected on our Site, www.repawcity.com, (the "Site"), whether accessed via computer, mobile device or other technology or any associated content, material, or functionality contained on the Site (the "Services"). If you make a donation to the rePawCity, the terms of our Donor Privacy Policy will also apply to you.
            </p>
            <p className="mt-3 text-repaw-text/90 leading-relaxed">
              For the purposes of applicable data protection laws, rePawCity is the controller of the information you provide to us. As a controller, we process information in accordance with this Privacy Policy.
            </p>
          </div>

          <div>
            <h2 className="font-serif text-xl font-bold text-repaw-dark mb-3">Information We Collect</h2>
            <ul className="list-disc list-inside space-y-2 text-repaw-text/90 leading-relaxed">
              <li>
                <strong>Personal Information:</strong> We may collect personal information such as your name, address, email address, phone number, and other relevant details when you voluntarily provide them to us through our website, forms, or communication channels.
              </li>
              <li>
                <strong>Non-Personal Information:</strong> We may automatically collect non-personal information such as your IP address, browser type, operating system, referring website, and pages visited on our site. This information is collected to analyze trends, administer the site, track user engagement, and gather demographic information.
              </li>
            </ul>
          </div>

          <div>
            <h2 className="font-serif text-xl font-bold text-repaw-dark mb-3">Use of Information</h2>
            <ul className="list-disc list-inside space-y-2 text-repaw-text/90 leading-relaxed">
              <li>To provide and improve our services, including processing appointments, donations, and volunteer applications.</li>
              <li>To respond to your inquiries, comments, or questions.</li>
              <li>To send you updates, newsletters, and other communications you have opted in to receive.</li>
              <li>To analyze usage patterns and improve the user experience.</li>
              <li>To comply with legal obligations and protect the rights, property, or safety of rePawCity, our users, or others.</li>
            </ul>
          </div>

          <div>
            <h2 className="font-serif text-xl font-bold text-repaw-dark mb-3">Data Security</h2>
            <p className="text-repaw-text/90 leading-relaxed">
              We take reasonable measures to protect the information we collect from loss, misuse, and unauthorized access, disclosure, alteration, and destruction. However, no method of transmission over the Internet, or method of electronic storage, is 100% secure. Therefore, while we strive to use commercially acceptable means to protect your personal information, we cannot guarantee its absolute security.
            </p>
          </div>
        </div>
      </section>
    </div>
  )
}

export function TermsOfUse() {
  return (
    <div>
      <PageHero title="Terms of Use" subtitle="" />
      <section className="max-w-3xl mx-auto px-6 sm:px-8 lg:px-12 pt-16 lg:pt-20 pb-20">
        <div className="bg-white/70 rounded-3xl p-8 sm:p-10 border border-repaw-hover/40 shadow-sm space-y-8">
          <div>
            <h2 className="font-serif text-xl font-bold text-repaw-dark mb-3">Acceptance of Terms</h2>
            <p className="text-repaw-text/90 leading-relaxed">
              By accessing and using the rePawCity website, you accept and agree to be bound by the terms and provisions of this agreement. In addition, when using these particular services, you shall be subject to any posted guidelines or rules applicable to such services. Any participation in these services will constitute acceptance of this agreement.
            </p>
          </div>

          <div>
            <h2 className="font-serif text-xl font-bold text-repaw-dark mb-3">Use of the Site</h2>
            <p className="text-repaw-text/90 leading-relaxed">
              You agree to use this site only for lawful purposes and in a manner that does not infringe the rights of, restrict, or inhibit anyone else's use and enjoyment of the site. Prohibited behavior includes harassing or causing distress or inconvenience to any person, transmitting obscene or offensive content, or disrupting the normal flow of dialogue within the site.
            </p>
          </div>

          <div>
            <h2 className="font-serif text-xl font-bold text-repaw-dark mb-3">Intellectual Property</h2>
            <p className="text-repaw-text/90 leading-relaxed">
              All content included on this site, such as text, graphics, logos, images, and software, is the property of rePawCity or its content suppliers and protected by copyright and other laws. You may not copy, reproduce, distribute, or create derivative works from this content without prior written consent.
            </p>
          </div>

          <div>
            <h2 className="font-serif text-xl font-bold text-repaw-dark mb-3">Limitation of Liability</h2>
            <p className="text-repaw-text/90 leading-relaxed">
              rePawCity shall not be liable for any damages arising out of or in connection with the use of this site. This limitation of liability applies to all damages of any kind, including but not limited to direct, indirect, incidental, punitive, and consequential damages.
            </p>
          </div>
        </div>
      </section>
    </div>
  )
}
