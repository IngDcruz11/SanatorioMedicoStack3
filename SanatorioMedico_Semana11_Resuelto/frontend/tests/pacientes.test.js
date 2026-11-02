import test from 'node:test'
import assert from 'node:assert/strict'
import axios from 'axios'
import { mensajeErrorPaciente } from '../src/Services/pacienteService.js'

test('servicio muestra mensajes entendibles para validación y conexión', () => {
  assert.match(mensajeErrorPaciente({ response: { status: 422 } }), /campos/i)
  assert.match(mensajeErrorPaciente({}), /Laravel|conectar/i)
})

test('axios está disponible para el frontend', () => {
  assert.equal(typeof axios.create, 'function')
})
