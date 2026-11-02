<script setup>
import { onMounted, reactive, ref } from 'vue'
import { consultarPaciente, editarPaciente, eliminarPaciente, guardarPaciente, mensajeErrorPaciente, obtenerPacientes } from '../Services/pacienteService.js'

// PASO 8 - CRUD PACIENTES: estado, búsqueda, formularios, validaciones y confirmación de eliminación.
const registros = ref([]), cargando = ref(false), ocupado = ref(false), buscar = ref('')
const mensaje = ref(''), error = ref(''), errores = ref({}), modo = ref(''), idActual = ref(''), panel = ref(null)
const campos = [
  { key: 'nombres', label: 'Nombres', required: true },
  { key: 'apellidos', label: 'Apellidos', required: true },
  { key: 'documento_identificacion', label: 'Documento de identificación', required: true, max: 30 },
  { key: 'fecha_nacimiento', label: 'Fecha de nacimiento', required: true, type: 'date' },
  { key: 'telefono', label: 'Teléfono', required: true, type: 'tel', max: 25 },
  { key: 'correo', label: 'Correo electrónico', type: 'email', max: 120 },
  { key: 'direccion', label: 'Dirección', required: true, max: 250 },
  { key: 'contacto_emergencia', label: 'Contacto de emergencia', required: true, max: 150 },
]
const vacio = () => Object.fromEntries([...campos.map(c => [c.key, '']), ['estado', true]])
const formulario = reactive(vacio())
const idDe = r => r.id || r._id

async function cargar() {
  cargando.value = true; error.value = ''
  try { registros.value = await obtenerPacientes(buscar.value.trim()) }
  catch (e) { error.value = mensajeErrorPaciente(e) }
  finally { cargando.value = false }
}
function abrir(nuevoModo, datos = null) {
  Object.assign(formulario, vacio())
  if (datos) campos.forEach(c => formulario[c.key] = c.type === 'date' ? String(datos[c.key] || '').slice(0, 10) : (datos[c.key] ?? ''))
  if (datos) formulario.estado = datos.estado ?? true
  modo.value = nuevoModo; idActual.value = datos ? idDe(datos) : ''; errores.value = {}; panel.value.showModal()
}
async function ver(registro, nuevoModo) {
  ocupado.value = true; error.value = ''; mensaje.value = ''
  try { abrir(nuevoModo, await consultarPaciente(idDe(registro))) }
  catch (e) { error.value = mensajeErrorPaciente(e) }
  finally { ocupado.value = false }
}
async function guardar() {
  ocupado.value = true; errores.value = {}; mensaje.value = ''; error.value = ''
  const datos = Object.fromEntries(Object.entries(formulario).map(([k, v]) => [k, typeof v === 'string' ? (v.trim() || null) : v]))
  try {
    const r = modo.value === 'nuevo' ? await guardarPaciente(datos) : await editarPaciente(idActual.value, datos)
    panel.value.close(); mensaje.value = r.mensaje; buscar.value = ''; await cargar()
  } catch (e) { errores.value = e.response?.data?.errors || {}; errores.value.general = [mensajeErrorPaciente(e)] }
  finally { ocupado.value = false }
}
async function eliminar() {
  ocupado.value = true
  try { const r = await eliminarPaciente(idActual.value); panel.value.close(); mensaje.value = r.mensaje; await cargar() }
  catch (e) { errores.value.general = [mensajeErrorPaciente(e)] }
  finally { ocupado.value = false }
}
async function limpiar() { buscar.value = ''; await cargar() }
onMounted(cargar)
</script>

