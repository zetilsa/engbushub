<template>
  <div class="container mt-5">
    <h3>Forgot Password</h3>
    <form @submit.prevent="submitEmail">
      <div class="mb-3">
        <label for="email" class="form-label">Enter your email</label>
        <input type="email" v-model="email" class="form-control" id="email" required>
      </div>
      <button class="btn btn-primary" :disabled="loading">
        {{ loading ? 'Sending...' : 'Send OTP' }}
      </button>
    </form>

    <div v-if="message" class="alert alert-info mt-3">{{ message }}</div>
  </div>
</template>

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
