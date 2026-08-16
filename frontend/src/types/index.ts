export interface User {
  id: number
  fname: string
  lname: string
  email: string
  user_type: string
  created_at: string
}

export type PetStatus = 'available' | 'on_hold' | 'adopted' | 'deceased'

export type PetRecordType = 'vaccination' | 'vet_visit' | 'grooming' | 'intake' | 'note'

export interface PetRecord {
  id: number
  type: PetRecordType
  title: string
  details: string
  record_date: string
  created_by?: string
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
  intake_date: string | null
  intake_notes: string | null
  microchip: string | null
  about: string
  image: string
  image_url: string
  thumb_url: string
  is_featured: number
  status: PetStatus
}

export interface NewsArticle {
  id: number
  title: string
  details: string
  image: string
  image_url: string
  thumb_url: string
  date_published: string
  is_featured: boolean
}

export type AppointmentType = 'Adopt' | 'Donate' | 'Visit' | 'Volunteer'
export type TimeSlot = 'Morning Session' | 'Afternoon Session'
export type AppointmentStatus = 'Pending' | 'Accepted' | 'Cancelled'

export interface Appointment {
  id: number
  appointment_type: AppointmentType
  pet: Pet | null
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

export type ApplicationStatus = 'draft' | 'submitted' | 'under_review' | 'approved' | 'adopted' | 'rejected'

export interface ApplicationAnswers {
  housing: string
  other_pets: string
  experience: string
  why_this_pet: string
}

export interface AdoptionApplication {
  id: number
  pet: Pet
  user?: {
    id: number
    fname: string
    lname: string
    email: string
  }
  appointment_id: number | null
  status: ApplicationStatus
  answers: ApplicationAnswers
  notes: string | null
  created_at: string
  updated_at: string
}

export interface AuthResponse {
  user: User
  token: string
}

export type VolunteerStatus = 'pending' | 'active' | 'inactive'

export interface Volunteer {
  id: number
  user_id: number
  user?: {
    id: number
    fname: string
    lname: string
    email: string
  }
  availability: string[] | null
  skills: string | null
  interests: string | null
  status: VolunteerStatus
  total_hours: number
  created_at: string
}

export interface VolunteerShift {
  id: number
  volunteer_id: number
  date: string
  time_slot: TimeSlot
  hours_logged: number
  activity: string | null
  created_at: string
}

export interface SlotsResponse {
  date: string
  is_open: boolean
  reason: string | null
  morning_capacity: number
  afternoon_capacity: number
  booked: TimeSlot[]
  morning_full: boolean
  afternoon_full: boolean
  fully_booked: boolean
}

export interface ScheduleDay {
  date: string
  is_open: boolean
  reason: string | null
  morning_capacity: number
  afternoon_capacity: number
  morning_booked: number
  afternoon_booked: number
  morning_full: boolean
  afternoon_full: boolean
  fully_booked: boolean
}

export interface AdminSchedule {
  id: number
  date: string
  is_open: boolean
  morning_capacity: number
  afternoon_capacity: number
  reason: string | null
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
