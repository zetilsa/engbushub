<template>
  <div class="container mt-5">
    <h3>Verify OTP</h3>
    <form @submit.prevent="verifyOtp">
      <div class="mb-3">
        <label for="otp" class="form-label">Enter OTP</label>
        <input type="text" v-model="otp" class="form-control" id="otp" required>
      </div>
      <button class="btn btn-success" :disabled="loading">
        {{ loading ? 'Verifying...' : 'Verify OTP' }}
      </button>
    </form>

    <div v-if="error" class="alert alert-danger mt-3">{{ error }}</div>
  </div>
</template>

<script>
import axios from 'axios'
export default {
  data() {
    return {
      otp: '',
      error: '',
      loading: false,
    }
  },
  methods: {
    async verifyOtp() {
      this.loading = true;
      const email = localStorage.getItem('reset_email');
      try {
        await axios.post('http://127.0.0.1:8000/api/password/verify-otp', { email, otp: this.otp });
        localStorage.setItem('otp', this.otp);
        this.$router.push('/reset-password');
        
      } catch (err) {
        this.error = err.response?.data?.message || 'OTP Invalid';
      } finally {
        this.loading = false;
      }
    }
  }
}
</script>
