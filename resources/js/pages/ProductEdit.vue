<template>
  <div class="container mt-4">
    <div class="d-flex align-items-center mb-4">
      <router-link to="/myproducts" class="btn btn-outline-secondary me-3">
        <i class="bi bi-arrow-left"></i>
      </router-link>
      <h2 class="mb-0">Edit Produk</h2>
    </div>

    <div class="card shadow-sm">
      <div class="card-body p-4">
        <div class="alert alert-info">
          <i class="bi bi-info-circle-fill me-2"></i>
          Pastikan produk tidak melanggar Hak Kekayaan Intelektual supaya tidak ditakedown
        </div>

        <form @submit.prevent="submitProduct" class="mt-4">
          <!-- Section 1: Basic Information -->
          <div class="mb-5">
            <h5 class="mb-4 pb-2 border-bottom"><i class="bi bi-info-square me-2"></i>Informasi Dasar Produk</h5>
            
            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="productName" class="form-label">Nama Produk <span class="text-danger">*</span></label>
                <input 
                  type="text" 
                  class="form-control form-control-lg" 
                  id="productName" 
                  v-model="fields.name" 
                  placeholder="Contoh: Kemeja Flanel Kotak-kotak"
                  required
                >
              </div>

              <div class="col-md-6 mb-3">
                <label for="category" class="form-label">Kategori <span class="text-danger">*</span></label>
                <select 
                  class="form-select form-select-lg"
                  id="category"
                  v-model="fields.category_id"
                  required
                >
                  <option value="" disabled selected>Pilih kategori</option>
                  <option v-for="category in categories" :key="category.id" :value="category.id">
                    {{ category.name }}
                  </option>
                </select>
              </div>
            </div>

            <div class="mb-3">
              <label for="description" class="form-label">Deskripsi Produk <span class="text-danger">*</span></label>
              <textarea 
                class="form-control" 
                id="description" 
                v-model="fields.description"
                rows="4"
                placeholder="Deskripsikan produk secara detail termasuk bahan, ukuran, dan spesifikasi lainnya"
                required
              ></textarea>
              <div class="form-text">Minimal 50 karakter</div>
            </div>
          </div>

          <!-- Section 2: Pricing -->
          <div class="mb-5">
            <h5 class="mb-4 pb-2 border-bottom"><i class="bi bi-tag me-2"></i>Harga Produk</h5>
            
            <div class="row">
              <div class="col-md-6">
                <label for="price" class="form-label">Harga <span class="text-danger">*</span></label>
                <div class="input-group input-group-lg">
                  <span class="input-group-text bg-light">Rp</span>
                  <input
                    type="number"
                    class="form-control"
                    placeholder="0"
                    v-model="fields.price"
                    min="0"
                    required
                  />
                </div>
              </div>
            </div>
          </div>

          <!-- Section 3: Images -->
          <div class="mb-4">
            <h5 class="mb-4 pb-2 border-bottom"><i class="bi bi-images me-2"></i>Gambar Produk</h5>
            
            <div class="mb-3">
              <label class="form-label">Upload Gambar <span class="text-danger">*</span></label>
              <p class="text-muted">Upload minimal 1 gambar (maks. 5 gambar)</p>
              
              <div class="d-flex flex-wrap gap-3">
                <!-- Upload Box -->
                <div 
                  class="upload-box d-flex flex-column align-items-center justify-content-center"
                  @click="openFilePicker"
                  :class="{ 'disabled': uploadedImages.length >= 5 }"
                >
                  <i class="bi bi-plus-lg fs-1 text-muted"></i>
                  <span class="text-muted">Tambah Gambar</span>
                  <input type="file" ref="fileInput" @change="onFileChange" accept="image/*" hidden multiple>
                </div>

                <!-- Image Previews -->
                <div 
                  v-for="(img, index) in existingImages" :key="'existing-'+index" 
                  class="image-preview position-relative"
                >
                  <img :src="img.url" alt="Preview" class="img-thumbnail">
                  <button 
                    @click="removeImage(index)" 
                    class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1 rounded-circle"
                  >
                    <i class="bi bi-x"></i>
                  </button>
                </div>
              </div>
            </div>
          </div>

          <!-- Submit Button -->
          <div class="d-flex justify-content-between border-top pt-4">
            <button type="button" class="btn btn-outline-secondary" @click="resetForm">
              <i class="bi bi-arrow-counterclockwise me-2"></i>Reset Form
            </button>
            <button type="submit" class="btn btn-primary px-4" :disabled="loading">
              <template v-if="loading">
                <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                Menyimpan...
              </template>
              <template v-else>
                <i class="bi bi-check-circle me-2"></i>Simpan Produk
              </template>
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Success Modal -->
    <div v-if="successMessage" class="modal fade show d-block" tabindex="-1">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-body text-center p-5">
            <div class="text-success mb-4">
              <i class="bi bi-check-circle-fill" style="font-size: 3rem;"></i>
            </div>
            <h5 class="mb-3">Produk Berhasil Ditambahkan!</h5>
            <p>{{ successMessage }}</p>
            <button class="btn btn-primary" @click="successMessage = ''">
              Tutup
            </button>
          </div>
        </div>
      </div>
      <div class="modal-backdrop fade show"></div>
    </div>

    <!-- Crop Modal -->
    <!-- Modal untuk Crop -->
      <div v-if="showModal" class="modal">
      <div class="modal-content">
        <h3>Crop Your Image</h3>
        <div class="crop-container">
          <img ref="cropImage" :src="imageUrl" class="crop-image">
        </div>
        <div class="modal-buttons">
          <button class="btn apply" @click="cropAndSave">Apply</button>
          <button class="btn cancel" @click="showModal = false">Cancel</button>
        </div>
      </div>
      </div>
  </div>
