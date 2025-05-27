<template>
  <div class="home-container">
    <!-- Hero Carousel -->
    <div id="carouselExampleInterval" class="carousel slide carousel-fade" data-bs-ride="carousel">
      <div class="carousel-inner">
        <div class="carousel-item active" data-bs-interval="5000">
          <div class="img-wrapper">
            <img src="/storage/app/public/carousel/1.png" class="d-block w-100" alt="Carousel Image 1">
          </div>
        </div>
        <div class="carousel-item" data-bs-interval="5000">
          <div class="img-wrapper">
            <img src="/storage/app/public/carousel/2.png" class="d-block w-100" alt="Carousel Image 2">
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

    <!-- Category Cards -->
    <div class="container mt-5 mb-5">
      <h2 class="text-center mb-4 section-title">Explore Our Services</h2>
      <div class="row justify-content-center g-4">
        <div class="col-lg-3 col-md-6 col-6">
          <div class="category-box text-center p-4">
            <div class="icon-wrapper mb-3">
              <i class="bi bi-suitcase-lg"></i>
            </div>
            <h5 class="mt-3">Lowongan pekerjaan</h5>
            <p class="text-muted small mt-2">Temukan pekerjaan terbaik untuk karir Anda</p>
          </div>
        </div>

        <div class="col-lg-3 col-md-6 col-6">
          <router-link to="/products" class="text-decoration-none">
            <div class="category-box text-center p-4">
              <div class="icon-wrapper mb-3">
                <i class="bi bi-cart"></i>
              </div>
              <h5 class="mt-3">Produk</h5>
              <p class="text-muted small mt-2">Berbagai produk berkualitas untuk kebutuhan Anda</p>
            </div>
          </router-link>
        </div>

        <div class="col-lg-3 col-md-6 col-6">
          <div class="category-box text-center p-4">
            <div class="icon-wrapper mb-3">
              <i class="bi bi-tools"></i>
            </div>
            <h5 class="mt-3">Jasa</h5>
            <p class="text-muted small mt-2">Layanan profesional untuk berbagai kebutuhan</p>
          </div>
        </div>

        <div class="col-lg-3 col-md-6 col-6">
          <div class="category-box text-center p-4">
            <div class="icon-wrapper mb-3">
              <i class="bi bi-buildings"></i>
            </div>
            <h5 class="mt-3">Workshop</h5>
            <p class="text-muted small mt-2">Pelatihan dan pengembangan keterampilan</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.home-container {
  background-color: #f8f9fa;
}

/* Carousel Styles */
.carousel {
  border-radius: 10px;
  overflow: hidden;
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
  margin-bottom: 30px;
}

.carousel-fade .carousel-item {
  opacity: 0;
  transition: opacity 1s ease-in-out;
}

.carousel-fade .carousel-item.active {
  opacity: 1;
}

.img-wrapper {
  height: 500px;
  display: flex;
  justify-content: center;
  align-items: center;
  overflow: hidden;
}

.img-wrapper img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  object-position: center;
}

.carousel-control-prev,
.carousel-control-next {
  width: 50px;
  height: 50px;
  background-color: rgba(0, 0, 0, 0.3);
  border-radius: 50%;
  top: 50%;
  transform: translateY(-50%);
  opacity: 0;
  transition: opacity 0.3s ease;
}

.carousel:hover .carousel-control-prev,
.carousel:hover .carousel-control-next {
  opacity: 1;
}

/* Category Box Styles */
.category-box {
  background: white;
  border-radius: 12px;
  padding: 25px 20px;
  height: 100%;
  transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
  border: none;
  cursor: pointer;
}

.category-box:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 20px rgba(0, 123, 255, 0.2);
}

.icon-wrapper {
  width: 80px;
  height: 80px;
  margin: 0 auto;
  display: flex;
  align-items: center;
  justify-content: center;
  background: linear-gradient(135deg, #007bff, #00b4ff);
  border-radius: 50%;
  color: white;
  font-size: 36px;
  transition: all 0.3s ease;
}

.category-box:hover .icon-wrapper {
  transform: scale(1.1);
  box-shadow: 0 5px 15px rgba(0, 123, 255, 0.4);
}

.section-title {
  position: relative;
  padding-bottom: 15px;
  color: #2c3e50;
}

.section-title:after {
  content: '';
  position: absolute;
  bottom: 0;
  left: 50%;
  transform: translateX(-50%);
  width: 80px;
  height: 3px;
  background: linear-gradient(to right, #007bff, #00b4ff);
  border-radius: 3px;
}

/* Responsive adjustments */
@media (max-width: 768px) {
  .img-wrapper {
    height: 300px;
  }
  
  .category-box {
    padding: 15px 10px;
  }
  
  .icon-wrapper {
    width: 60px;
    height: 60px;
    font-size: 28px;
  }
}

@media (max-width: 576px) {
  .img-wrapper {
    height: 200px;
  }
  
  .col-6 {
    padding: 0 5px;
  }
}
</style>