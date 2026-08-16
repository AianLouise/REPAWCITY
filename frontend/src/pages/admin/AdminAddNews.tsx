import { useState } from 'react'
import { useAdminActions } from '../../hooks/useAdmin'

export default function AdminAddNews() {
  const actions = useAdminActions()
  const [form, setForm] = useState({ title: '', details: '' })
  const [image, setImage] = useState<File | null>(null)
  const [notice, setNotice] = useState<string | null>(null)
  const [error, setError] = useState<string | null>(null)

  const inputCls =
    'w-full rounded-xl border border-repaw-hover bg-repaw-bg px-4 py-2.5 text-repaw-text focus:outline-none focus:ring-2 focus:ring-repaw-text'

  async function handleSubmit(e: React.FormEvent) {
    e.preventDefault()
    setNotice(null)
    setError(null)
    if (!image) {
      setError('Image is required.')
      return
    }
    const fd = new FormData()
    fd.append('title', form.title)
    fd.append('details', form.details)
    fd.append('image', image)

    try {
      await actions.storeNews.mutateAsync(fd)
      setNotice('Successfully Added')
      setForm({ title: '', details: '' })
      setImage(null)
    } catch (e) {
      const err = e as { response?: { data?: { errors?: Record<string, string[]>; message?: string } } }
      setError(err.response?.data?.errors?.image?.[0] ?? err.response?.data?.message ?? 'Failed to add news')
    }
  }

  return (
    <div className="max-w-3xl mx-auto">
      <div className="bg-white/70 rounded-3xl p-8 border border-repaw-hover/40 shadow-sm">
        <div className="flex items-center gap-3 mb-6">
          <span className="mui-icon text-3xl text-repaw-dark">newspaper</span>
          <h1 className="font-serif text-3xl font-bold text-repaw-dark">News Form</h1>
        </div>

        {notice && <div className="mb-4 rounded-xl border border-green-400/40 bg-green-50 px-4 py-3 text-sm text-green-800">{notice}</div>}
        {error && <div className="mb-4 rounded-xl border border-repaw-danger/40 bg-red-50 px-4 py-3 text-sm text-repaw-danger">{error}</div>}

        <form onSubmit={handleSubmit} className="space-y-5">
          <div>
            <label className="block text-sm font-medium text-repaw-dark mb-1.5">News Title:</label>
            <input
              type="text"
              required
              value={form.title}
              onChange={(e) => setForm((f) => ({ ...f, title: e.target.value }))}
              className={inputCls}
            />
          </div>
          <div>
            <label className="block text-sm font-medium text-repaw-dark mb-1.5">Details:</label>
            <textarea
              required
              value={form.details}
              onChange={(e) => setForm((f) => ({ ...f, details: e.target.value }))}
              rows={6}
              className={inputCls}
            />
          </div>
          <div>
            <label className="block text-sm font-medium text-repaw-dark mb-1.5">Image:</label>
            <input
              type="file"
              accept="image/jpeg,image/png"
              required
              onChange={(e) => setImage(e.target.files?.[0] ?? null)}
              className={`${inputCls} file:mr-4 file:rounded-lg file:border-0 file:bg-repaw-text file:px-4 file:py-2 file:text-repaw-bg`}
            />
          </div>
          <button
            type="submit"
            disabled={actions.storeNews.isPending}
            className="inline-flex items-center gap-2 bg-repaw-text text-repaw-bg rounded-full px-8 py-3 text-[15px] font-medium uppercase tracking-wide hover:bg-repaw-dark transition-colors duration-300 disabled:opacity-60"
          >
            <span className="mui-icon">add_circle</span> Submit
          </button>
        </form>
      </div>
    </div>
  )
}
