<template>
  <div class="container d-flex justify-content-center align-items-center min-vh-100 mt-4 mb-4">
    <div class="card shadow p-4" style="width: 400px; border-color: #007bff;">
      <h2 class="text-center text-primary mb-4">Register</h2>
      <form @submit.prevent="submit">
        <div class="mb-3">
          <label for="name" class="form-label">Name</label>
          <input type="text" class="form-control border-primary" v-model="fields.name" id="name" placeholder="Enter your Name" required>
          <span v-if="errors.username" class="text-danger">{{ errors.name[0] }}</span>
        </div>

        <div class="mb-3">
          <label for="username" class="form-label">Username</label>
          <input type="text" class="form-control border-primary" v-model="fields.username" id="username" placeholder="Enter your username" required>
          <span v-if="errors.username" class="text-danger">{{ errors.username[0] }}</span>
        </div>

        <div class="mb-3">
          <label for="phone_number" class="form-label">Phone Number</label>
          <input type="tel" class="form-control border-primary" id="phone_number" v-model="fields.phone_number" placeholder="Enter your Phone Number" required>
          <span v-if="errors.phone_number" class="text-danger">{{ errors.phone_number[0] }}</span>
        </div>

        <div class="mb-3">
          <label for="email" class="form-label">Email</label>
          <input type="email" class="form-control border-primary" id="email" v-model="fields.email" placeholder="Enter your email" required>
          <span v-if="errors.email" class="text-danger">{{ errors.email[0] }}</span>
        </div>

        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <input type="password" class="form-control border-primary" id="password" v-model="fields.password" placeholder="Enter your password" required>
          <span v-if="errors.password" class="text-danger">{{ errors.password[0] }}</span>
        </div>

        <!-- Dropdown Provinsi -->
        <div class="mb-3">
          <label for="province" class="form-label">Province</label>
          <select class="form-select border-primary" id="province" v-model="selectedProvince" @change="fetchCities" required>
            <option value="" disabled>Select Province</option>
            <option v-for="province in provinces" :key="province.id" :value="province.id">
              {{ province.name }}
            </option>
          </select>
          <span v-if="errors.province_id" class="text-danger">{{ errors.province_id[0] }}</span>
        </div>

        <!-- Dropdown Kota -->
        <div class="mb-3">
          <label for="city" class="form-label">City</label>
          <select class="form-select border-primary" id="city" v-model="fields.city_id" required>
            <option value="" disabled>Select City</option>
            <option v-for="city in cities" :key="city.id" :value="city.id">
              {{ city.name }}
            </option>
          </select>
          <span v-if="errors.city_id" class="text-danger">{{ errors.city_id[0] }}</span>
        </div>

        <div class="mb-4 form-check">
          <input type="checkbox" name="agreeTerm" id="agreeTerm" class="form-check-input" required>
          <label class="form-check-label" for="agreeTerm">
              I agree to
              <router-link to="/terms" target="_blank" class="text-primary text-decoration-none">Terms Condition</router-link>
              and our
              <router-link to="/policy" class="text-primary text-decoration-none" target="_blank">Privacy Policy</router-link>
          </label>
        </div>

        <div class="d-grid mb-3">
          <button type="submit" class="btn btn-primary">Register</button>
        </div>
        <div class="mb-3 mt-3 text-center">
          <span class="form-label-check">Already have an account?
            <router-link to="/login" class="text-primary text-decoration-none">Login</router-link>
          </span>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      fields: {
        name: '',
        username: '',
        email: '',
        phone_number: '',
        password: '',
        city_id: '',
      },
      errors: {},
      provinces: [],
      cities: [],
      selectedProvince: ''
    };
  },
  async created() {
    await this.fetchProvinces();
  },
  methods: {
    async fetchProvinces() {
      try {
        const response = await axios.get('/api/provinces');
        this.provinces = response.data;
      } catch (error) {
        console.error('Error fetching provinces:', error);
      }
    },
    async fetchCities() {
      if (!this.selectedProvince) return;
      try {
        const response = await axios.get(`/api/cities/${this.selectedProvince}`);
        this.cities = response.data;
        this.fields.city_id = ''; // Reset city selection
      } catch (error) {
        console.error('Error fetching cities:', error);
      }
    },
    async submit() {
      this.errors = {};
      try {
        const response = await axios.post('/api/register', this.fields);
        localStorage.setItem('token', response.data.token);
        this.$router.push('/');
      } catch (error) {
        this.errors = error.response.data.errors;
      }
    }
  }
};
</script>