</template>

<script>
import axios from 'axios';
import Cropper from "cropperjs";
import "cropperjs/dist/cropper.css";

export default {
  data() {
    return {
      fields: {
        category_id: '',
        name: '',
        description: '',
        price: '',
        image: [], // file baru yg diupload
      },
      categories: [],
      uploadedImages: [], // untuk preview semua gambar (termasuk gambar lama)
      existingImages: [],
      newImages: [],   // untuk menyimpan url gambar lama dari server
      imageUrl: null,
      showModal: false,
      cropper: null,
      loading: false,
      successMessage: '',
      errorMessage: '',
    };
  },
  methods: {
    openFilePicker() {
      this.$refs.fileInput.click();
    },
    removeImage(index) {
  // Cek apakah gambar existing atau baru
  if (index < this.existingImages.length) {
    // Hapus dari existingImages
    this.existingImages.splice(index, 1);
  } else {
    // Hapus dari newImages
    const newIndex = index - this.existingImages.length;
    URL.revokeObjectURL(this.newImages[newIndex].url); // Bersihkan memory
    this.newImages.splice(newIndex, 1);
  }
},
    onFileChange(event) {
      const file = event.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = (e) => {
          this.imageUrl = e.target.result;
          this.showModal = true;

          this.$nextTick(() => {
            if (this.cropper) {
              this.cropper.destroy();
            }
            this.cropper = new Cropper(this.$refs.cropImage, {
              aspectRatio: 1,
              viewMode: 1,
              autoCropArea: 0.8
            });
          });
        };
        reader.readAsDataURL(file);
      }
    },
    cropAndSave() {
  if (this.cropper) {
    this.cropper.getCroppedCanvas().toBlob((blob) => {
      const fileName = `cropped_image_${Date.now()}.png`;
      const croppedFile = new File([blob], fileName, { type: "image/png" });
      
      // Tambahkan ke newImages
      this.newImages.push({
        url: URL.createObjectURL(blob),
        file: croppedFile
      });

      this.showModal = false;
    }, "image/png");
  }
},
    formatPrice(event) {
      let value = event.target.value.replace(/\D/g, "");
      this.fields.price = value ? parseInt(value) : "";
    },

    async fetchCategories() {
      try {
        let response = await axios.get("http://127.0.0.1:8000/api/categories");
        this.categories = response.data;
      } catch (error) {
        console.error("Error fetching categories:", error);
      }
    },

    async fetchProduct() {
  const slug = this.$route.params.slug;
  const token = localStorage.getItem("token");

  try {
    let response = await axios.get(`http://127.0.0.1:8000/api/products/${slug}`, {
      headers: { Authorization: `Bearer ${token}` }
    });
    let product = response.data.product;

    // Isi fields
    this.fields = {
      category_id: product.category.id,
      name: product.name,
      description: product.description,
      price: product.price,
      image: []
    };

    // Isi existingImages
    this.existingImages = product.image.map(imgPath => ({
      url: `http://127.0.0.1:8000/storage/${imgPath}`,
      path: imgPath
    }));
    
    this.newImages = [];

  } catch (error) {
    console.error('Error:', error.response);
    this.errorMessage = "Gagal memuat data produk.";
  }
},

  async submitProduct() {
  this.loading = true;
  this.successMessage = '';
  this.errorMessage = '';
  const formData = new FormData();
  
  // Data dasar produk
  formData.append("category_id", this.fields.category_id);
  formData.append("name", this.fields.name);
  formData.append("description", this.fields.description);
  formData.append("price", this.fields.price);

  // Tambahkan gambar baru
  this.newImages.forEach((img, index) => {
    formData.append(`image[${index}]`, img.file);
  });

  // Kirim existing images sebagai array
  const keptExisting = this.existingImages.map(img => img.path);
  keptExisting.forEach((path, index) => {
    formData.append(`existing_images[${index}]`, path);
  });

  try {
    const response = await axios.post(
      `http://127.0.0.1:8000/api/product/update/${this.$route.params.slug}`,
      formData,
      {
        headers: {
          "Content-Type": "multipart/form-data",
          "Authorization": `Bearer ${localStorage.getItem("token")}`
        }
      }
    );
    
    // Handle success
    this.successMessage = "Produk berhasil diperbarui!";
    this.$router.push('/myProducts');
  } catch (error) {
    // Handle error
     this.errorMessage = "Terjadi kesalahan saat mengirim data.";
    console.error(error);
  } finally {
    this.loading = false;
  }
}
  },

  mounted() {
    this.fetchCategories();
    this.fetchProduct();
  }
}
</script>
<style scoped>
/* Improved Form Styling */
.card {
  border-radius: 12px;
  border: none;
}

