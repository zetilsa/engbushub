<template>
  <div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="card shadow p-4" style="width: 400px; border-color: #007bff;">
      <h2 class="text-center text-primary mb-4">Login</h2>
      <form @submit.prevent="submit">
        <!-- Email -->
        <div class="mb-3">
          <label for="email" class="form-label">Email</label>
          <input type="email" class="form-control border-primary" id="email" v-model="fields.email" placeholder="Enter your email" required>
          <span v-if="errors.email" class="text-danger">{{ errors.email[0] }}</span>
        </div>

        <!-- Password -->
        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <input type="password" class="form-control border-primary" id="password" v-model="fields.password" placeholder="Enter your password" required>
          <span v-if="errors.password" class="text-danger">{{ errors.password[0] }}</span>
        </div>

        <router-link to="/forgot-password" class="text-primary text-decoration-none">Forgot Password</router-link>

        <!-- Submit -->
        <div class="d-grid align-item-center">
          <button type="submit" class="btn btn-primary mt-4 mb-3">Login</button>
          <span v-if="errors.general" class="text-danger">{{ errors.general }}</span>
        </div>

        <!-- Register -->
        <div class="mb-3 mt-3 text-center">
          <span class="form-label-check">Don't have an account?
            <router-link to="/register" class="text-primary text-decoration-none">Register</router-link>
          </span>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'

const router = useRouter()

const fields = ref({
  email: '',
  password: ''
})

const errors = ref({})

const submit = async () => {
  errors.value = {}
  try {
    const response = await axios.post('http://127.0.0.1:8000/api/login', fields.value)

    if (response.data.status === 'otp_required') {
      // Simpan email sementara
      localStorage.setItem('otp_email', fields.value.email)

      router.push({
        name: 'VerifyOtp' // pastikan route ini ada
      })
    } else if (response.data.token) {
      // Kalau dapat token langsung (login biasa)
      localStorage.setItem('token', response.data.token)
      axios.defaults.headers.common['Authorization'] = `Bearer ${response.data.token}`
      router.push('/')
    }

  } catch (error) {
    if (error.response) {
      if (error.response.data.errors) {
        errors.value = error.response.data.errors
      } else if (error.response.data.message) {
        errors.value.general = error.response.data.message
      } else {
        errors.value.general = 'Unknown Error'
      }
    }
  }
}
</script>

