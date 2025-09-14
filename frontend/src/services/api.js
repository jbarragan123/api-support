import axios from 'axios'
import { useAuthStore } from '../stores/auth'
import { useRouter } from 'vue-router'

const api = axios.create({
  baseURL: 'http://127.0.0.1:8000/api',
  headers: {
    'Content-Type': 'application/json'
  }
})

api.interceptors.response.use(
  response => response,
  error => {
    const authStore = useAuthStore()
    const router = useRouter()

    if (error.response && error.response.status === 401) {
      alert(error.response.data.message || 'Sesión inválida. Por favor, inicia sesión de nuevo.')
      authStore.logout()
      router.push('/login')
    }
    return Promise.reject(error)
  }
)

export default api