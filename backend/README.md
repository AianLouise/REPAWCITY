# rePaw City — Backend API

Laravel 13 REST API for the rePaw City animal shelter system. Serves the
client SPA (`repawcity.com`) and the admin portal (`admin.repawcity.com`).

## Requirements

- PHP 8.3+ with GD, PDO MySQL
- Composer
- MySQL 8+

## Setup

```bash
composer install
cp .env.example .env

# Configure DB credentials in .env, then:
php artisan key:generate
php artisan migrate --force
php artisan db:seed --force           # admin + base pets/news
php artisan storage:link

# Optional: realistic demo data for every screen
php artisan db:seed --class=DemoDataSeeder
```

Run locally:

```bash
php artisan serve --port=8000
```

## Accounts

| Account | Email | Password | Role |
|---------|-------|----------|------|
| Admin | `admin@gmail.com` | `1234` | Admin (portal only) |
| Regular | `user@gmail.com` | `1234` | Regular user (client only) |

Demo users created by `DemoDataSeeder` use `demoN@repawcity.com` / `demo1234`.

## Modules & endpoints

### Auth & account
- `POST /api/register`, `POST /api/login`, `POST /api/logout`, `GET /api/user`
- `PUT /api/user/profile`, `PUT /api/user/password`
- `GET /api/dashboard` (user overview)

### Pets & adoption
- `GET /api/pets` (filters: type/sex/weight/age/featured/q/status, paginated), `GET /api/pets/{pet}`
- `POST /api/admin/pets`, `PUT/DELETE /api/admin/pets/{pet}`, `POST /api/admin/pets/{pet}/status`, `POST /api/admin/pets/featured`
- `GET /api/pets/{pet}/records` (public, sanitized) + admin record CRUD

### Appointments & booking
- `GET /api/appointments/slots`, `GET /api/schedules` (public availability)
- `POST /api/appointments`, `GET /api/appointments/my`, `GET /api/appointments/{id}/message`
- `POST /api/admin/appointments/{id}/status`

### Adoption applications
- `POST /api/adoption-applications`, `GET /api/adoption-applications/my`, `POST .../cancel`
- `GET /api/admin/adoption-applications`, `PUT .../{id}/status`

### Shelter operations
- `GET /api/admin/schedules` + `PUT /api/admin/schedules` (availability calendar)
- `GET /api/admin/reports` (monthly analytics)

### Community
- `POST /api/volunteers/apply`, `GET /api/volunteers/my`, `GET /api/volunteers/shifts`, `PUT .../{shift}/hours`
- `GET /api/admin/volunteers`, `PUT .../{volunteer}/status`, `POST .../shifts`

### Favorites & notifications
- `POST /api/favorites/{pet}`, `GET /api/favorites`
- `GET /api/notifications`, `POST /api/notifications/{id}/read`, `POST /api/notifications/read-all`

Admin-only routes require an admin Sanctum token (see `routes/api.php`).

## Email & media

- **Notifications** send in-app records + queued emails via `App\Notifications\*`
  and `App\Mail\*`. Templates live in `resources/views/emails/`.
  Set `MAIL_MAILER=smtp` in production (default `log` for dev).
- **Uploads** go to the disk configured by `MEDIA_DISK` (`public` local / `s3`).
  Images are resized (max 1200px) and a 400px thumbnail is generated; both are
  served via `image_url` / `thumb_url`.

## Testing

```bash
php artisan test
```

## API docs

Scribe-generated docs are served at `/docs` and `/docs.openapi`.
