export interface User {
  id: number
  fname: string
  lname: string
  email: string
  user_type: string
  created_at: string
}

export interface Pet {
  id: number
  name: string
  type: 'Dog' | 'Cat'
  breed: string
  sex: 'Male' | 'Female'
  weight: string
  age: string
  date: string | null
  about: string
  image: string
  image_url: string
  is_featured: number
}

export interface NewsArticle {
  id: number
  title: string
  details: string
  image: string
  image_url: string
  date_published: string
  is_featured: boolean
}

export type AppointmentType = 'Adopt' | 'Donate' | 'Visit' | 'Volunteer'
export type TimeSlot = 'Morning Session' | 'Afternoon Session'
export type AppointmentStatus = 'Pending' | 'Accepted' | 'Cancelled'

export interface Appointment {
  id: number
  appointment_type: AppointmentType
  appointment_date: string
  time_slot: TimeSlot
  first_name: string
  middle_name: string | null
  last_name: string
  mobile_number: string
  home_address: string
  email_address: string
  status: AppointmentStatus
  message: string
  created_at: string
}

export interface AuthResponse {
  user: User
  token: string
}

export interface SlotsResponse {
  date: string
  booked: TimeSlot[]
}

export interface DashboardResponse {
  counts: {
    total: number
    adopt: number
    donate: number
    visit: number
    volunteer: number
  }
  events: { start: string; title: TimeSlot }[]
  date: string
}

export interface Paginated<T> {
  data: T[]
  links: Record<string, string | null>
  meta: {
    current_page: number
    from: number
    last_page: number
    per_page: number
    to: number
    total: number
  }
}
