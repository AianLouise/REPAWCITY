# rePaw City — Frontend

Two React apps sharing one codebase, built with Vite + React 19 + TypeScript:

- **Client SPA** (`repawcity.com`) — public pages, auth, user account, booking wizard
- **Admin portal** (`admin.repawcity.com`) — login, dashboard, pets/news/users, applications, availability, donations, volunteers, reports

## Development

Requires the Laravel backend running on port 8000 (see `backend/README.md`).

```bash
npm install
npm run dev          # client app  -> http://localhost:5173
npm run dev:admin    # admin app   -> http://localhost:5174
```

Both dev servers proxy `/api` and `/storage` to `http://localhost:8000`.

## Build

```bash
npm run build          # builds both apps -> dist/ (client) + dist-admin/ (admin)
npm run build:client   # client only
npm run build:admin    # admin only
```

## Environment

Copy `.env.production.example` to `.env.production` to override defaults:

| Variable | Default | Purpose |
|----------|---------|---------|
| `VITE_API_URL` | (empty) | Absolute API origin when the SPA and API are on different hosts |
| `VITE_CLIENT_URL` | `http://localhost:5173` | Public client URL (used for cross-app links) |
| `VITE_ADMIN_URL` | `http://localhost:5174` | Admin portal URL (used for cross-app redirects) |

## Testing & lint

```bash
npm test        # Vitest + React Testing Library
npm run lint    # oxlint
```

## Structure

```
src/
  api/          # axios API modules (client, auth, pets, appointments, ...)
  components/   # shared UI (Navbar, PetCard, FavoriteButton, guards bootstrap)
  hooks/        # TanStack Query hooks per feature
  pages/
    public/     # client pages (Home, Adopt, Donate, News, Volunteer, ...)
    auth/       # client Login / Register
    user/       # /account hub (overview, appointments, applications, favorites, ...)
    booking/    # 6-step booking wizard
    admin/      # admin portal pages (dashboard, pets, news, users, ...)
    static/     # static/legal pages
  router/       # client + admin route guards
  store/        # Zustand auth store
  types/        # shared TypeScript types
```

The admin portal has its own entry (`admin.html` -> `src/admin-main.tsx` ->
`src/AdminApp.tsx`) so it can be deployed to a separate subdomain. Route
guards enforce the separation: admins are locked out of the client app and
non-admins are bounced out of the portal.
