<template>
    <div class="container py-5">
      <div class="row">
        <div class="col-md-6">
          <img
          v-if="mainImageUrl"
          :src="mainImageUrl"
          class="product-img mb-3 img-fluid"
          alt="Main Product Image"
          style="max-width: 100%; height: auto; max-height: 400px;"
        />
          <div class="d-flex gap-2">
            <img
            v-for="(img, index) in images"
            :key="index"
            :src="getFullImageUrl(img)"
            class="img-thumbnail"
            :class="{ active: mainImage === img }"
            style="width: 80px; height: 80px; object-fit: cover; cursor: pointer;"
            @click="mainImage = img"
            alt="Thumbnail"
          />
          </div>
        </div>

        <div class="col-md-6 mt-4">
          <h6 class="text-primary">{{ product.category.name }}</h6>
          <h2>{{ product.name }}</h2>
          <h4>{{ product.price.toLocaleString() }}</h4>

          <div class="mb-3 mt-5">
          <button class="btn btn-success me-2"><i class="bi bi-whatsapp"></i>Hubungi Whatsapp</button>
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
      this.images = JSON.parse(this.product.image || '[]');
      this.mainImage = this.images[0] || '';
    } catch (error) {
      console.error('Gagal memuat data produk:', error);
    }
  },
};
  </script>
  
  <style scoped>
  img.img-thumbnail.active {
    border: 2px solid #007bff;
  }
  </style>