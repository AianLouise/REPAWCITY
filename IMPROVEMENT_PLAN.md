# rePaw City — System Improvement Plan

**Goal:** Evolve the current pet-adoption site into a **fully functional animal shelter management system** where prospective adopters can book shelter visits, manage their adoption journey, and shelter staff can manage pets, appointments, and operations end-to-end.

**Current state (as built):**
- Laravel 13 API (Sanctum auth, ~30 routes) + two React SPAs (client at `repawcity.com`, admin at `admin.repawcity.com`).
- Pets catalog (dogs/cats) with search/filter/pagination + featured pets.
- News/blog + static pages.
- **Appointment booking wizard** (Adopt / Donate / Visit / Volunteer) with date + morning/afternoon slot, unique-slot locking, admin accept/cancel with message.
- Admin panel: dashboard stats, pet/news/users CRUD, featured management, appointment status.
- 58 backend + 20 frontend tests, Scribe API docs.

---

## Part A — Gaps & Problems Today

| # | Gap | Impact |
|---|-----|--------|
| A1 | Appointments are **generic** — not linked to a specific pet | Adopter can't request to see a specific animal; staff can't prepare |
| A2 | No **availability calendar** — admin can't block days (closures, adoption events) | Double-handling, overbooking stress |
| A3 | No **adoption application flow** after a visit | "Book" ends at appointment; no adoption pipeline |
| A4 | No **pet status lifecycle** (Available / On Hold / Adopted / Deceased) | Public sees pets that are already adopted |
| A5 | No **donation tracking** — Donate page is a placeholder | No donor records, no shelter income visibility |
| A6 | No **volunteer management** beyond booking a visit | No volunteer profiles, shifts, or hours |
| A7 | No user dashboard | Logged-in users only have profile/password/notifications |
| A8 | Notifications are **status-only text** | No push/email, no action buttons |
| A9 | No **pet care records** (vet visits, vaccinations, intake history) | Shelter ops not tracked in-system |
| A10 | No analytics beyond daily appointment counts | Can't measure adoption funnel or pet demand |
| A11 | Images upload directly to local storage | No S3/object storage, no image variants (thumb vs full) |
| A12 | No email/notification service wired | Users only see in-app messages when logged in |

---

## Part B — Proposed Architecture (target system)

```
                          ┌──────────────────────────────┐
  repawcity.com           │          rePaw City          │
  (client SPA) ──────────►│  Laravel 13 API + Sanctum    │──► MySQL
  admin.repawcity.com ───►│  (roles: visitor, adopter,   │
  (admin SPA)             │   volunteer, admin)          │
                          └──────────┬───────────────────┘
                                     │
              ┌──────────────────────┼──────────────────────┐
              │                      │                      │
        ┌─────▼─────┐         ┌──────▼──────┐        ┌──────▼──────┐
        │ Email     │         │ Queue       │        │ Object     │
        │ (Mailpit  │         │ (database   │        │ storage    │
        │  /SES)    │         │  queue)     │        │ (S3/Disk)  │
        └───────────┘         └─────────────┘        └─────────────┘
```

**New domain modules (each = migration + model + API + frontend pages):**

1. **Adoption** — pet-linked appointments + multi-step adoption application + pipeline statuses.
2. **Shelter Operations** — pet lifecycle, care/vet records, intake, availability calendar.
3. **Community** — donations (in-kind + monetary) and volunteer program (profiles, shifts, hours).
4. **User Account** — dashboard, saved favorite pets, appointment history, notifications center.
5. **Engagement** — transactional email, in-app notification actions, adoption success stories.

---

## Part C — Detailed Work Plan

> Estimates assume one full-stack dev familiar with the codebase. Backend uses the existing patterns (Controller + FormRequest + Resource + tests); frontend uses existing hooks/API conventions.

---

### Phase 1 — Adoption-linked appointments & pet status lifecycle

**Objective:** An appointment is tied to a specific pet (or "any available"), and pets have a status the public respects.

