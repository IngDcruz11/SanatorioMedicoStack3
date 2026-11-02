<script setup>
import { ref } from 'vue'
import SucursalesView from './Views/SucursalesView.vue'
import PacienteView from './Views/PacienteView.vue'
import MedicoView from './Views/MedicoView.vue'

// PASO 9 - PANEL PRINCIPAL: menú funcional para abrir los tres CRUD solicitados.
const modulo = ref('sucursales')
const opciones = [
  { id: 'sucursales', texto: 'Sucursales', icono: '▦' },
  { id: 'pacientes', texto: 'Pacientes', icono: '♙' },
  { id: 'medicos', texto: 'Médicos', icono: '✚' },
]
</script>
<template>
  <div class="app-shell">
    <aside class="sidebar">
      <div class="brand"><div class="brand-mark">✚</div><div><strong>SANATORIO MÉDICO</strong><small>Sistema administrativo</small></div></div>
      <p class="nav-label">MENÚ PRINCIPAL</p>
      <button v-for="op in opciones" :key="op.id" class="nav-item" :class="{ selected: modulo === op.id }" @click="modulo = op.id"><span>{{ op.icono }}</span>{{ op.texto }}</button>
      <div class="sidebar-footer"><span>⚙</span><div><strong>Configuración</strong><small>Semana 11</small></div></div>
    </aside>
    <div class="content-shell">
      <header class="topbar"><div><strong>Sanatorio Médico</strong><span class="crumb">/ {{ opciones.find(o=>o.id===modulo)?.texto }}</span></div><div class="user-pill"><span class="status-dot"></span> Sistema conectado</div></header>
      <main class="content"><SucursalesView v-if="modulo==='sucursales'"/><PacienteView v-else-if="modulo==='pacientes'"/><MedicoView v-else/></main>
    </div>
  </div>
</template>
