/**
 * Frontend configuration for the client/admin app split.
 *
 * In local development the two apps run on separate ports:
 *   client: http://localhost:5173   (npm run dev)
 *   admin:  http://localhost:5174   (npm run dev:admin)
 *
 * In production set these at build time (see .env.production.example):
 *   VITE_CLIENT_URL=https://repawcity.com
 *   VITE_ADMIN_URL=https://admin.repawcity.com
 */

export const CLIENT_URL = import.meta.env.VITE_CLIENT_URL ?? 'http://localhost:5173'
export const ADMIN_URL = import.meta.env.VITE_ADMIN_URL ?? 'http://localhost:5174'