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

/** Shelter contact + address — single source of truth for every page. */
export const SHELTER = {
  name: 'rePaw City',
  phone: '+63 917 123 4567',
  email: 'repawcity.makati@gmail.com',
  address: {
    line1: 'Unit 8, 123 Gil Puyat Avenue, Barangay San Isidro',
    line2: 'Makati City, Metro Manila',
    line3: 'Philippines 1200',
  },
  mapsUrl: 'https://maps.google.com/?q=Gil+Puyat+Avenue+Makati',
}

/** Full address on one line (e.g. confirmation emails / booking success). */
export const SHELTER_ADDRESS_INLINE = `${SHELTER.address.line1}, ${SHELTER.address.line2}, ${SHELTER.address.line3}`