<template>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="p-3 vh-100" style="width: 250px; background-color: blue; border-right: 2px solid #0a58ca;">
            <h5 class="fw-bold text-white text-center mt-4">Menu</h5>
            <ul class="list-group list-group-flush mt-5">
                <router-link class="list-group-item text-white border border-light rounded bg-primary mt-2 sidebar-item" to="/myproducts">
                    <i class="bi bi-cart"></i> Produk
                </router-link>
                <router-link class="list-group-item text-white border border-light rounded bg-primary mt-3 sidebar-item" to="/myservices">
                    <i class="bi bi-tools"></i> Jasa
                </router-link>
                <li class="list-group-item text-white border border-light rounded bg-primary mt-3 sidebar-item">
                    <i class="bi bi-suitcase-lg"></i> Lowongan Kerja
                </li>
                <li class="list-group-item text-white border border-light rounded bg-primary mt-3 sidebar-item">
                    <i class="bi bi-buildings"></i> Workshop
                </li>
            </ul>
        </div>

        <!-- Konten Utama -->
        <div class="p-4 flex-grow-1">
            <div class="container-fluid mt-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h2 class="fw-bold">Daftar Produk</h2>
                    <div class="d-flex gap-2">
                        <router-link class="btn btn-success" to="/product/create">+ Tambah Produk</router-link>
                    </div>
                </div>
            </div>
            <section class="card shadow-sm p-3 mt-5">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Semua Produk</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">Aktif</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab" aria-controls="contact" aria-selected="false">Tidak aktif</button>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="card-body">
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                            <div v-if="products.length === 0" class="text-center p-3">
                                <p class="text-muted">Belum ada produk.</p>
                            </div>
                            <div v-for="product in products" :key="product.id" class="product-card">
                                <img :src="'http://127.0.0.1:8000/storage/' + product.image[0]" alt="Product Image">
                                <div class="product-info">
                                    <h5>{{ product.name }}</h5>
                                    <p class="product-price">Rp{{ (product.price || 0).toLocaleString() }}</p>
                                </div>
                                <div class="dropdown-action">
                                <button class="btn btn-primary btn-sm dropdown-toggle action-btn" type="button" data-bs-toggle="dropdown">
                                    Action
                                </button>
                                <ul class="dropdown-menu">
                                    <li><router-link class="dropdown-item" :to="`/product/edit/${product.slug}`">Edit</router-link></li>
                                    <li><a class="dropdown-item text-danger" href="#" @click="deleteProduct(product.id)">Delete</a></li>
                                </ul>
                            </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">...</div>
                        <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">...</div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

// State
const products = ref([])
const token = localStorage.getItem('token')

// Ambil daftar produk dari API
const fetchProducts = async () => {
  try {
    const response = await axios.get('http://127.0.0.1:8000/api/myProducts', {
      headers: {
        Authorization: `Bearer ${token}`
      }
    })

    products.value = response.data.map(product => ({
      ...product,
      image: JSON.parse(product.image)
    }))
  } catch (error) {
    console.error('Gagal memuat produk:', error)
  }
}

// Hapus produk
const deleteProduct = async (id) => {
  const confirmDelete = confirm('Apakah kamu yakin ingin menghapus produk ini?')
  if (!confirmDelete) return

  try {
    await axios.delete(`http://127.0.0.1:8000/api/products/${id}`, {
      headers: {
        Authorization: `Bearer ${token}`
      }
    })

    // Filter produk yang masih ada
    products.value = products.value.filter(product => product.id !== id)

    alert('Produk berhasil dihapus!')
  } catch (error) {
    console.error('Gagal menghapus produk:', error)
    alert('Terjadi kesalahan saat menghapus produk.')
  }
}

// Panggil saat komponen dimuat
onMounted(() => {
  fetchProducts()
})
</script>


<style>
      /* Efek Hover */
    .sidebar-item {
        transition: background-color 0.3s ease, transform 0.2s ease;
    }

    .sidebar-item:hover {
        background-color: #0056b3 !important; /* Warna lebih gelap saat hover */
        transform: scale(1.05); /* Membesar sedikit */
    }
    body {
    background-color: #f8f9fa;
}

.nav-tabs .nav-link {
    color: #333;
    font-weight: bold;
}

.nav-tabs .nav-link.active {
    border-bottom: 3px solid #0d6efd;
    color: #0d6efd;
}

.product-card {
    border: 1px solid #004ddd;
    border-radius: 8px;
    padding: 10px 16px;
    background: #fff;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    margin-bottom: 12px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.06);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.product-card:hover {
    transform: scale(1.01);
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.12);
}

.product-card img {
    width: 60px;
    height: 60px;
    border-radius: 6px;
    object-fit: cover;
    flex-shrink: 0;
}

.product-info {
    flex: 1;
}

.product-info h5 {
    margin: 0;
    font-size: 1rem;
    font-weight: 600;
    color: #212529;
}

.product-price {
    margin-top: 5px;
    font-weight: bold;
    color: #28a745;
    font-size: 0.95rem;
}

.dropdown-action {
    margin-left: auto;
}

.action-btn {
    border-radius: 6px;
}

.sidebar-item:hover {
    background-color: #0056b3 !important;
    transform: scale(1.05);
}


.action-btn {
    border-radius: 7px;
}
</style>