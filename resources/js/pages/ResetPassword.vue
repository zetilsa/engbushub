<template>
  <div class="reset-password-container">
    <div class="card shadow border-0" style="border-radius: 12px; max-width: 500px; margin: 0 auto;">
      <div class="card-body p-4 p-md-5">
        <div class="text-center mb-4">
          <div class="password-icon bg-light-warning mb-3">
            <i class="bi bi-key-fill text-warning"></i>
          </div>
          <h3 class="fw-bold mb-2">Reset Password</h3>
          <p class="text-muted">Create a new secure password</p>
        </div>

        <form @submit.prevent="resetPassword" class="mt-3">
          <div class="mb-4">
            <label for="password" class="form-label fw-semibold">New Password</label>
            <div class="input-group">
              <span class="input-group-text bg-light">
                <i class="bi bi-lock text-muted"></i>
              </span>
              <input 
                type="password" 
                v-model="password" 
                class="form-control form-control-lg" 
                id="password" 
                placeholder="Enter new password"
                required
              >
            </div>
          </div>

          <div class="mb-4">
            <label for="confirmPassword" class="form-label fw-semibold">Confirm Password</label>
            <div class="input-group">
              <span class="input-group-text bg-light">
                <i class="bi bi-lock-fill text-muted"></i>
              </span>
              <input 
                type="password" 
                v-model="confirmPassword" 
                class="form-control form-control-lg" 
                id="confirmPassword" 
                placeholder="Confirm new password"
                required
              >
            </div>
          </div>

          <button 
            class="btn btn-warning w-100 py-3 fw-semibold text-white" 
            :disabled="loading"
          >
            <span v-if="!loading">
              <i class="bi bi-arrow-repeat me-2"></i>Reset Password
            </span>
            <span v-else>
              <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
              Resetting...
            </span>
          </button>
        </form>

        <div 
          v-if="message" 
          class="alert alert-success mt-4 d-flex align-items-center animate__animated animate__fadeIn"
        >
          <i class="bi bi-check-circle-fill me-2"></i>
          <div>{{ message }}</div>
          <button type="button" class="btn-close ms-auto" @click="message = ''" aria-label="Close"></button>
        </div>

        <div 
          v-if="error" 
          class="alert alert-danger mt-4 d-flex align-items-center animate__animated animate__fadeIn"
        >
          <i class="bi bi-exclamation-triangle-fill me-2"></i>
          <div>{{ error }}</div>
          <button type="button" class="btn-close ms-auto" @click="error = ''" aria-label="Close"></button>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.reset-password-container {
  padding: 2rem 1rem;
}

.password-icon {
  width: 70px;
  height: 70px;
  border-radius: 50%;
  display: inline-flex;
  align-items: center;
  justify-content: center;
}

.password-icon i {
  font-size: 2rem;
}

.bg-light-warning {
  background-color: rgba(255, 193, 7, 0.1);
}

.input-group-text {
  border-right: 0;
  background-color: #f8f9fa !important;
}

.form-control-lg {
  padding: 0.75rem 1rem;
  border-left: 0;
}

.btn-warning {
  background-color: #ffc107;
  border: none;
  transition: all 0.3s ease;
}

.btn-warning:hover {
  background-color: #e0a800;
  transform: translateY(-2px);
}

.btn-warning:disabled {
  background-color: #ffda6a;
  transform: none;
}

.btn-warning:active {
  transform: translateY(0);
}

.alert {
  border-radius: 8px;
}

@media (max-width: 576px) {
  .card-body {
    padding: 2rem !important;
  }
  
  .password-icon {
    width: 60px;
    height: 60px;
  }
  
  .password-icon i {
    font-size: 1.75rem;
  }
}
</style>
<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';

const password = ref('');
const confirmPassword = ref('');
const loading = ref(false);
const error = ref('');
const message = ref('');
const router = useRouter();

const resetPassword = async () => {
  loading.value = true;
  const email = localStorage.getItem('reset_email');
  const otp = localStorage.getItem('otp');

  try {
    await axios.post('http://127.0.0.1:8000/api/password/reset', {
      email,
      otp,
      password: password.value,
      password_confirmation: confirmPassword.value
    });

    message.value = 'Password berhasil direset. Silakan login kembali.';
    localStorage.removeItem('reset_email');
    localStorage.removeItem('otp');
    router.push('/login');
  } catch (err) {
    error.value = err.response?.data?.message || 'Gagal reset password';
  } finally {
    loading.value = false;
  }
};
</script>
