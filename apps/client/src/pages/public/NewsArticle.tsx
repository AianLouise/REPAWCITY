import { Link, useParams } from 'react-router-dom'
import { Loading } from '@repaw/ui'
import { useNewsArticle } from '../../hooks/useContent'
import { resolveMedia } from '@repaw/api-client'
import { format } from 'date-fns'

export default function NewsArticle() {
  const { id } = useParams()
  const newsId = Number(id)
  const { data: article, isLoading, isError } = useNewsArticle(newsId)

  if (isLoading) return <Loading />

  if (isError || !article) {
    return (
      <div className="max-w-3xl mx-auto px-6 py-20 text-center">
        <h1 className="font-serif text-3xl font-bold text-repaw-dark">Article Not Found</h1>
        <p className="mt-3 text-repaw-text/80">The requested article could not be found.</p>
        <Link to="/news" className="inline-flex items-center gap-2 text-repaw-dark font-medium hover:text-repaw-text transition-colors mt-8">
          <span className="mui-icon">arrow_back</span> Back to News
        </Link>
      </div>
    )
  }

  return (
    <section className="max-w-3xl mx-auto px-6 sm:px-8 lg:px-12 pt-16 lg:pt-20 pb-20">
      <Link to="/news" className="inline-flex items-center gap-2 text-repaw-dark font-medium hover:text-repaw-text transition-colors mb-8">
        <span className="mui-icon">arrow_back</span> Back to News
      </Link>

      <article className="bg-white/70 rounded-3xl p-8 sm:p-10 border border-repaw-hover/40 shadow-sm">
        <h1 className="font-serif text-3xl sm:text-4xl font-bold text-repaw-dark">{article.title}</h1>
        <p className="mt-2 text-sm text-repaw-text/60">Published: {format(new Date(article.date_published), 'MMMM dd, yyyy')}</p>

        <div className="mt-8 rounded-2xl overflow-hidden border border-repaw-hover/40">
          <img src={resolveMedia(article.image_url)} alt={article.title} className="w-full max-h-[420px] object-cover" />
        </div>

        <div className="mt-8 text-repaw-text/90 leading-relaxed whitespace-pre-line">{article.details}</div>
      </article>
    </section>
  )
}
