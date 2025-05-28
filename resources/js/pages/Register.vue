<template>
  <div class="register-container bg-light d-flex justify-content-center align-items-center min-vh-100 py-4">
    <div class="card border-0 shadow-lg p-4 p-md-5" style="width: 100%; max-width: 500px; border-radius: 12px;">
      <div class="card-body">
        <!-- Header -->
        <div class="text-center mb-4">
          <div class="register-icon bg-primary bg-opacity-10 d-inline-flex p-3 rounded-circle mb-3">
            <i class="bi bi-person-plus-fill text-primary" style="font-size: 1.75rem;"></i>
          </div>
          <h2 class="text-primary fw-bold mb-2">Create Account</h2>
          <p class="text-muted">Fill in your details to get started</p>
        </div>

        <form @submit.prevent="submit">
          <!-- Name -->
          <div class="mb-3">
            <label for="name" class="form-label fw-semibold">Full Name</label>
            <div class="input-group">
              <span class="input-group-text bg-light border-end-0">
                <i class="bi bi-person text-muted"></i>
              </span>
              <input 
                type="text" 
                class="form-control form-control-lg border-start-0" 
                v-model="fields.name" 
                id="name" 
                placeholder="Masukkan Nama" 
                required
              >
            </div>
            <span v-if="errors.name" class="text-danger small mt-1 d-block">{{ errors.name[0] }}</span>
          </div>

          <!-- Username -->
          <div class="mb-3">
            <label for="username" class="form-label fw-semibold">Username</label>
            <div class="input-group">
              <span class="input-group-text bg-light border-end-0">
                <i class="bi bi-at text-muted"></i>
              </span>
              <input 
                type="text" 
                class="form-control form-control-lg border-start-0" 
                v-model="fields.username" 
                id="username" 
                placeholder="Masukkan Username" 
                required
              >
            </div>
            <span v-if="errors.username" class="text-danger small mt-1 d-block">{{ errors.username[0] }}</span>
          </div>

          <!-- Phone Number -->
          <div class="mb-3">
            <label for="phone_number" class="form-label fw-semibold">Phone Number</label>
            <div class="input-group">
              <span class="input-group-text bg-light border-end-0">
                <i class="bi bi-phone text-muted"></i>
              </span>
              <input 
                type="tel" 
                class="form-control form-control-lg border-start-0" 
                id="phone_number" 
                v-model="fields.phone_number" 
                placeholder="Nomor Telepon" 
                required
              >
            </div>
            <span v-if="errors.phone_number" class="text-danger small mt-1 d-block">{{ errors.phone_number[0] }}</span>
          </div>

          <!-- Email -->
          <div class="mb-3">
            <label for="email" class="form-label fw-semibold">Email</label>
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
          <div class="mb-3">
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
                placeholder="Masukkan Password Baru" 
                required
              >
            </div>
            <span v-if="errors.password" class="text-danger small mt-1 d-block">{{ errors.password[0] }}</span>
          </div>

          <!-- Province Dropdown -->
          <div class="mb-3">
            <label for="province" class="form-label fw-semibold">Province</label>
            <div class="input-group">
              <span class="input-group-text bg-light border-end-0">
                <i class="bi bi-map text-muted"></i>
              </span>
              <select 
                class="form-select form-control-lg border-start-0" 
                id="province" 
                v-model="selectedProvince" 
                @change="fetchCities" 
                required
              >
                <option value="" disabled>Select Province</option>
                <option v-for="province in provinces" :key="province.id" :value="province.id">
                  {{ province.name }}
                </option>
              </select>
            </div>
            <span v-if="errors.province_id" class="text-danger small mt-1 d-block">{{ errors.province_id[0] }}</span>
          </div>

          <!-- City Dropdown -->
          <div class="mb-4">
            <label for="city" class="form-label fw-semibold">City</label>
            <div class="input-group">
              <span class="input-group-text bg-light border-end-0">
                <i class="bi bi-geo-alt text-muted"></i>
              </span>
              <select 
                class="form-select form-control-lg border-start-0" 
                id="city" 
                v-model="fields.city_id" 
                required
              >
                <option value="" disabled>Select City</option>
                <option v-for="city in cities" :key="city.id" :value="city.id">
                  {{ city.name }}
                </option>
              </select>
            </div>
            <span v-if="errors.city_id" class="text-danger small mt-1 d-block">{{ errors.city_id[0] }}</span>
          </div>

          <!-- Terms Checkbox -->
          <div class="mb-4 form-check">
            <input 
              type="checkbox" 
              name="agreeTerm" 
              id="agreeTerm" 
              class="form-check-input" 
              required
            >
            <label class="form-check-label small" for="agreeTerm">
              I agree to
              <router-link to="/terms" target="_blank" class="text-primary text-decoration-none">Terms & Conditions</router-link>
              and
              <router-link to="/policy" class="text-primary text-decoration-none" target="_blank">Privacy Policy</router-link>
            </label>
          </div>

          <!-- Submit Button -->
          <div class="d-grid mb-3">
            <button type="submit" class="btn btn-primary btn-lg py-3 fw-semibold">
              <span v-if="!loading">Register Now</span>
              <span v-else class="d-flex align-items-center justify-content-center">
                <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                Processing...
              </span>
            </button>
          </div>

          <!-- Login Link -->
          <div class="text-center mt-4 pt-3 border-top">
            <p class="text-muted mb-0">Already have an account?
              <router-link to="/login" class="text-primary fw-semibold text-decoration-none ms-1">
                Sign in
              </router-link>
            </p>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<style scoped>
.register-container {
  background-color: #f8f9fa;
}

.register-icon {
  transition: transform 0.3s ease;
}

.register-icon:hover {
  transform: scale(1.1);
}

.card {
  border: none;
  overflow: hidden;
}

.form-control-lg, .form-select {
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

.form-check-input {
  margin-top: 0.25em;
}

@media (max-width: 576px) {
  .card {
    border-radius: 0;
    box-shadow: none;
  }
  
  .register-container {
    background-color: white;
    padding: 1rem;
  }
  
  .register-icon {
    width: 60px;
    height: 60px;
  }
  
  .register-icon i {
    font-size: 1.5rem;
  }
}
</style>

<script setup>
import { ref, reactive, onMounted, watch } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';

const router = useRouter();

const fields = reactive({
  name: '',
  username: '',
  email: '',
  phone_number: '',
  password: '',
  city_id: '',
});

const errors = ref({});
const provinces = ref([]);
const cities = ref([]);
const selectedProvince = ref('');

// Ambil data provinsi saat komponen dimount
onMounted(async () => {
  await fetchProvinces();
});

// Watch perubahan provinsi untuk fetch data kota
watch(selectedProvince, async (newVal) => {
  if (newVal) {
    await fetchCities();
  }
});

const fetchProvinces = async () => {
  try {
    const response = await axios.get('/api/provinces');
    provinces.value = response.data;
  } catch (error) {
    console.error('Error fetching provinces:', error);
  }
};

const fetchCities = async () => {
  try {
    const response = await axios.get(`/api/cities/${selectedProvince.value}`);
    cities.value = response.data;
    fields.city_id = ''; // Reset city selection
  } catch (error) {
    console.error('Error fetching cities:', error);
  }
};

const submit = async () => {
  errors.value = {};
  try {
    const response = await axios.post('/api/register', fields);
    localStorage.setItem('token', response.data.token);
    router.push('/');
  } catch (error) {
    errors.value = error.response?.data?.errors || {};
  }
};
</script>

