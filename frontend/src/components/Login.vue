<template>
  <div class="card">
    <div class="card-body">
      <h2>Iniciar Sesión</h2>
      <form @submit.prevent="login">
        <div class="mb-3">
          <label for="email" class="form-label">Email</label>
          <input
            v-model="email"
            type="email"
            class="form-control"
            id="email"
            required
            placeholder="Ej. cliente@demo.com"
          >
          <div v-if="errors.email" class="text-danger mt-1">
            {{ errors.email.join(', ') }}
          </div>
        </div>
        <div class="mb-3">
          <label for="password" class="form-label">Contraseña</label>
          <input
            v-model="password"
            type="password"
            class="form-control"
            id="password"
            required
            placeholder="Ingresa tu contraseña"
          >
          <div v-if="errors.password" class="text-danger mt-1">
            {{ errors.password.join(', ') }}
          </div>
        </div>
        <button type="submit" class="btn btn-primary" :disabled="loading">
          {{ loading ? 'Iniciando...' : 'Iniciar Sesión' }}
        </button>
        <p v-if="error" class="text-danger mt-2">{{ error }}</p>
      </form>
    </div>
  </div>
</template>

<script>
import { useAuthStore } from '../stores/auth'
import { useRouter } from 'vue-router'

export default {
  setup() {
    const authStore = useAuthStore()
    const router = useRouter()
    return { authStore, router }
  },
  data() {
    return {
      email: '',
      password: '',
      error: '',
      errors: {},
      loading: false
    }
  },
  methods: {
    async login() {
      this.loading = true
      this.error = ''
      this.errors = {}
      try {
        const success = await this.authStore.login(this.email, this.password)
        if (success) {
          const role = this.authStore.userRole
          this.router.push(
            role === 'cliente' ? '/cliente' :
            role === 'soporte' ? '/soporte' :
            '/admin'
          )
        } else {
          this.error = 'Error al iniciar sesión. Por favor, intenta de nuevo.'
        }
      } catch (error) {
        if (error.response) {
          if (error.response.status === 422) {
            this.errors = error.response.data.errors || {}
            this.error = error.response.data.message || 'Datos inválidos'
          } else if (error.response.status === 401) {
            this.error = error.response.data.message || 'Credenciales inválidas'
          } else {
            this.error = 'Error en el servidor. Por favor, intenta de nuevo.'
          }
        } else {
          this.error = 'No se pudo conectar con el servidor.'
        }
      } finally {
        this.loading = false
      }
    }
  }
}
</script>