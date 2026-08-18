import { useMemo, useState } from 'react'
import { useNavigate, useSearchParams } from 'react-router-dom'
import { logo } from '@repaw/ui'
import { useSlots } from '../../hooks/useBooking'
import { useUpcomingSchedule } from '@repaw/api-client'
import { usePets, usePet } from '../../hooks/useContent'
import { appointmentsApi, type BookingPayload } from '../../api/appointments'
import type { Appointment, AppointmentType, ScheduleDay, TimeSlot } from '@repaw/api-client'
import { useForm } from 'react-hook-form'
import { zodResolver } from '@hookform/resolvers/zod'
import { z } from 'zod'
import { SHELTER_ADDRESS_INLINE } from '@repaw/config'

type Step = 1 | 2 | 3 | 4 | 5 | 6

interface BookingDraft {
  appointment_type: AppointmentType | null
  pet_id: number | null
  appointment_date: string | null
  time_slot: TimeSlot | null
  first_name: string
  middle_name: string
  last_name: string
  mobile_number: string
  home_address: string
  email_address: string
}

const infoSchema = z.object({
  first_name: z.string().min(1, 'First name is required'),
  middle_name: z.string(),
  last_name: z.string().min(1, 'Last name is required'),
  mobile_number: z.string().min(1, 'Mobile number is required'),
  home_address: z.string().min(1, 'Home address is required'),
  email_address: z.string().email('Enter a valid email'),
})

type InfoForm = z.infer<typeof infoSchema>

const progressImages: Record<Exclude<Step, 6>, string> = {
  1: '/images/book-appointment/progressbar1.png',
  2: '/images/book-appointment/progressbar2.png',
  3: '/images/book-appointment/progressbar3.png',
  4: '/images/book-appointment/progressbar4.png',
  5: '/images/book-appointment/progressbar5.png',
}

