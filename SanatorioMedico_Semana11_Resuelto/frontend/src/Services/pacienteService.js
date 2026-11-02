import axios from 'axios'

// PASO 7 - SERVICIO FRONTEND PACIENTES: conecta Vue con la API Laravel.
const api = axios.create({
  baseURL: import.meta.env?.VITE_API_URL || '/api',
  timeout: 15000,
  headers: { Accept: 'application/json' }
})

export const obtenerPacientes = async (buscar = '') => (await api.get('/pacientes', { params: { buscar } })).data
export const consultarPaciente = async id => (await api.get(`/pacientes/${id}`)).data
export const guardarPaciente = async datos => (await api.post('/pacientes', datos)).data
export const editarPaciente = async (id, datos) => (await api.put(`/pacientes/${id}`, datos)).data
export const eliminarPaciente = async id => (await api.delete(`/pacientes/${id}`)).data

export function mensajeErrorPaciente(error) {
  if (error.response?.status === 422) return 'Revisa los campos indicados.'
  if (error.response?.status === 404) return 'El paciente ya no existe. Actualiza la lista.'
  if (!error.response) return 'No se pudo conectar con Laravel. Verifica que el backend esté iniciado.'
  return 'No fue posible completar la operación.'
}
