<template>
  <div class="container">
    <nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
      <div class="container-fluid">
        <a class="navbar-brand" href="/">Sistema de Solicitudes</a>
        <button
          class="navbar-toggler"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#navbarNav"
          aria-controls="navbarNav"
          aria-expanded="false"
          aria-label="Toggle navigation"
        >
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <div class="navbar-nav">
            <router-link class="nav-link" to="/">Inicio</router-link>
            <router-link
              v-if="authStore.isAuthenticated && authStore.userRole === 'cliente'"
              class="nav-link"
              to="/cliente"
            >
              Mis Solicitudes
            </router-link>
            <router-link
              v-if="authStore.isAuthenticated && authStore.userRole === 'soporte'"
              class="nav-link"
              to="/soporte"
            >
              Solicitudes Asignadas
            </router-link>
            <router-link
              v-if="authStore.isAuthenticated && authStore.userRole === 'admin'"
              class="nav-link"
              to="/admin"
            >
              Panel Admin
            </router-link>
            <a
              v-if="authStore.isAuthenticated"
              class="nav-link"
              href="#"
              @click.prevent="logout"
            >
              Cerrar Sesión
            </a>
            <router-link
              v-else
              class="nav-link"
              to="/login"
            >
              Iniciar Sesión
            </router-link>
          </div>
        </div>
      </div>
    </nav>
    <router-view></router-view>
  </div>
</template>

<script>
import { useAuthStore } from './stores/auth'
import { useRouter } from 'vue-router'

export default {
  setup() {
    const authStore = useAuthStore()
    const router = useRouter()
    return { authStore, router }
  },
  methods: {
    logout() {
      this.authStore.logout()
      this.router.push('/login')
    }
  }
}
</script>

<style>
@import './assets/styles.css';
</style>