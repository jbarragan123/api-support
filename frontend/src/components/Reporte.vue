<template>
  <div class="card">
    <div class="card-body">
      <h2>Reporte de Solicitudes</h2>
      <div v-if="error" class="alert alert-danger">
        {{ error }}
      </div>
      <table v-else-if="Object.keys(reporte).length" class="table">
        <thead>
          <tr>
            <th>Estado</th>
            <th>Cantidad</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(value, estado) in reporte" :key="estado">
            <td>{{ formatStatus(estado) }}</td>
            <td>{{ value }}</td>
          </tr>
        </tbody>
      </table>
      <p v-else class="text-muted">No hay datos de reporte disponibles.</p>
    </div>
  </div>
</template>

<script>
import api from '../services/api'

export default {
  data() {
    return {
      reporte: {},
      error: ''
    }
  },
  async mounted() {
    await this.fetchReporte()
  },
  methods: {
    async fetchReporte() {
      this.error = ''
      try {
        const response = await api.get('/reportes/solicitudes')
        this.reporte = response.data.data || {}
      } catch (error) {
        this.error = error.response?.data?.message || 'Error al cargar el reporte'
        console.error('Error al cargar reporte:', error)
      }
    },
    formatStatus(status) {
      const statuses = {
        abierto: 'Abierto',
        'en progreso': 'En Progreso',
        cerrado: 'Cerrado'
      }
      return statuses[status] || status
    }
  }
}
</script>