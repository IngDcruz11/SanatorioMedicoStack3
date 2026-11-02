import axios from 'axios'
import { attachAuth } from './authSession.js'

const api = axios.create({
  baseURL: import.meta.env?.VITE_API_URL || '/api',
  timeout: 15000,
  headers: { Accept: 'application/json' }
})

attachAuth(api)
export const obtenerSucursales = async (buscar = '') => (await api.get('/sucursales', { params: { buscar } })).data
export const consultarSucursal = async (id) => (await api.get(`/sucursales/${id}`)).data
export const guardarSucursal = async (datos) => (await api.post('/sucursales', datos)).data
export const editarSucursal = async (id, datos) => (await api.put(`/sucursales/${id}`, datos)).data
export const eliminarSucursal = async (id) => (await api.delete(`/sucursales/${id}`)).data

export function mensajeError(error) {
  if (error.response?.status === 422) return 'Revisa los campos indicados.'
  if (error.response?.status === 404) return 'La sucursal ya no existe. Actualiza la lista.'
  if (!error.response) return 'No se pudo conectar con el servidor. Comprueba que Laravel esté iniciado.'
  return 'No fue posible completar la operación. Comprueba la conexión de Laravel con MongoDB.'
}
