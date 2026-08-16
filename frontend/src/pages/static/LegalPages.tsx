import { PageHero } from '../../components/Shared'

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
