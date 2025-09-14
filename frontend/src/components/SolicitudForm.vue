<template>
  <div class="card mb-4">
    <div class="card-body">
      <h3>Crear Nueva Solicitud</h3>
      <form @submit.prevent="crearSolicitud">
        <div class="mb-3">
          <label for="title" class="form-label">Título</label>
          <input
            v-model="form.title"
            type="text"
            class="form-control"
            id="title"
            placeholder="Ej. Problema con el sistema"
            required
          >
          <div v-if="errors.title" class="text-danger mt-1">
            {{ errors.title.join(', ') }}
          </div>
        </div>
        <div class="mb-3">
          <label for="description" class="form-label">Descripción</label>
          <textarea
            v-model="form.description"
            class="form-control"
            id="description"
            rows="4"
            placeholder="Describe el problema..."
            required
          ></textarea>
          <div v-if="errors.description" class="text-danger mt-1">
            {{ errors.description.join(', ') }}
          </div>
        </div>
        <button type="submit" class="btn btn-primary" :disabled="loading">
          {{ loading ? 'Creando...' : 'Crear Solicitud' }}
        </button>
        <div v-if="successMessage" class="alert alert-success mt-3">
          {{ successMessage }}
        </div>
        <div v-if="error" class="alert alert-danger mt-3">
          {{ error }}
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import api from '../services/api'
import { useAuthStore } from '../stores/auth'

export default {
  setup() {
    const authStore = useAuthStore()
    return { authStore }
  },
  data() {
    return {
      form: {
        title: '',
        description: ''
      },
      errors: {},
      error: '',
      successMessage: '',
      loading: false
    }
  },
  methods: {
    async crearSolicitud() {
      this.loading = true
      this.errors = {}
      this.error = ''
      this.successMessage = ''
      try {
        const response = await api.post('/solicitudes', {
          title: this.form.title,
          description: this.form.description
        })
        this.successMessage = response.data.message || 'Solicitud creada correctamente'
        this.form.title = ''
        this.form.description = ''
        this.$emit('solicitudCreada') // Notificar a Cliente.vue para refrescar la lista
      } catch (error) {
        if (error.response) {
          if (error.response.status === 422) {
            this.errors = error.response.data.errors || {}
            this.error = error.response.data.message || 'Datos inválidos'
          } else if (error.response.status === 403) {
            this.error = 'No tienes permiso para crear solicitudes'
          } else if (error.response.status === 401) {
            this.error = error.response.data.message || 'Sesión inválida'
          } else {
            this.error = 'Error al crear la solicitud'
          }
        } else {
          this.error = 'No se pudo conectar con el servidor'
        }
        console.error('Error al crear solicitud:', error)
      } finally {
        this.loading = false
      }
    }
  }
}
</script>