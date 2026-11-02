import axios from 'axios'
import { attachAuth } from './authSession.js'
const http = axios.create({ baseURL: import.meta.env?.VITE_API_URL || '/api', timeout: 15000, headers: { Accept: 'application/json' } })
attachAuth(http)
export const citaService = {
 async listar(params = {}) { return (await http.get('/citas', { params })).data },
 async buscar(params = {}) { return (await http.get('/citas/buscar', { params })).data },
 async obtener(id) { return (await http.get(`/citas/${encodeURIComponent(id)}`)).data.data },
 async crear(datos) { return (await http.post('/citas', datos)).data.data },
 async actualizar(id, datos) { return (await http.put(`/citas/${encodeURIComponent(id)}`, datos)).data.data },
 async eliminar(id) { await http.delete(`/citas/${encodeURIComponent(id)}`) },
 async catalogos() { return (await http.get('/citas/catalogos')).data },
 async pacientes() { return (await http.get('/pacientes', { params: { per_page: 100 } })).data },
 async sucursales() { return (await http.get('/sucursales')).data },
}
export function mensajeErrorCita(error) {
 if (!error.response) return 'No se pudo conectar con el servidor. Revisa la conexión e intenta de nuevo.'
 const s = error.response.status
 if (s === 401) return 'Tu sesión expiró. Inicia sesión de nuevo.'
 if (s === 403) return 'Tu cuenta no tiene permiso para administrar citas.'
 if (s === 404) return 'La cita o una referencia ya no existe. Actualiza los catálogos.'
 if (s === 422) return 'Revisa los campos señalados y confirma que las referencias sigan existiendo.'
 if (s === 503) return 'El servicio de datos no está disponible.'
 return 'No se pudo completar la operación. Intenta de nuevo.'
}
