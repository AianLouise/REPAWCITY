import { useAdminDonations } from '../../hooks/useCommunity'
import { Loading } from '../../components/Shared'

export default function AdminDonations() {
  const { data, isLoading } = useAdminDonations()

  if (isLoading) return <Loading />

  return (
    <div className="space-y-6">
      <div className="flex items-center gap-3">
        <span className="mui-icon text-3xl text-repaw-dark">volunteer_activism</span>
        <h1 className="font-serif text-3xl font-bold text-repaw-dark">Donations</h1>
      </div>

      <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div className="bg-white/70 rounded-3xl p-6 border border-repaw-hover/40 shadow-sm">
          <p className="text-sm text-repaw-text/70">Total Cash Donations</p>
          <p className="font-serif text-3xl font-bold text-repaw-dark mt-1">₱ {data?.totals.cash ?? '0.00'}</p>
        </div>
        <div className="bg-white/70 rounded-3xl p-6 border border-repaw-hover/40 shadow-sm">
          <p className="text-sm text-repaw-text/70">In-kind Donations</p>
          <p className="font-serif text-3xl font-bold text-repaw-dark mt-1">{data?.totals.in_kind_count ?? 0}</p>
        </div>
      </div>

      <div className="bg-white/70 rounded-3xl p-8 border border-repaw-hover/40 shadow-sm">
        <h2 className="font-serif text-2xl font-bold text-repaw-dark mb-4">Recent Donations</h2>
        <div className="overflow-x-auto rounded-2xl border border-repaw-hover/40 max-h-[500px] overflow-y-auto">
          <table className="w-full text-left text-sm">
            <thead className="bg-repaw-bg/70 text-repaw-dark sticky top-0">
              <tr>
                <th className="px-3 py-3 font-semibold">Date</th>
                <th className="px-3 py-3 font-semibold">Donor</th>
                <th className="px-3 py-3 font-semibold">Email</th>
                <th className="px-3 py-3 font-semibold">Type</th>
                <th className="px-3 py-3 font-semibold">Amount / Items</th>
                <th className="px-3 py-3 font-semibold">Notes</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-repaw-hover/40">
              {data && data.data.length > 0 ? (
                data.data.map((d) => (
                  <tr key={d.id} className="hover:bg-repaw-bg/40">
                    <td className="px-3 py-3">{d.date}</td>
                    <td className="px-3 py-3 font-medium text-repaw-dark">{d.donor_name}</td>
                    <td className="px-3 py-3">{d.donor_email}</td>
                    <td className="px-3 py-3">
                      <span className={`rounded-full px-2.5 py-0.5 text-xs font-medium uppercase ${d.type === 'cash' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800'}`}>
                        {d.type === 'cash' ? 'Cash' : 'In-kind'}
                      </span>
                    </td>
                    <td className="px-3 py-3">
                      {d.type === 'cash' ? `₱ ${d.amount}` : d.item_description}
                    </td>
                    <td className="px-3 py-3 max-w-xs truncate">{d.notes ?? '—'}</td>
                  </tr>
                ))
              ) : (
                <tr>
                  <td colSpan={6} className="px-3 py-6 text-center text-repaw-text/70">No donations recorded yet.</td>
                </tr>
              )}
            </tbody>
          </table>
        </div>
      </div>
    </div>
  )
}
