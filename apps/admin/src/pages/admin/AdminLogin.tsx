import { useState } from 'react'
import { useForm } from 'react-hook-form'
import { zodResolver } from '@hookform/resolvers/zod'
import { z } from 'zod'
import { useLocation, useNavigate } from 'react-router-dom'
import { AuthLayout } from '@repaw/ui'
import { authApi } from '@repaw/api-client'
import { useAuthStore } from '@repaw/auth'
import { CLIENT_URL } from '@repaw/config'
import { MdLock, MdPerson, MdVisibility, MdVisibilityOff } from 'react-icons/md'

const schema = z.object({
  email: z.string().email('Enter a valid email'),
  password: z.string().min(1, 'Password is required'),
})

type FormData = z.infer<typeof schema>

export default function AdminLogin() {
  const setAuth = useAuthStore((s) => s.setAuth)
  const navigate = useNavigate()
  const location = useLocation()
  const [error, setError] = useState<string | null>(null)
  const [showPassword, setShowPassword] = useState(false)

  const {
    register,
    handleSubmit,
    formState: { errors, isSubmitting },
  } = useForm<FormData>({ resolver: zodResolver(schema) })

  const from = (location.state as { from?: string } | null)?.from

  async function onSubmit(data: FormData) {
    setError(null)
    try {
      const res = await authApi.login(data)
      if (res.user.user_type !== '1') {
        setError('This account does not have admin access.')
        return
      }
      setAuth(res.user, res.token)
      navigate(from ?? '/', { replace: true })
    } catch (e) {
      const err = e as { response?: { data?: { message?: string } } }
      setError(err.response?.data?.message ?? 'Login failed. Please try again.')
    }
  }

  return (
    <AuthLayout>
      <form
        onSubmit={handleSubmit(onSubmit)}
        className="bg-white/70 rounded-3xl p-8 border border-repaw-hover/40 shadow-sm"
        noValidate
      >
        <div className="text-center mb-8">
          <h1 className="font-serif text-3xl font-bold text-repaw-dark">Admin Portal</h1>
          <p className="text-repaw-text/80 mt-1">Sign in to manage rePaw City</p>
        </div>

        {error && (
          <div className="mb-4 rounded-xl border border-repaw-danger/40 bg-red-50 px-4 py-3 text-sm text-repaw-danger">
            {error}
          </div>
        )}

        <div className="relative mb-4">
          <MdPerson size={20} className="text-repaw-dark absolute left-4 top-1/2 -translate-y-1/2" />
          <input
            type="email"
            placeholder="Email"
            {...register('email')}
            className="w-full rounded-xl border border-repaw-hover bg-repaw-bg pl-12 pr-4 py-3 text-repaw-text placeholder-repaw-text/50 focus:outline-none focus:ring-2 focus:ring-repaw-text"
          />
          {errors.email && <p className="mt-1 text-xs text-repaw-danger">{errors.email.message}</p>}
        </div>

        <div className="relative mb-4">
          <MdLock size={20} className="text-repaw-dark absolute left-4 top-1/2 -translate-y-1/2" />
          <input
            type={showPassword ? 'text' : 'password'}
            placeholder="Password"
            {...register('password')}
            className="w-full rounded-xl border border-repaw-hover bg-repaw-bg pl-12 pr-12 py-3 text-repaw-text placeholder-repaw-text/50 focus:outline-none focus:ring-2 focus:ring-repaw-text"
          />
          <button
            type="button"
            onClick={() => setShowPassword((s) => !s)}
            className="absolute right-4 top-1/2 -translate-y-1/2 text-repaw-text/60 hover:text-repaw-dark transition-colors"
            aria-label={showPassword ? 'Hide password' : 'Show password'}
          >
            {showPassword ? <MdVisibilityOff size={20} /> : <MdVisibility size={20} />}
          </button>
          {errors.password && <p className="mt-1 text-xs text-repaw-danger">{errors.password.message}</p>}
        </div>

        <button
          type="submit"
          disabled={isSubmitting}
          className="w-full mt-2 bg-repaw-text text-repaw-bg rounded-full px-6 py-3 text-[15px] font-medium uppercase tracking-wide hover:bg-repaw-dark transition-colors duration-300 disabled:opacity-60"
        >
          {isSubmitting ? 'Signing in...' : 'Login'}
        </button>

        <p className="text-center text-repaw-text/80 mt-6">
          <a href={CLIENT_URL} className="text-repaw-dark font-medium underline underline-offset-2 hover:text-repaw-text">
            &larr; Back to rePaw City
          </a>
        </p>
      </form>
    </AuthLayout>
  )
}