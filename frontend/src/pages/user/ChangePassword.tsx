import { useState } from 'react'
import { useForm } from 'react-hook-form'
import { zodResolver } from '@hookform/resolvers/zod'
import { z } from 'zod'
import { useChangePassword } from '../../hooks/useUser'

const schema = z
  .object({
    old_password: z.string().min(1, 'Old password is required'),
    new_password: z.string().min(8, 'Password must be at least 8 characters'),
    new_password_confirmation: z.string().min(1, 'Confirm your new password'),
  })
  .refine((d) => d.new_password === d.new_password_confirmation, {
    message: 'Passwords do not match',
    path: ['new_password_confirmation'],
  })

type FormData = z.infer<typeof schema>

export default function ChangePassword() {
  const changePassword = useChangePassword()
  const [notice, setNotice] = useState<string | null>(null)
  const [error, setError] = useState<string | null>(null)
  const [showOld, setShowOld] = useState(false)
  const [showNew, setShowNew] = useState(false)
  const [showConfirm, setShowConfirm] = useState(false)

  const {
    register,
    handleSubmit,
    reset,
    formState: { errors, isSubmitting },
  } = useForm<FormData>({ resolver: zodResolver(schema) })

  async function onSubmit(data: FormData) {
    setNotice(null)
    setError(null)
    try {
      const res = await changePassword.mutateAsync(data)
      setNotice(res.message)
      reset()
    } catch (e) {
      const err = e as { response?: { data?: { message?: string; errors?: Record<string, string[]> } } }
      setError(err.response?.data?.message ?? 'Failed to update password')
    }
  }

  return (
    <div className="space-y-6">
      <div className="flex items-center gap-3">
        <span className="mui-icon text-3xl text-repaw-dark">lock</span>
        <h1 className="font-serif text-3xl font-bold text-repaw-dark">Change Password</h1>
      </div>

      <div className="bg-white/70 rounded-3xl p-8 border border-repaw-hover/40 shadow-sm">
        {notice && (
          <div className="mb-6 rounded-xl border border-green-400/40 bg-green-50 px-4 py-3 text-sm text-green-800">{notice}</div>
        )}
          {error && (
            <div className="mb-6 rounded-xl border border-repaw-danger/40 bg-red-50 px-4 py-3 text-sm text-repaw-danger">{error}</div>
          )}

          <form onSubmit={handleSubmit(onSubmit)} className="space-y-5" noValidate>
            <PasswordField
              label="Old Password:"
              placeholder="Enter Old Password"
              show={showOld}
              onToggle={() => setShowOld((s) => !s)}
              error={errors.old_password?.message}
              {...register('old_password')}
            />
            <PasswordField
              label="New Password:"
              placeholder="Enter New Password"
              show={showNew}
              onToggle={() => setShowNew((s) => !s)}
              error={errors.new_password?.message}
              {...register('new_password')}
            />
            <PasswordField
              label="Confirm New Password:"
              placeholder="Confirm New Password"
              show={showConfirm}
              onToggle={() => setShowConfirm((s) => !s)}
              error={errors.new_password_confirmation?.message}
              {...register('new_password_confirmation')}
            />

            <button
              type="submit"
              disabled={isSubmitting}
              className="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-repaw-text text-repaw-bg rounded-full px-8 py-3 text-[15px] font-medium uppercase tracking-wide hover:bg-repaw-dark transition-colors duration-300 disabled:opacity-60"
            >
              <span className="mui-icon">lock</span> {isSubmitting ? 'Updating...' : 'Update'}
            </button>
          </form>
        </div>
      </div>
  )
}

function PasswordField({
  label,
  placeholder,
  show,
  onToggle,
  error,
  ...rest
}: {
  label: string
  placeholder: string
  show: boolean
  onToggle: () => void
  error?: string
  name: string
}) {
  return (
    <div>
      <label className="block text-sm font-medium text-repaw-dark mb-1.5">{label}</label>
      <div className="relative">
        <input
          type={show ? 'text' : 'password'}
          placeholder={placeholder}
          {...rest}
          className="w-full rounded-xl border border-repaw-hover bg-repaw-bg px-4 py-2.5 pr-12 text-repaw-text placeholder-repaw-text/50 focus:outline-none focus:ring-2 focus:ring-repaw-text"
        />
        <span
          className="mui-icon text-repaw-text/60 absolute right-4 top-1/2 -translate-y-1/2 text-[20px] cursor-pointer"
          onClick={onToggle}
        >
          {show ? 'visibility_off' : 'visibility'}
        </span>
      </div>
      {error && <p className="mt-1 text-xs text-repaw-danger">{error}</p>}
    </div>
  )
}
