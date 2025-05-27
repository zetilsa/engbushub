<template>
  <div class="otp-container">
    <div class="card shadow-sm border-0" style="border-radius: 12px;">
      <div class="card-body p-4 p-md-5">
        <div class="text-center mb-4">
          <div class="otp-icon bg-light-primary mb-3">
            <i class="bi bi-shield-lock text-primary"></i>
          </div>
          <h3 class="fw-bold mb-2">Verify OTP</h3>
          <p class="text-muted">Enter the 6-digit code sent to your email</p>
        </div>

        <form @submit.prevent="verifyOtp" class="mt-3">
          <div class="mb-4">
            <label for="otp" class="form-label fw-semibold">Verification Code</label>
            <div class="input-group">
              <span class="input-group-text bg-light">
                <i class="bi bi-key text-muted"></i>
              </span>
              <input 
                type="text" 
                v-model="otp" 
                class="form-control form-control-lg" 
                id="otp" 
                placeholder="Enter 6-digit OTP"
                required
                maxlength="6"
                inputmode="numeric"
                pattern="[0-9]*"
              >
            </div>
            <small class="text-muted d-block text-end mt-2">
              Didn't receive code? 
              <a href="#" @click.prevent="resendOtp" class="text-primary">Resend</a>
            </small>
          </div>

          <button 
            class="btn btn-primary w-100 py-3 fw-semibold"
            :disabled="loading || otp.length !== 6"
          >
            <span v-if="!loading">
              <i class="bi bi-check-circle me-2"></i>Verify OTP
            </span>
            <span v-else>
              <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
              Verifying...
            </span>
          </button>
        </form>

        <div 
          v-if="error" 
          class="alert alert-danger mt-4 d-flex align-items-center animate__animated animate__fadeIn"
        >
          <i class="bi bi-exclamation-triangle-fill me-2"></i>
          <div>{{ error }}</div>
          <button 
            type="button" 
            class="btn-close ms-auto" 
            @click="error = ''"
            aria-label="Close"
          ></button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';

const otp = ref('');
const error = ref('');
const loading = ref(false);
const router = useRouter();

const verifyOtp = async () => {
  loading.value = true;
  error.value = '';
  const email = localStorage.getItem('reset_email');

  try {
    const response = await axios.post('http://127.0.0.1:8000/api/password/verify-otp', {
      email,
      otp: otp.value,
    });

    localStorage.setItem('otp', otp.value);
    router.push('/reset-password');
  } catch (err) {
    error.value = err.response?.data?.message || 'Invalid OTP code. Please try again.';
  } finally {
    loading.value = false;
  }
};

const resendOtp = async () => {
  success.value = ''
  error.value = ''
  try {
    await axios.post('/api/resend-login-otp', { email: email.value })
    success.value = 'OTP berhasil dikirim ulang!'
    startCooldown()
  } catch (err) {
    error.value = err.response?.data?.message || 'Gagal mengirim ulang OTP'
  }
}
</script>


<style scoped>
.otp-container {
  max-width: 500px;
  margin: 2rem auto;
}

.card {
  border: none;
}

.otp-icon {
  width: 70px;
  height: 70px;
  border-radius: 50%;
  display: inline-flex;
  align-items: center;
  justify-content: center;
}

.otp-icon i {
  font-size: 2rem;
}

.bg-light-primary {
  background-color: rgba(13, 110, 253, 0.1);
}

.input-group-text {
  border-right: 0;
  background-color: #f8f9fa !important;
}

.form-control-lg {
  padding: 0.75rem 1rem;
  border-left: 0;
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

.btn-primary:disabled {
  background-color: #86b7fe;
  transform: none;
}

.btn-primary:active {
  transform: translateY(0);
}

.alert {
  border-radius: 8px;
}

@media (max-width: 576px) {
  .card-body {
    padding: 2rem !important;
  }
}
</style>