# REPAWCITY — Migration Plan: Procedural PHP → Laravel API + React

> **Goal:** Convert the current procedural PHP (mysqli) + server-rendered HTML application into a modern two-tier architecture:
> **Laravel 11** (RESTful JSON API backend) + **React 18** (SPA frontend built with Vite).
>
> This plan is a complete, ordered, file-by-file blueprint. Follow the phases sequentially; each phase ends in a testable, runnable state.

---

## Table of Contents

1. [Current State Analysis](#1-current-state-analysis)
2. [Target Architecture](#2-target-architecture)
3. [Tech Stack Decisions](#3-tech-stack-decisions)
4. [Target Project Structure](#4-target-project-structure)
5. [Database Migration Strategy](#5-database-migration-strategy)
6. [Laravel API Design (Endpoints)](#6-laravel-api-design-endpoints)
7. [Backend Implementation (Laravel)](#7-backend-implementation-laravel)
8. [Frontend Implementation (React)](#8-frontend-implementation-react)
9. [Page-by-Page Mapping Table](#9-page-by-page-mapping-table)
10. [Authentication & Roles](#10-authentication--roles)
11. [The Booking Flow (6 Steps → React Wizard)](#11-the-booking-flow-6-steps--react-wizard)
12. [File Upload Strategy](#12-file-upload-strategy)
13. [Static Assets & Images](#13-static-assets--images)
14. [Security Improvements](#14-security-improvements)
15. [Phased Implementation Roadmap](#15-phased-implementation-roadmap)
16. [Testing Strategy](#16-testing-strategy)
17. [Deployment Plan](#17-deployment-plan)
18. [Risks & Mitigations](#18-risks--mitigations)
19. [Rollback & Parody-Data Strategy](#19-rollback--parody-data-strategy)

---

## 1. Current State Analysis

### 1.1 What exists today

| Layer | Current implementation |
|-------|------------------------|
| Backend | PHP 8.2, **procedural**, no framework, raw `mysqli` |
| Database | MySQL/MariaDB, 4 tables: `user`, `pets`, `news`, `appointment` |
| Frontend | Server-rendered HTML + page-specific CSS files (`css/*.css`) |
| Styling | Mixed: Tailwind CDN (modern pages) + legacy custom CSS (booking/auth) |
| JS | Vanilla JS + jQuery 3.6, FullCalendar 3.10, Bootstrap 4.5 (legacy pages) |
| Auth | PHP `$_SESSION` + session guards (`user_guard.php`, `admin_guard.php`) |
| Booking | 6-step wizard that accumulates data in `$_SESSION`, inserts on step 5 |

### 1.2 Inventory of legacy files (everything to migrate)

**Public (root + pages/):**
- `index.php` — landing page (static)
- `pages/adoptpage.php` — pet listing with filters (type/sex/weight/age)
- `pages/adoptprofile.php` — pet detail (`?id=`)
- `pages/donatepage.php` — donation methods (Bank/GCash/Cash)
- `pages/news.php` — news listing, featured headline, slideshow, relative timestamps
- `pages/news-page.php` — single article (`?news_id=`)
- `pages/volunteer.php` — static volunteer info + downloadable files
- `pages/mission.php`, `success-stories.php`, `FAQ.php`, `contact.php`, `team.php`, `reference.php`, `privacy-policy.php`, `terms-of-use.php` — static info pages

**Auth (`auth/`):**
- `loginpage.php`, `signuppage.php`, `logout.php`

**Booking (`booking/`):**
- `book-appointment.php` (step 1 intro) → `book-appointment2.php` (type) → `book-appointment3.php` (date/slot + FullCalendar) → `book-appointment4.php` (personal info) → `book-appointment5.php` (confirmation + DB insert) → `book-appointment6.php` (success)

**User (`user/`):**
- `edit-profile.php`, `change-password.php`, `notification.php` (`?appointmentId=`)

**Admin (`admin/`):**
- `admin-dashboard.php` — stat cards + FullCalendar + daily appointment tables + Accept/Cancel
- `update_status.php` — AJAX status handler (returns HTTP 200/400/500)
- `admin-add-pets.php`, `admin-manage-pets.php`, `admin-manage-featured.php`
- `admin-add-news.php`, `admin-manage-news.php`
- `admin-manage-user.php` — promote/demote/update/delete users

**Includes (`includes/`):**
- `config.php` (DB creds), `authcode.php` (register/login), `navbar.php`, `footer.php`
- `user_guard.php`, `admin_guard.php`, `no_cache.php`, `save_selected_date.php`, `userfunction.php`
- `admin_head.php`, `admin_navbar.php`, `admin_sidebar.php`

**Assets:** `css/` (35 files), `image/` (37 dirs/files), `script/script.js`, `upload/` (user uploads).

### 1.3 Database schema (to replicate)

```
user         user_id PK, fname, lname, email, password, user_type(1=admin,2=user), created_at
pets         pets_id PK, name, type(Dog/Cat), breed, sex, weight, age, date(rescue),
             about, image, is_featured(0-4), user_id FK
news         news_id PK, title, details(mediumtext), image, date_published, is_featured(0/1), user_id FK
appointment  appointment_id PK, appointment_type(Adopt/Donate/Visit/Volunteer),
             appointment_date, time_slot(Morning/Afternoon Session), first_name, middle_name,
             last_name, mobile_number, home_address, email_address, user_id FK, status,
             message
```

---

## 2. Target Architecture

```
┌───────────────────────────────┐         ┌──────────────────────────────┐
│      React SPA (Vite)         │  JSON   │       Laravel 11 API         │
│  /frontend  (Vite dev :5173)  │ ──────► │  /backend (php artisan serve │
│  React Router                 │  fetch  │  :8000) → routes/api.php     │
│  Tailwind + Radix/HeadlessUI  │ ◄────── │  Controllers → Models        │
│  React Query (TanStack)       │  JSON   │  Sanctum (token auth)        │
│  Zustand (auth/session state) │         │  Eloquent → MySQL            │
└───────────────────────────────┘         └──────────────────────────────┘
```

- **Single-page app** on the frontend with client-side routing.
- **Stateless API** — JWT-free bearer tokens via **Laravel Sanctum** (personal access tokens).
- Static legacy pages (About, FAQ, Contact, etc.) become static React route components; their content is copied from the PHP markup.

---

## 3. Tech Stack Decisions

| Concern | Choice | Why |
|---------|--------|-----|
| Backend framework | **Laravel 13** (PHP 8.5+) | Native REST, Eloquent, migrations, validation, Sanctum (Phase 0 installed latest stable; API surface identical to L11) |
| API auth | **Laravel Sanctum** | Token-based for SPAs, easy to implement |
| API docs | **Scribe** (`knuckleswtf/scribe`) | Auto-generate OpenAPI-style docs |
| Frontend | **React 18 + TypeScript** | Type-safe components matching API payloads |
| Build tool | **Vite** | Fast HMR dev, first-class React support |
| Routing | **React Router v6** | Client-side routing for SPA |
| Data fetching | **TanStack Query (React Query)** | Cache, mutations, invalidations |
| Global state | **Zustand** | Small, simple auth/user store |
| Forms | **React Hook Form + Zod** | Validated forms mirroring Laravel rules |
| Styling | **Tailwind CSS v4** (build-time via `@tailwindcss/vite`, CSS-first `@theme`, NOT CDN) | Reuse existing `repaw-*` palette from `includes/navbar.php` |
| UI components | Headless UI / Radix + heroicons | Accessible dropdowns, modals, calendar |
| Calendar | **react-big-calendar** or **FullCalendar React** | Replaces jQuery FullCalendar |
| Date/relative time | **date-fns** | Replace PHP `date_diff` relative-time logic in `news.php` |
| Icons | Material Symbols Rounded (keep) | Preserve existing icon look |
| HTTP client | **Axios** | Interceptors for auth token + 401 handling |
| Image uploads | Laravel `Storage` + **intervention/image** | Validation + optimization |

---

## 4. Target Project Structure

Recommended **monorepo** layout (keeps the existing repo usable):

```
REPAWCITY/
├── backend/                        # Laravel 11 application
│   ├── app/
│   │   ├── Http/
│   │   │   ├── Controllers/Api/
│   │   │   │   ├── AuthController.php
│   │   │   │   ├── PetController.php
│   │   │   │   ├── NewsController.php
│   │   │   │   ├── AppointmentController.php
│   │   │   │   ├── UserController.php
│   │   │   │   └── DashboardController.php
│   │   │   ├── Middleware/ (EnsureUserRole.php)
│   │   │   └── Requests/ (StorePetRequest.php, StoreAppointmentRequest.php, ...)
│   │   ├── Models/
│   │   │   ├── User.php
│   │   │   ├── Pet.php
│   │   │   ├── News.php
│   │   │   └── Appointment.php
│   │   └── Services/ (FileUploadService.php, TimeSlotService.php)
│   ├── database/
│   │   ├── migrations/             # fresh schema
│   │   └── seeders/                # re-seed 18 pets, 3 news, 1 admin
│   ├── routes/api.php              # all API routes
│   └── .env                        # DB creds, SANCTUM, etc.
│
├── frontend/                       # React SPA (Vite + TS)
│   ├── src/
│   │   ├── api/                    # axios instance + endpoint functions
│   │   │   ├── client.ts
│   │   │   ├── auth.ts, pets.ts, news.ts, appointments.ts, admin.ts
│   │   ├── components/             # shared UI (Navbar, Footer, PetCard, ...)
│   │   ├── pages/
│   │   │   ├── public/             # Home, Adopt, AdoptProfile, Donate, News, NewsArticle, Volunteer
│   │   │   ├── static/             # About, FAQ, Contact, Team, Mission, References, Legal
│   │   │   ├── auth/               # Login, Register
│   │   │   ├── user/               # Profile, ChangePassword, Notifications
│   │   │   ├── booking/            # BookingWizard steps 1-6
│   │   │   └── admin/              # Dashboard, Pets, News, Users, Featured
│   │   ├── store/                  # zustand (authStore.ts)
│   │   ├── hooks/                  # useAuth, usePets, useAppointments, ...
│   │   ├── router/                 # route definitions + ProtectedRoute/AdminRoute
│   │   └── styles/                 # Tailwind config (repaw palette)
│   ├── vite.config.ts              # proxy /api → backend
│   └── package.json
│
├── database/repawcity.sql          # KEEP — reference seed source
├── css/  image/  script/  upload/  # KEEP during migration; move later (see §12-13)
└── *.php  admin/ auth/ booking/ pages/ user/ includes/   # KEEP until phase complete, then archive
```

---

## 5. Database Migration Strategy

### 5.1 Approach: replicate schema with Laravel migrations + reseed

Do **not** try to use the existing database as-is. Write fresh Laravel migrations and a seeder that reproduces the current data, then point the API at it. Keep `database/repawcity.sql` as the canonical seed source.

### 5.2 Migration definitions (deliverables)

| Migration | Table | Key columns / changes vs legacy |
|-----------|-------|---------------------------------|
| `create_users_table` | `users` | `id` (bigint), `fname`, `lname`, `email` (unique), `password` (hashed), `user_type` enum `['1','2']`, `created_at` |
| `create_pets_table` | `pets` | `id`, `name`, `type` enum(Dog,Cat), `breed`, `sex` enum(Male,Female), `weight`, `age`, `date` (rescue), `about` (text), `image`, `is_featured` smallint (0-4), `user_id` FK → users |
| `create_news_table` | `news` | `id`, `title`, `details` (longText), `image`, `date_published` (datetime, default now, `useCurrent`), `is_featured` boolean, `user_id` FK |
| `create_appointments_table` | `appointments` | `id`, `appointment_type` enum(Adopt,Donate,Visit,Volunteer), `appointment_date` date, `time_slot` enum(Morning Session,Afternoon Session), `first_name`, `middle_name`, `last_name`, `mobile_number`, `home_address`, `email_address`, `status` enum(Pending,Accepted,Cancelled), `message` string, `user_id` FK cascade |

> Note: Laravel convention uses plural snake_case table names (`users`, `pets`, `news`, `appointments`) and `id` as PK. Eloquent maps automatically. `news` is a reserved-ish name but works fine as a table; the model must be `News` with `$table = 'news'`.

### 5.3 Seeder plan

- **Admin user:** Aian Louise Alfaro — `admin@gmail.com` / `1234` (hash via `Hash::make`) — must keep working for demo.
- **Pets:** copy the 18 rows (names, breeds, images already in `upload/`).
- **News:** copy the 3 articles (long `details` text).
- **FK integrity:** ensure every `pets.user_id`, `news.user_id`, `appointment.user_id` references user id 1.

### 5.4 Image path mapping

Legacy stores just a filename (`646ae881bbbf9.jpg`) and the pages prepend `upload/` or `upload/news/`. The API should return an **absolute URL** built from the storage disk:
- pets → `storage/pets/<filename>`
- news → `storage/news/<filename>`

Add an accessor on each model, e.g. `Pet::getImageUrlAttribute()`. The React app just uses `pet.image_url`.

---

## 6. Laravel API Design (Endpoints)

All routes prefixed `/api` (from `routes/api.php`). Public routes require no auth; authenticated routes use `auth:sanctum`; admin routes add a custom `role:admin` middleware.

### 6.1 Public

| Method | URI | Controller@method | Purpose |
|--------|-----|-------------------|---------|
| GET | `/api/pets` | `PetController@index` | List pets, optional query filters: `type`, `sex`, `weight`, `age`, `featured`, `q` (search) |
| GET | `/api/pets/{pet}` | `PetController@show` | Single pet detail |
| GET | `/api/news` | `NewsController@index` | List news (desc), optional `featured=1` returns headline |
| GET | `/api/news/{news}` | `NewsController@show` | Single article |
| GET | `/api/appointments/slots?date=YYYY-MM-DD` | `AppointmentController@slots` | Which time slots are booked for a date (for calendar) |
| GET | `/api/static/*` | (frontend) | Static pages need no API |

### 6.2 Auth

| Method | URI | Controller@method | Purpose |
|--------|-----|-------------------|---------|
| POST | `/api/register` | `AuthController@register` | Register user, return token + user |
| POST | `/api/login` | `AuthController@login` | Login, return token + user |
| POST | `/api/logout` | `AuthController@logout` | Revoke current token |
| GET | `/api/user` | `AuthController@me` | Current authenticated user (for session restore) |

### 6.3 User (auth:sanctum)

| Method | URI | Controller@method | Purpose |
|--------|-----|-------------------|---------|
| PUT | `/api/user/profile` | `UserController@updateProfile` | Update fname/lname/email |
| PUT | `/api/user/password` | `UserController@changePassword` | Verify old, set new |
| GET | `/api/user/appointments` | `AppointmentController@myAppointments` | All appointments for the logged-in user |
| GET | `/api/appointments/{id}/message` | `AppointmentController@message` | The notification message (replaces `notification.php`) |

### 6.4 Booking (auth:sanctum)

| Method | URI | Controller@method | Purpose |
|--------|-----|-------------------|---------|
| POST | `/api/appointments` | `AppointmentController@store` | Single call that replaces the 6-step session wizard: validates type, date, slot, personal info; checks slot availability (server-side); inserts with `status=Pending`; returns created appointment |
| POST | `/api/appointments/check-slot` | `AppointmentController@checkSlot` | Availability check for a date+slot before submit |

> **Key design change:** The 6-step wizard becomes a **single POST** in the API. The React wizard collects all fields client-side, then submits once. Server-side re-validates everything and re-checks slot availability (prevents double-booking races — see §14).

### 6.5 Admin (auth:sanctum + role:admin)

| Method | URI | Controller@method | Purpose |
|--------|-----|-------------------|---------|
| GET | `/api/admin/dashboard` | `DashboardController@index` | Stats (total/adopt/donate/visit/volunteer) + calendar events + daily appointments by date+slot |
| POST | `/api/admin/appointments/{id}/status` | `AppointmentController@updateStatus` | Accept/Cancel; auto-generates the `message` (moved from `update_status.php`) |
| POST | `/api/admin/pets` | `PetController@store` | Add pet (with image upload) |
| PUT | `/api/admin/pets/{pet}` | `PetController@update` | Update pet (optional new image) |
| DELETE | `/api/admin/pets/{pet}` | `PetController@destroy` | Delete pet |
| POST | `/api/admin/pets/featured` | `PetController@setFeatured` | Accepts 4 ids → clears all `is_featured`, assigns 1-4 |
| POST | `/api/admin/news` | `NewsController@store` | Add news (with image upload) |
| PUT | `/api/admin/news/{news}` | `NewsController@update` | Update title/details |
| DELETE | `/api/admin/news/{news}` | `NewsController@destroy` | Delete news |
| POST | `/api/admin/news/{news}/feature` | `NewsController@setFeatured` | Set headline, clear others |
| GET | `/api/admin/users` | `UserController@index` | List all users |
| PUT | `/api/admin/users/{user}` | `UserController@update` | Update name/email/optional password |
| DELETE | `/api/admin/users/{user}` | `UserController@destroy` | Delete user |
| POST | `/api/admin/users/{user}/role` | `UserController@updateRole` | Promote (2→1) / demote (1→2) |

---

## 7. Backend Implementation (Laravel)

### 7.1 Setup commands

```bash
composer create-project laravel/laravel backend
cd backend
composer require laravel/sanctum
php artisan install:api            # publishes sanctum + api routes
composer require intervention/image
```

### 7.2 Models (with key relationships / casts)

- **User:** `hasMany(Pet)`, `hasMany(News)`, `hasMany(Appointment)`. `$casts` no special. Hide `password`. `user_type` string `'1'`/`'2'` with helper `isAdmin()`.
- **Pet:** `belongsTo(User)`. `$casts = ['date' => 'date']`. Accessor `image_url` → `Storage::url('pets/'.$image)`.
- **News:** `belongsTo(User)`. `$casts = ['date_published' => 'datetime', 'is_featured' => 'boolean']`. Accessor `image_url`.
- **Appointment:** `belongsTo(User)`. `$casts = ['appointment_date' => 'date']`. `$fillable` all booking fields.

### 7.3 Controllers — key logic to port

**AuthController@register**
Port from `includes/authcode.php`:
- Validate: `fname`, `lname` required; `email` required|unique:users; `password` required|confirmed.
- Create with `Hash::make`, `user_type='2'`.
- Return `{ user, token }` (201).

**AuthController@login**
Port from `authcode.php`:
- Validate email+password.
- `Hash::check`; on success `$user->createToken('spa')->plainTextToken`.
- Return `{ user, token }`. On failure 401.

**PetController@index**
Port from `pages/adoptpage.php` + `includes/userfunction.php`:
- Use Eloquent `when($request->type, fn($q)=>$q->where('type',$type))` for type/sex/weight/age.
- `featured=1` → `where('is_featured','>',0)->orderBy('is_featured')`.
- Optionally `paginate(12)` to fix the "no pagination" limitation (progressive enhancement).
- Return resource collection.

**PetController@store / update / destroy / setFeatured**
Port from `admin-add-pets.php`, `admin-manage-pets.php`, `admin-manage-featured.php`:
- FormRequests validate fields + `image: mimes:jpg,jpeg,png|max:2048` + `getimagesize` check.
- Store via `FileUploadService` to `storage/app/public/pets` with `uniqid()`-style random name.
- `setFeatured`: `Pet::query()->update(['is_featured'=>0])` then loop 4 ids setting 1..4. Wrap in a DB transaction.

**NewsController@index / show / store / update / destroy / setFeatured**
Port from `pages/news.php`, `news-page.php`, `admin-add-news.php`, `admin-manage-news.php`:
- Relative time ("x hours ago") is computed **client-side** in React with `date-fns` (from `published_at`), so the API just returns `date_published`.
- `setFeatured`: transaction — clear all, set one.

**AppointmentController@slots / store / myAppointments / message / updateStatus**
Port from `booking/*` + `admin/update_status.php` + `user/notification.php`:
- `slots`: return `{ date, booked: ['Morning Session', 'Afternoon Session'] }` for the calendar.
- `store`: validate type, date (>= today), slot, names, mobile, address, email. **Transactionally** check `where date+slot` with a lock (`lockForUpdate()` or unique constraint) to prevent double-booking; insert with status `Pending`, `message = 'Your appointment is currently pending approval.'`, `user_id = auth()->id()`. Return 201.
- `updateStatus`: validate status ∈ {Accepted, Cancelled}; port the two message templates verbatim from `update_status.php`; update row.

**DashboardController@index**
Port from `admin-dashboard.php`:
- Stats: counts per type (use `Appointment::query()->count()` + `->where()` aggregates or `groupBy`).
- Calendar events: `Appointment::select('appointment_date','time_slot')->get()` mapped to `{start, title}`.
- Daily appointments: `?date=...&slot=...` filtered rows.

### 7.4 Middleware & Guards

| Middleware | File | Behavior |
|------------|------|----------|
| `auth:sanctum` | built-in | 401 if no valid bearer token |
| `role:admin` (custom) | `EnsureUserRole.php` | `abort_unless(auth()->user()->user_type === '1', 403)` |

Routes:
```php
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', ...);
    Route::put('/user/profile', ...);
    Route::put('/user/password', ...);
    Route::get('/user/appointments', ...);
    Route::post('/appointments', ...);
    Route::post('/appointments/check-slot', ...);
    Route::get('/appointments/{id}/message', ...);
});

Route::middleware(['auth:sanctum','role:admin'])->prefix('admin')->group(function () {
    // all admin endpoints above
});
```

### 7.5 Form Requests (validation mirroring legacy)

- `StorePetRequest` — required name/type/breed/sex/weight/age/date/about; image file required, mimes jpg/jpeg/png, max 2048.
- `UpdatePetRequest` — same but image optional.
- `StoreNewsRequest` — title/details required; image mimes jpg/jpeg/png, max 3072.
- `StoreAppointmentRequest` — appointment_type in list; appointment_date date >= today; time_slot in list; names/mobile/address/email required.
- `ChangePasswordRequest` — old_password required; new_password required|confirmed|min:8.

### 7.6 CORS

`config/cors.php` — allow `frontend origin` (`http://localhost:5173` dev; `https://your-domain` prod) + `Authorization`, `Content-Type`. With Vite proxy this is mostly bypassed in dev, but configure anyway.

---

## 8. Frontend Implementation (React)

### 8.1 Scaffolding

```bash
npm create vite@latest frontend -- --template react-ts
cd frontend
npm i react-router-dom @tanstack/react-query zustand axios react-hook-form zod @hookform/resolvers date-fns
npm i -D tailwindcss postcss autoprefixer  # Tailwind v3 build-time
npm i @headlessui/react react-big-calendar
```

### 8.2 Vite config — dev proxy (avoids CORS entirely)

```ts
// frontend/vite.config.ts
server: {
  proxy: {
    '/api': { target: 'http://localhost:8000', changeOrigin: true },
    '/storage': { target: 'http://localhost:8000', changeOrigin: true },
  },
}
```

### 8.3 Axios client with auth interceptor

```ts
// src/api/client.ts
const client = axios.create({ baseURL: '/api' });
client.interceptors.request.use(cfg => {
  const token = useAuthStore.getState().token;
  if (token) cfg.headers.Authorization = `Bearer ${token}`;
  return cfg;
});
client.interceptors.response.use(
  r => r,
  err => {
    if (err.response?.status === 401) useAuthStore.getState().logout();
    return Promise.reject(err);
  }
);
```

### 8.4 Zustand auth store

```ts
interface AuthState { user: User | null; token: string | null; setAuth: ...; logout: ... }
```
Persistence via `persist` middleware (localStorage) so a page refresh restores login. On app boot, if token exists, call `GET /api/user` to refresh `user`.

### 8.5 Routing & guards

```tsx
<BrowserRouter>
  <Routes>
    {/* Public */}
    <Route path="/" element={<Home />} />
    <Route path="/adopt" element={<Adopt />} />
    <Route path="/adopt/:id" element={<AdoptProfile />} />
    <Route path="/donate" element={<Donate />} />
    <Route path="/news" element={<News />} />
    <Route path="/news/:id" element={<NewsArticle />} />
    <Route path="/volunteer" element={<Volunteer />} />
    <Route path="/about/*" element={<AboutLayout />}>  {/* FAQ, Team, Contact, Mission, References, SuccessStories */}
    <Route path="/privacy" element={<PrivacyPolicy />} />
    <Route path="/terms" element={<Terms />} />

    {/* Auth */}
    <Route path="/login" element={<Login />} />
    <Route path="/register" element={<Register />} />

    {/* Protected (user) */}
    <Route element={<ProtectedRoute />}>
      <Route path="/profile" element={<Profile />} />
      <Route path="/change-password" element={<ChangePassword />} />
      <Route path="/notifications" element={<Notifications />} />
      <Route path="/book" element={<BookingWizard />} />
    </Route>

    {/* Protected (admin) */}
    <Route element={<AdminRoute />}>
      <Route path="/admin" element={<AdminLayout />}>
        <Route index element={<AdminDashboard />} />
        <Route path="pets/add" element={<AdminAddPet />} />
        <Route path="pets/manage" element={<AdminManagePets />} />
        <Route path="pets/featured" element={<AdminFeatured />} />
        <Route path="news/add" element={<AdminAddNews />} />
        <Route path="news/manage" element={<AdminManageNews />} />
        <Route path="users" element={<AdminManageUsers />} />
      </Route>
    </Route>
  </Routes>
</BrowserRouter>
```

`ProtectedRoute`: if `!user` → `<Navigate to="/login" />`. `AdminRoute`: if `!user || user.user_type !== '1'` → redirect home.

### 8.6 Tailwind repaw theme (port from `includes/navbar.php`)

```js
// tailwind.config.js
theme: { extend: {
  colors: { repaw: { bg:'#f5e6d3', text:'#6c4421', hover:'#d6bca8',
                     dark:'#4a2c17', accent:'#fad046', danger:'#c62828' } },
  fontFamily: { sans:['Roboto','sans-serif'], serif:['Montserrat','sans-serif'] },
}}
```

### 8.7 Navbar & Footer components

- **Navbar.tsx** — port `includes/navbar.php`: logo, Home/Adopt/Donate/News/Volunteer, About Us dropdown, Profile dropdown (Edit Profile, Change Password, and dynamic list of the user's appointments → `/notifications`), Logout, mobile hamburger. Use `useAuthStore` to switch logged-in/logged-out views.
- **Footer.tsx** — port `includes/footer.php`.

### 8.8 Component inventory

| Component | Used on | Replaces |
|-----------|---------|----------|
| `Navbar`, `Footer`, `Layout` | all public pages | includes/navbar.php, footer.php |
| `PetCard` | Home, Adopt | PHP card loop in adoptpage/home |
| `FilterBar` | Adopt | PHP filter form + auto-submit |
| `PetSlideshow` | News | PHP slideshow `showSlides()` |
| `NewsCard`, `HeadlineCard` | News | PHP featured/latest loops |
| `AppointmentCalendar` | Booking step 3, Admin | jQuery FullCalendar init |
| `StatCard` | Admin dashboard | PHP stat cards |
| `AdminSidebar`, `AdminTopbar` | Admin | includes/admin_sidebar.php, admin_navbar.php |
| `DataTable` + `RowForm` | Admin manage pages | jQuery row-click populate pattern |
| `ProtectedRoute`, `AdminRoute` | routing | user_guard.php, admin_guard.php |

---

## 9. Page-by-Page Mapping Table

| # | Legacy file | React route | API call | Notes |
|---|-------------|-------------|----------|-------|
| 1 | `index.php` | `/` | none (static) | Port hero + 3 help cards + CTA |
| 2 | `pages/adoptpage.php` | `/adopt` | `GET /api/pets?type=&sex=&weight=&age=` | FilterBar + PetCard grid |
| 3 | `pages/adoptprofile.php` | `/adopt/:id` | `GET /api/pets/:id` | Detail layout; "Book Appointment" → `/book` or `/login` |
| 4 | `pages/donatepage.php` | `/donate` | none | Static (Bank/GCash/Cash + QR) |
| 5 | `pages/news.php` | `/news` | `GET /api/news?featured=1`, `GET /api/news` | Slideshow + headline + latest (relative time via date-fns) |
| 6 | `pages/news-page.php` | `/news/:id` | `GET /api/news/:id` | Article + share icons |
| 7 | `pages/volunteer.php` | `/volunteer` | none | Static + download links to PDFs (serve via `/storage`) |
| 8 | `pages/mission.php` | `/about/mission` | none | Static |
| 9 | `pages/success-stories.php` | `/about/success-stories` | none | Static |
| 10 | `pages/FAQ.php` | `/about/faq` | none | Static |
| 11 | `pages/contact.php` | `/about/contact` | none | Static (map embed) |
| 12 | `pages/team.php` | `/about/team` | none | Static (5 members) |
| 13 | `pages/reference.php` | `/about/references` | none | Static |
| 14 | `pages/privacy-policy.php` | `/privacy` | none | Static |
| 15 | `pages/terms-of-use.php` | `/terms` | none | Static |
| 16 | `auth/loginpage.php` | `/login` | `POST /api/login` | React Hook Form |
| 17 | `auth/signuppage.php` | `/register` | `POST /api/register` | RHF + confirm password |
| 18 | `auth/logout.php` | (button) | `POST /api/logout` | Zustand `logout()` |
| 19 | `booking/book-appointment.php` | `/book` step 1 | none | Intro screen |
| 20 | `booking/book-appointment2.php` | `/book` step 2 | none | Type select (state) |
| 21 | `booking/book-appointment3.php` | `/book` step 3 | `GET /api/appointments/slots?date=` | Calendar + session select |
| 22 | `booking/book-appointment4.php` | `/book` step 4 | none | Personal info (state) |
| 23 | `booking/book-appointment5.php` | `/book` step 5 | `POST /api/appointments` (single submit) | Confirmation checkboxes → submit |
| 24 | `booking/book-appointment6.php` | `/book` success | — | Show returned appointment details |
| 25 | `user/edit-profile.php` | `/profile` | `GET /api/user`, `PUT /api/user/profile` | Prefill from store |
| 26 | `user/change-password.php` | `/change-password` | `PUT /api/user/password` | Old/New/Confirm |
| 27 | `user/notification.php` | `/notifications` | `GET /api/user/appointments` + `GET /api/appointments/:id/message` | List all user appointments; show message |
| 28 | `admin/admin-dashboard.php` | `/admin` | `GET /api/admin/dashboard?date=` | Stats + calendar + tables + accept/cancel |
| 29 | `admin/update_status.php` | (called by admin actions) | `POST /api/admin/appointments/:id/status` | Accept/Cancel |
| 30 | `admin/admin-add-pets.php` | `/admin/pets/add` | `POST /api/admin/pets` | Form + image upload |
| 31 | `admin/admin-manage-pets.php` | `/admin/pets/manage` | `GET /api/pets`, `PUT /api/admin/pets/:id`, `DELETE .../:id` | Table + row-click form |
| 32 | `admin/admin-manage-featured.php` | `/admin/pets/featured` | `POST /api/admin/pets/featured` | 4 ID inputs |
| 33 | `admin/admin-add-news.php` | `/admin/news/add` | `POST /api/admin/news` | Form + image upload |
| 34 | `admin/admin-manage-news.php` | `/admin/news/manage` | `PUT/DELETE /api/admin/news/:id`, `POST .../feature` | Table + actions |
| 35 | `admin/admin-manage-user.php` | `/admin/users` | `GET /api/admin/users`, `PUT/DELETE/:id`, `POST :id/role` | Table + actions |

**35 pages → 30 React routes + ~25 API endpoints.**

---

## 10. Authentication & Roles

### 10.1 Token flow

1. `POST /api/login` → returns `{ user, token }`.
2. React stores in Zustand + localStorage.
3. Axios attaches `Authorization: Bearer <token>`.
4. `GET /api/user` refreshes the profile on app boot.
5. `POST /api/logout` revokes token; store cleared.

### 10.2 Role gating (replaces `user_guard.php` / `admin_guard.php`)

- **Frontend:** `<ProtectedRoute>` and `<AdminRoute>` render components.
- **Backend:** `role:admin` middleware returns 403 for non-admins. **Never rely on frontend only** — the API is the source of truth (fixes the legacy bug where `admin-*.php` pages had no real session check, and even then only checked the client side).

### 10.3 Password hashing

- All new registrations use `Hash::make`.
- Seed admin password `1234` is hashed.
- Legacy plain-text `1234` in the SQL dump is replaced on seed — no migration-of-hash needed since we reseed.

---

## 11. The Booking Flow (6 Steps → React Wizard)

### 11.1 State model (replaces `$_SESSION` wizard)

Local React state (or a Zustand slice) holds a `BookingDraft`:

```ts
interface BookingDraft {
  step: 1|2|3|4|5|6;
  appointment_type?: 'Adopt'|'Donate'|'Visit'|'Volunteer';
  appointment_date?: string;     // YYYY-MM-DD
  time_slot?: 'Morning Session'|'Afternoon Session';
  first_name?; middle_name?; last_name?; mobile_number?; home_address?; email_address?;
  confirmed_availability?: boolean; confirmed_location?: boolean; confirmed_reschedule?: boolean;
}
```

No data leaves the browser until **step 5**. This eliminates the legacy "session persists across tabs / abandoned wizard" bug.

### 11.2 Step-by-step

| Step | Screen | Behavior |
|------|--------|----------|
| 1 | Intro | "Set Up An Appointment Online", Get Started → step 2 |
| 2 | Type | 4 options (Adopt/Donate/Visit/Volunteer) → step 3 |
| 3 | Date & slot | `AppointmentCalendar` (react-big-calendar) with `GET /api/appointments/slots` to grey out fully-booked days; Morning/Afternoon selector; pre-submit `check-slot` for early feedback |
| 4 | Personal info | RHF form (first/middle/last, mobile, address, email) |
| 5 | Confirmation | 3 checkboxes (availability/location/reschedule) → `POST /api/appointments` once |
| 6 | Success | Show returned appointment date/slot/type/location + important info |

### 11.3 Race-condition safety

- Backend `store()` runs inside a transaction and re-checks the date+slot under a row lock before insert.
- Optional hardening: add a unique index on `(appointment_date, time_slot)` — matches current business rule "one booking per slot".

---

## 12. File Upload Strategy

| Legacy | New |
|--------|-----|
| `move_uploaded_file($tmp, '../upload/'.uniqid())` | `Storage::disk('public')->putFileAs('pets', $file, Str::uuid().'.'.$ext)` |
| Extension-only validation | `getimagesize()` check + `mimes:jpg,jpeg,png` + `max:2048`/`max:3072` (as per legacy sizes) + optional Intervention resize |
| Images referenced as `upload/<name>` | API returns `image_url` absolute URL (`/storage/pets/<name>`) |
| `upload/` dir in repo | `backend/storage/app/public/pets` & `.../news`; run `php artisan storage:link` |

**Copy legacy images** into the new storage on first boot (or add to the seeder path config) so existing pet/news photos render immediately.

---

## 13. Static Assets & Images

- Move the **`image/`** folder into `frontend/public/images/` (or `backend/public` served via `/storage`) for anything referenced by markup.
- Google Fonts (Montserrat, Roboto, Acme/Sigmar, Material Symbols Rounded) → include via `index.html` `<link>` tags.
- The existing **`css/`** folder is **not migrated** — Tailwind replaces it. Legacy CSS files stay only as reference until their page is converted, then archived.
- `script/script.js` (password toggle) → a small React `PasswordInput` component.
- **`index.php` used the `<script src="https://cdn.tailwindcss.com">`** — must be replaced with the **build-time Tailwind** setup (no CDN in production).
- Volunteer PDFs (in `image/volunteer/`) → `frontend/public` or `/storage`, linked directly.

---

## 14. Security Improvements

This migration is the perfect opportunity to fix every issue documented in `CODEXPLANATION.md §11`:

| Legacy issue | Fix in new architecture |
|--------------|-------------------------|
| Plain-text passwords | `Hash::make`/`Hash::check` everywhere |
| SQL injection (`admin-manage-pets`, `news`, etc.) | Eloquent query builder / prepared statements + Form Requests |
| No session protection on admin pages | `auth:sanctum` + `role:admin` middleware (server-enforced 403) |
| Weak file upload validation | `getimagesize()` + mime/extension whitelist + random UUID names + size caps |
| Hardcoded DB credentials | `.env` (git-ignored) + `APP_ENV` config |
| No CSRF protection | Token-based auth — CSRF not applicable; use `Accept: application/json` and content-type checks |
| `notification.php` SQLi via GET param | Authenticated route, bound `id`, ownership check (`where user_id = auth id`) |
| Password shown in admin table | Never returned by API (`User` resource hides it) |
| No pagination | `paginate()` on pet/news listings |
| Booking slot race | Transactional lock in `AppointmentController@store` |
| reCAPTCHA not really wired | Either remove the fake "I'm not a robot" image or integrate `anugrahpm/laravel-recaptcha` properly (recommended: remove for MVP, add later) |
| `edit-profile` updates by email | Update by authenticated `id` |
| Legacy `change-password` dead code populating `fname`/`lname` | Removed — React form only has password fields |
| Session data persisting across tabs | Booking draft is component-local state |

---

## 15. Phased Implementation Roadmap

Ordered so every phase leaves the app runnable.

### Phase 0 — Repo scaffold (0.5 day)
- [ ] Create `backend/` (Laravel 11) and `frontend/` (Vite React-TS).
- [ ] Commit `backend/.env.example` and `.gitignore` additions.
- [ ] Set up Tailwind (build-time) with the `repaw` palette.
- [ ] Vite dev proxy `/api` → `:8000`.

### Phase 1 — Database & Models (1 day)
- [ ] Write 4 migrations + seeders (admin, 18 pets, 3 news).
- [ ] Eloquent models with accessors (`image_url`) and relationships.
- [ ] Run `php artisan migrate --seed`; verify against `database/repawcity.sql`.

### Phase 2 — Auth API (1 day)
- [ ] `AuthController` (register/login/logout/me) with Sanctum.
- [ ] `UserController` profile + password endpoints.
- [ ] Test with curl/Postman.

### Phase 3 — Public content APIs (1 day)
- [ ] `PetController@index/show`, `NewsController@index/show`.
- [ ] `AppointmentController@slots`.
- [ ] Test filters and response shape.

### Phase 4 — Booking API (1 day)
- [ ] `AppointmentController@store` (+ transactional slot check) and `@checkSlot`.
- [ ] `myAppointments`, `@message`.
- [ ] Test: happy path, duplicate slot → 422/409, unauthenticated → 401.

### Phase 5 — Admin APIs (2 days)
- [ ] `DashboardController@index`.
- [ ] `AppointmentController@updateStatus` with the two message templates.
- [ ] Pet/News CRUD + featured logic + image upload.
- [ ] User CRUD + promote/demote.
- [ ] `role:admin` middleware wired to routes.

### Phase 6 — React shell + public pages (3 days)
- [ ] Axios client, Zustand auth store, React Query provider, router + guards.
- [ ] `Navbar`, `Footer`, `Layout`.
- [ ] Home, Adopt (+filters), AdoptProfile, Donate, News (+slideshow/relative time), NewsArticle, Volunteer.
- [ ] Static pages (About group, legal).

### Phase 7 — Auth + user pages (1.5 days)
- [ ] Login/Register forms (RHF + Zod).
- [ ] Profile, ChangePassword.
- [ ] Notifications (list appointments + view message).

### Phase 8 — Booking wizard (1.5 days)
- [ ] 6-step wizard with calendar + slot check.
- [ ] Single submit → success screen.

### Phase 9 — Admin panel (3 days)
- [ ] Admin layout + sidebar + dashboard (stats/calendar/tables/accept/cancel).
- [ ] Pets add/manage/featured.
- [ ] News add/manage/headline.
- [ ] Users manage (promote/demote).

### Phase 10 — Hardening & polish (2 days)
- [x] Error/loading/empty states everywhere.
- [x] Image optimization (Intervention Image: resize >1200px, re-encode at q80).
- [x] Pagination on pet/news listings (frontend `Pagination` component + `page` param).
- [x] Scribe API docs (served at `/docs` + `/docs.openapi`).
- [x] `npm run build` + `php artisan optimize` checks (caches work; cleared after verify for dev).
- [x] Legacy `css/` + `*.php` archived to `_legacy/` (git-tracked renames, history preserved).

### Phase 11 — Testing & QA (2 days)
- [x] Backend: feature tests (auth, pets, news, appointments, admin) — **58 tests passing**.
- [x] Frontend: Vitest + React Testing Library (guards, auth store, booking wizard) — **15 tests passing**.
- [x] Backend additions: `FileUploadServiceTest` (store/resize/delete) + `BookingConcurrencyTest` (double-booking 409, different-slot 201, transactional lock).
- [x] Manual parity pass: all 14 public routes serve; featured pets order; single headline; register→login→book→admin accept→message; non-owner 403.

### Phase 12 — Deployment (1 day)
- [x] Configurable API base URL via `VITE_API_URL` (defaults to relative `/api` for single-origin) + `resolveMedia()` helper for storage URLs.
- [x] `deploy/nginx.conf` — single-server nginx config (SPA history fallback + `/api` proxy + `/storage` alias + asset caching).
- [x] `deploy/Dockerfile.backend` (PHP-FPM) + `deploy/Dockerfile.web` (nginx SPA) + `deploy/docker-compose.yml` (MySQL 8 + Laravel + nginx).
- [x] `deploy/build.sh` — production build script (frontend build + composer + config/route cache).
- [x] `frontend/.env.production.example` — documents `VITE_API_URL` for split-API deployments.
- [x] Verified: production `dist/` serves all SPA routes via history fallback; API + storage reachable; `VITE_API_URL` bakes into the bundle when set.

### Phase 13 — Client / Admin subdomain split (repawcity.com + admin.repawcity.com)
- [x] Split the React app into **two Vite entries** sharing one codebase:
  - `index.html` + `src/main.tsx` → `src/App.tsx` — client SPA (public pages, auth, user pages, booking wizard).
  - `admin.html` + `src/admin-main.tsx` → `src/AdminApp.tsx` — admin portal (login + dashboard/pets/news/users) at the route root.
- [x] Separate route guards: `src/router/clientGuards.tsx` (bounces admins to `ADMIN_URL`) and `src/router/adminGuards.tsx` (bounces non-admins to `CLIENT_URL`).
- [x] `AdminLockout` wraps the whole client app: a logged-in admin visiting repawcity.com is redirected to the admin portal and never sees client pages.
- [x] `src/config.ts` exposes `CLIENT_URL` / `ADMIN_URL` from `VITE_CLIENT_URL` / `VITE_ADMIN_URL` (dev defaults :5173 / :5174).
- [x] Login supports an `adminPortal` mode (admin users land on the portal, non-admins rejected; client login sends admins to `ADMIN_URL`).
- [x] `vite.admin.config.ts` builds to `dist-admin/`; `npm run build` builds both; `dev:admin` runs on port 5174.
- [x] Deploy artifacts updated: `deploy/build.sh`, `deploy/nginx.conf` (repawcity.com + admin.repawcity.com + shared `/api` proxy), `deploy/nginx.docker.conf` + `Dockerfile.web` (two server blocks), `.env.production.example`.
- [x] Tests updated: client + admin guard suites (18 frontend tests passing).

**Total estimate: ~18–19 developer-days** for 1 full-time dev.

---

## Phase 14 — Shelter system enhancements (see IMPROVEMENT_PLAN.md)

All 10 phases of the improvement plan are **complete**. Backend: **135 tests**,
frontend: **39 tests**, both builds + lint green.

- [x] **Phase 1 — Pet-linked appointments & pet status lifecycle** (`pets.status`, `appointments.pet_id`, `POST /admin/pets/{pet}/status`, status chips, `/book?pet=`).
- [x] **Phase 2 — Shelter availability calendar & capacity** (`shelter_schedules`, per-session capacity, admin availability page, wizard shows open/closed/full).
- [x] **Phase 3 — Adoption application pipeline** (`adoption_applications`, apply form, my-applications, admin kanban, adopt→pet adopted).
- [x] **Phase 4 — Donation & volunteer management** (`donations`, `volunteers`, `volunteer_shifts`, donate form, volunteer dashboard, admin pages).
- [x] **Phase 5 — User dashboard & favorites** (`favorites`, `/account` hub with sidebar, heart toggles, dashboard aggregates).
- [x] **Phase 6 — Notifications & email** (Laravel notifications, queued Mailables + Blade templates, notification page + navbar unread badge).
- [x] **Phase 7 — Pet care & shelter operations** (`pet_records`, intake/microchip fields, public care history, admin records panel).
- [x] **Phase 8 — Reporting & analytics** (`GET /admin/reports`, monthly series, top pets, admin reports page with SVG charts).
- [x] **Phase 9 — Uploads & media hardening** (`MEDIA_DISK` config, 400px thumbnails + `thumb_url`, 5MB upload limit).
- [x] **Phase 10 — Email config, seed data & docs** (documented `.env.example`, `DemoDataSeeder`, project READMEs).

---

## 16. Testing Strategy

### Backend (Pest or PHPUnit)

- **Auth:** register (unique email, password mismatch), login success/fail, logout revokes token, `/api/user`.
- **Pets:** list + each filter, show 404, create/update/delete only as admin (403 as user), image validation, setFeatured clears others.
- **News:** list/headline, single, CRUD, setFeatured single-headline invariant.
- **Appointments:** slots response, store happy path, duplicate slot rejected, status update generates correct message, user can only see own appointments.
- **Users (admin):** list, update, delete, promote/demote.

### Frontend (Vitest + RTL)

- Route guards redirect unauthenticated/non-admin.
- Booking wizard reaches submit only when all steps valid.
- Auth store login/logout flows; 401 interceptor clears session.
- FilterBar sends correct query params.

### Manual parity checklist

- [ ] Every legacy URL has a React equivalent (update old links or add redirects).
- [ ] Featured pets show on Home in order 1–4.
- [ ] Featured news headline shows once; setting a new headline clears the old.
- [ ] Fully-booked days render yellow/unselectable on both booking & admin calendars.

---

## 17. Deployment Plan

### Option A — Two separate services (recommended)

- **Backend:** Laravel on a VPS (nginx + PHP-FPM) or shared hosting. Set `APP_URL`, real DB creds in `.env`, `php artisan migrate --force`, `storage:link`.
- **Frontend:** `npm run build` → static `dist/` on a CDN/Vercel/Netlify. Set `VITE_API_URL` to the backend domain.
- Configure CORS for the production frontend origin.

### Option B — Single server (nginx serves both)

```
location /api { proxy_pass http://127.0.0.1:8000; }   # or php-fpm
location /storage { alias .../backend/storage/app/public; }
location / { try_files $uri /frontend/dist/index.html; }  # SPA fallback
```

### Database

- Export existing `repawcity` data → import into new Laravel-managed schema via seeder (or a one-time import script mapping `pets`→`pets`, `news`→`news`, etc.).

---

## 18. Risks & Mitigations

| Risk | Mitigation |
|------|------------|
| `news` is a non-standard table name in Laravel | Model sets `protected $table = 'news'`; no issue |
| Breaking 30+ existing URLs | Keep `_legacy/` PHP running in parallel; add old-path → React-route redirects during Phase 10 |
| Booking slot race during launch | Transaction + unique index on `(appointment_date, time_slot)` |
| Large news `details` (mediumtext) payloads | Return truncated `excerpt` field + full `details` on `show` only |
| Legacy admin password `1234` | Re-hash in seeder; note in README |
| User uploads referenced by relative `upload/` paths | New `image_url` accessor + storage copy step in Phase 12/5 |
| Team unfamiliarity with new stack | Keep controllers thin, mirror old logic function-by-function, document in `CODEXPLANATION.md` follow-up |
| SEO (server-rendered PHP pages had content) | SPA relies on React rendering; add `react-helmet-async` for meta tags + prerender/sitemap or migrate to SSR (Next.js) later if SEO is a hard requirement |

---

## 19. Rollback & Parody-Data Strategy

- **Git:** the legacy code is fully preserved in git history. Keep the old `.php` files until Phase 10, then move them into a `_legacy/` directory (still deployed nowhere) rather than deleting, so nothing is lost.
- **DB:** the original `database/repawcity.sql` remains the source of truth for reseeding. New schema is a strict superset of behavior.
- **Parallel run:** run the legacy app on `localhost:8000` and the new stack on `:8001/:5173` during migration; only cut over when parity checklist passes.
- **Rollback trigger:** if any phase produces unexpected data loss, the seeders + `repawcity.sql` allow full database recreation in minutes.

---

### Summary

| | Legacy | New |
|--|--------|-----|
| Frontend | 35 server-rendered PHP pages + jQuery/Bootstrap | ~30 React routes (SPA) |
| Backend | procedural mysqli, no auth framework | Laravel REST API + Sanctum |
| Auth | PHP sessions | Bearer tokens |
| Booking | 6-page session wizard | Client wizard → 1 API call |
| Admin | unguarded pages | server-enforced role middleware |
| Styling | Tailwind CDN + 35 CSS files | Build-time Tailwind (1 config) |
| Security | 9 known issues | all resolved (see §14) |

Ready to begin with **Phase 0**.
