import { useState } from 'react'
import { useAllApplications, useApplicationActions } from '@repaw/api-client'
import { Loading } from '@repaw/ui'
import { resolveMedia } from '@repaw/api-client'
import type { AdoptionApplication, ApplicationStatus } from '@repaw/api-client'
import { MdHowToReg } from 'react-icons/md'

const COLUMNS: { status: ApplicationStatus; label: string }[] = [
  { status: 'submitted', label: 'Submitted' },
  { status: 'under_review', label: 'Under Review' },
  { status: 'approved', label: 'Approved' },
  { status: 'adopted', label: 'Adopted' },
  { status: 'rejected', label: 'Rejected' },
]

const NEXT_ACTIONS: Partial<Record<ApplicationStatus, { status: ApplicationStatus; label: string }[]>> = {
  submitted: [
    { status: 'under_review', label: 'Start Review' },
    { status: 'rejected', label: 'Reject' },
  ],
  under_review: [
    { status: 'approved', label: 'Approve' },
    { status: 'rejected', label: 'Reject' },
  ],
  approved: [
    { status: 'adopted', label: 'Mark Adopted' },
    { status: 'rejected', label: 'Reject' },
  ],
}

export default function AdminApplications() {
  const { data, isLoading } = useAllApplications()
  const { updateStatus } = useApplicationActions()
  const [busy, setBusy] = useState<number | null>(null)
  const [noteFor, setNoteFor] = useState<number | null>(null)
  const [noteText, setNoteText] = useState('')

  if (isLoading) return <Loading />

  const applications = data ?? []

  async function handleTransition(app: AdoptionApplication, status: ApplicationStatus) {
    setBusy(app.id)
    try {
      await updateStatus.mutateAsync({
        id: app.id,
        status,
        notes: noteFor === app.id && noteText.trim() ? noteText.trim() : app.notes,
      })
      setNoteFor(null)
      setNoteText('')
    } finally {
      setBusy(null)
    }
  }

  return (
    <div className="space-y-6">
      <div className="flex items-center gap-3">
        <MdHowToReg size={30} className="text-repaw-dark" />
        <h1 className="font-serif text-3xl font-bold text-repaw-dark">Adoption Applications</h1>
      </div>

      <div className="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-5 gap-4 items-start">
        {COLUMNS.map((col) => {
          const items = applications.filter((a) => a.status === col.status)
          return (
            <div key={col.status} className="bg-white/60 rounded-3xl p-4 border border-repaw-hover/40 shadow-sm">
              <div className="flex items-center justify-between mb-3">
                <h2 className="font-serif text-lg font-bold text-repaw-dark">{col.label}</h2>
                <span className="rounded-full bg-repaw-hover/70 text-repaw-text text-xs font-medium px-2.5 py-0.5">{items.length}</span>
              </div>
              <div className="space-y-3">
                {items.length === 0 && <p className="text-xs text-repaw-text/50 text-center py-4">No applications</p>}
                {items.map((app) => (
                  <div key={app.id} className="bg-white/80 rounded-2xl p-3 border border-repaw-hover/30 shadow-sm">
                    <div className="flex items-center gap-3">
                      <img src={resolveMedia(app.pet.thumb_url)} alt="" className="h-12 w-12 rounded-xl object-cover" />
                      <div className="min-w-0">
                        <p className="font-semibold text-repaw-dark truncate">{app.pet.name}</p>
                        <p className="text-xs text-repaw-text/70 truncate">
                          {app.user?.fname} {app.user?.lname}
                        </p>
                        <p className="text-[11px] text-repaw-text/50 truncate">{app.user?.email}</p>
                      </div>
                    </div>

                    <details className="mt-2 text-xs text-repaw-text/80">
                      <summary className="cursor-pointer text-repaw-text/60">Application answers</summary>
                      <div className="mt-2 space-y-1.5 bg-repaw-bg/70 rounded-xl p-2.5">
                        <p><strong>Housing:</strong> {app.answers?.housing}</p>
                        <p><strong>Other pets:</strong> {app.answers?.other_pets}</p>
                        <p><strong>Experience:</strong> {app.answers?.experience}</p>
                        <p><strong>Why this pet:</strong> {app.answers?.why_this_pet}</p>
                      </div>
                    </details>

                    {app.notes && <p className="mt-2 text-[11px] italic text-repaw-text/60">" {app.notes} "</p>}

                    <button
                      onClick={() => {
                        setNoteFor(noteFor === app.id ? null : app.id)
                        setNoteText(app.notes ?? '')
                      }}
                      className="mt-2 text-xs text-repaw-text/60 underline underline-offset-2 hover:text-repaw-dark"
                    >
                      {noteFor === app.id ? 'Hide notes' : (app.notes ? 'Edit note' : 'Add note')}
                    </button>

                    {noteFor === app.id && (
                      <div className="mt-2">
                        <textarea
                          value={noteText}
                          onChange={(e) => setNoteText(e.target.value)}
                          rows={2}
                          placeholder="Staff note..."
                          className="w-full rounded-lg border border-repaw-hover bg-repaw-bg px-3 py-2 text-xs text-repaw-text focus:outline-none focus:ring-2 focus:ring-repaw-text"
                        />
                      </div>
                    )}

                    {(NEXT_ACTIONS[app.status] ?? []).length > 0 && (
                      <div className="mt-2 flex flex-wrap gap-1.5">
                        {(NEXT_ACTIONS[app.status] ?? []).map((action) => (
                          <button
                            key={action.status}
                            onClick={() => void handleTransition(app, action.status)}
                            disabled={busy === app.id}
                            className={`rounded-full px-3 py-1 text-[11px] font-medium uppercase tracking-wide transition-colors disabled:opacity-60 ${
                              action.status === 'rejected'
                                ? 'bg-red-100 text-red-700 hover:bg-red-200'
                                : 'bg-repaw-text text-repaw-bg hover:bg-repaw-dark'
                            }`}
                          >
                            {busy === app.id ? '...' : action.label}
                          </button>
                        ))}
                      </div>
                    )}
                  </div>
                ))}
              </div>
            </div>
          )
        })}
      </div>
    </div>
  )
}
