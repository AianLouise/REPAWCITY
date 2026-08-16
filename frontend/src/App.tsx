import { BrowserRouter, Route, Routes } from 'react-router-dom'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import Layout from './components/Layout'
import AuthBootstrap from './components/AuthBootstrap'
import ScrollToTop from './components/ScrollToTop'
import { ProtectedRoute, GuestRoute, AdminLockout } from './router/clientGuards'
import Home from './pages/public/Home'
import Adopt from './pages/public/Adopt'
import AdoptProfile from './pages/public/AdoptProfile'
import Donate from './pages/public/Donate'
import News from './pages/public/News'
import NewsArticle from './pages/public/NewsArticle'
import ApplyForAdoption from './pages/public/ApplyForAdoption'
import Volunteer from './pages/public/Volunteer'
import { Mission, SuccessStories, FAQ, Contact } from './pages/static/StaticPages'
import { PrivacyPolicy, TermsOfUse } from './pages/static/LegalPages'
import Login from './pages/auth/Login'
import Register from './pages/auth/Register'
import AccountLayout from './pages/user/AccountLayout'
import AccountOverview from './pages/user/AccountOverview'
import AccountAppointments from './pages/user/AccountAppointments'
import AccountFavorites from './pages/user/AccountFavorites'
import Profile from './pages/user/Profile'
import ChangePassword from './pages/user/ChangePassword'
import Notifications from './pages/user/Notifications'
import UserApplications from './pages/user/UserApplications'
import VolunteerDashboard from './pages/user/VolunteerDashboard'
import BookingWizard from './pages/booking/BookingWizard'

const queryClient = new QueryClient({
  defaultOptions: {
    queries: {
      staleTime: 60_000,
      retry: 1,
    },
  },
})

export default function App() {
  return (
    <QueryClientProvider client={queryClient}>
      <BrowserRouter>
        <AuthBootstrap />
        <ScrollToTop />
        <AdminLockout>
          <Routes>
            <Route element={<Layout />}>
              <Route index element={<Home />} />
            <Route path="adopt" element={<Adopt />} />
            <Route path="adopt/:id" element={<AdoptProfile />} />
            <Route path="adopt/:id/apply" element={<ApplyForAdoption />} />
              <Route path="donate" element={<Donate />} />
              <Route path="news" element={<News />} />
              <Route path="news/:id" element={<NewsArticle />} />
              <Route path="volunteer" element={<Volunteer />} />

              <Route path="about/mission" element={<Mission />} />
              <Route path="about/success-stories" element={<SuccessStories />} />
              <Route path="about/faq" element={<FAQ />} />
              <Route path="about/contact" element={<Contact />} />
              <Route path="privacy" element={<PrivacyPolicy />} />
              <Route path="terms" element={<TermsOfUse />} />

            <Route element={<ProtectedRoute />}>
              <Route path="account" element={<AccountLayout />}>
                <Route index element={<AccountOverview />} />
                <Route path="appointments" element={<AccountAppointments />} />
                <Route path="applications" element={<UserApplications />} />
                <Route path="favorites" element={<AccountFavorites />} />
                <Route path="volunteer" element={<VolunteerDashboard />} />
                <Route path="profile" element={<Profile />} />
                <Route path="change-password" element={<ChangePassword />} />
                <Route path="notifications" element={<Notifications />} />
              </Route>
            </Route>

              <Route path="*" element={<Home />} />
            </Route>

            <Route element={<ProtectedRoute />}>
              <Route path="book" element={<BookingWizard />} />
            </Route>

            <Route element={<GuestRoute />}>
              <Route path="login" element={<Login />} />
              <Route path="register" element={<Register />} />
            </Route>
          </Routes>
        </AdminLockout>
      </BrowserRouter>
    </QueryClientProvider>
  )
}