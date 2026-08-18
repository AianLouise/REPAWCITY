export interface User {
  id: number
  fname: string
  lname: string
  email: string
  user_type: string
  created_at: string
}

export interface AuthResponse {
  user: User
  token: string
}
