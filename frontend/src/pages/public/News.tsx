import { useEffect, useState } from 'react'
import { Link } from 'react-router-dom'
import { Empty, Loading, PageHero } from '../../components/Shared'
import { useNews } from '../../hooks/useContent'
import { timeAgo } from '../../utils/time'
import { resolveMedia } from '../../api/client'

const tipImages = ['/images/pet-tips.jpg', '/images/pet-tips2.jpg', '/images/pet-tips3.jpg']

export default function News() {
  const { data: featured, isLoading: loadingFeatured } = useNews(true)
  const { data: latest, isLoading: loadingLatest } = useNews(false)
  const [slide, setSlide] = useState(0)

  useEffect(() => {
    const id = setInterval(() => setSlide((s) => (s + 1) % tipImages.length), 4000)
    return () => clearInterval(id)
  }, [])

  const headline = featured?.data[0]

  return (
    <div>
      <PageHero title="Blogs, Latest News & Updates" subtitle="Stay up to date with rePaw City and learn how to care for your new companion." />

      <section className="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 pt-16 lg:pt-20 pb-20">
        <div className="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center mb-16">
          <div>
            <h2 className="font-serif text-3xl font-bold text-repaw-dark mb-4">Pet Care Tips</h2>
            <p className="text-repaw-text/90 leading-relaxed">
              How to Ensure a Happy and Healthy Life for Your New Companion: discover essential tips for providing optimal care and well-being to your newly adopted pet, including nutrition, exercise, grooming, and more.
            </p>
          </div>
          <div className="slideshow-container relative rounded-3xl overflow-hidden aspect-video bg-repaw-bg/60 shadow-sm">
            {tipImages.map((src, i) => (
              <img
                key={src}
                src={src}
                alt={`Pet tips ${i + 1}`}
                className={`absolute inset-0 w-full h-full object-cover transition-opacity duration-700 ${i === slide ? 'opacity-100' : 'opacity-0'}`}
              />
            ))}
          </div>
        </div>

        <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
          <div className="lg:col-span-2">
            <h2 className="font-serif text-2xl font-bold text-repaw-dark mb-5">Featured News</h2>
            {loadingFeatured ? (
              <Loading />
            ) : headline ? (
              <Link to={`/news/${headline.id}`} className="group block bg-white/70 rounded-3xl overflow-hidden border border-repaw-hover/40 shadow-sm hover:shadow-xl transition-shadow duration-300">
                <div className="aspect-video overflow-hidden bg-repaw-bg/60">
                  <img src={resolveMedia(headline.image_url)} alt="Featured news" className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" />
                </div>
                <div className="p-6">
                  <h3 className="font-serif text-2xl font-semibold text-repaw-dark group-hover:text-repaw-text transition-colors">{headline.title}</h3>
                  <p className="mt-3 text-repaw-text/80 leading-relaxed">{headline.details}</p>
                  <span className="mt-4 inline-flex items-center gap-1 text-sm font-medium text-repaw-dark uppercase tracking-wide">
                    Read more
                    <span className="mui-icon text-[18px] transition-transform duration-300 group-hover:translate-x-1">arrow_forward</span>
                  </span>
                </div>
              </Link>
            ) : (
              <div className="bg-white/70 rounded-3xl p-6 border border-repaw-hover/40">
                <h3 className="font-serif text-2xl font-semibold text-repaw-dark">No Featured News Available</h3>
                <p className="mt-3 text-repaw-text/80">There is no featured news at the moment. Check back later for updates.</p>
              </div>
            )}
          </div>

          <div>
            <h2 className="font-serif text-2xl font-bold text-repaw-dark mb-5">Latest News</h2>
            {loadingLatest ? (
              <Loading />
            ) : latest && latest.data.length > 0 ? (
              <div className="space-y-4">
                {latest.data.map((item) => (
                  <Link key={item.id} to={`/news/${item.id}`} className="group flex gap-4 bg-white/70 rounded-2xl p-4 border border-repaw-hover/40 shadow-sm hover:shadow-md transition-shadow duration-300">
                    <div className="w-20 h-20 shrink-0 rounded-xl overflow-hidden bg-repaw-bg/60">
                      <img src={resolveMedia(item.thumb_url)} alt="News thumbnail" className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" />
                    </div>
                    <div className="min-w-0">
                      <h4 className="font-serif text-base font-semibold text-repaw-dark truncate">{item.title}</h4>
                      <p className="mt-1 text-sm text-repaw-text/80 leading-snug line-clamp-2">{item.details}</p>
                      <p className="mt-1 text-xs text-repaw-text/60">{timeAgo(item.date_published)}</p>
                    </div>
                  </Link>
                ))}
              </div>
            ) : (
              <Empty message="No news available." />
            )}
          </div>
        </div>
      </section>
    </div>
  )
}
