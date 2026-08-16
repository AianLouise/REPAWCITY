import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react'
import tailwindcss from '@tailwindcss/vite'

/**
 * Dev middleware: rewrite browser page navigations to serve `admin.html`
 * (instead of `index.html`) so the admin portal loads for every route in dev
 * mode. Only requests that accept `text/html` are rewritten — Vite's internal
 * module requests (e.g. /@vite/client, /@fs/...) pass through untouched.
 */
function adminHtmlPlugin() {
  return {
    name: 'admin-html',
    configureServer(server: { middlewares: { use: (fn: (req: { url?: string; headers?: Record<string, string> }, res: unknown, next: () => void) => void) => void } }) {
      server.middlewares.use((req: { url?: string; headers?: Record<string, string> }, _res: unknown, next: () => void) => {
        const url = req.url ?? '/'
        const acceptsHtml = (req.headers?.accept ?? '').includes('text/html')
        if (acceptsHtml && url !== '/admin.html') {
          req.url = '/admin.html'
        }
        next()
      })
    },
  }
}

// Admin portal build/dev config — serves admin.html on a separate port so it
// can be deployed to a different subdomain (e.g. admin.repawcity.com).
// https://vite.dev/config/
export default defineConfig({
  plugins: [react(), tailwindcss(), adminHtmlPlugin()],
  build: {
    outDir: 'dist-admin',
    rollupOptions: {
      input: {
        admin: new URL('./admin.html', import.meta.url).pathname,
      },
    },
  },
  server: {
    port: 5174,
    proxy: {
      '/api': {
        target: 'http://localhost:8000',
        changeOrigin: true,
      },
      '/storage': {
        target: 'http://localhost:8000',
        changeOrigin: true,
      },
    },
  },
})