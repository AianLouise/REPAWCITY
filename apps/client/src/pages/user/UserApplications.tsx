import { useState } from 'react'
import { Link } from 'react-router-dom'
import { useMyApplications, useApplicationActions } from '@repaw/api-client'
import { Loading } from '@repaw/ui'
import { resolveMedia } from '@repaw/api-client'

const STATUS_LABEL: Record<string, string> = {
  draft: 'Draft',
  submitted: 'Submitted',
  under_review: 'Under Review',
  approved: 'Approved',
  adopted: 'Adopted',
  rejected: 'Withdrawn / Rejected',
}

const STATUS_COLOR: Record<string, string> = {
  draft: 'bg-repaw-hover text-repaw-dark',
  submitted: 'bg-repaw-accent text-repaw-dark',
  under_review: 'bg-blue-100 text-blue-800',
  approved: 'bg-green-100 text-green-800',
  adopted: 'bg-repaw-text text-repaw-bg',
  rejected: 'bg-red-100 text-red-700',
}

export default function UserApplications() {
  const { data, isLoading } = useMyApplications()
  const { cancel } = useApplicationActions()
  const [busy, setBusy] = useState<number | null>(null)

  if (isLoading) return <Loading />

  if (!data || data.length === 0) {
    return (
      <div className="text-center py-12 space-y-4">
        <h2 className="font-serif text-2xl font-bold text-repaw-dark">No Adoption Applications</h2>
        <p className="text-repaw-text/80">You haven't applied to adopt any pets yet. Browse our pets to get started.</p>
        <Link to="/adopt" className="inline-block bg-repaw-text text-repaw-bg rounded-full px-6 py-2.5 text-sm font-medium uppercase tracking-wide hover:bg-repaw-dark transition-colors">
          Browse Pets
        </Link>
      </div>
    )
  }

  async function handleCancel(id: number) {
    if (!confirm('Withdraw this application?')) return
    setBusy(id)
    try {
      await cancel.mutateAsync(id)
    } finally {
      setBusy(null)
    }
  }

  return (
    <div className="space-y-6">
      <div className="flex items-center gap-3">
        <span className="mui-icon text-3xl text-repaw-dark">favorite</span>
        <h1 className="font-serif text-3xl font-bold text-repaw-dark">My Applications</h1>
      </div>

      <div className="space-y-4">
        {data.map((app) => {
          const cancellable = app.status === 'submitted' || app.status === 'under_review'
          return (
            <div key={app.id} className="bg-white/70 rounded-3xl p-6 border border-repaw-hover/40 shadow-sm flex items-center gap-5 flex-wrap">
              <Link to={`/adopt/${app.pet.id}`} className="shrink-0">
                <img src={resolveMedia(app.pet.thumb_url)} alt={app.pet.name} className="h-20 w-20 rounded-2xl object-cover border border-repaw-hover/40" />
              </Link>
              <div className="flex-1 min-w-[200px]">
                <Link to={`/adopt/${app.pet.id}`} className="font-serif text-xl font-semibold text-repaw-dark hover:text-repaw-text">
                  {app.pet.name}
                </Link>
                <p className="text-sm text-repaw-text/80">
                  {app.pet.breed} · {app.pet.type}
                </p>
                <span className={`inline-block mt-2 rounded-full px-3 py-1 text-xs font-medium uppercase tracking-wide ${STATUS_COLOR[app.status]}`}>
                  {STATUS_LABEL[app.status] ?? app.status}
                </span>
              </div>
              <div className="text-sm text-repaw-text/80 max-w-sm">
                {app.notes ? <p className="italic">" {app.notes} "</p> : null}
                <p className="mt-1">Applied {new Date(app.created_at).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })}</p>
              </div>
              <div className="flex gap-2">
                <Link to={`/adopt/${app.pet.id}`} className="bg-repaw-hover text-repaw-dark rounded-full px-4 py-2 text-xs font-medium uppercase tracking-wide hover:bg-repaw-hover/70 transition-colors">
                  View Pet
                </Link>
                {cancellable && (
                  <button
                    onClick={() => void handleCancel(app.id)}
                    disabled={busy === app.id}
                    className="bg-repaw-danger text-white rounded-full px-4 py-2 text-xs font-medium uppercase tracking-wide hover:opacity-90 transition-opacity disabled:opacity-60"
                  >
                    {busy === app.id ? '...' : 'Withdraw'}
                  </button>
                )}
              </div>
            </div>
          )
        })}
      </div>
    </div>
  )
}
