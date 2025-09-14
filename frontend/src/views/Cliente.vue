<template>
  <div>
    <h1>Mis Solicitudes</h1>
    <SolicitudForm @solicitudCreada="fetchSolicitudes" />
    <SolicitudList :solicitudes="solicitudes" @refreshSolicitudes="fetchSolicitudes" />
  </div>
</template>

<script>
import api from '../services/api'
import SolicitudForm from '../components/SolicitudForm.vue'
import SolicitudList from '../components/SolicitudList.vue'

export default {
  components: { SolicitudForm, SolicitudList },
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