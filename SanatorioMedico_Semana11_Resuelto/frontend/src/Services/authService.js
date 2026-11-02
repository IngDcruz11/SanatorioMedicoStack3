import axios from 'axios'
import { attachAuth, clearAuthSession, setAuthToken } from './authSession.js'
export const authHttp = attachAuth(axios.create({ baseURL: import.meta.env?.VITE_API_URL || '/api', timeout: 15000, headers: { Accept: 'application/json' } }))
export const authService = {
  async login(credentials) {
    const { data } = await authHttp.post('/auth/login', credentials)
    setAuthToken(data.token)
    return data.user
  },
  async user() { return (await authHttp.get('/auth/user')).data.user },
  async logout() { await authHttp.post('/auth/logout'); clearAuthSession() },
}
