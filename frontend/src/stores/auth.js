import { defineStore } from 'pinia'
import api from '../services/api'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    token: localStorage.getItem('token') || null,
    user: JSON.parse(localStorage.getItem('user')) || null,
    role: localStorage.getItem('role') || null
  }),
  actions: {
    async login(email, password) {
      try {
        const response = await api.post('/auth/login', {
          email,
          password
        })
        this.token = response.data.data.token
        this.user = response.data.data.user
        const roleMap = {
          'role_client': 'cliente',
          'role_support': 'soporte',
          'role_admin': 'admin'
        }
        this.role = roleMap[response.data.data.user.role] || 'cliente'
        localStorage.setItem('token', this.token)
        localStorage.setItem('user', JSON.stringify(this.user))
        localStorage.setItem('role', this.role)
        api.defaults.headers.common['Authorization'] = `Bearer ${this.token}`
        return true
      } catch (error) {
        console.error('Login error:', error)
        return false
      }
    },
    logout() {
      this.token = null
      this.user = null
      this.role = null
      localStorage.removeItem('token')
      localStorage.removeItem('user')
      localStorage.removeItem('role')
      delete api.defaults.headers.common['Authorization']
    }
  },
  getters: {
    isAuthenticated: (state) => !!state.token,
    userRole: (state) => state.role
  }
})