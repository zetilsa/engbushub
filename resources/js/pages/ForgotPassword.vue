<template>
  <div class="forgot-password-container">
    <div class="card shadow-sm border-0">
      <div class="card-body p-4 p-md-5">
        <div class="text-center mb-4">
          <i class="bi bi-shield-lock text-primary" style="font-size: 3rem;"></i>
          <h3 class="mt-3 mb-2 fw-bold">Forgot Password?</h3>
          <p class="text-muted">Enter your email to receive a password reset OTP</p>
        </div>

        <form @submit.prevent="submitEmail" class="mt-4">
          <div class="mb-4">
            <label for="email" class="form-label fw-semibold">Email Address</label>
            <div class="input-group">
              <span class="input-group-text bg-light">
                <i class="bi bi-envelope text-muted"></i>
              </span>
              <input 
                type="email" 
                v-model="email" 
                class="form-control form-control-lg" 
                id="email" 
                placeholder="your@email.com"
                required
              >
            </div>
          </div>

          <button 
            class="btn btn-primary w-100 py-2 fw-semibold" 
            :disabled="loading"
            :class="{ 'btn-loading': loading }"
          >
            <span v-if="!loading">
              <i class="bi bi-send-fill me-2"></i>Send OTP
            </span>
            <span v-else>
              <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
              Sending...
            </span>
          </button>

          <div class="text-center mt-3">
            <router-link to="/login" class="text-decoration-none">
              <i class="bi bi-arrow-left me-1"></i>Back to Login
            </router-link>
          </div>
        </form>

        <div v-if="message" class="alert alert-dismissible fade show mt-4" 
             :class="messageType === 'error' ? 'alert-danger' : 'alert-success'">
          <div class="d-flex align-items-center">
            <i class="bi me-2" :class="messageType === 'error' ? 'bi-exclamation-triangle-fill' : 'bi-check-circle-fill'"></i>
            {{ message }}
          </div>
          <button type="button" class="btn-close" @click="message = ''" aria-label="Close"></button>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.forgot-password-container {
  max-width: 500px;
  margin: 2rem auto;
  animation: fadeIn 0.3s ease;
}

.card {
  border-radius: 12px;
  overflow: hidden;
}

.form-control-lg {
  padding: 0.75rem 1rem;
  border-left: 0;
}

.input-group-text {
  border-right: 0;
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

.btn-loading {
  position: relative;
  opacity: 0.8;
}

.alert {
  border-radius: 8px;
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(10px); }
  to { opacity: 1; transform: translateY(0); }
}

@media (max-width: 576px) {
  .card-body {
    padding: 2rem !important;
  }
}
</style>
<script>
import axios from 'axios'
export default {
  data() {
    return {
      email: '',
      message: '',
      loading: false,
    }
  },
  methods: {
    async submitEmail() {
      this.loading = true;
      try {
        await axios.post('http://127.0.0.1:8000/api/password/request-otp', { email: this.email });
        localStorage.setItem('reset_email', this.email);
        this.$router.push('/verify-otp');
      } catch (err) {
        this.message = err.response?.data?.message || 'Something went wrong';
      } finally {
        this.loading = false;
      }
    }
  }
}
</script>
