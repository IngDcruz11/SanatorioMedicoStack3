<script setup>
defineProps({ sucursales: { type: Array, required: true }, ocupado: Boolean })
defineEmits(['consultar', 'editar', 'eliminar'])
</script>

<template>
  <div class="table-container">
    <table>
      <caption class="sr-only">Sucursales registradas</caption>
      <thead><tr><th>Nombre</th><th>Ciudad</th><th>Departamento</th><th>Teléfono</th><th>Estado</th><th>Acciones</th></tr></thead>
      <tbody>
        <tr v-for="sucursal in sucursales" :key="sucursal.id || sucursal._id">
          <td>{{ sucursal.nombre }}</td><td>{{ sucursal.ciudad || '—' }}</td>
          <td>{{ sucursal.departamento || '—' }}</td><td>{{ sucursal.telefono }}</td>
          <td>{{ sucursal.estado ? 'Activa' : 'Inactiva' }}</td>
          <td><div class="actions">
            <button :disabled="ocupado" @click="$emit('consultar', sucursal)">Consultar</button>
            <button :disabled="ocupado" @click="$emit('editar', sucursal)">Editar</button>
            <button class="danger" :disabled="ocupado" @click="$emit('eliminar', sucursal)">Eliminar</button>
          </div></td>
        </tr>
      </tbody>
    </table>
  </div>
</template>
