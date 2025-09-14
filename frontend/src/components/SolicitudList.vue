<template>
  <div class="card">
    <div class="card-body">
      <h2>Lista de Solicitudes</h2>
      <table class="table table-striped">
        <thead>
          <tr>
            <th>ID</th>
            <th>Título</th>
            <th>Descripción</th>
            <th>Estado</th>
            <th>Creado por</th>
            <th>Asignado a</th>
            <th>Fecha Creación</th>
            <th v-if="canUpdate">Acciones</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="solicitud in solicitudes" :key="solicitud.id">
            <td>{{ solicitud.id }}</td>
            <td>{{ solicitud.title }}</td>
            <td>{{ solicitud.description }}</td>
            <td>{{ formatStatus(solicitud.status) }}</td>
            <td>{{ solicitud.user ? solicitud.user.name : 'N/A' }}</td>
            <td>{{ solicitud.soporte ? solicitud.soporte.name : 'No asignado' }}</td>
            <td>{{ formatDate(solicitud.created_at) }}</td>
            <td v-if="canUpdate">
              <router-link
                :to="{ name: 'UpdateSolicitud', params: { id: solicitud.id } }"
                class="btn btn-sm btn-primary"
              >
                Actualizar
              </router-link>
            </td>
          </tr>
        </tbody>
      </table>
      <p v-if="!solicitudes.length" class="text-muted">No hay solicitudes disponibles.</p>
    </div>
  </div>
</template>

<script>
import { useAuthStore } from '../stores/auth'

export default {
  props: {
    solicitudes: {
      type: Array,
      required: true
    }
  },
  setup() {
    const authStore = useAuthStore()
    return { authStore }
  },
  computed: {
    canUpdate() {
      return ['soporte', 'admin'].includes(this.authStore.userRole)
    }
  },
  methods: {
    formatStatus(status) {
      const statuses = {
        abierto: 'Abierto',
        'en progreso': 'En Progreso',
        cerrado: 'Cerrado'
      }
      return statuses[status] || status
    },
    formatDate(date) {
      return new Date(date).toLocaleDateString('es-ES', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
      })
    }
  }
}
</script>