<template>
  <section class="module-page">
    <header class="module-header"><div><p class="eyebrow">Administración</p><h1>Consulta de pacientes</h1><p>Administra y consulta los datos de los pacientes.</p></div><button class="primary" @click="abrir('nuevo')">+ Nuevo paciente</button></header>
    <div class="summary-grid"><article><span>Pacientes</span><strong>{{ registros.length }}</strong></article><article><span>Activos</span><strong>{{ registros.filter(r => r.estado).length }}</strong></article><article><span>Inactivos</span><strong>{{ registros.filter(r => !r.estado).length }}</strong></article></div>
    <section class="card">
      <div class="card-title-row"><div><h2>Listado de pacientes</h2><p>Consulta, busca, agrega, edita o elimina registros.</p></div><form class="inline-search" @submit.prevent="cargar"><input v-model="buscar" placeholder="Buscar paciente..."><button class="primary">Buscar</button><button type="button" @click="limpiar">Limpiar</button></form></div>
      <p v-if="mensaje" class="success">{{ mensaje }}</p><p v-if="error" class="error">{{ error }}</p><p v-if="cargando">Cargando pacientes...</p>
      <div v-else class="table-container"><table><thead><tr><th>Paciente</th><th>Documento</th><th>Teléfono</th><th>Correo</th><th>Estado</th><th>Acciones</th></tr></thead><tbody><tr v-for="r in registros" :key="idDe(r)"><td><strong>{{ r.nombres }} {{ r.apellidos }}</strong></td><td>{{ r.documento_identificacion }}</td><td>{{ r.telefono }}</td><td>{{ r.correo || '—' }}</td><td><span class="badge" :class="r.estado ? 'active' : 'inactive'">{{ r.estado ? 'Activo' : 'Inactivo' }}</span></td><td><div class="actions"><button @click="ver(r,'consulta')">Ver</button><button @click="ver(r,'editar')">Editar</button><button class="danger" @click="abrir('eliminar',r)">Eliminar</button></div></td></tr><tr v-if="!registros.length"><td colspan="6" class="empty-cell">No hay pacientes para mostrar.</td></tr></tbody></table></div>
    </section>
    <dialog ref="panel" @cancel="ocupado && $event.preventDefault()">
      <h2>{{ {nuevo:'Nuevo paciente',editar:'Editar paciente',consulta:'Detalle del paciente',eliminar:'Eliminar paciente'}[modo] }}</h2><p v-if="errores.general" class="error">{{ errores.general[0] }}</p>
      <template v-if="modo==='eliminar'"><p>¿Deseas eliminar a <strong>{{ formulario.nombres }} {{ formulario.apellidos }}</strong>? Esta acción no se puede deshacer.</p><div class="dialog-actions"><button @click="panel.close()">Cancelar</button><button class="danger" @click="eliminar">Confirmar eliminación</button></div></template>
      <template v-else-if="modo==='consulta'"><dl class="details"><div v-for="c in campos" :key="c.key"><dt>{{ c.label }}</dt><dd>{{ formulario[c.key] || 'Sin especificar' }}</dd></div><div><dt>Estado</dt><dd>{{ formulario.estado ? 'Activo' : 'Inactivo' }}</dd></div></dl><div class="dialog-actions"><button @click="panel.close()">Cerrar</button><button class="primary" @click="modo='editar'">Editar</button></div></template>
      <form v-else @submit.prevent="guardar"><p>Los campos con * son obligatorios.</p><fieldset :disabled="ocupado" class="form-grid"><div v-for="c in campos" :key="c.key" class="field"><label :for="`pac-${c.key}`">{{ c.label }}{{ c.required ? ' *' : '' }}</label><input :id="`pac-${c.key}`" v-model="formulario[c.key]" :type="c.type || 'text'" :required="c.required" :maxlength="c.max || 255"><span v-if="errores[c.key]" class="field-error">{{ errores[c.key][0] }}</span></div><label class="checkbox"><input v-model="formulario.estado" type="checkbox"> Paciente activo</label></fieldset><div class="dialog-actions"><button type="button" @click="panel.close()">Cancelar</button><button class="primary">{{ ocupado ? 'Guardando...' : 'Guardar' }}</button></div></form>
    </dialog>
  </section>
</template>
