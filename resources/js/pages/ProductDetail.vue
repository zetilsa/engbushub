<template>
<div class="container py-4">
  <div class="row g-4">
    <!-- Product Images (Left) -->
    <div class="col-lg-6">
      <div class="sticky-top" style="top: 20px;">
        <!-- Main Image -->
        <div class="bg-light rounded-3 overflow-hidden mb-3" style="height: 400px;">
          <img
            v-if="mainImageUrl"
            :src="mainImageUrl"
            class="img-fluid w-100 h-100 object-fit-contain"
            alt="Main Product Image"
          />
        </div>
        
        <!-- Thumbnails -->
        <div class="d-flex gap-2 flex-wrap">
          <div 
            v-for="(img, index) in images"
            :key="index"
            class="border rounded-2 overflow-hidden cursor-pointer"
            :class="{ 'border-primary border-2': mainImage === img }"
            style="width: 70px; height: 70px;"
            @click="mainImage = img"
          >
            <img
              :src="getFullImageUrl(img)"
              class="w-100 h-100 object-fit-cover"
              alt="Thumbnail"
            />
          </div>
        </div>
      </div>
    </div>

    <!-- Product Details (Right) -->
    <div class="col-lg-6">
      <!-- Category and Title -->
      <div class="mb-3">
        <span class="badge bg-primary bg-opacity-10 text-primary mb-2">{{ product?.category?.name }}</span>
        <h1 class="fw-bold mb-2">{{ product?.name }}</h1>
        <div class="d-flex align-items-center gap-2 mb-3">
          <h3 class="text-primary mb-0">Rp {{ product?.price?.toLocaleString() }}</h3>
        </div>
      </div>

      <!-- Product Description -->
      <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-0 py-3">
          <h5 class="fw-bold mb-0">Deskripsi Produk</h5>
        </div>
        <div class="card-body pt-0">
          <p class="mb-0">{{ product?.description }}</p>
        </div>
      </div>

      <!--Info Seller -->
      <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-3">
          <div class="d-flex align-items-center gap-3">
            <div class="bg-light rounded-circle" style="width: 50px; height: 50px;"></div>
            <div>
              <h6 class="fw-bold mb-0">{{ product?.user?.name }}</h6>
              <small class="text-muted">{{ product?.user?.province }}</small>
            </div>
            <button class="btn btn-outline-primary ms-auto px-3 py-2 d-flex align-items-center gap-1">
              <span>Kunjungi Profil</span>
            </button>
          </div>
        </div>
      </div>

      <!-- Action Buttons -->
      <div class="d-flex gap-3 mb-4">
        <button class="btn btn-outline-primary flex-grow-1 py-3 fw-bold">
          <i class="bi bi-share me-2"></i> Bagikan
        </button>
        <button class="btn btn-success flex-grow-1 py-3 fw-bold">
          <i class="bi bi-whatsapp fs-5 me-1"></i>
              <span>Chat Penjual</span>
        </button>
      </div>
    </div>
  </div>
</div>

</template>
  
  <script>
  export default {
  data() {
    return {
      product: null,
      images: [],
      mainImage: '',
    };
  },
  computed: {
    mainImageUrl() {
      return this.mainImage ? this.getFullImageUrl(this.mainImage) : '';
    },
  },
  methods: {
    getFullImageUrl(path) {
      return `http://127.0.0.1:8000/storage/${path}`;
    },
  },
  async created() {
    const slug = this.$route.params.slug;
    try {
      const res = await fetch(`http://127.0.0.1:8000/api/products/${slug}`);
      const data = await res.json();
      this.product = data.product;
      this.images = this.product.image || [];
      this.mainImage = this.images[0] || '';
    } catch (error) {
      console.error('Gagal memuat data produk:', error);
    }
  },
};
  </script>
  
  <style scoped>
.img-thumbnail.active {
  border: 2px solid #0d6efd;
}
</style>