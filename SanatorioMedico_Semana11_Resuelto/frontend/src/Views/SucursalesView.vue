<script setup>
import { onMounted, reactive, ref } from 'vue'
import SucursalesTable from '../components/sucursales/SucursalesTable.vue'
import { obtenerSucursales, consultarSucursal, guardarSucursal, editarSucursal, eliminarSucursal, mensajeError } from '../Services/sucursalesService'

const sucursales = ref([])
const cargando = ref(false)
const ocupado = ref(false)
const error = ref('')
const mensaje = ref('')
const buscar = ref('')
const modo = ref('')
const idActual = ref('')
const errores = ref({})
const panel = ref(null)
const campos = [
  { key: 'nombre', label: 'Nombre', required: true },
  { key: 'direccion', label: 'Dirección', required: true },
  { key: 'telefono', label: 'Teléfono', required: true, type: 'tel', max: 50 },
  { key: 'correo', label: 'Correo electrónico', type: 'email' },
  { key: 'ciudad', label: 'Ciudad' },
  { key: 'departamento', label: 'Departamento' },
  { key: 'fecha_apertura', label: 'Fecha de apertura', type: 'date' },
  { key: 'tipo_sucursal', label: 'Tipo de sucursal' }
]
const vacio = () => Object.fromEntries([...campos.map(c => [c.key, '']), ['estado', true]])
const formulario = reactive(vacio())
const idDe = s => s.id || s._id

async function cargar() {
  cargando.value = true
  error.value = ''
  try { sucursales.value = await obtenerSucursales(buscar.value.trim()) }
  catch (e) { error.value = mensajeError(e) }
  finally { cargando.value = false }
}
function abrir(modoNuevo, datos = null) {
  Object.assign(formulario, vacio())
  if (datos) {
    for (const campo of campos) formulario[campo.key] = datos[campo.key] ?? ''
    formulario.fecha_apertura = String(datos.fecha_apertura || '').slice(0, 10)
    formulario.estado = datos.estado ?? true
  }
  errores.value = {}
  modo.value = modoNuevo
  idActual.value = datos ? idDe(datos) : ''
  panel.value.showModal()
}
function cerrar() { if (!ocupado.value) panel.value.close() }
async function ver(sucursal, modoNuevo) {
  ocupado.value = true; error.value = ''; mensaje.value = ''
  try { abrir(modoNuevo, await consultarSucursal(idDe(sucursal))) }
  catch (e) { error.value = mensajeError(e) }
  finally { ocupado.value = false }
}
async function guardar() {
  ocupado.value = true; errores.value = {}; error.value = ''; mensaje.value = ''
  const datos = Object.fromEntries(Object.entries(formulario).map(([k, v]) => [k, typeof v === 'string' ? (v.trim() || null) : v]))
  try {
    const respuesta = modo.value === 'nuevo' ? await guardarSucursal(datos) : await editarSucursal(idActual.value, datos)
    panel.value.close()
    mensaje.value = respuesta.mensaje
    buscar.value = ''
    await cargar()
  } catch (e) {
    errores.value = e.response?.data?.errors || {}
    errores.value.general = [mensajeError(e)]
  } finally { ocupado.value = false }
}
async function eliminar() {
  ocupado.value = true; errores.value = {}; error.value = ''; mensaje.value = ''
  try {
    const respuesta = await eliminarSucursal(idActual.value)
    panel.value.close(); mensaje.value = respuesta.mensaje
    await cargar()
  } catch (e) { errores.value.general = [mensajeError(e)] }
  finally { ocupado.value = false }
}
async function limpiar() { buscar.value = ''; await cargar() }
onMounted(cargar)
</script>

