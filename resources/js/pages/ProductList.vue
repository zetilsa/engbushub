<template>
    <div class="container mt-4">
        <div class="row">
            <!-- Tombol untuk membuka Modal Filter -->
            <div class="col-md-12 mb-3">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#filterModal">Terapkan Filter</button>
            </div>

            <!-- Daftar Produk -->
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-4 mb-4" v-for="product in products" :key="product.id" style="cursor: pointer;">
                        <div class="card">
                            <img v-if="product.image" :src="product.image" class="card-img-top" alt="Product Image">
                            <div class="card-body">
                                <h5 class="card-title" @click="goToDetail(product.slug)">{{ product.name }}</h5>
                                <p class="card-text">Rp{{ product.price.toLocaleString() }}</p>
                                <button class="btn btn-success d-flex align-items-center justify-content-center mx-auto">
                                    <i class="bi bi-whatsapp"></i> Hubungi WhatsApp
                                </button>
                                <div class="d-flex align-items-center text-secondary mt-3">
                                    <i class="bi bi-geo-alt me-1"></i>
                                    <span>{{ product.user.city.name }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <p v-if="products.length === 0" class="text-center">Tidak ada produk yang sesuai</p>
                </div>
            </div>
        </div>

        <!-- Modal Filter -->
        <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="filterModalLabel">Filter Produk</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Filter Kategori -->
                        <div class="mb-3">
                            <h6>Kategori</h6>
                            <div v-for="category in categories" :key="category.id" class="form-check">
                                <input class="form-check-input" type="checkbox" :value="category.id" v-model="selectedCategories">
                                <label class="form-check-label">{{ category.name }}</label>
                            </div>
                        </div>

                        <!-- Filter Harga -->
                        <div class="mb-3">
                            <h6>Harga</h6>
                            <div class="input-group mb-2">
                                <span class="input-group-text">Rp</span>
                                <input type="number" class="form-control" v-model="minPrice" placeholder="Harga Minimum">
                            </div>
                            <div class="input-group mb-2">
                                <span class="input-group-text">Rp</span>
                                <input type="number" class="form-control" v-model="maxPrice" placeholder="Harga Maksimum">
                            </div>
                        </div>

                        <!-- Filter Lokasi -->
                        <div class="mb-3">
                            <h6>Lokasi</h6>
                            <!-- Dropdown Provinsi -->
                            <select class="form-select mb-2" v-model="selectedProvince" @change="fetchCities">
                                <option value="">Pilih Provinsi</option>
                                <option v-for="province in provinces" :key="province.id" :value="province.id">
                                    {{ province.name }}
                                </option>
                            </select>

                            <!-- Dropdown Kota -->
                            <select class="form-select" v-model="selectedCity" :disabled="!selectedProvince">
                                <option value="">Pilih Kota</option>
                                <option v-for="city in cities" :key="city.id" :value="city.id">
                                    {{ city.name }}
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="button" class="btn btn-primary" @click="fetchFilteredProducts" data-bs-dismiss="modal">Terapkan Filter</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>


<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';

const products = ref([]);
const categories = ref([]);
const selectedCategories = ref([]); // Menyimpan kategori yang dipilih
const minPrice = ref('');
const maxPrice = ref('');
const selectedProvince = ref('');
const selectedCity = ref('');
const provinces = ref([]); // Menyimpan daftar provinsi
const cities = ref([]); // Menyimpan daftar kota berdasarkan provinsi
const router = useRouter();

const fetchCategories = async () => {
    try {
        const response = await axios.get('http://127.0.0.1:8000/api/categories');
        categories.value = response.data;
    } catch (error) {
        console.error('Error fetching categories', error);
    }
};

const fetchProvinces = async () => {
    try {
        const response = await axios.get('http://127.0.0.1:8000/api/provinces');
        provinces.value = response.data;
    } catch (error) {
        console.error('Error fetching provinces:', error);
    }
};

const fetchCities = async () => {
    if (!selectedProvince.value) return; // Jika belum memilih provinsi, hentikan proses

    try {
        const response = await axios.get(`http://127.0.0.1:8000/api/cities/${selectedProvince.value}`);
        cities.value = response.data;
        selectedCity.value = ''; // Reset kota jika provinsi berubah
    } catch (error) {
        console.error('Error fetching cities:', error);
    }
};

// Perbaikan fetchFilteredProducts agar mengirimkan parameter dengan benar
const fetchFilteredProducts = async () => {
    try {
        const params = {
            category_id: selectedCategories.value.length ? selectedCategories.value.join(',') : null,
            min_price: minPrice.value || null,
            max_price: maxPrice.value || null,
            province_id: selectedProvince.value || null,
            city_id: selectedCity.value || null
        };

        const response = await axios.get('http://127.0.0.1:8000/api/products', { params });
        products.value = response.data.products;
    } catch (error) {
        console.error('Error fetching products', error);
    }
};

const goToDetail = (slug) => {
    router.push(`/produk/${slug}`);
};

onMounted(() => {
    fetchCategories();
    fetchProvinces();
    fetchFilteredProducts(); // Panggil untuk menampilkan produk awal tanpa filter
});

</script>
