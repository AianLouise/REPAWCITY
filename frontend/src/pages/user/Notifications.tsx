import { useState } from 'react'
import { Link } from 'react-router-dom'
import { useNotifications, useNotificationActions } from '../../hooks/useNotifications'
import { Loading } from '../../components/Shared'

export default function Notifications() {
  const { data, isLoading } = useNotifications()
  const { markAllRead } = useNotificationActions()
  const [busy, setBusy] = useState(false)

  const notifications = data?.data ?? []
  const unreadCount = data?.unread_count ?? 0

  async function handleMarkAll() {
    setBusy(true)
    try {
      await markAllRead.mutateAsync()
    } finally {
      setBusy(false)
    }
  }

  return (
    <div className="space-y-6">
      <div className="flex items-center justify-between flex-wrap gap-3">
        <div className="flex items-center gap-3">
          <span className="mui-icon text-3xl text-repaw-dark">notifications</span>
          <h1 className="font-serif text-3xl font-bold text-repaw-dark">Notifications</h1>
        </div>
        {unreadCount > 0 && (
          <button
            onClick={() => void handleMarkAll()}
            disabled={busy}
            className="bg-repaw-text text-repaw-bg rounded-full px-4 py-2 text-xs font-medium uppercase tracking-wide hover:bg-repaw-dark transition-colors disabled:opacity-60"
          >
            {busy ? '...' : `Mark all read (${unreadCount})`}
          </button>
        )}
      </div>

      {isLoading ? (
        <Loading />
      ) : notifications.length === 0 ? (
        <div className="bg-white/70 rounded-3xl p-8 border border-repaw-hover/40 shadow-sm text-center">
          <p className="text-repaw-text/80">No notifications yet.</p>
        </div>
      ) : (
        <div className="space-y-3">
          {notifications.map((n) => (
            <NotificationCard key={n.id} notification={n} />
          ))}
        </div>
      )}
    </div>
  )
}

function NotificationCard({ notification }: { notification: import('../../api/notifications').AppNotification }) {
  const { markRead } = useNotificationActions()
  const [busy, setBusy] = useState(false)
  const unread = notification.read_at === null

  async function handleRead() {
    setBusy(true)
    try {
      await markRead.mutateAsync(notification.id)
    } finally {
      setBusy(false)
    }
  }

  const d = notification.data
  const target = d.type === 'application.status' && d.application_id
    ? '/account/applications'
    : d.appointment_id
      ? '/account/appointments'
      : null

  const body = (
    <div className="flex-1 min-w-0">
      <p className="font-semibold text-repaw-dark">{d.title}</p>
      <p className="text-sm text-repaw-text/80 mt-1 whitespace-pre-line">{d.message}</p>
      <p className="text-xs text-repaw-text/50 mt-2">
        {new Date(notification.created_at).toLocaleString('en-US', { month: 'short', day: 'numeric', hour: 'numeric', minute: '2-digit' })}
      </p>
    </div>
  )

  return (
    <div
      className={`bg-white/70 rounded-3xl p-5 border shadow-sm flex items-start gap-4 transition-colors ${
        unread ? 'border-repaw-accent/70 ring-1 ring-repaw-accent/40' : 'border-repaw-hover/40'
      }`}
    >
      <span className={`mui-icon text-2xl mt-0.5 ${unread ? 'text-repaw-accent' : 'text-repaw-text/40'}`}>
        {d.type === 'application.status' ? 'how_to_reg' : 'event'}
      </span>
      {target ? <Link to={target} className="flex-1 min-w-0 block">{body}</Link> : <div className="flex-1 min-w-0">{body}</div>}
      {unread && (
        <button
          onClick={() => void handleRead()}
          disabled={busy}
          className="shrink-0 bg-repaw-hover/70 text-repaw-text rounded-full px-3 py-1 text-xs font-medium hover:bg-repaw-hover transition-colors disabled:opacity-60"
        >
          {busy ? '...' : 'Mark read'}
        </button>
      )}
    </div>
  )
}
