import { useState } from 'react'
import { useForm } from 'react-hook-form'
import { zodResolver } from '@hookform/resolvers/zod'
import { z } from 'zod'
import { Link, useNavigate } from 'react-router-dom'
import AuthLayout from '../../components/AuthLayout'
import { authApi } from '../../api/auth'
import { useAuthStore } from '../../store/authStore'

const schema = z
  .object({
    fname: z.string().min(1, 'First name is required'),
    lname: z.string().min(1, 'Last name is required'),
    email: z.string().email('Enter a valid email'),
    password: z.string().min(8, 'Password must be at least 8 characters'),
    password_confirmation: z.string().min(1, 'Confirm your password'),
  })
  .refine((d) => d.password === d.password_confirmation, {
    message: 'Passwords do not match',
    path: ['password_confirmation'],
  })

type FormData = z.infer<typeof schema>

export default function Register() {
  const setAuth = useAuthStore((s) => s.setAuth)
  const navigate = useNavigate()
  const [error, setError] = useState<string | null>(null)
  const [showPassword, setShowPassword] = useState(false)
  const [showConfirm, setShowConfirm] = useState(false)

  const {
    register,
    handleSubmit,
    formState: { errors, isSubmitting },
  } = useForm<FormData>({ resolver: zodResolver(schema) })

  async function onSubmit(data: FormData) {
    setError(null)
    try {
      const res = await authApi.register(data)
      setAuth(res.user, res.token)
      navigate('/', { replace: true })
    } catch (e) {
      const err = e as { response?: { data?: { message?: string; errors?: Record<string, string[]> } } }
      const msg = err.response?.data?.errors?.email?.[0] ?? err.response?.data?.message
      setError(msg ?? 'Registration failed. Please try again.')
    }
  }

  return (
    <AuthLayout>
      <form onSubmit={handleSubmit(onSubmit)} className="bg-white/70 rounded-3xl p-8 border border-repaw-hover/40 shadow-sm" noValidate autoComplete="off">
        <div className="text-center mb-8">
          <h1 className="font-serif text-3xl font-bold text-repaw-dark">Create an Account</h1>
          <p className="text-repaw-text/80 mt-1">Let's get started!</p>
        </div>

        {error && (
          <div className="mb-4 rounded-xl border border-repaw-danger/40 bg-red-50 px-4 py-3 text-sm text-repaw-danger">{error}</div>
        )}

        <Field icon="person" placeholder="First Name" error={errors.fname?.message} {...register('fname')} />
        <Field icon="person" placeholder="Last Name" error={errors.lname?.message} {...register('lname')} />
        <Field icon="mail" type="email" placeholder="Email" error={errors.email?.message} {...register('email')} />

        <div className="relative mb-4">
          <span className="mui-icon text-repaw-dark absolute left-4 top-1/2 -translate-y-1/2 text-[20px]">lock</span>
          <input
            type={showPassword ? 'text' : 'password'}
            placeholder="Password"
            {...register('password')}
            className="w-full rounded-xl border border-repaw-hover bg-repaw-bg pl-12 pr-12 py-3 text-repaw-text placeholder-repaw-text/50 focus:outline-none focus:ring-2 focus:ring-repaw-text"
          />
          <span
            className="mui-icon text-repaw-text/60 absolute right-4 top-1/2 -translate-y-1/2 text-[20px] cursor-pointer"
            onClick={() => setShowPassword((s) => !s)}
          >
            {showPassword ? 'visibility_off' : 'visibility'}
          </span>
          {errors.password && <p className="mt-1 text-xs text-repaw-danger">{errors.password.message}</p>}
        </div>

        <div className="relative mb-4">
          <span className="mui-icon text-repaw-dark absolute left-4 top-1/2 -translate-y-1/2 text-[20px]">lock</span>
          <input
            type={showConfirm ? 'text' : 'password'}
            placeholder="Confirm Password"
            {...register('password_confirmation')}
            className="w-full rounded-xl border border-repaw-hover bg-repaw-bg pl-12 pr-12 py-3 text-repaw-text placeholder-repaw-text/50 focus:outline-none focus:ring-2 focus:ring-repaw-text"
          />
          <span
            className="mui-icon text-repaw-text/60 absolute right-4 top-1/2 -translate-y-1/2 text-[20px] cursor-pointer"
            onClick={() => setShowConfirm((s) => !s)}
          >
            {showConfirm ? 'visibility_off' : 'visibility'}
          </span>
          {errors.password_confirmation && <p className="mt-1 text-xs text-repaw-danger">{errors.password_confirmation.message}</p>}
        </div>

        <button
          type="submit"
          disabled={isSubmitting}
          className="w-full mt-2 bg-repaw-text text-repaw-bg rounded-full px-6 py-3 text-[15px] font-medium uppercase tracking-wide hover:bg-repaw-dark transition-colors duration-300 disabled:opacity-60"
        >
          {isSubmitting ? 'Creating account...' : 'Sign Up'}
        </button>

        <p className="text-center text-repaw-text/80 mt-6">
          Already have an Account?
          <br />
          <Link to="/login" className="text-repaw-dark font-medium underline underline-offset-2 hover:text-repaw-text">
            Log in
          </Link>
        </p>
      </form>
    </AuthLayout>
  )
}

function Field({
  icon,
  type = 'text',
  placeholder,
  error,
  ...rest
}: {
  icon: string
  type?: string
  placeholder: string
  error?: string
  name: string
}) {
  return (
    <div className="relative mb-4">
      <span className="mui-icon text-repaw-dark absolute left-4 top-1/2 -translate-y-1/2 text-[20px]">{icon}</span>
      <input
        type={type}
        placeholder={placeholder}
        {...rest}
        className="w-full rounded-xl border border-repaw-hover bg-repaw-bg pl-12 pr-4 py-3 text-repaw-text placeholder-repaw-text/50 focus:outline-none focus:ring-2 focus:ring-repaw-text"
      />
      {error && <p className="mt-1 text-xs text-repaw-danger">{error}</p>}
    </div>
  )
}
