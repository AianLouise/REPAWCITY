import { useState } from 'react'
import { useAllNews, useAdminActions } from '../../hooks/useAdmin'
import { resolveMedia } from '../../api/client'
import type { NewsArticle } from '../../types'

export default function AdminManageNews() {
  const { data, isLoading } = useAllNews()
  const actions = useAdminActions()
  const [selected, setSelected] = useState<NewsArticle | null>(null)
  const [edit, setEdit] = useState({ title: '', details: '' })
  const [notice, setNotice] = useState<string | null>(null)

  const inputCls =
    'w-full rounded-xl border border-repaw-hover bg-repaw-bg px-4 py-2.5 text-repaw-text focus:outline-none focus:ring-2 focus:ring-repaw-text'

  function select(news: NewsArticle) {
    setSelected(news)
    setEdit({ title: news.title, details: news.details })
    setNotice(null)
  }

  async function handleUpdate(e: React.FormEvent) {
    e.preventDefault()
    if (!selected) return
    await actions.updateNews.mutateAsync({ id: selected.id, data: edit })
    setNotice('Data updated successfully')
  }

  async function handleDelete(news: NewsArticle) {
    if (!confirm(`Delete "${news.title}"?`)) return
    await actions.deleteNews.mutateAsync(news.id)
    if (selected?.id === news.id) setSelected(null)
  }

  async function handleFeature(news: NewsArticle) {
    await actions.featureNews.mutateAsync(news.id)
    setNotice(`"${news.title}" is now the headline`)
  }

  return (
    <div className="space-y-8">
      <div className="bg-white/70 rounded-3xl p-8 border border-repaw-hover/40 shadow-sm">
        <div className="flex items-center gap-3 mb-6">
          <span className="mui-icon text-3xl text-repaw-dark">newspaper</span>
          <h1 className="font-serif text-3xl font-bold text-repaw-dark">News List</h1>
        </div>

        <div className="overflow-x-auto rounded-2xl border border-repaw-hover/40 max-h-[400px] overflow-y-auto">
          <table className="w-full text-left text-sm">
            <thead className="bg-repaw-bg/70 text-repaw-dark sticky top-0">
              <tr>
                <th className="px-3 py-3 font-semibold">News ID</th>
                <th className="px-3 py-3 font-semibold">Image</th>
                <th className="px-3 py-3 font-semibold">Title</th>
                <th className="px-3 py-3 font-semibold">Details</th>
                <th className="px-3 py-3 font-semibold">Date Published</th>
                <th className="px-3 py-3 font-semibold">Is Featured</th>
                <th className="px-3 py-3 font-semibold">Actions</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-repaw-hover/40">
              {isLoading ? (
                <tr>
                  <td colSpan={7} className="px-3 py-6 text-center text-repaw-text/70">Loading...</td>
                </tr>
              ) : data && data.data.length > 0 ? (
                data.data.map((news) => (
                  <tr key={news.id} className="hover:bg-repaw-bg/40 cursor-pointer" onClick={() => select(news)}>
                    <td className="px-3 py-3">{news.id}</td>
                    <td className="px-3 py-3">
                      <img src={resolveMedia(news.image_url)} alt="" className="h-12 w-12 rounded-lg object-cover" />
                    </td>
                    <td className="px-3 py-3 font-medium text-repaw-dark">{news.title}</td>
                    <td className="px-3 py-3 max-w-xs truncate">{news.details}</td>
                    <td className="px-3 py-3">{news.date_published}</td>
                    <td className="px-3 py-3">{news.is_featured ? '1' : '0'}</td>
                    <td className="px-3 py-3 space-x-2">
                      <button
                        onClick={(e) => {
                          e.stopPropagation()
                          void handleFeature(news)
                        }}
                        className="inline-flex items-center gap-1 bg-repaw-accent text-repaw-dark rounded-full px-3 py-1.5 text-xs font-medium uppercase tracking-wide hover:opacity-90 transition-opacity"
                      >
                        <span className="mui-icon text-[16px]">push_pin</span>Headline
                      </button>
                      <button
                        onClick={(e) => {
                          e.stopPropagation()
                          void handleDelete(news)
                        }}
                        className="inline-flex items-center gap-1 bg-repaw-danger text-white rounded-full px-3 py-1.5 text-xs font-medium uppercase tracking-wide hover:opacity-90 transition-opacity"
                      >
                        <span className="mui-icon text-[16px]">delete</span>Delete
                      </button>
                    </td>
                  </tr>
                ))
              ) : (
                <tr>
                  <td colSpan={7} className="px-3 py-6 text-center text-repaw-text/70">No data available</td>
                </tr>
              )}
            </tbody>
          </table>
        </div>
      </div>

      {selected && (
        <div className="max-w-3xl mx-auto bg-white/70 rounded-3xl p-8 border border-repaw-hover/40 shadow-sm">
          <h1 className="font-serif text-2xl font-bold text-repaw-dark mb-6">News Details</h1>

          {notice && <div className="mb-4 rounded-xl border border-green-400/40 bg-green-50 px-4 py-3 text-sm text-green-800">{notice}</div>}

          <form onSubmit={handleUpdate} className="space-y-5">
            <div className="grid grid-cols-1 sm:grid-cols-2 gap-5">
              <div>
                <label className="block text-sm font-medium text-repaw-dark mb-1.5">ID:</label>
                <input type="text" value={selected.id} readOnly className={inputCls} />
              </div>
              <div>
                <label className="block text-sm font-medium text-repaw-dark mb-1.5">Is Featured:</label>
                <input type="text" value={selected.is_featured ? '1' : '0'} readOnly className={inputCls} />
              </div>
            </div>
            <div>
              <label className="block text-sm font-medium text-repaw-dark mb-1.5">Title:</label>
              <input type="text" required value={edit.title} onChange={(e) => setEdit((f) => ({ ...f, title: e.target.value }))} className={inputCls} />
            </div>
            <div>
              <label className="block text-sm font-medium text-repaw-dark mb-1.5">Details:</label>
              <textarea required value={edit.details} onChange={(e) => setEdit((f) => ({ ...f, details: e.target.value }))} rows={6} className={inputCls} />
            </div>
            <div>
              <label className="block text-sm font-medium text-repaw-dark mb-1.5">Date Published:</label>
              <input type="text" value={selected.date_published} readOnly className={inputCls} />
            </div>
            <button
              type="submit"
              disabled={actions.updateNews.isPending}
              className="inline-flex items-center gap-2 bg-repaw-text text-repaw-bg rounded-full px-8 py-3 text-[15px] font-medium uppercase tracking-wide hover:bg-repaw-dark transition-colors duration-300 disabled:opacity-60"
            >
              <span className="mui-icon">save</span> Update
            </button>
          </form>
        </div>
      )}
    </div>
  )
}
