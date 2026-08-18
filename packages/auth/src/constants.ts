/** Admin users have user_type === '1'. */
export const ADMIN_ROLE_TYPE = '1'

/** Regular (client) users have user_type === '2'. */
export const CLIENT_ROLE_TYPE = '2'

export function isAdmin(user: { user_type: string } | null): boolean {
  return user?.user_type === ADMIN_ROLE_TYPE
}
