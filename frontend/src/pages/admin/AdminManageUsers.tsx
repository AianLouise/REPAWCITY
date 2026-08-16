import { useState } from 'react'
import { useAdminUsers, useAdminActions } from '../../hooks/useAdmin'
import type { User } from '../../types'

export default function AdminManageUsers() {
  const { data: users, isLoading } = useAdminUsers()
  const actions = useAdminActions()
  const [selected, setSelected] = useState<User | null>(null)
  const [edit, setEdit] = useState({ fname: '', lname: '', email: '', password: '' })
  const [notice, setNotice] = useState<string | null>(null)
  const [error, setError] = useState<string | null>(null)

  const inputCls =
    'w-full rounded-xl border border-repaw-hover bg-repaw-bg px-4 py-2.5 text-repaw-text focus:outline-none focus:ring-2 focus:ring-repaw-text'

  function select(user: User) {
    setSelected(user)
    setEdit({ fname: user.fname, lname: user.lname, email: user.email, password: '' })
    setNotice(null)
    setError(null)
  }

  async function handleUpdate(e: React.FormEvent) {
    e.preventDefault()
    if (!selected) return
    setNotice(null)
    setError(null)
    const data: { fname: string; lname: string; email: string; password?: string } = {
      fname: edit.fname,
      lname: edit.lname,
      email: edit.email,
    }
    if (edit.password) data.password = edit.password
    try {
      await actions.updateUser.mutateAsync({ id: selected.id, data })
      setNotice('Data updated successfully')
      setEdit((f) => ({ ...f, password: '' }))
    } catch (e) {
      const err = e as { response?: { data?: { errors?: Record<string, string[]> } } }
      setError(err.response?.data?.errors?.email?.[0] ?? 'Failed to update user')
    }
  }

  async function handleDelete(user: User) {
    if (!confirm(`Delete user ${user.fname} ${user.lname}?`)) return
    await actions.deleteUser.mutateAsync(user.id)
    if (selected?.id === user.id) setSelected(null)
  }

  async function handleRole(user: User, target: '1' | '2') {
    await actions.updateUserRole.mutateAsync({ id: user.id, userType: target })
  }

  return (
    <div className="space-y-8">
      <div className="bg-white/70 rounded-3xl p-8 border border-repaw-hover/40 shadow-sm">
        <div className="flex items-center gap-3 mb-6">
          <span className="mui-icon text-3xl text-repaw-dark">group</span>
          <h1 className="font-serif text-3xl font-bold text-repaw-dark">User List</h1>
        </div>

        <div className="overflow-x-auto rounded-2xl border border-repaw-hover/40 max-h-[400px] overflow-y-auto">
          <table className="w-full text-left text-sm">
            <thead className="bg-repaw-bg/70 text-repaw-dark sticky top-0">
              <tr>
                <th className="px-3 py-3 font-semibold">User ID</th>
                <th className="px-3 py-3 font-semibold">First Name</th>
                <th className="px-3 py-3 font-semibold">Last Name</th>
                <th className="px-3 py-3 font-semibold">Email</th>
                <th className="px-3 py-3 font-semibold">User Type</th>
                <th className="px-3 py-3 font-semibold">Date Created</th>
                <th className="px-3 py-3 font-semibold">Actions</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-repaw-hover/40">
              {isLoading ? (
                <tr>
                  <td colSpan={7} className="px-3 py-6 text-center text-repaw-text/70">Loading...</td>
                </tr>
              ) : users && users.length > 0 ? (
                users.map((user) => (
                  <tr key={user.id} className="hover:bg-repaw-bg/40 cursor-pointer" onClick={() => select(user)}>
                    <td className="px-3 py-3">{user.id}</td>
                    <td className="px-3 py-3">{user.fname}</td>
                    <td className="px-3 py-3">{user.lname}</td>
                    <td className="px-3 py-3">{user.email}</td>
                    <td className="px-3 py-3">
                      <span
                        className={`inline-block rounded-full px-3 py-1 text-xs font-medium ${
                          user.user_type === '1' ? 'bg-repaw-dark text-repaw-bg' : 'bg-repaw-hover/60 text-repaw-dark'
                        }`}
                      >
                        {user.user_type === '1' ? 'Admin' : 'User'}
                      </span>
                    </td>
                    <td className="px-3 py-3">{user.created_at}</td>
                    <td className="px-3 py-3 space-x-2">
                      <button
                        onClick={(e) => {
                          e.stopPropagation()
                          void handleRole(user, user.user_type === '1' ? '2' : '1')
                        }}
                        className="inline-flex items-center gap-1 bg-repaw-accent text-repaw-dark rounded-full px-3 py-1.5 text-xs font-medium uppercase tracking-wide hover:opacity-90 transition-opacity"
                      >
                        <span className="mui-icon text-[16px]">{user.user_type === '1' ? 'arrow_downward' : 'arrow_upward'}</span>
                        {user.user_type === '1' ? 'Demote' : 'Promote'}
                      </button>
                      <button
                        onClick={(e) => {
                          e.stopPropagation()
                          void handleDelete(user)
                        }}
                        className="inline-flex items-center gap-1 bg-repaw-danger text-white rounded-full px-3 py-1.5 text-xs font-medium uppercase tracking-wide hover:opacity-90 transition-opacity"
                      >
                        <span className="mui-icon text-[16px]">delete</span>Delete
                      </button>
                    </td>
                  </tr>
                ))
              ) : (
                <tr>
                  <td colSpan={7} className="px-3 py-6 text-center text-repaw-text/70">No data available</td>
                </tr>
              )}
            </tbody>
          </table>
        </div>
      </div>

      {selected && (
        <div className="max-w-3xl mx-auto bg-white/70 rounded-3xl p-8 border border-repaw-hover/40 shadow-sm">
          <h1 className="font-serif text-2xl font-bold text-repaw-dark mb-6">User Details</h1>

          {notice && <div className="mb-4 rounded-xl border border-green-400/40 bg-green-50 px-4 py-3 text-sm text-green-800">{notice}</div>}
          {error && <div className="mb-4 rounded-xl border border-repaw-danger/40 bg-red-50 px-4 py-3 text-sm text-repaw-danger">{error}</div>}

          <form onSubmit={handleUpdate} className="space-y-5">
            <div className="grid grid-cols-1 sm:grid-cols-2 gap-5">
              <div>
                <label className="block text-sm font-medium text-repaw-dark mb-1.5">ID:</label>
                <input type="text" value={selected.id} readOnly className={inputCls} />
              </div>
              <div>
                <label className="block text-sm font-medium text-repaw-dark mb-1.5">User Type:</label>
                <input type="text" value={selected.user_type === '1' ? 'Admin' : 'User'} readOnly className={inputCls} />
              </div>
              <div>
                <label className="block text-sm font-medium text-repaw-dark mb-1.5">First Name:</label>
                <input type="text" required value={edit.fname} onChange={(e) => setEdit((f) => ({ ...f, fname: e.target.value }))} className={inputCls} />
              </div>
              <div>
                <label className="block text-sm font-medium text-repaw-dark mb-1.5">Last Name:</label>
                <input type="text" required value={edit.lname} onChange={(e) => setEdit((f) => ({ ...f, lname: e.target.value }))} className={inputCls} />
              </div>
              <div>
                <label className="block text-sm font-medium text-repaw-dark mb-1.5">Email:</label>
                <input type="email" required value={edit.email} onChange={(e) => setEdit((f) => ({ ...f, email: e.target.value }))} className={inputCls} />
              </div>
              <div>
                <label className="block text-sm font-medium text-repaw-dark mb-1.5">New Password (leave blank to keep current):</label>
                <input
                  type="password"
                  value={edit.password}
                  onChange={(e) => setEdit((f) => ({ ...f, password: e.target.value }))}
                  placeholder="Leave blank to keep current password"
                  className={inputCls}
                />
              </div>
              <div>
                <label className="block text-sm font-medium text-repaw-dark mb-1.5">Date Created:</label>
                <input type="text" value={selected.created_at} readOnly className={inputCls} />
              </div>
            </div>
            <button
              type="submit"
              disabled={actions.updateUser.isPending}
              className="inline-flex items-center gap-2 bg-repaw-text text-repaw-bg rounded-full px-8 py-3 text-[15px] font-medium uppercase tracking-wide hover:bg-repaw-dark transition-colors duration-300 disabled:opacity-60"
            >
              <span className="mui-icon">save</span> Update
            </button>
          </form>
        </div>
      )}
    </div>
  )
}
