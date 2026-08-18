# rePaw City

Monorepo for rePaw City — a pet shelter website (adoption, donation,
volunteering, booking) with a separate admin portal. Uses npm workspaces.

- **Client SPA** (`repawcity.com`) — `apps/client`
- **Admin portal** (`admin.repawcity.com`) — `apps/admin`
- **Shared packages** — `packages/*`

Built with Vite + React 19 + TypeScript, Tailwind CSS v4, TanStack Query,
Zustand, and the Laravel API in `backend/`.

## Development

Requires the Laravel backend running on port 8000 (see `backend/README.md`).

```bash
npm install
npm run dev          # runs both apps concurrently
npm run dev:client   # client app  -> http://localhost:5173
npm run dev:admin    # admin app   -> http://localhost:5174
```

Both dev servers proxy `/api` and `/storage` to `http://localhost:8000`.

## Build

```bash
npm run build          # builds both apps -> apps/client/dist + apps/admin/dist
npm run build:client   # client only
npm run build:admin    # admin only
```

## Environment

Copy `apps/<app>/.env.production.example` to `.env.production` in each app to
override defaults:

| Variable | Default | Purpose |
|----------|---------|---------|
| `VITE_API_URL` | (empty) | Absolute API origin when the SPA and API are on different hosts |
| `VITE_CLIENT_URL` | `http://localhost:5173` | Public client URL (used for cross-app links) |
| `VITE_ADMIN_URL` | `http://localhost:5174` | Admin portal URL (used for cross-app redirects) |

## Testing & lint

```bash
npm test        # Vitest + React Testing Library (client + admin + packages)
npm run lint    # oxlint
```

## Structure

```
apps/
  client/       # deploys to repawcity.com (public pages, auth, account, booking)
  admin/        # deploys to admin.repawcity.com (dashboard, pets, news, users, ...)
packages/
  ui/           # shared components (AuthLayout, AuthBootstrap, PageHero, Loading, ...)
  api-client/   # axios instance + API endpoint modules + TanStack Query hooks + types
  auth/         # Zustand auth store, role constants, User/AuthResponse types
  config/       # shared constants (URLs, shelter info)
backend/        # Laravel API
deploy/         # production build + nginx/docker configs
```

Each app is self-contained with its own entry, `vite.config.ts`, and build
output so they can be deployed to separate subdomains. Route guards enforce
the separation: admins are locked out of the client app and non-admins are
bounced out of the portal.
