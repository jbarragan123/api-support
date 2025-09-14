<template>
  <div>
    <h1>Solicitudes Asignadas</h1>
    <SolicitudUpdate
      v-if="selectedSolicitud"
      :solicitud-id="selectedSolicitud.id"
      :initial-status="selectedSolicitud.status"
      :initial-response="selectedSolicitud.response"
      @refreshSolicitudes="fetchSolicitudes" 
    />
    <SolicitudList
      :solicitudes="solicitudes"
      @selectSolicitud="selectSolicitud"
    />
  </div>
</template>

<script>
import api from '../services/api'
import SolicitudUpdate from '../components/SolicitudUpdate.vue'
import SolicitudList from '../components/SolicitudList.vue'
import { useAuthStore } from '../stores/auth'

export default {
  components: { SolicitudUpdate, SolicitudList },
  setup() {
    const authStore = useAuthStore()
    return { authStore }
  },
  data() {
    return {
      solicitudes: [],
      selectedSolicitud: null
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
        // Resetear la selección si la solicitud seleccionada ya no existe
        if (this.selectedSolicitud && !this.solicitudes.find(s => s.id === this.selectedSolicitud.id)) {
          this.selectedSolicitud = null
        }
      } catch (error) {
        console.error('Error al cargar solicitudes:', error)
        this.solicitudes = []
        this.selectedSolicitud = null
      }
    },
    selectSolicitud(solicitud) {
      this.selectedSolicitud = solicitud
    }
  }
}
</script>