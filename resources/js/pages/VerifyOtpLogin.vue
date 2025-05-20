<template>
  <div class="container mt-5">
    <div class="card p-4 shadow-sm" style="max-width: 400px; margin: auto;">
      <h2 class="text-center mb-3">Verifikasi OTP</h2>
      <p class="text-center">Kode OTP telah dikirim ke email <strong>{{ maskedEmail }}</strong></p>

      <form @submit.prevent="verifyOtp">
        <div class="mb-3">
          <label for="otp" class="form-label">Kode OTP</label>
          <input type="text" v-model="otp" class="form-control text-center" required autocomplete="off" data-autosubmit="false"  />
        </div>
        <div class="d-grid gap-2">
          <button class="btn btn-success mx-auto" style="width: 150px;" :disabled="loading">Verifikasi</button>
        </div>
        <p class="text-danger mt-2" v-if="error">{{ error }}</p>
      </form>

      <div class="text-center mt-4">
        <p>Belum Menerima Otp</p>
        <button class="btn btn-outline-primary" :disabled="cooldown > 0" @click="resendOtp">
          <template v-if="cooldown > 0">
            Kirim ulang dalam {{ cooldown }} detik
          </template>
          <template v-else>
            Kirim Ulang OTP
          </template>
        </button>
        <p class="text-success mt-2" v-if="success">{{ success }}</p>
      </div>

    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'

const router = useRouter()

const email = ref(localStorage.getItem('otp_email') || '')
const otp = ref('')
const error = ref('')
const success = ref('')
const loading = ref(false)
const cooldown = ref(30) 

let intervalId = null

onMounted(() => {
  if (!email.value) {
    router.push({ name: 'Login' })
  }
  startCooldown()
})

const startCooldown = () => {
  cooldown.value = 30
  intervalId = setInterval(() => {
    if (cooldown.value > 0) {
      cooldown.value--
    } else {
      clearInterval(intervalId)
    }
  }, 1000)
}

const verifyOtp = async () => {
  loading.value = true
  error.value = ''
  try {
    const res = await axios.post('/api/verify-login-otp', {
      email: email.value,
      otp: otp.value
    })
    localStorage.removeItem('otp_email')
    localStorage.setItem('token', res.data.token)
    axios.defaults.headers.common['Authorization'] = `Bearer ${res.data.token}`
    router.push({ name: 'Home' })
  } catch (err) {
    error.value = err.response?.data?.message || 'OTP salah atau kadaluwarsa'
  } finally {
    loading.value = false
  }
}

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

const maskedEmail = computed(() => {
  if (!email.value) return ''

  const parts = email.value.split('@')
  const namePart = parts[0]
  const domainPart = parts[1]

  // Sensor bagian nama, hanya tampilkan huruf pertama dan terakhir
  if (namePart.length <= 2) {
    return '*'.repeat(namePart.length) + '@' + domainPart
  } else {
    return namePart[0] + '*'.repeat(namePart.length - 2) + namePart[namePart.length - 1] + '@' + domainPart
  }
})

</script>