.form-control, .form-select {
  padding: 12px;
  border-radius: 8px;
}

.form-control-lg, .form-select-lg {
  padding: 14px;
}

.input-group-text {
  min-width: 45px;
  justify-content: center;
}

.modal {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
}

.modal-content {
  background: white;
  padding: 20px;
  border-radius: 10px;
  text-align: center;
  width: 400px;
}

.crop-container {
  width: 100%;
  max-height: 300px;
  overflow: hidden;
}

.crop-image {
  max-width: 100%;
  max-height: 300px;
}

.modal-buttons {
  margin-top: 10px;
}

.btn {
  padding: 10px 15px;
  margin: 5px;
  border: none;
  cursor: pointer;
  font-size: 14px;
  border-radius: 5px;
}

.btn.apply {
  background: green;
  color: white;
}

.btn.cancel {
  background: red;
  color: white;
}

/* Upload Box Styling */
.upload-box {
  width: 150px;
  height: 150px;
  border: 2px dashed #dee2e6;
  border-radius: 8px;
  cursor: pointer;
  transition: all 0.2s;
}

.upload-box:hover {
  border-color: #0d6efd;
  background-color: #f8f9fa;
}

.upload-box.disabled {
  opacity: 0.5;
  pointer-events: none;
}

/* Image Preview Styling */
.image-preview {
  width: 150px;
  height: 150px;
}

.image-preview img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  border-radius: 8px;
}

/* Crop Modal Styling */
.crop-container {
  max-height: 60vh;
  overflow: hidden;
}

/* Responsive Adjustments */
@media (max-width: 768px) {
  .upload-box, .image-preview {
    width: 120px;
    height: 120px;
  }
  
  .form-control, .form-select {
    padding: 10px;
  }
}

/* Transition Effects */
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.3s;
}
.fade-enter, .fade-leave-to {
  opacity: 0;
}
</style>