- **DB**
  - `pets.status` enum: `available, on_hold, adopted, deceased` (default `available`). Migration + backfill all existing pets to `available`.
  - `appointments.pet_id` nullable FK → `pets.id`. `adopter_user_id` nullable FK (who the appointment is for, when booked by an admin on behalf of someone).
  - `appointments.visit_purpose` (enum already partially covered by `appointment_type`).
- **Backend**
  - `AppointmentController@store`: validate that the selected pet is `available` at booking time; reject `on_hold`/`adopted`/`deceased` with 422.
  - `PetController@index`: accept `status` filter; default public list excludes `adopted` and `deceased` (or shows "Adopted" banner if admin requests).
  - New `PetController@setStatus` (admin): transitions `available → on_hold → adopted`, records who/when.
  - `AppointmentResource` includes `pet` summary.
- **Frontend (client)**
  - Pet profile page: "Reserve an appointment" opens the wizard **pre-selected with that pet**.
  - Adopt listing: show status chip; disable booking for non-available pets.
  - Booking wizard step 3: pet selector becomes "choose a pet or any".
- **Frontend (admin)**
  - Manage Pets: inline status dropdown + "Mark as adopted" action.
  - Appointment detail: show linked pet.
- **Tests:** store rejects on_hold pet; public index filters adopted; setStatus transitions + guard.
- **Effort:** ~3–4 days.
- **STATUS: DONE ✅**
  - Migration `2026_08_16_000001_add_pet_status_and_appointment_pet` (pets.status + appointments.pet_id).
  - `Pet::STATUS_*` consts + `availableForAdoption` scope; `PetResource` includes `status`.
  - `POST /api/admin/pets/{pet}/status` (SetPetStatusRequest), status filter + `include_unavailable` on public index.
  - `StoreAppointmentRequest` rejects non-bookable pets; store re-checks under lock.
  - Frontend: status chips on PetCard + AdoptProfile, bookable gate, `/book?pet=` preselection, PetPicker, admin Manage Pets status dropdown.
  - Tests: +12 backend (PetStatusLifecycleTest) = 70 passing; +1 frontend = 21 passing.

---

### Phase 2 — Shelter availability calendar & block-outs

**Objective:** Shelter staff control which dates are open for visits; the public sees real availability.

- **DB**
  - `shelter_schedules`: `date` (unique), `is_open` boolean, `morning_capacity` int, `afternoon_capacity` int, `reason` (closure/event note), timestamps.
- **Backend**
  - `ScheduleController@index` (public): returns open/closed + remaining capacity per day for next 60 days.
  - `ScheduleController@update` / `@toggle` (admin): bulk set a date open/closed, set capacities.
  - `AppointmentController@slots`: now computes capacity from schedules (e.g., 10 per session), counts existing appointments, returns `open/closed/full`.
  - `AppointmentController@store`: enforce capacity under the same transaction/lock as the unique-slot check.
- **Frontend**
  - Booking wizard calendar: closed/full days visually distinct (already partially styled) — now driven by schedules, not just existing bookings.
  - Admin: new "Availability / Calendar" page — month grid, click day to open/close, edit capacities.
- **Tests:** slots respect capacities; store rejects when session full; admin bulk-close blocks booking.
- **Effort:** ~3 days.
- **STATUS: DONE ✅**
  - Migration `2026_08_16_000002_create_shelter_schedules_table` (date unique, is_open, morning/afternoon capacity, reason).
  - Migration `2026_08_16_000003_drop_appointment_slot_unique_constraint` — capacity replaces one-booking-per-slot.
  - `ShelterSchedule` model + `AppointmentCapacityService` (schedule lookup, booked count, slot availability).
  - Public `GET /api/schedules` (61 days availability) + `GET /api/appointments/slots` now returns open/closed + per-session capacity/full.
  - Admin `GET /api/admin/schedules` (range) + `PUT /api/admin/schedules` (updateOrCreate).
  - `AppointmentController@store` enforces closed days + capacity under `lockForUpdate`.
  - Frontend: booking wizard calendar shows open/closed/full days + per-session disable; new admin **Shelter Availability** page (month grid + day editor modal).
  - Tests: +7 backend (ScheduleTest) = 78 passing; +3 frontend (AdminAvailability) = 24 passing.

---

### Phase 3 — Adoption application & pipeline