<template>
  <main class="module-page">
    <header class="module-header">
      <div><p class="eyebrow">Administración</p><h1>Consulta de sucursales</h1><p>Administra y consulta las sedes del sanatorio.</p></div>
      <button class="primary" :disabled="ocupado || cargando" @click="abrir('nuevo')">+ Nueva sucursal</button>
    </header>
    <div class="summary-grid"><article><span>Sucursales</span><strong>{{ sucursales.length }}</strong></article><article><span>Activas</span><strong>{{ sucursales.filter(s => s.estado).length }}</strong></article><article><span>Inactivas</span><strong>{{ sucursales.filter(s => !s.estado).length }}</strong></article></div>
    <form class="search-bar" @submit.prevent="cargar">
      <label for="buscar">Buscar sucursal</label>
      <input id="buscar" v-model="buscar" maxlength="255" placeholder="Nombre, teléfono, ciudad, dirección…">
      <button class="primary" :disabled="cargando || ocupado">Buscar</button>
      <button type="button" :disabled="cargando || ocupado" @click="limpiar">Mostrar todas</button>
    </form>
    <p v-if="mensaje" class="success" role="status">{{ mensaje }}</p>
    <p v-if="error" class="error" role="alert">{{ error }} <button :disabled="ocupado || cargando" @click="cargar">Reintentar</button></p>
    <p v-if="cargando" role="status">Cargando sucursales…</p>
    <template v-else-if="!error">
      <p>{{ sucursales.length }} sucursal(es) encontrada(s).</p>
      <p v-if="!sucursales.length" class="empty">No hay sucursales para mostrar. Registra una nueva o cambia la búsqueda.</p>
      <SucursalesTable v-else :sucursales="sucursales" :ocupado="ocupado" @consultar="ver($event, 'consulta')" @editar="ver($event, 'editar')" @eliminar="abrir('eliminar', $event)" />
    </template>
    <dialog ref="panel" aria-labelledby="titulo-panel" @cancel="ocupado && $event.preventDefault()">
      <h2 id="titulo-panel">{{ { nuevo: 'Nueva sucursal', editar: 'Editar sucursal', consulta: 'Detalle de sucursal', eliminar: 'Eliminar sucursal' }[modo] }}</h2>
      <p v-if="errores.general" class="error" role="alert">{{ errores.general[0] }}</p>
      <template v-if="modo === 'eliminar'">
        <p>¿Deseas eliminar la sucursal <strong>{{ formulario.nombre }}</strong>? Esta acción no se puede deshacer.</p>
        <div class="dialog-actions"><button :disabled="ocupado" @click="cerrar">Cancelar</button><button class="danger" :disabled="ocupado" @click="eliminar">{{ ocupado ? 'Eliminando…' : 'Confirmar eliminación' }}</button></div>
      </template>
      <template v-else-if="modo === 'consulta'">
        <dl class="details"><div v-for="campo in campos" :key="campo.key"><dt>{{ campo.label }}</dt><dd>{{ formulario[campo.key] || 'Sin especificar' }}</dd></div><div><dt>Estado</dt><dd>{{ formulario.estado ? 'Activa' : 'Inactiva' }}</dd></div></dl>
        <div class="dialog-actions"><button @click="cerrar">Cerrar</button><button class="primary" @click="modo = 'editar'">Editar</button></div>
      </template>
      <form v-else @submit.prevent="guardar">
        <p>Los campos con * son obligatorios.</p>
        <fieldset :disabled="ocupado" class="form-grid">
          <div v-for="campo in campos" :key="campo.key" class="field">
            <label :for="campo.key">{{ campo.label }}{{ campo.required ? ' *' : '' }}</label>
            <input :id="campo.key" v-model="formulario[campo.key]" :type="campo.type || 'text'" :required="campo.required" :maxlength="campo.max || 255" :aria-invalid="!!errores[campo.key]" :aria-describedby="errores[campo.key] ? `${campo.key}-error` : undefined">
            <span v-if="errores[campo.key]" :id="`${campo.key}-error`" class="field-error">{{ errores[campo.key][0] }}</span>
          </div>
          <label class="checkbox"><input type="checkbox" v-model="formulario.estado"> Sucursal activa</label>
        </fieldset>
        <div class="dialog-actions"><button type="button" :disabled="ocupado" @click="cerrar">Cancelar</button><button class="primary" :disabled="ocupado">{{ ocupado ? 'Guardando…' : 'Guardar' }}</button></div>
      </form>
    </dialog>
  </main>
</template>
