import { useMemo, useState } from 'react'
import { useAdminSchedules, useScheduleActions } from '@repaw/api-client'
import type { AdminSchedule } from '@repaw/api-client'
import { MdCalendarMonth, MdChevronLeft, MdChevronRight } from 'react-icons/md'

interface DayState {
  date: string
  isOpen: boolean
  morningCapacity: number
  afternoonCapacity: number
  reason: string
}

function dateKey(d: Date): string {
  return `${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, '0')}-${String(d.getDate()).padStart(2, '0')}`
}

export default function AdminAvailability() {
  const [monthOffset, setMonthOffset] = useState(0)
  const [selected, setSelected] = useState<DayState | null>(null)
  const [saving, setSaving] = useState(false)
  const [msg, setMsg] = useState<{ type: 'ok' | 'err'; text: string } | null>(null)

  const month = useMemo(() => {
    const t = new Date()
    const d = new Date(t.getFullYear(), t.getMonth() + monthOffset, 1)
    return d
  }, [monthOffset])

  const from = dateKey(month)
  const to = dateKey(new Date(month.getFullYear(), month.getMonth() + 1, 0))

  const { data: schedules, isLoading } = useAdminSchedules(from, to)
  const { update } = useScheduleActions()

  const scheduleMap = useMemo(() => {
    const m = new Map<string, AdminSchedule>()
    ;(schedules ?? []).forEach((s) => m.set(s.date, s))
    return m
  }, [schedules])

  const cells = useMemo(() => {
    const first = new Date(month.getFullYear(), month.getMonth(), 1)
    const startOffset = (first.getDay() + 6) % 7
    const daysInMonth = new Date(month.getFullYear(), month.getMonth() + 1, 0).getDate()
    const list: (Date | null)[] = Array.from({ length: startOffset }, () => null)
    for (let d = 1; d <= daysInMonth; d++) {
      list.push(new Date(month.getFullYear(), month.getMonth(), d))
    }
    return list
  }, [month])

  function openDayEditor(date: string) {
    const existing = scheduleMap.get(date)
    setSelected({
      date,
      isOpen: existing?.is_open ?? true,
      morningCapacity: existing?.morning_capacity ?? 10,
      afternoonCapacity: existing?.afternoon_capacity ?? 10,
      reason: existing?.reason ?? '',
    })
  }

  async function saveDay() {
    if (!selected) return
    setSaving(true)
    setMsg(null)
    try {
      await update.mutateAsync({
        date: selected.date,
        is_open: selected.isOpen,
        morning_capacity: selected.morningCapacity,
        afternoon_capacity: selected.afternoonCapacity,
        reason: selected.isOpen ? null : (selected.reason || 'Shelter closed'),
      })
      setMsg({ type: 'ok', text: `Schedule for ${selected.date} saved.` })
      setSelected(null)
    } catch {
      setMsg({ type: 'err', text: 'Failed to save schedule. Please try again.' })
    } finally {
      setSaving(false)
    }
  }

  const todayKey = dateKey(new Date())

  return (
    <div className="space-y-8">
      <div className="bg-white/70 rounded-3xl p-8 border border-repaw-hover/40 shadow-sm">
        <div className="flex items-center gap-3 mb-2">
          <MdCalendarMonth size={30} className="text-repaw-dark" />
          <h1 className="font-serif text-3xl font-bold text-repaw-dark">Shelter Availability</h1>
        </div>
        <p className="text-repaw-text/80 text-sm mb-6">
          Click a day to open or close the shelter, adjust per-session capacity, or note a reason (events, maintenance).
        </p>

        {msg && (
          <div
            className={`mb-4 rounded-xl border px-4 py-3 text-sm ${msg.type === 'ok' ? 'border-green-400/40 bg-green-50 text-green-800' : 'border-repaw-danger/40 bg-red-50 text-repaw-danger'}`}
          >
            {msg.text}
          </div>
        )}

        <div className="flex items-center justify-between mb-4">
          <button onClick={() => setMonthOffset((o) => o - 1)} className="text-repaw-dark hover:text-repaw-text transition-colors" aria-label="Previous month">
            <MdChevronLeft size={24} />
          </button>
          <h2 className="font-serif text-xl font-bold text-repaw-dark">
            {month.toLocaleDateString('en-US', { month: 'long', year: 'numeric' })}
          </h2>
          <button onClick={() => setMonthOffset((o) => o + 1)} className="text-repaw-dark hover:text-repaw-text transition-colors" aria-label="Next month">
            <MdChevronRight size={24} />
          </button>
        </div>

        <div className="grid grid-cols-7 gap-1 text-center text-xs font-semibold text-repaw-text/70 mb-2">
          {['Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa', 'Su'].map((d) => (
            <div key={d}>{d}</div>
          ))}
        </div>

        {isLoading ? (
          <p className="text-center text-repaw-text/70 py-8">Loading schedule...</p>
        ) : (
          <div className="grid grid-cols-7 gap-1">
            {cells.map((d, i) => {
              if (!d) return <div key={i} />
              const key = dateKey(d)
              const s = scheduleMap.get(key)
              const past = key < todayKey
              const isOpen = s?.is_open ?? true
              let cls = 'aspect-square rounded-lg flex flex-col items-center justify-center text-sm transition-colors border '
              if (past) {
                cls += 'text-repaw-text/25 border-transparent'
              } else if (!isOpen) {
                cls += 'bg-repaw-hover/70 text-repaw-text/50 line-through border-repaw-hover cursor-pointer hover:bg-repaw-hover'
              } else {
                cls += 'bg-repaw-bg text-repaw-text border-repaw-hover cursor-pointer hover:bg-repaw-hover'
              }
              return (
                <button key={i} disabled={past} onClick={() => openDayEditor(key)} className={cls}>
                  <span>{d.getDate()}</span>
                  {!past && s && (
                    <span className={`text-[10px] font-medium ${s.is_open ? 'text-repaw-text/60' : 'text-repaw-danger'}`}>
                      {s.is_open ? `${s.morning_capacity}/${s.afternoon_capacity}` : 'closed'}
                    </span>
                  )}
                </button>
              )
            })}
          </div>
        )}

        <div className="flex items-center gap-6 mt-5 text-sm text-repaw-text/80">
          <span className="inline-flex items-center gap-2">
            <span className="w-4 h-4 rounded bg-repaw-bg border border-repaw-hover" /> Open
          </span>
          <span className="inline-flex items-center gap-2">
            <span className="w-4 h-4 rounded bg-repaw-hover/70" /> Closed
          </span>
        </div>
      </div>

      {selected && (
        <div className="fixed inset-0 z-50 flex items-center justify-center bg-repaw-dark/40 p-4" onClick={() => setSelected(null)}>
          <div
            className="bg-white rounded-3xl p-8 border border-repaw-hover/40 shadow-xl w-full max-w-md"
            onClick={(e) => e.stopPropagation()}
          >
            <h2 className="font-serif text-2xl font-bold text-repaw-dark mb-1">Schedule</h2>
            <p className="text-sm text-repaw-text/80 mb-6">
              {new Date(selected.date + 'T00:00:00').toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })}
            </p>

            <div className="space-y-4">
              <label className="flex items-center gap-3 text-sm text-repaw-text/90">
                <input
                  type="checkbox"
                  checked={selected.isOpen}
                  onChange={(e) => setSelected((s) => (s ? { ...s, isOpen: e.target.checked } : s))}
                  className="w-5 h-5"
                />
                Shelter is open for visits
              </label>

              <div className="grid grid-cols-2 gap-4">
                <div>
                  <label className="block text-sm font-medium text-repaw-dark mb-1.5">Morning capacity</label>
                  <input
                    type="number"
                    min={0}
                    max={100}
                    disabled={!selected.isOpen}
                    value={selected.morningCapacity}
                    onChange={(e) => setSelected((s) => (s ? { ...s, morningCapacity: Number(e.target.value) } : s))}
                    className="w-full rounded-xl border border-repaw-hover bg-repaw-bg px-4 py-2.5 text-repaw-text focus:outline-none focus:ring-2 focus:ring-repaw-text disabled:opacity-50"
                  />
                </div>
                <div>
                  <label className="block text-sm font-medium text-repaw-dark mb-1.5">Afternoon capacity</label>
                  <input
                    type="number"
                    min={0}
                    max={100}
                    disabled={!selected.isOpen}
                    value={selected.afternoonCapacity}
                    onChange={(e) => setSelected((s) => (s ? { ...s, afternoonCapacity: Number(e.target.value) } : s))}
                    className="w-full rounded-xl border border-repaw-hover bg-repaw-bg px-4 py-2.5 text-repaw-text focus:outline-none focus:ring-2 focus:ring-repaw-text disabled:opacity-50"
                  />
                </div>
              </div>

              {!selected.isOpen && (
                <div>
                  <label className="block text-sm font-medium text-repaw-dark mb-1.5">Reason (shown to visitors)</label>
                  <input
                    type="text"
                    value={selected.reason}
                    onChange={(e) => setSelected((s) => (s ? { ...s, reason: e.target.value } : s))}
                    placeholder="e.g. Shelter maintenance"
                    className="w-full rounded-xl border border-repaw-hover bg-repaw-bg px-4 py-2.5 text-repaw-text focus:outline-none focus:ring-2 focus:ring-repaw-text"
                  />
                </div>
              )}
            </div>

            <div className="flex justify-end gap-3 mt-8">
              <button onClick={() => setSelected(null)} className="bg-repaw-hover text-repaw-dark rounded-full px-6 py-2.5 text-sm font-medium uppercase tracking-wide hover:bg-repaw-hover/70 transition-colors">
                Cancel
              </button>
              <button onClick={() => void saveDay()} disabled={saving} className="bg-repaw-text text-repaw-bg rounded-full px-6 py-2.5 text-sm font-medium uppercase tracking-wide hover:bg-repaw-dark transition-colors disabled:opacity-60">
                {saving ? 'Saving...' : 'Save'}
              </button>
            </div>
          </div>
        </div>
      )}
    </div>
  )
}
