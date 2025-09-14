<template>
  <div class="text-center">
    <h1>Bienvenido al Sistema de Gestión de Solicitudes</h1>
    <p v-if="!authStore.isAuthenticated">
      Por favor, <router-link to="/login">inicia sesión</router-link> para continuar.
    </p>
    <div v-else>
      <p>Estás autenticado como <strong>{{ authStore.userRole }}</strong>.</p>
      <div v-if="authStore.userRole === 'cliente'">
        <p>Accede a tus solicitudes o crea una nueva:</p>
        <router-link class="btn btn-primary" to="/cliente">Ir a Mis Solicitudes</router-link>
      </div>
      <div v-if="authStore.userRole === 'soporte'">
        <p>Gestiona las solicitudes asignadas:</p>
        <router-link class="btn btn-primary" to="/soporte">Ir a Solicitudes Asignadas</router-link>
      </div>
      <div v-if="authStore.userRole === 'admin'">
        <p>Administra usuarios, solicitudes y reportes:</p>
        <router-link class="btn btn-primary" to="/admin">Ir al Panel Admin</router-link>
      </div>
    </div>
  </div>
</template>

<script>
import { useAuthStore } from '../stores/auth'

export default {
  setup() {
    const authStore = useAuthStore()
    return { authStore }
  }
}
</script>