export default function BookingWizard() {
  const navigate = useNavigate()
  const [searchParams] = useSearchParams()
  const initialPetId = searchParams.get('pet') ? Number(searchParams.get('pet')) : null
  const [step, setStep] = useState<Step>(1)
  const [draft, setDraft] = useState<BookingDraft>({
    appointment_type: initialPetId ? 'Adopt' : null,
    pet_id: initialPetId,
    appointment_date: null,
    time_slot: null,
    first_name: '',
    middle_name: '',
    last_name: '',
    mobile_number: '',
    home_address: '',
    email_address: '',
  })
  const [created, setCreated] = useState<Appointment | null>(null)
  const [slotConflict, setSlotConflict] = useState(false)
  const [submitting, setSubmitting] = useState(false)
  const [confirmError, setConfirmError] = useState<string | null>(null)
  const [confirmations, setConfirmations] = useState({ availability: false, location: false, changes: false })

  const { data: slots } = useSlots(draft.appointment_date ?? '')
  const { data: schedule } = useUpcomingSchedule()

  const set = <K extends keyof BookingDraft>(key: K, value: BookingDraft[K]) =>
    setDraft((d) => ({ ...d, [key]: value }))

  async function handleSubmitBooking() {
    setConfirmError(null)
    if (!confirmations.availability || !confirmations.location || !confirmations.changes) {
      setConfirmError('Please tick all the checkboxes to confirm your availability and understanding of the appointment details.')
      return
    }
    if (!draft.appointment_type || !draft.appointment_date || !draft.time_slot) return

    setSubmitting(true)
    try {
      const payload: BookingPayload = {
        appointment_type: draft.appointment_type,
        pet_id: draft.pet_id,
        appointment_date: draft.appointment_date,
        time_slot: draft.time_slot,
        first_name: draft.first_name,
        middle_name: draft.middle_name,
        last_name: draft.last_name,
        mobile_number: draft.mobile_number,
        home_address: draft.home_address,
        email_address: draft.email_address,
      }
      const appt = await appointmentsApi.store(payload)
      setCreated(appt)
      setStep(6)
    } catch (e) {
      const err = e as { response?: { status?: number; data?: { message?: string } } }
      if (err.response?.status === 409) {
        setSlotConflict(true)
        setStep(3)
      } else {
        setConfirmError(err.response?.data?.message ?? 'Failed to book appointment. Please try again.')
      }
    } finally {
      setSubmitting(false)
    }
  }

  return (
    <div className="min-h-screen bg-repaw-bg font-sans">
      <div className="bg-repaw-dark sticky top-0 z-40">
        <div className="max-w-5xl mx-auto px-6 py-4 flex items-center justify-between">
          <img src={logo} alt="rePaw City" className="h-12 w-auto" />
          <h1 className="text-repaw-bg font-serif text-xl sm:text-2xl font-bold">Make an Appointment</h1>
        </div>
      </div>

      <div className="max-w-5xl mx-auto px-6 py-8">
        {step < 6 && (
          <div className="flex justify-center mb-8">
            <img src={progressImages[step as Exclude<Step, 6>]} alt={`Step ${step} of 5`} className="max-w-xs sm:max-w-md" />
          </div>
        )}

        {step === 1 && (
          <StepShell title="SET UP AN APPOINTMENT ONLINE">
            <div className="flex flex-col items-center gap-6">
              <img src="/images/book-appointment/imnotarobot.png" alt="I am not a robot" className="w-64" />
              <button onClick={() => setStep(2)} className="btnn bg-repaw-text text-repaw-bg rounded-full px-8 py-3 text-[15px] font-medium uppercase tracking-wide hover:bg-repaw-dark transition-colors duration-300">
                Get Started
              </button>
            </div>
          </StepShell>
        )}

        {step === 2 && (
          <StepShell title="APPOINTMENT TYPE">
            <div className="flex flex-col items-center gap-6">
              <select
                value={draft.appointment_type ?? ''}
                onChange={(e) => {
                  const t = e.target.value as AppointmentType
                  set('appointment_type', t)
                  if (t !== 'Adopt' && t !== 'Visit') set('pet_id', null)
                }}
                className="w-80 rounded-xl border border-repaw-hover bg-white/70 px-4 py-3 text-repaw-text focus:outline-none focus:ring-2 focus:ring-repaw-text"
              >
                <option value="">Select</option>
                {(['Adopt', 'Donate', 'Visit', 'Volunteer'] as const).map((t) => (
                  <option key={t} value={t}>
                    {t}
                  </option>
                ))}
              </select>

              {(draft.appointment_type === 'Adopt' || draft.appointment_type === 'Visit') && (
                <PetPicker selectedId={draft.pet_id} onChange={(id) => set('pet_id', id)} />
              )}

              <div className="flex gap-4">
                <button onClick={() => setStep(1)} className="bg-repaw-hover text-repaw-dark rounded-full px-8 py-3 text-[15px] font-medium uppercase tracking-wide hover:bg-repaw-hover/70 transition-colors">
                  Back
                </button>
                <button
                  onClick={() => draft.appointment_type && setStep(3)}
                  disabled={!draft.appointment_type}
                  className="bg-repaw-text text-repaw-bg rounded-full px-8 py-3 text-[15px] font-medium uppercase tracking-wide hover:bg-repaw-dark transition-colors disabled:opacity-50"
                >
                  Next
                </button>
              </div>
            </div>
          </StepShell>
        )}

        {step === 3 && <StepDate draft={draft} set={set} slots={slots?.booked ?? []} schedule={schedule ?? []} slotConflict={slotConflict} setSlotConflict={setSlotConflict} onBack={() => setStep(2)} onNext={() => setStep(4)} />}

        {step === 4 && <StepInfo draft={draft} onBack={() => setStep(3)} onNext={() => setStep(5)} />}

        {step === 5 && (
          <StepShell title="Appointment Confirmation">
            <div className="max-w-xl mx-auto">
              <div className="bg-white/70 rounded-3xl p-8 border border-repaw-hover/40 shadow-sm">
                <div className="space-y-3 text-sm text-repaw-text/90 mb-6">
                  <p>
                    <strong className="text-repaw-dark">Type:</strong> {draft.appointment_type}
                  </p>
                  {draft.pet_id && <PetSummary petId={draft.pet_id} />}
                  <p>
                    <strong className="text-repaw-dark">Date:</strong> {draft.appointment_date ? new Date(draft.appointment_date + 'T00:00:00').toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' }) : ''}
                  </p>
                  <p>
                    <strong className="text-repaw-dark">Time:</strong> {draft.time_slot}
                  </p>
                </div>

                {confirmError && (
                  <div className="mb-4 rounded-xl border border-repaw-danger/40 bg-red-50 px-4 py-3 text-sm text-repaw-danger">{confirmError}</div>
                )}

                <div className="space-y-3">
                  <label className="flex items-start gap-3 text-sm text-repaw-text/90">
                    <input type="checkbox" checked={confirmations.availability} onChange={(e) => setConfirmations((c) => ({ ...c, availability: e.target.checked }))} className="mt-1" />
                    I confirm my availability for the scheduled appointment on{' '}
                    {draft.appointment_date ? new Date(draft.appointment_date + 'T00:00:00').toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' }) : ''} at {draft.time_slot}.
                  </label>
                  <label className="flex items-start gap-3 text-sm text-repaw-text/90">
                    <input type="checkbox" checked={confirmations.location} onChange={(e) => setConfirmations((c) => ({ ...c, location: e.target.checked }))} className="mt-1" />
                    I am aware of the location/address where the appointment will take place.
                  </label>
                  <label className="flex items-start gap-3 text-sm text-repaw-text/90">
                    <input type="checkbox" checked={confirmations.changes} onChange={(e) => setConfirmations((c) => ({ ...c, changes: e.target.checked }))} className="mt-1" />
                    I will notify you promptly if there are any changes or if I need to reschedule the appointment.
                  </label>
                </div>

                <div className="flex gap-4 mt-8">
                  <button onClick={() => setStep(4)} className="bg-repaw-hover text-repaw-dark rounded-full px-8 py-3 text-[15px] font-medium uppercase tracking-wide hover:bg-repaw-hover/70 transition-colors">
                    Back
                  </button>
                  <button
                    onClick={() => void handleSubmitBooking()}
                    disabled={submitting}
                    className="bg-repaw-text text-repaw-bg rounded-full px-8 py-3 text-[15px] font-medium uppercase tracking-wide hover:bg-repaw-dark transition-colors disabled:opacity-60"
                  >
                    {submitting ? 'Submitting...' : 'Submit'}
                  </button>
                </div>
              </div>
            </div>
          </StepShell>
        )}

        {step === 6 && created && <StepSuccess created={created} onExit={() => navigate('/')} />}
      </div>
    </div>
  )
}

function StepShell({ title, children }: { title: string; children: React.ReactNode }) {
  return (
    <div>
      <h2 className="text-center font-serif text-2xl sm:text-3xl font-bold text-repaw-dark mb-8">{title}</h2>
      {children}
    </div>
  )
}

function PetPicker({ selectedId, onChange }: { selectedId: number | null; onChange: (id: number | null) => void }) {
  const { data, isLoading } = usePets({ per_page: 100 })
  const pets = data?.data ?? []

  return (
    <div className="w-80">
      <label className="block text-sm font-medium text-repaw-dark mb-1.5 text-center">
        Which pet would you like to see? (optional)
      </label>
      <select
        value={selectedId ?? ''}
        onChange={(e) => onChange(e.target.value ? Number(e.target.value) : null)}
        className="w-full rounded-xl border border-repaw-hover bg-white/70 px-4 py-3 text-repaw-text focus:outline-none focus:ring-2 focus:ring-repaw-text"
      >
        <option value="">Any available pet</option>
        {isLoading ? (
          <option disabled>Loading pets...</option>
        ) : (
          pets
            .filter((p) => p.status === 'available' || p.status === 'on_hold')
            .map((p) => (
              <option key={p.id} value={p.id}>
                {p.name} ({p.type})
              </option>
            ))
        )}
      </select>
    </div>
  )
}

function PetSummary({ petId }: { petId: number }) {
  const { data: pet } = usePet(petId)
  if (!pet) return null
  return (
    <p>
      <strong className="text-repaw-dark">Pet:</strong> {pet.name} ({pet.breed})
    </p>
  )
}

function StepDate({
  draft,
  set,
  slots,
  schedule,
  slotConflict,
  setSlotConflict,
  onBack,
  onNext,
}: {
  draft: BookingDraft
  set: <K extends keyof BookingDraft>(key: K, value: BookingDraft[K]) => void
  slots: TimeSlot[]
  schedule: ScheduleDay[]
  slotConflict: boolean
  setSlotConflict: (v: boolean) => void
  onBack: () => void
  onNext: () => void
}) {
  const [monthOffset, setMonthOffset] = useState(0)
  const bookedSet = new Set(slots)
  const scheduleMap = useMemo(() => new Map(schedule.map((s) => [s.date, s])), [schedule])

  const today = useMemo(() => {
    const t = new Date()
    t.setHours(0, 0, 0, 0)
    return t
  }, [])

  const month = useMemo(() => {
    const d = new Date(today.getFullYear(), today.getMonth() + monthOffset, 1)
    return d
  }, [today, monthOffset])

  const cells = useMemo(() => {
    const first = new Date(month.getFullYear(), month.getMonth(), 1)
    const startOffset = (first.getDay() + 6) % 7 // Monday-first
    const daysInMonth = new Date(month.getFullYear(), month.getMonth() + 1, 0).getDate()
    const list: (Date | null)[] = Array.from({ length: startOffset }, () => null)
    for (let d = 1; d <= daysInMonth; d++) {
      list.push(new Date(month.getFullYear(), month.getMonth(), d))
    }
    return list
  }, [month])

  const dateKey = (d: Date) =>
    `${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, '0')}-${String(d.getDate()).padStart(2, '0')}`

  const selectedDay = draft.appointment_date ? scheduleMap.get(draft.appointment_date) : undefined

  const canSubmit = draft.appointment_date && draft.time_slot

  function next() {
    if (!canSubmit) return
    setSlotConflict(false)
    onNext()
  }

  return (
    <StepShell title="CHOOSE APPOINTMENT DATE">
      <div className="max-w-lg mx-auto space-y-6">
        {slotConflict && (
          <div className="rounded-xl border border-repaw-danger/40 bg-red-50 px-4 py-3 text-sm text-repaw-danger">
            The selected date and time slot are unavailable. Please choose another slot or day.
          </div>
        )}

        <div className="bg-white/70 rounded-3xl p-6 border border-repaw-hover/40 shadow-sm">
          <div className="flex items-center justify-between mb-4">
            <button onClick={() => setMonthOffset((o) => Math.max(o - 1, 0))} className="mui-icon text-repaw-dark hover:text-repaw-text" aria-label="Previous month">
              chevron_left
            </button>
            <h3 className="font-serif text-xl font-bold text-repaw-dark">
              {month.toLocaleDateString('en-US', { month: 'long', year: 'numeric' })}
            </h3>
            <button onClick={() => setMonthOffset((o) => o + 1)} className="mui-icon text-repaw-dark hover:text-repaw-text" aria-label="Next month">
              chevron_right
            </button>
          </div>

          <div className="grid grid-cols-7 gap-1 text-center text-xs font-semibold text-repaw-text/70 mb-2">
            {['Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa', 'Su'].map((d) => (
              <div key={d}>{d}</div>
            ))}
          </div>

          <div className="grid grid-cols-7 gap-1">
            {cells.map((d, i) => {
              if (!d) return <div key={i} />
              const key = dateKey(d)
              const past = d < today
              const day = scheduleMap.get(key)
              const closed = day !== undefined && !day.is_open
              const fullyBooked = day?.fully_booked ?? (bookedSet.has('Morning Session') && bookedSet.has('Afternoon Session'))
              const selected = draft.appointment_date === key
              let cls = 'aspect-square rounded-lg flex items-center justify-center text-sm transition-colors '
              if (past) {
                cls += 'text-repaw-text/25 cursor-not-allowed'
              } else if (selected) {
                cls += 'bg-repaw-text text-repaw-bg font-semibold'
              } else if (closed) {
                cls += 'bg-repaw-hover/60 text-repaw-text/50 line-through cursor-not-allowed'
              } else if (fullyBooked) {
                cls += 'bg-repaw-accent text-repaw-dark cursor-not-allowed'
              } else {
                cls += 'hover:bg-repaw-hover cursor-pointer text-repaw-text'
              }
              return (
                <button
                  key={i}
                  disabled={past || closed || fullyBooked}
                  onClick={() => set('appointment_date', key)}
                  className={cls}
                >
                  {d.getDate()}
                </button>
              )
            })}
          </div>
        </div>

        <div className="bg-white/70 rounded-3xl p-6 border border-repaw-hover/40 shadow-sm space-y-4">
          <div className="flex items-center gap-3 text-sm">
            <span className="w-4 h-4 rounded bg-repaw-text" />
            <span>Available booking slots.</span>
          </div>
          <div className="flex items-center gap-3 text-sm">
            <span className="w-4 h-4 rounded bg-repaw-accent" />
            <span>Fully booked day.</span>
          </div>
          <div className="flex items-center gap-3 text-sm">
            <span className="w-4 h-4 rounded bg-repaw-hover" />
            <span>Shelter closed.</span>
          </div>

          <div>
            <label className="block text-sm font-medium text-repaw-dark mb-1.5">Date:</label>
            <input
              type="date"
              value={draft.appointment_date ?? ''}
              min={dateKey(today)}
              onChange={(e) => set('appointment_date', e.target.value)}
              className="w-full rounded-xl border border-repaw-hover bg-repaw-bg px-4 py-2.5 text-repaw-text focus:outline-none focus:ring-2 focus:ring-repaw-text"
            />
          </div>

          <div>
            <label className="block text-sm font-medium text-repaw-dark mb-1.5">Session:</label>
            <select
              value={draft.time_slot ?? ''}
              disabled={!!draft.appointment_date && selectedDay?.is_open === false}
              onChange={(e) => set('time_slot', e.target.value as TimeSlot)}
              className="w-full rounded-xl border border-repaw-hover bg-repaw-bg px-4 py-2.5 text-repaw-text focus:outline-none focus:ring-2 focus:ring-repaw-text disabled:opacity-50"
            >
              <option value="">Select Session</option>
              <option value="Morning Session" disabled={selectedDay?.morning_full ?? bookedSet.has('Morning Session')}>
                Morning Session (9:00 AM - 11:30 AM)
              </option>
              <option value="Afternoon Session" disabled={selectedDay?.afternoon_full ?? bookedSet.has('Afternoon Session')}>
                Afternoon Session (1:00 PM - 4:30 PM)
              </option>
            </select>
            {draft.appointment_date && selectedDay && !selectedDay.is_open && (
              <p className="mt-1 text-xs text-repaw-danger">{selectedDay.reason ?? 'Shelter closed'}</p>
            )}
            {draft.appointment_date && draft.time_slot && (selectedDay?.is_open === false || (draft.time_slot === 'Morning Session' && selectedDay?.morning_full) || (draft.time_slot === 'Afternoon Session' && selectedDay?.afternoon_full)) && (
              <p className="mt-1 text-xs text-repaw-danger">This slot is unavailable.</p>
            )}
          </div>
        </div>

        <div className="flex justify-center gap-4">
          <button onClick={onBack} className="bg-repaw-hover text-repaw-dark rounded-full px-8 py-3 text-[15px] font-medium uppercase tracking-wide hover:bg-repaw-hover/70 transition-colors">
            Back
          </button>
          <button onClick={next} disabled={!canSubmit} className="bg-repaw-text text-repaw-bg rounded-full px-8 py-3 text-[15px] font-medium uppercase tracking-wide hover:bg-repaw-dark transition-colors disabled:opacity-50">
            Next
          </button>
        </div>
      </div>
    </StepShell>
  )
}

function StepInfo({ draft, onBack, onNext }: { draft: BookingDraft; onBack: () => void; onNext: () => void }) {
  const {
    register,
    handleSubmit,
    formState: { errors },
  } = useForm<InfoForm>({
    resolver: zodResolver(infoSchema),
    defaultValues: {
      first_name: draft.first_name,
      middle_name: draft.middle_name,
      last_name: draft.last_name,
      mobile_number: draft.mobile_number,
      home_address: draft.home_address,
      email_address: draft.email_address,
    },
  })

  const onSubmit = (data: InfoForm) => {
    Object.assign(draft, data)
    onNext()
  }

  return (
    <StepShell title="Personal Information">
      <form onSubmit={handleSubmit(onSubmit)} className="max-w-xl mx-auto bg-white/70 rounded-3xl p-8 border border-repaw-hover/40 shadow-sm space-y-4" noValidate>
        <Input label="First Name:" required error={errors.first_name?.message} {...register('first_name')} />
        <Input label="Middle Name:" {...register('middle_name')} />
        <Input label="Last Name:" required error={errors.last_name?.message} {...register('last_name')} />
        <Input label="Mobile Number:" type="tel" required error={errors.mobile_number?.message} {...register('mobile_number')} />
        <Input label="Home Address:" required error={errors.home_address?.message} {...register('home_address')} />
        <Input label="Email Address:" type="email" required error={errors.email_address?.message} {...register('email_address')} />

        <div className="flex justify-center gap-4 pt-2">
          <button type="button" onClick={onBack} className="bg-repaw-hover text-repaw-dark rounded-full px-8 py-3 text-[15px] font-medium uppercase tracking-wide hover:bg-repaw-hover/70 transition-colors">
            Back
          </button>
          <button type="submit" className="bg-repaw-text text-repaw-bg rounded-full px-8 py-3 text-[15px] font-medium uppercase tracking-wide hover:bg-repaw-dark transition-colors">
            Next
          </button>
        </div>

        <p className="text-xs text-repaw-text/70 text-center">
          We value your privacy. Your information will not be used for purposes other than this appointment application.
        </p>
      </form>
    </StepShell>
  )
}

function Input({
  label,
  required,
  error,
  type = 'text',
  ...rest
}: {
  label: string
  required?: boolean
  error?: string
  type?: string
  name: string
  onChange: (event: React.ChangeEvent<HTMLInputElement>) => void
  onBlur: (event: React.FocusEvent<HTMLInputElement>) => void
  ref: React.Ref<HTMLInputElement>
}) {
  return (
    <div>
      <label className="block text-sm font-medium text-repaw-dark mb-1.5">
        {label} {required && <span className="text-repaw-danger">*</span>}
      </label>
      <input
        type={type}
        {...rest}
        className="w-full rounded-xl border border-repaw-hover bg-repaw-bg px-4 py-2.5 text-repaw-text focus:outline-none focus:ring-2 focus:ring-repaw-text"
      />
      {error && <p className="mt-1 text-xs text-repaw-danger">{error}</p>}
    </div>
  )
}

function StepSuccess({ created, onExit }: { created: Appointment; onExit: () => void }) {
  return (
    <div className="max-w-2xl mx-auto">
      <h1 className="text-center font-serif text-3xl font-bold text-repaw-dark mb-6">Booking Successful!</h1>
      <div className="bg-white/70 rounded-3xl p-8 border border-repaw-hover/40 shadow-sm whitespace-pre-line text-sm text-repaw-text/90 leading-relaxed">
        <p>
          Congratulations! Your appointment has been successfully booked. We are excited to assist you with your needs and look forward to meeting you at the scheduled time. Please find the details of your appointment below:
        </p>
        <div className="mt-4 space-y-1">
          <p>
            - Appointment Date: {new Date(created.appointment_date + 'T00:00:00').toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })}
          </p>
          <p>- Appointment Time: {created.time_slot}</p>
          <p>- Service: {created.appointment_type}</p>
          <p>- Location: {SHELTER_ADDRESS_INLINE}</p>
        </div>
        <div className="mt-4">
          <strong>Important Information:</strong>
          <p>1. Arrival Time: Please arrive at the location at least 10 minutes prior to your scheduled appointment time.</p>
          <p>2. Cancellation or Rescheduling: If you need to cancel or reschedule your appointment, kindly contact our customer support team at repawcity@gmail.com at least 24 hours in advance.</p>
          <p>3. Payment: Payment for the service will be collected at the time of the appointment. We accept various forms of payment, including cash, credit cards, and online transfers.</p>
        </div>
        <p className="mt-4">
          To check the status of your appointment or make any modifications, you can log in to your profile on our website. Your appointment details and status will be available under your profile for easy access and management.
        </p>
        <p className="mt-4">
          Should you have any questions or require further assistance, please feel free to reach out to our customer support team. We are here to ensure your experience is seamless and satisfactory.
        </p>
        <p className="mt-4">Thank you for choosing our services. We appreciate your trust and look forward to serving you soon!</p>
        <p className="mt-4">Best regards, RePaw City</p>
      </div>
      <div className="text-center mt-8">
        <button onClick={onExit} className="bg-repaw-text text-repaw-bg rounded-full px-8 py-3 text-[15px] font-medium uppercase tracking-wide hover:bg-repaw-dark transition-colors">
          Exit
        </button>
      </div>
    </div>
  )
}
