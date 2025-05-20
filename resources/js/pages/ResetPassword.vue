<template>
  <div class="container mt-5">
    <h3>Reset Password</h3>
    <form @submit.prevent="resetPassword">
      <div class="mb-3">
        <label for="password" class="form-label">New Password</label>
        <input type="password" v-model="password" class="form-control" required>
      </div>

      <div class="mb-3">
        <label for="confirmPassword" class="form-label">Confirm Password</label>
        <input type="password" v-model="confirmPassword" class="form-control" required>
      </div>

      <button class="btn btn-warning" :disabled="loading">
        {{ loading ? 'Resetting...' : 'Reset Password' }}
      </button>
    </form>

    <div v-if="message" class="alert alert-success mt-3">{{ message }}</div>
    <div v-if="error" class="alert alert-danger mt-3">{{ error }}</div>
  </div>
</template>

<script>
import axios from 'axios'
export default {
  data() {
    return {
      password: '',
      confirmPassword: '',
      loading: false,
      error: '',
      message: ''
    }
  },
  methods: {
    async resetPassword() {
      this.loading = true;
      const email = localStorage.getItem('reset_email');
      const otp = localStorage.getItem('otp');

      try {
        await axios.post('http://127.0.0.1:8000/api/password/reset', {
          email,
          otp,
          password: this.password,
          password_confirmation: this.confirmPassword
        });

        this.message = 'Password berhasil direset. Silakan login kembali.';
        localStorage.removeItem('reset_email');
        localStorage.removeItem('otp');
        this.$router.push('/login');
      } catch (err) {
        this.error = err.response?.data?.message || 'Gagal reset password';
      } finally {
        this.loading = false;
      }
    }
  }
}
</script>