**Objective:** After (or instead of) a visit, an adopter can apply for a specific pet; staff move applications through a pipeline.

- **DB**
  - `adoption_applications`: `pet_id` FK, `user_id` FK, `appointment_id` nullable FK, `status` enum (`draft, submitted, under_review, approved, adopted, rejected`), `answers` JSON (housing situation, other pets, experience, why this pet), `notes` (staff), timestamps.
- **Backend**
  - `ApplicationController@store` (auth): create application for an `available` pet; one active application per user per pet.
  - `ApplicationController@my` (auth): user's applications with status timeline.
  - `ApplicationController@cancel` (auth): withdraw `submitted`/`under_review`.
  - `ApplicationController@index` / `@updateStatus` (admin): pipeline board operations + notes.
  - Events: `ApplicationApproved`, `ApplicationRejected` → notification + email.
- **Frontend (client)**
  - Pet profile "Apply for Adoption" button → application form (multi-field questionnaire).
  - User dashboard "My Applications" section: status timeline, cancel button.
- **Frontend (admin)**
  - "Applications" page: kanban-style columns (Submitted → Under Review → Approved → Adopted / Rejected), notes, applicant details.
- **Tests:** one-active-application invariant; status transition rules; 403 for other users' applications.
- **Effort:** ~4–5 days.
- **STATUS: DONE ✅**
  - Migration `2026_08_16_000004_create_adoption_applications_table` (pet_id, user_id, appointment_id nullable, status enum, answers JSON, notes, unique pet_id+user_id).
  - `AdoptionApplication` model (status consts, active-status list, casts) + `User::adoptionApplications()`.
  - User endpoints: `POST /api/adoption-applications` (one-active invariant + pet availability), `GET .../my`, `POST .../{id}/cancel` (ownership 403).
  - Admin endpoints: `GET /api/admin/adoption-applications` (with pet+user), `PUT .../{id}/status` (guarded transitions; adopting also sets pet to adopted).
  - Frontend: `ApplyForAdoption` questionnaire page (`/adopt/:id/apply`), pet profile "Apply to Adopt" + "Book a Visit" buttons, `UserApplications` page (`/applications`) with withdraw, admin `AdminApplications` kanban board (Submitted → Under Review → Approved → Adopted/Rejected) with notes.
  - Tests: +13 backend (AdoptionApplicationTest) = 91 passing; +3 frontend (ApplyForAdoption) = 27 passing.

---

### Phase 4 — Donation & volunteer management

**Objective:** Replace placeholder pages with real, trackable donation and volunteer programs.

- **DB**
  - `donations`: `donor_name`, `donor_email`, `type` enum (`cash, in_kind`), `amount` decimal nullable, `item_description`, `date`, `notes`, `user_id` nullable, timestamps.
  - `volunteers`: `user_id` FK, `availability` (days/hours JSON), `skills`, `interests`, `status` enum (`pending, active, inactive`), `total_hours` int default 0.
  - `volunteer_shifts`: `volunteer_id` FK, `date`, `time_slot`, `hours_logged`, `activity`, timestamps.
- **Backend**
  - Public: `DonationController@store` (record a donation pledge; no payment gateway yet), `VolunteerController@apply`.
  - Auth (volunteer): `ShiftsController@my`, `ShiftsController@logHours`.
  - Admin: `DonationController@index` (list + totals), `VolunteerController@index`, `ShiftsController@store/update` (assign shifts, log hours), approve volunteers.
- **Frontend**
  - Donate page: real form (cash/in-kind), shelter thanks the donor, admin sees donations table + monthly totals.
  - Volunteer page: apply form → volunteer dashboard (shifts, log hours).
  - Admin: Donations page, Volunteers page (approve/assign shifts).
