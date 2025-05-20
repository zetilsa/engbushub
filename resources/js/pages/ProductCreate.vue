<template>
    <div class="container mt-4">
      <h2>Tambah Produk</h2>
  
      <div class="card shadow-sm p-3 mt-3">
        <div class="card-body">
          <h5 class="card-title">Informasi Produk</h5>
          <p>Pastikan produk tidak melanggar Hak Kekayaan Intelektual supaya tidak ditakedown</p>
  
          <form @submit.prevent="submitProduct">
            <!-- Nama Produk -->
            <div class="mb-3">
              <label for="productName" class="form-label">Nama Produk</label>
              <input 
                type="text" 
                class="form-control" 
                id="productName" 
                v-model="fields.name" 
                required
              >
            </div>
  
            <!-- Deskripsi -->
            <div class="mb-3">
              <label for="description" class="form-label">Deskripsi</label>
              <textarea 
                class="form-control" 
                id="description" 
                v-model="fields.description"
                rows="3"
                required
              ></textarea>
            </div>
  
            <!-- Harga -->
            <div class="mb-3">
              <label for="price" class="form-label">Harga</label>
              <div class="input-group">
                <span class="input-group-text">Rp</span>
                <input
                    type="number"
                    class="form-control"
                    placeholder="Masukkan Harga"
                    v-model="fields.price"
                    @input="formatPrice"
                />
                </div>

            </div>
  
            <!-- Kategori -->
            <div class="mb-3">
              <label for="category" class="form-label">Kategori Produk</label>
              <select 
                class="form-select"
                id="category"
                v-model="fields.category_id"
                required
              >
                <option value="">Pilih kategori</option>
                <option v-for="category in categories" :key="category.id" :value="category.id">
                  {{ category.name }}
                </option>
              </select>
            </div>
  
            <!-- Upload & Crop Gambar -->
            <label class="form-label mb-3">Upload Gambar</label>
            <div class="upload-container mb-3">
              <div class="upload-box" @click="openFilePicker">
                <span>+</span>
                <p>Upload Image</p>
              </div>
                <input type="file" ref="fileInput" @change="onFileChange" hidden>

                <!-- Preview Images (Setiap Gambar dalam Box Terpisah) -->
                <div v-if="uploadedImages.length > 0" class="uploaded-images">
                <div v-for="(img, index) in uploadedImages" :key="index" class="image-preview">
                  <img :src="img" alt="Preview" class="preview-img">
                  <button @click="removeImage(index)" class="delete-btn">❌</button>
                </div>
                </div>
            </div>
  
            <!-- Tombol Submit -->
            <button type="submit" class="btn btn-primary" :disabled="loading">
              <span v-if="loading">Mengirim...</span>
              <span v-else>Tambah Produk</span>
            </button>
          </form>
  
          <!-- Notifikasi Sukses -->
          <div v-if="successMessage" class="alert alert-success mt-3">
            {{ successMessage }}
          </div>
  
          <!-- Notifikasi Error -->
          <div v-if="errorMessage" class="alert alert-danger mt-3">
            {{ errorMessage }}
          </div>
        </div>
      </div>
  
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
        image: [],
      },
      categories: [],
      uploadedImages: [],
      imageUrl: null,
      showModal: false,
      cropper: null,
      loading: false,
      successMessage: '',
      errorMessage: '',
    };
  },
  computed: {
    formattedPrice() {
      return this.price ? this.price.toLocaleString("id-ID") : "";
    },
  },
  methods: {
    openFilePicker() {
      this.$refs.fileInput.click();
    },
    removeImage(index) {
  this.uploadedImages.splice(index, 1);
  this.fields.image.splice(index, 1);
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
      const fileName = `cropped_image_${Date.now()}.png`; // Nama file unik
      const croppedFile = new File([blob], fileName, { type: "image/png" });

      this.uploadedImages.push(URL.createObjectURL(blob)); // Pratinjau gambar

      if (!this.fields.image) {
        this.fields.image = [];
      }
      this.fields.image.push(croppedFile);
      console.log("File yang akan dikirim:", croppedFile);
      this.showModal = false;
    }, "image/png");
  }
},
preventExit(event) {
    if (this.hasUnsavedChanges()) {
      event.preventDefault();
      event.returnValue = ""; // Chrome butuh returnValue agar peringatan muncul
    }
  },

  hasUnsavedChanges() {
    return this.fields.category_id || 
           this.fields.name || 
           this.fields.description || 
           this.fields.price || 
           this.fields.image.length > 0;
  },
  
    formatPrice(event) {
      let value = event.target.value.replace(/\D/g, "");
      this.price = value ? parseInt(value) : "";
    },
    async submitProduct() {
  this.loading = true;
  this.successMessage = '';
  this.errorMessage = '';

  let formData = new FormData();
  console.log("Isi this.fields.image:", this.fields.image);

  formData.append("category_id", this.fields.category_id);
  formData.append("name", this.fields.name);
  formData.append("description", this.fields.description);
  formData.append("price", this.fields.price);

  if (this.fields.image && this.fields.image.length > 0) {
  this.fields.image.forEach((img, index) => {
    formData.append(`image[]`, img, img.name);
  });
}


  for (let pair of formData.entries()) {
  console.log(pair[0], pair[1]);
  }

  const token = localStorage.getItem("token");

  if (!token) {
    this.errorMessage = "Anda harus login terlebih dahulu!";
    this.loading = false;
    return;
  }

  try {
    let response = await axios.post("http://127.0.0.1:8000/api/product/create", formData, {
      headers: {
        "Content-Type": "multipart/form-data",
        "Authorization": `Bearer ${token}`
      },
    });

    this.successMessage = response.data.message;
    this.resetForm();
    console.log([...formData]); // Debug isi formData
    this.$router.push('/myProducts');
  } catch (error) {
    this.errorMessage = "Terjadi kesalahan saat mengirim data.";
  } finally {
    this.loading = false;
  }
},
    resetForm() {
      this.fields = { category_id: '', name: '', description: '', price: '', image: null };
      this.uploadedImages = [];
    },
    async fetchCategories() {
    try {
        let response = await axios.get("http://127.0.0.1:8000/api/categories");
        this.categories = response.data;
        console.log("Categories:", this.categories); // Cek apakah data masuk
    } catch (error) {
        console.error("Error fetching categories:", error);
    }
}
  },
  mounted() {
    this.fetchCategories();

    window.addEventListener("beforeunload", this.preventExit);
    window.addEventListener("popstate", this.preventExit);
  },

  beforeUnmount() {
  // Hapus event listener saat komponen tidak digunakan lagi
  window.removeEventListener("beforeunload", this.preventExit);
  window.removeEventListener("popstate", this.preventExit);
},
};
</script>

  
  <style scoped>
.upload-container {
  display: flex;
  align-items: center;
}

.upload-box {
  width: 150px;
  height: 150px;
  border: 2px dashed blue;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-direction: column;
  cursor: pointer;
  margin-right: 10px; /* Tambahkan sedikit jarak ke kanan */
}

/* Preview Wrapper: agar semua gambar tampil secara horizontal */
.preview-wrapper {
  display: flex;
  align-items: center;
  gap: 10px; /* Beri jarak antar gambar */
}

/* Preview Container: agar setiap gambar punya box sendiri */
.preview-container {
  display: flex;
  align-items: center;
  justify-content: center;
  border: 1px solid #ddd;
  padding: 5px;
  border-radius: 5px;
}

.preview-img {
  width: 80px;
  height: 80px;
  object-fit: cover;
  border-radius: 5px;
}

/* Modal Styling */
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

.delete-btn {
  position: absolute;
  top: -5px;
  right: -5px;
  background: red;
  color: white;
  border: none;
  border-radius: 50%;
  cursor: pointer;
}

.uploaded-images {
  display: flex;
  gap: 10px;
  margin-top: 10px;
}

.image-preview {
  position: relative;
}


  </style>
  