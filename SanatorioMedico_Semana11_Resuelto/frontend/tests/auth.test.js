import test from 'node:test'
import assert from 'node:assert/strict'
import axios from 'axios'
import { authHttp, authService } from '../src/Services/authService.js'
import { attachAuth, getAuthToken, setAuthToken, onSessionExpired } from '../src/Services/authSession.js'
import { pacienteHttp, pacienteService } from '../src/Services/pacienteService.js'
test('login, usuario, token compartido con Pacientes y logout', async () => {
  const calls = []
  authHttp.defaults.adapter = async config => {
    calls.push(config)
    return { data: { token: 'fictional-token', user: { id: 'abc', name: 'Prueba' } }, status: 200, headers: {}, config }
  }
  const user = await authService.login({ email: 'test@example.invalid', password: 'fictional-password' })
  assert.equal(user.name,'Prueba'); assert.equal(getAuthToken(),'fictional-token')
  assert.equal((await authService.user()).id,'abc'); assert.equal(calls[1].headers.get('Authorization'),'Bearer fictional-token')
  pacienteHttp.defaults.adapter = async config => {
    assert.equal(config.headers.get('Authorization'),'Bearer fictional-token')
    return { data: { data: [], meta: { total: 0 } }, status: 200, headers: {}, config }
  }
  await pacienteService.listar({ page: 1 })
  await authService.logout(); assert.equal(getAuthToken(),'')
  assert.deepEqual(calls.map(c=>[c.method,c.url]),[['post','/auth/login'],['get','/auth/user'],['post','/auth/logout']])
})
test('401 cierra sesión, 403 la conserva y un 401 anterior no cierra la nueva', async () => {
  let expired = 0; const unsubscribe = onSessionExpired(()=>expired++)
  const api = attachAuth(axios.create())
  setAuthToken('fictional-one')
  api.defaults.adapter = async config => { throw { config, response: { status: 403 } } }
  await assert.rejects(api.get('/pacientes')); assert.equal(getAuthToken(),'fictional-one')
  api.defaults.adapter = async config => { setAuthToken('fictional-two'); throw { config, response: { status: 401 } } }
  await assert.rejects(api.get('/pacientes')); assert.equal(getAuthToken(),'fictional-two')
  api.defaults.adapter = async config => { throw { config, response: { status: 401 } } }
  await assert.rejects(api.get('/pacientes')); assert.equal(getAuthToken(),''); assert.equal(expired,1)
  unsubscribe()
})