- **Tests:** donation store + admin totals; volunteer apply + approval gate; shift hour logging.
- **Effort:** ~4 days.
- **STATUS: DONE ✅**
  - Migration `2026_08_16_000005_create_donations_and_volunteers_tables` (donations, volunteers, volunteer_shifts).
  - Models: `Donation`, `Volunteer`, `VolunteerShift` + `User::donations()` / `User::volunteer()`.
  - Public/auth: `POST /api/donations` (cash/in-kind), `POST /api/volunteers/apply`, `GET /api/volunteers/my`, `GET /api/volunteers/shifts`, `PUT .../{shift}/hours` (ownership 403).
  - Admin: `GET /api/admin/donations` (totals + list), `GET /api/admin/volunteers`, `PUT .../{volunteer}/status` (approve/deactivate), `POST .../{volunteer}/shifts`, `PUT .../shifts/{shift}`.
  - Frontend: Donate page donation form (cash/in-kind), admin Donations page (totals + table), Volunteer page apply form, Volunteer Dashboard (`/volunteer-dashboard` with shift hours), admin Volunteers page (approve + assign shift).
  - Tests: +13 backend (DonationVolunteerTest) = 104 passing; +3 frontend (Donate) = 30 passing.

---

### Phase 5 — User dashboard & favorites

**Objective:** A logged-in user gets a real account hub.

- **DB**
  - `favorites`: `user_id`, `pet_id`, timestamps (unique pair).
- **Backend**
  - `FavoritesController@toggle`, `@index` (auth).
  - `DashboardController@user` (auth): upcoming appointments, application statuses, recent notifications, favorite pet ids.
- **Frontend**
  - New `/account` layout (sidebar) with sections: **Overview** (welcome + quick stats + upcoming visit), **My Appointments**, **My Applications**, **Favorite Pets**, **Profile**, **Change Password**, **Notifications**.
  - Heart/favorite toggle on pet cards + pet profile.
- **Tests:** favorites toggle/duplicate; dashboard aggregates correct data.
- **Effort:** ~3–4 days.
- **STATUS: DONE ✅**
  - Migration `2026_08_16_000006_create_favorites_table` (unique user+pet pair) + `Favorite` model.
  - `User::favorites()` / `User::favoritePets()` relationships.
  - `POST /api/favorites/{pet}` (toggle) + `GET /api/favorites` (list).
  - `DashboardController@user` → `GET /api/dashboard` (auth): welcome info, upcoming appointments, active applications, favorite pet ids, stats.
  - Frontend: new `/account` sidebar layout with Overview (welcome + stats + upcoming + active apps), Appointments, Applications, Favorites, Volunteer, Profile, Change Password, Notifications.
  - `FavoriteButton` (heart) on PetCard grid + pet profile; anonymous users get a login link.
  - Tests: +8 backend (FavoritesDashboardTest) = 112 passing; +3 frontend (FavoriteButton) = 33 passing.

---

### Phase 6 — Notifications & email

**Objective:** Move from status-text to actionable notifications with optional email delivery.

- **DB**
  - `notifications` (Laravel default table) storing notification records.
  - `notification_types`: keys like `appointment.status`, `application.status`, `welcome`.
- **Backend**
  - Replace current ad-hoc message system with Laravel Notifications + a `NotificationResource`.
  - On appointment accept/cancel and application status change: create in-app notification **and** queue an email (Mailpit locally / SES in prod).
  - `NotificationsController@index` (auth, paginated), `@markRead`, `@markAllRead`.
  - Keep `GET /appointments/{id}/message` for backward compat or migrate UI to notifications.
- **Frontend**
  - Notifications page: grouped by date, unread badge in navbar, "mark all read".
  - Email templates (Blade) for appointment accepted/rejected and application status.
- **Tests:** notification created on status change; mark-read endpoint; email queued (Mail::fake()).
- **Effort:** ~3 days.
- **STATUS: DONE ✅**
  - Migration `2026_08_16_000007_create_notifications_table` (Laravel default).
  - Notifications: `AppointmentStatusNotification` + `ApplicationStatusNotification` (database + mail channels) with `toArray` payloads.
  - Mailables: `AppointmentStatusMail` + `ApplicationStatusMail` (queueable, Blade templates in `resources/views/emails/`).
  - Wired into `AppointmentController@updateStatus` and `AdoptionApplicationController@updateStatus`.
  - `NotificationsController`: `GET /api/notifications` (paginated + unread_count), `POST /api/notifications/{id}/read`, `POST /api/notifications/read-all`.
  - Frontend: notifications page rebuilt on the new API (unread ring, mark-read, mark-all), unread badge in Navbar (desktop + mobile).
  - Tests: +8 backend (NotificationTest) = 120 passing; frontend 33 passing.

