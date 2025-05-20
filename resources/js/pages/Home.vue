<template>
<div id="carouselExampleInterval" class="carousel slide" data-bs-ride="carousel">
  <div class="carousel-inner">
    <div class="carousel-item active" data-bs-interval="10000">
      <div class="img-wrapper">
        <img src="/storage/app/public/carousel/1.png" class="d-flex" alt="...">
      </div>
    </div>
    <div class="carousel-item" data-bs-interval="2000">
      <div class="img-wrapper">
        <img src="/storage/app/public/carousel/2.png" class="d-flex" alt="...">
      </div>
    </div>
  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleInterval" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleInterval" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </button>
</div>
    <div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-3 col-6 mb-4">
      <div class="category-box text-center p-4 border rounded">
        <!-- Icon -->
        <i class="bi bi-suitcase-lg" style="font-size: 50px; color: #007bff;"></i>
        <h5 class="mt-3">Lowongan pekerjaan</h5>
      </div>
    </div>

    <div class="col-md-3 col-6 mb-4">
    <router-link to="/products" class="text-decoration-none">
      <div class="category-box text-center p-4 border rounded">
        <!-- Icon -->
        <i class="bi bi-cart" style="font-size: 50px; color: #007bff;"></i>
        <h5 class="mt-3">Produk</h5>
      </div>
    </router-link>    
</div>

    <div class="col-md-3 col-6 mb-4">
      <div class="category-box text-center p-4 border rounded">
        <!-- Icon -->
        <i class="bi bi-tools" style="font-size: 50px; color: #007bff;"></i>
        <h5 class="mt-3">Jasa</h5>
      </div>
    </div>

    <div class="col-md-3 col-6 mb-4">
      <div class="category-box text-center p-4 border rounded">
        <!-- Icon -->
        <i class="bi bi-buildings" style="font-size: 50px; color: #007bff;"></i>
        <h5 class="mt-3">Workshop</h5>
      </div>
    </div>
  </div>
</div>
</template>
<style>
.category-box {
  border: 2px solid #6c757d; /* Ukuran dan warna border */
  border-radius: 5px; /* Membuat sudut agak melengkung */
  padding: 15px; /* Jarak konten ke tepi kotak */
  min-width: 150px; /* Lebar minimum kotak */
  min-height: 100px; /* Tinggi minimum kotak */
  transition: all 0.3s; /* Animasi hover */
}

.category-box:hover {
  border-color: #007bff; /* Ubah warna outline saat hover */
  transform: scale(1.10); /* Sedikit perbesar kotak */
}

.carousel-inner {
    display: flex;
    align-items: center; /* Pusatkan gambar secara vertikal */
    margin-top: 20px;
}

.img-wrapper {
    max-width: 95%; /* Batasi lebar gambar agar tidak penuh */
    height: 500px;
    display: flex;
    justify-content: center;
    align-items: center;
    overflow: hidden;
    margin: 0 auto; /* Memberikan ruang kosong di kiri dan kanan */
}

.img-wrapper img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}


</style>
