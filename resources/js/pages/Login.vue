<template>
  <div class="login-container d-flex justify-content-center align-items-center min-vh-100 bg-light">
    <div class="card border-0 shadow-lg p-4 p-md-5" style="width: 100%; max-width: 450px; border-radius: 12px;">
      <div class="card-body">
        <!-- Logo/Header -->
        <div class="text-center mb-4">
          <div class="login-icon bg-primary bg-opacity-10 d-inline-flex p-3 rounded-circle mb-3">
            <i class="bi bi-person-fill text-primary" style="font-size: 1.75rem;"></i>
          </div>
          <h2 class="text-primary fw-bold mb-2">Welcome Back</h2>
          <p class="text-muted">Please enter your credentials to login</p>
        </div>

        <form @submit.prevent="submit">
          <!-- Email -->
          <div class="mb-4">
            <label for="email" class="form-label fw-semibold">Email Address</label>
            <div class="input-group">
              <span class="input-group-text bg-light border-end-0">
                <i class="bi bi-envelope text-muted"></i>
              </span>
              <input 
                type="email" 
                class="form-control form-control-lg border-start-0" 
                id="email" 
                v-model="fields.email" 
                placeholder="your@email.com" 
                required
              >
            </div>
            <span v-if="errors.email" class="text-danger small mt-1 d-block">{{ errors.email[0] }}</span>
          </div>

          <!-- Password -->
          <div class="mb-4">
            <label for="password" class="form-label fw-semibold">Password</label>
            <div class="input-group">
              <span class="input-group-text bg-light border-end-0">
                <i class="bi bi-lock text-muted"></i>
              </span>
              <input 
                type="password" 
                class="form-control form-control-lg border-start-0" 
                id="password" 
                v-model="fields.password" 
                placeholder="••••••••" 
                required
              >
            </div>
            <span v-if="errors.password" class="text-danger small mt-1 d-block">{{ errors.password[0] }}</span>
          </div>

          <!-- Forgot Password -->
          <div class="d-flex justify-content-end mb-4">
            <router-link to="/forgot-password" class="text-primary text-decoration-none small">
              Forgot Password?
            </router-link>
          </div>

          <!-- Submit Button -->
          <div class="d-grid mb-3">
            <button type="submit" class="btn btn-primary btn-lg py-3 fw-semibold">
              <span v-if="!loading">Login</span>
              <span v-else class="d-flex align-items-center justify-content-center">
                <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                Logging in...
              </span>
            </button>
            <span v-if="errors.general" class="text-danger small mt-2 text-center">{{ errors.general }}</span>
          </div>

          <!-- Register Link -->
          <div class="text-center mt-4 pt-3 border-top">
            <p class="text-muted mb-0">Don't have an account?
              <router-link to="/register" class="text-primary fw-semibold text-decoration-none ms-1">
                Create account
              </router-link>
            </p>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<style scoped>
.login-container {
  background-color: #f8f9fa;
}

.login-icon {
  transition: transform 0.3s ease;
}

.login-icon:hover {
  transform: scale(1.1);
}

.card {
  border: none;
  overflow: hidden;
}

.form-control-lg {
  padding: 0.75rem 1rem;
}

.input-group-text {
  background-color: #f8f9fa !important;
}

.btn-primary {
  background-color: #0d6efd;
  border: none;
  transition: all 0.3s ease;
}

.btn-primary:hover {
  background-color: #0b5ed7;
  transform: translateY(-2px);
}

.btn-primary:active {
  transform: translateY(0);
}

.border-top {
  border-top: 1px solid #eee !important;
}

@media (max-width: 576px) {
  .card {
    border-radius: 0;
    box-shadow: none;
  }
  
  .login-container {
    background-color: white;
    padding: 1rem;
  }
}
</style>

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