---

### Phase 7 — Pet care & shelter operations

**Objective:** Staff record medical + care history per pet.

- **DB**
  - `pet_records`: `pet_id` FK, `type` enum (`vaccination, vet_visit, grooming, intake, note`), `title`, `details`, `record_date`, `created_by` (user_id FK), timestamps.
  - `pets`: add `intake_date`, `intake_notes`, `microchip` nullable.
- **Backend**
  - `PetRecordController@index` (public: non-sensitive only), `@store/@destroy` (admin).
  - Extend `PetResource` with latest record + intake info.
- **Frontend**
  - Admin pet detail drawer: timeline of records, add vaccination/vet/grooming entries.
  - Client pet profile: "About" now pulls from `about` + latest care records.
- **Tests:** record CRUD admin-gated; public index sanitized.
- **Effort:** ~3 days.
- **STATUS: DONE ✅**
  - Migration `2026_08_16_000008_create_pet_records_table` (type enum, title, details, record_date, created_by) + `pets` intake_date / intake_notes / microchip columns.
  - `PetRecord` model (types consts, `PUBLIC_TYPES`), `Pet::records()`, `PetRecordController`.
  - Public `GET /api/pets/{pet}/records` (only vaccination/grooming/intake); admin `GET/POST/DELETE .../records`.
  - `PetResource` now exposes intake_date / intake_notes / microchip; StorePet/UpdatePet requests accept the new fields.
  - Frontend: `CareSection` on the public pet profile; admin `PetRecordsPanel` (timeline + add/delete) inside Manage Pets; PetForm gains intake/microchip fields.
  - Tests: +7 backend (PetRecordTest) = 127 passing; +3 frontend (PetRecordsPanel) = 36 passing.

---

### Phase 8 — Reporting & analytics

**Objective:** Shelter leadership sees trends, not just today's counts.

- **Backend**
  - `ReportController@index` (admin): 
    - Adoption funnel (appointments → applications → adopted) per month.
    - Top pets by appointment/application interest.
    - Donation totals by type/month.
    - Volunteer hours per month.
    - Visit capacity utilization.
- **Frontend**
  - Admin dashboard gains chart cards (use a lightweight chart lib — e.g., `recharts` — or hand-rolled SVG bars to avoid a big dep).
- **Tests:** report queries return expected aggregates.
- **Effort:** ~3 days.
- **STATUS: DONE ✅**
  - `ReportController@index` → `GET /api/admin/reports?months=12` (driver-agnostic month grouping).
  - Monthly series: appointments, applications, adoptions, cash donations, volunteer hours + grand totals.
  - Top 5 pets by appointments and by applications.
  - Frontend `AdminReports` page (`/reports`): stat cards, hand-rolled SVG bar charts (appointments, applications vs adoptions, donations, volunteer hours), top-pets lists, 3/6/12-month selector.
  - Tests: +5 backend (ReportTest) = 132 passing; +3 frontend (AdminReports) = 39 passing.

---

### Phase 9 — Uploads & media hardening

**Objective:** Production-grade image handling.

- **Backend**
  - Move uploads to a configurable disk (`s3` in prod, `public` local); keep `FileUploadService` resize/optimize.
  - Generate a small thumbnail variant; expose `thumb_url` in resources.
  - Add `max:5MB` + MIME allow-list already present; enforce in Pet/News store/update requests.
- **Frontend**
  - Use `thumb_url` in card grids, full `image_url` in profile/detail views.
- **Tests:** thumb variant created; S3 disk driver mocked.
- **Effort:** ~2 days.
- **STATUS: DONE ✅**
  - `FileUploadService` rewritten: configurable `MEDIA_DISK` (public local / s3 prod), 400px thumbnail variant in `thumbs/`, main resize kept at 1200px, delete removes both.
  - `Pet`/`News` image_url accessors now go through the service; new `thumb_url` in both resources.
  - Upload limits bumped to 5MB (5120 KB) on Pet/News store + update requests.
  - Frontend: pet cards, news list thumbnails, and admin/user small avatars use `thumb_url`; detail/large images keep `image_url`.
  - Tests: +3 unit (thumbnail variant, delete both, configurable disk) = 135 backend passing; frontend 39 passing.

