import { useState } from 'react'
import { useForm } from 'react-hook-form'
import { zodResolver } from '@hookform/resolvers/zod'
import { z } from 'zod'
import { useAuthStore } from '../../store/authStore'
import { useUpdateProfile } from '../../hooks/useUser'
import { PageHero } from '../../components/Shared'

const schema = z.object({
  fname: z.string().min(1, 'First name is required'),
  lname: z.string().min(1, 'Last name is required'),
  email: z.string().email('Enter a valid email'),
})

type FormData = z.infer<typeof schema>

export default function Profile() {
  const user = useAuthStore((s) => s.user)
  const updateProfile = useUpdateProfile()
  const [notice, setNotice] = useState<string | null>(null)
  const [error, setError] = useState<string | null>(null)

  const {
    register,
    handleSubmit,
    formState: { errors, isSubmitting },
  } = useForm<FormData>({
    resolver: zodResolver(schema),
    defaultValues: {
      fname: user?.fname ?? '',
      lname: user?.lname ?? '',
      email: user?.email ?? '',
    },
  })

  async function onSubmit(data: FormData) {
    setNotice(null)
    setError(null)
    try {
      await updateProfile.mutateAsync(data)
      setNotice('Profile updated successfully')
    } catch (e) {
      const err = e as { response?: { data?: { errors?: Record<string, string[]> } } }
      setError(err.response?.data?.errors?.email?.[0] ?? 'Failed to update profile')
    }
  }

  return (
    <div>
      <PageHero title="Edit Profile" subtitle="Update your personal information." />
      <section className="max-w-2xl mx-auto px-6 sm:px-8 lg:px-12 pt-16 lg:pt-20 pb-20">
        <div className="bg-white/70 rounded-3xl p-8 border border-repaw-hover/40 shadow-sm">
          {notice && (
            <div className="mb-6 rounded-xl border border-green-400/40 bg-green-50 px-4 py-3 text-sm text-green-800">{notice}</div>
          )}
          {error && (
            <div className="mb-6 rounded-xl border border-repaw-danger/40 bg-red-50 px-4 py-3 text-sm text-repaw-danger">{error}</div>
          )}

          <form onSubmit={handleSubmit(onSubmit)} className="space-y-5" noValidate>
            <div>
              <label htmlFor="fname" className="block text-sm font-medium text-repaw-dark mb-1.5">
                First Name:
              </label>
              <input
                type="text"
                id="fname"
                {...register('fname')}
                className="w-full rounded-xl border border-repaw-hover bg-repaw-bg px-4 py-2.5 text-repaw-text focus:outline-none focus:ring-2 focus:ring-repaw-text"
              />
              {errors.fname && <p className="mt-1 text-xs text-repaw-danger">{errors.fname.message}</p>}
            </div>

            <div>
              <label htmlFor="lname" className="block text-sm font-medium text-repaw-dark mb-1.5">
                Last Name:
              </label>
              <input
                type="text"
                id="lname"
                {...register('lname')}
                className="w-full rounded-xl border border-repaw-hover bg-repaw-bg px-4 py-2.5 text-repaw-text focus:outline-none focus:ring-2 focus:ring-repaw-text"
              />
              {errors.lname && <p className="mt-1 text-xs text-repaw-danger">{errors.lname.message}</p>}
            </div>

            <div>
              <label htmlFor="email" className="block text-sm font-medium text-repaw-dark mb-1.5">
                Email:
              </label>
              <input
                type="email"
                id="email"
                {...register('email')}
                className="w-full rounded-xl border border-repaw-hover bg-repaw-bg px-4 py-2.5 text-repaw-text focus:outline-none focus:ring-2 focus:ring-repaw-text"
              />
              {errors.email && <p className="mt-1 text-xs text-repaw-danger">{errors.email.message}</p>}
            </div>

            <button
              type="submit"
              disabled={isSubmitting}
              className="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-repaw-text text-repaw-bg rounded-full px-8 py-3 text-[15px] font-medium uppercase tracking-wide hover:bg-repaw-dark transition-colors duration-300 disabled:opacity-60"
            >
              <span className="mui-icon">save</span> {isSubmitting ? 'Saving...' : 'Update'}
            </button>
          </form>
        </div>
      </section>
    </div>
  )
}
