import axios from 'axios'

// PASO 7 - SERVICIO FRONTEND MÉDICOS: todas las operaciones viajan a Laravel API.
const api = axios.create({
  baseURL: import.meta.env?.VITE_API_URL || '/api',
  timeout: 15000,
  headers: { Accept: 'application/json' }
})

export const obtenerMedicos = async (buscar = '') => (await api.get('/medicos', { params: { buscar } })).data
export const consultarMedico = async id => (await api.get(`/medicos/${id}`)).data
export const guardarMedico = async datos => (await api.post('/medicos', datos)).data
export const editarMedico = async (id, datos) => (await api.put(`/medicos/${id}`, datos)).data
export const eliminarMedico = async id => (await api.delete(`/medicos/${id}`)).data

export function mensajeErrorMedico(error) {
  if (error.response?.status === 422) return 'Revisa los campos indicados.'
  if (error.response?.status === 404) return 'El médico ya no existe. Actualiza la lista.'
  if (!error.response) return 'No se pudo conectar con Laravel. Verifica que el backend esté iniciado.'
  return 'No fue posible completar la operación.'
}