---

### Phase 10 — Email config, seed data & docs

- **Objective:** Realistic demo + documentation.
- `.env.example`: mail, queue, filesystem vars documented.
- Seeders: sample applications, donations, volunteers, schedules, care records, 10–20 regular users so all screens demo well. `withCount`-safe.
- Update `README.md` / `MIGRATION_PLAN` with new modules + run instructions.
- **Effort:** ~2 days.
- **STATUS: DONE ✅**
  - `backend/.env.example` documents `MEDIA_DISK`, `MAIL_*`, `QUEUE_CONNECTION`, S3 vars.
  - `DemoDataSeeder` (idempotent): 10 demo users, appointments, adoption applications, donations, volunteers + shifts, pet care records, closed shelter days. Run with `php artisan db:seed --class=DemoDataSeeder`.
  - `backend/README.md` and `frontend/README.md` rewritten as project-specific docs (setup, accounts, modules, endpoints, env vars, testing).
  - `MIGRATION_PLAN_LARAVEL_REACT.md` gains **Phase 14** summarizing all 10 improvement phases.
  - Final state: backend **135 tests**, frontend **39 tests**, both builds + lint green, DB in clean base state (1 admin / 18 pets / 3 news).

---

## Part D — Priority & Phasing Summary

| Phase | Focus | Value | Effort |
|-------|-------|-------|--------|
| 1 | Pet-linked appointments + pet status | **High** | 3–4d |
| 2 | Availability calendar & capacity | **High** | 3d |
| 3 | Adoption applications pipeline | **High** | 4–5d |
| 4 | Donations & volunteers | **Medium** | 4d |
| 5 | User dashboard & favorites | **High** | 3–4d |
| 6 | Notifications + email | **Medium** | 3d |
| 7 | Pet care records | **Medium** | 3d |
| 8 | Reporting & analytics | **Medium** | 3d |
| 9 | Media hardening | **Low** | 2d |
| 10 | Seed data + docs | **Low** | 2d |
| | **Total** | | **~30–33 dev-days** |

**Recommended order:** Phase 1 → 2 → 3 first (the core adoption journey), then 5 (user hub), then 4 & 6 (community + comms), then 7–10 (ops + polish).

---

## Part E — Non-Functional & Cross-Cutting

- **Testing:** continue the established pattern — each phase ships Feature/Unit tests + at least smoke tests for new client screens. Target: >90 backend tests.
- **Auth/roles:** formalize a `roles`/abilities model (visitor/adopter/volunteer/admin) — for now `user_type` enum is fine; introduce Spatie Permission **only if** more roles appear (defer).
- **Queue:** switch queue driver to `database` for email/report jobs; run `queue:work` in dev.
- **Validation & soft-deletes:** soft-delete pets/users instead of hard delete where data must be preserved (applications, records).
- **CORS/security:** keep existing guards; add rate limiting on public booking/application endpoints; keep Sanctum.
- **Deployment:** unchanged two-subdomain layout; env-driven `VITE_API_URL`/`VITE_ADMIN_URL` already supported.

---

## Part F — Definition of "Fully Functional"

This plan is complete when a typical flow works without staff workarounds:

1. Visitor browses pets, reads profiles, bookmarks favorites.
2. Adopter books a visit for a specific pet on a real open date/time.
3. Shelter staff sees the booking, accepts/cancels with capacity respected.
4. Adopter applies to adopt a specific pet; staff review & approve; pet moves to `on_hold` → `adopted`; adopter is notified by in-app + email.
5. Donor makes a cash/in-kind donation that staff track.
6. Volunteer applies, gets approved, is assigned shifts, logs hours.
7. Staff records vaccinations/vet visits per pet.
8. Admin sees monthly reports on adoption funnel, donations, volunteer hours.
9. Everything is tested and deployable to the two existing subdomains.
