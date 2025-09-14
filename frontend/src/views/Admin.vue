<template>
  <div>
    <h1>Panel Administrador</h1>
    <Reporte />
    <SolicitudList :solicitudes="solicitudes"  />
  </div>
</template>

<script>
import api from '../services/api'
import SolicitudList from '../components/SolicitudList.vue'
import Reporte from '../components/Reporte.vue'

export default {
  components: { SolicitudList, Reporte },
  data() {
    return {
      solicitudes: []
    }
  },
  async mounted() {
    await this.fetchSolicitudes()
  },
  methods: {
    async fetchSolicitudes() {
      try {
        const response = await api.get('/solicitudes')
        this.solicitudes = response.data.data || []
      } catch (error) {
        console.error('Error al cargar solicitudes:', error)
        this.solicitudes = []
      }
    }
  }
}
</script>