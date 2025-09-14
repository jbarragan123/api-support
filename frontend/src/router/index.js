import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '../stores/auth'
import Home from '../views/Home.vue'
import Cliente from '../views/Cliente.vue'
import Soporte from '../views/Soporte.vue'
import Admin from '../views/Admin.vue'
import Login from '../components/Login.vue'
import SolicitudUpdate from '../components/SolicitudUpdate.vue'

const routes = [
  { path: '/', name: 'Home', component: Home },
  { path: '/login', name: 'Login', component: Login },
  {
    path: '/cliente',
    name: 'Cliente',
    component: Cliente,
    meta: { requiresAuth: true, roles: ['cliente'] }
  },
  {
    path: '/soporte',
    name: 'Soporte',
    component: Soporte,
    meta: { requiresAuth: true, roles: ['soporte'] }
  },
  {
    path: '/admin',
    name: 'Admin',
    component: Admin,
    meta: { requiresAuth: true, roles: ['admin'] }
  },
  {
    path: '/solicitudes/:id/edit',
    name: 'UpdateSolicitud',
    component: SolicitudUpdate,
    props: true,
    meta: { requiresAuth: true, roles: ['soporte', 'admin'] }
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

router.beforeEach((to, from, next) => {
  const authStore = useAuthStore()
  if (to.meta.requiresAuth && !authStore.isAuthenticated) {
    next('/login')
  } else if (to.meta.roles && !to.meta.roles.includes(authStore.userRole)) {
    next('/')
  } else {
    next()
  }
})

export default router