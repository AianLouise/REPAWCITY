import { BrowserRouter, Navigate, Route, Routes } from 'react-router-dom'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import AuthBootstrap from './components/AuthBootstrap'
import { AdminRoute, GuestRoute } from './router/adminGuards'
import AdminLayout from './pages/admin/AdminLayout'
import AdminDashboard from './pages/admin/AdminDashboard'
import AdminAddPet from './pages/admin/AdminAddPet'
import AdminManagePets from './pages/admin/AdminManagePets'
import AdminFeatured from './pages/admin/AdminFeatured'
import AdminAddNews from './pages/admin/AdminAddNews'
import AdminManageNews from './pages/admin/AdminManageNews'
import AdminManageUsers from './pages/admin/AdminManageUsers'
import AdminApplications from './pages/admin/AdminApplications'
import AdminDonations from './pages/admin/AdminDonations'
import AdminReports from './pages/admin/AdminReports'
import AdminVolunteers from './pages/admin/AdminVolunteers'
import AdminAvailability from './pages/admin/AdminAvailability'
import AdminLogin from './pages/admin/AdminLogin'

const queryClient = new QueryClient({
  defaultOptions: {
    queries: {
      staleTime: 60_000,
      retry: 1,
    },
  },
})

export default function AdminApp() {
  return (
    <QueryClientProvider client={queryClient}>
      <BrowserRouter>
        <AuthBootstrap />
        <Routes>
          <Route element={<GuestRoute />}>
            <Route path="/admin/login" element={<AdminLogin />} />
          </Route>

          <Route element={<AdminRoute />}>
            <Route path="/" element={<AdminLayout />}>
              <Route index element={<AdminDashboard />} />
              <Route path="reports" element={<AdminReports />} />
              <Route path="pets/add" element={<AdminAddPet />} />
              <Route path="pets/manage" element={<AdminManagePets />} />
              <Route path="pets/featured" element={<AdminFeatured />} />
              <Route path="availability" element={<AdminAvailability />} />
              <Route path="applications" element={<AdminApplications />} />
              <Route path="donations" element={<AdminDonations />} />
              <Route path="volunteers" element={<AdminVolunteers />} />
              <Route path="news/add" element={<AdminAddNews />} />
              <Route path="news/manage" element={<AdminManageNews />} />
              <Route path="users" element={<AdminManageUsers />} />
            </Route>
          </Route>

          <Route path="*" element={<Navigate to="/" replace />} />
        </Routes>
      </BrowserRouter>
    </QueryClientProvider>
  )
}