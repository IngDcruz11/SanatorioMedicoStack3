let token = ''
const listeners = new Set()
export const getAuthToken = () => token
export function setAuthToken(value) { token = value || '' }
export function onSessionExpired(listener) { listeners.add(listener); return () => listeners.delete(listener) }
export function clearAuthSession() { token = ''; for (const listener of listeners) listener() }
export function attachAuth(client) {
  client.interceptors.request.use(config => {
    if (token) config.headers.set('Authorization', `Bearer ${token}`)
    return config
  })
  client.interceptors.response.use(response => response, error => {
    // Una respuesta tardía de un token anterior no debe cerrar una sesión nueva.
    if (error.response?.status === 401 && token && error.config?.headers?.get('Authorization') === `Bearer ${token}`) clearAuthSession()
    return Promise.reject(error)
  })
  return client
}
