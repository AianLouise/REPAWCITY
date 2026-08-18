export { api, API_URL, resolveMedia } from './api/client'
export { authApi } from './api/auth'
export { applicationsApi } from './api/applications'
export type { ApplicationPayload } from './api/applications'
export { communityApi } from './api/community'
export type { VolunteerApplyPayload } from './api/community'
export { newsApi } from './api/news'
export { petRecordsApi } from './api/petRecords'
export { petsApi } from './api/pets'
export type { PetFilters } from './api/pets'
export { schedulesApi } from './api/schedules'

export { useMyApplications, useAllApplications, useApplicationActions } from './hooks/useApplications'
export { useMyVolunteer, useMyShifts, useAdminVolunteers, useCommunityActions } from './hooks/useCommunity'
export { usePetRecords, useAdminPetRecords, usePetRecordActions } from './hooks/usePetRecords'
export { useUpcomingSchedule, useAdminSchedules, useScheduleActions } from './hooks/useSchedule'

export type {
  Pet,
  PetStatus,
  PetRecord,
  PetRecordType,
  NewsArticle,
  Appointment,
  AppointmentType,
  TimeSlot,
  AppointmentStatus,
  ApplicationStatus,
  ApplicationAnswers,
  AdoptionApplication,
  Volunteer,
  VolunteerStatus,
  VolunteerShift,
  SlotsResponse,
  ScheduleDay,
  AdminSchedule,
  DashboardResponse,
  Paginated,
} from './types'

export type { User, AuthResponse } from '@repaw/auth'
