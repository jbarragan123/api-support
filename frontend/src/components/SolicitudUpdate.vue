<template>
  <div class="card mb-4" v-if="id">
    <div class="card-body">
      <h3>Actualizar Solicitud #{{ id }}</h3>
      <form @submit.prevent="actualizarSolicitud">
        <div class="mb-3">
          <label for="status" class="form-label">Estado</label>
          <select
            v-model="form.status"
            class="form-control"
            id="status"
            required
          >
            <option value="" disabled>Selecciona un estado</option>
            <option value="abierto">Abierto</option>
            <option value="en progreso">En Progreso</option>
            <option value="cerrado">Cerrado</option>
          </select>
          <div v-if="errors.status" class="text-danger mt-1">
            {{ errors.status.join(', ') }}
          </div>
        </div>
        <div class="mb-3">
          <label for="response" class="form-label">Respuesta</label>
          <textarea
            v-model="form.response"
            class="form-control"
            id="response"
            rows="4"
            placeholder="Describe la respuesta..."
          ></textarea>
          <div v-if="errors.response" class="text-danger mt-1">
            {{ errors.response.join(', ') }}
          </div>
        </div>
        <button type="submit" class="btn btn-primary" :disabled="loading">
          {{ loading ? 'Actualizando...' : 'Actualizar Solicitud' }}
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
  <div v-else class="alert alert-danger">
    Error: No se ha seleccionado ninguna solicitud
  </div>
</template>

<script>
import api from '../services/api'
import { useAuthStore } from '../stores/auth'

export default {
  props: {
    id: {
      type: Number,
      required: true
    },
    initialStatus: {
      type: String,
      default: 'abierto'
    },
    initialResponse: {
      type: String,
      default: null
    }
  },
  setup() {
    const authStore = useAuthStore()
    return { authStore }
  },
  data() {
    return {
      form: {
        status: this.initialStatus,
        response: this.initialResponse || ''
      },
      errors: {},
      error: '',
      successMessage: '',
      loading: false
    }
  },
  methods: {
    async actualizarSolicitud() {
      if (!this.id) {
        this.error = 'ID de solicitud inválido'
        this.loading = false
        return
      }
      this.loading = true
      this.errors = {}
      this.error = ''
      this.successMessage = ''
      console.log('Datos enviados:', { status: this.form.status, response: this.form.response });
      try {
        const response = await api.put(`/solicitudes/${this.id}`, {
          status: this.form.status,
          response: this.form.response || null
        })
        this.successMessage = response.data.message || 'Solicitud actualizada correctamente'
        this.$emit('refreshSolicitudes')
        // Redirigir al listado después de 1 segundo para mostrar el mensaje de éxito
        setTimeout(() => {
          this.$router.push('/soporte') // Ajusta la ruta según la vista de listado
        }, 1000)
      } catch (error) {
        if (error.response) {
          if (error.response.status === 422) {
            this.errors = error.response.data.errors || {}
            this.error = error.response.data.message || 'Datos inválidos'
          } else if (error.response.status === 403) {
            this.error = 'No tienes permiso para actualizar solicitudes'
          } else if (error.response.status === 401) {
            this.error = error.response.data.message || 'Sesión inválida'
          } else if (error.response.status === 404) {
            this.error = 'Solicitud no encontrada'
          } else {
            this.error = 'Error al actualizar la solicitud'
          }
        } else {
          this.error = 'No se pudo conectar con el servidor'
        }
        console.error('Error al actualizar solicitud:', error)
      } finally {
        this.loading = false
      }
    }
  }
}
</script>