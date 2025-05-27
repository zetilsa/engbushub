<template>
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
    <div class="container">
      <!-- Brand/Logo -->
      <router-link to="/" class="navbar-brand fw-bold">
        <span class="brand-gradient">EBH</span>
      </router-link>
      
      <!-- Mobile Toggle Button -->
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <!-- Navbar Content -->
      <div class="collapse navbar-collapse" id="navbarContent">
        <!-- Main Navigation -->
        <ul class="navbar-nav me-auto">
          <li class="nav-item">
            <router-link to="/" class="nav-link" active-class="active" exact>
              <i class="bi bi-house-door me-1"></i> Home
            </router-link>
          </li>
          <li class="nav-item">
            <router-link to="/news" class="nav-link" active-class="active">
              <i class="bi bi-newspaper me-1"></i> Berita
            </router-link>
          </li>
          <li class="nav-item">
            <router-link to="/contact" class="nav-link" active-class="active">
              <i class="bi bi-headset me-1"></i> Hubungi Kami
            </router-link>
          </li>
        </ul>

        <!-- Search Form -->
        <form class="d-flex me-3 my-2 my-lg-0">
          <div class="input-group">
            <input class="form-control border-0" type="search" placeholder="Cari..." aria-label="Search">
            <button class="btn btn-light" type="submit">
              <i class="bi bi-search"></i>
            </button>
          </div>
        </form>

        <!-- Auth Buttons -->
        <template v-if="!isAuthenticated">
          <div class="d-flex">
            <router-link to="/login" class="btn btn-outline-light me-2">
              <i class="bi bi-box-arrow-in-right me-1"></i> Login
            </router-link>
            <router-link to="/register" class="btn btn-light text-primary">
              <i class="bi bi-person-plus me-1"></i> Daftar
            </router-link>
          </div>
        </template>

        <!-- User Dropdown -->
        <template v-else>
          <ul class="navbar-nav">
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <div class="avatar-sm me-2">
                  <i class="bi bi-person-circle fs-5"></i>
                </div>
                <span class="d-none d-lg-inline">Profil</span>
              </a>
              <ul class="dropdown-menu dropdown-menu-end mt-2" aria-labelledby="userDropdown">
                <li v-if="isAuthenticated">
                  <router-link class="dropdown-item" to="/profile">
                    <i class="bi bi-person me-2"></i> Profil Saya
                  </router-link>
                </li>
                <li v-if="isAuthenticated">
                  <router-link class="dropdown-item" to="/myproducts">
                    <i class="bi bi-speedometer2 me-2"></i>Dashboard
                  </router-link>
                </li>
                <li>
                  <router-link class="dropdown-item" to="/settings">
                    <i class="bi bi-gear me-2"></i> Pengaturan
                  </router-link>
                </li>
                <li>
                  <router-link class="dropdown-item" to="/help">
                    <i class="bi bi-question-circle me-2"></i> Bantuan
                  </router-link>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                  <button class="dropdown-item text-danger" @click="logout">
                    <i class="bi bi-box-arrow-left me-2"></i> Logout
                  </button>
                </li>
              </ul>
            </li>
          </ul>
        </template>
      </div>
    </div>
  </nav>
</template>

<style scoped>
.navbar {
  padding: 0.8rem 1rem;
  transition: all 0.3s ease;
}

.brand-gradient {
  background: linear-gradient(135deg, #ffffff, #e0e0e0);
  -webkit-background-clip: text;
  background-clip: text;
  color: transparent;
  font-size: 1.5rem;
}

.nav-link {
  font-weight: 500;
  padding: 0.5rem 1rem;
  border-radius: 0.5rem;
  transition: all 0.2s ease;
}

.nav-link:hover, .nav-link.active {
  background-color: rgba(255, 255, 255, 0.1);
}

.avatar-sm {
  width: 32px;
  height: 32px;
  display: flex;
  align-items: center;
  justify-content: center;
  background-color: rgba(255, 255, 255, 0.2);
  border-radius: 50%;
}

.dropdown-menu {
  border: none;
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
  border-radius: 0.5rem;
}

.dropdown-item {
  padding: 0.5rem 1rem;
  border-radius: 0.25rem;
  transition: all 0.2s;
}

.dropdown-item:hover {
  background-color: #f8f9fa;
  color: #0d6efd;
}

.btn-outline-light:hover {
  color: #0d6efd;
}

.input-group {
  width: 250px;
}

.input-group .form-control {
  border-radius: 0.5rem 0 0 0.5rem;
}

.input-group .btn {
  border-radius: 0 0.5rem 0.5rem 0;
}

@media (max-width: 992px) {
  .input-group {
    width: 100%;
    margin-top: 0.5rem;
  }
  
  .navbar-collapse {
    padding: 1rem 0;
  }
  
  .nav-item {
    margin: 0.25rem 0;
  }
}
</style>
<script>
import axios from 'axios';
 export default {
   data() {
     return {
       isAuthenticated: false
     };
   },
   methods: {
     checkAuthentication() {
       this.isAuthenticated = !!localStorage.getItem('token');
     },
     async logout() {
       try {
         const token = localStorage.getItem('token');
       if (token) {
         await axios.post('/api/logout', {},
           {
             headers: {
               Authorization: `Bearer ${token}`,
             },
           }
         );
         localStorage.removeItem('token');
         this.$router.push('/login');
       }
       } catch (error) {
         console.error('Error During Logout:', error.response?.data || error.message);
       } finally {
         this.isAuthenticated = false;
       }
     },
   },
   mounted() {
     this.checkAuthentication();
   },
   watch: {
     $route() {
       this.checkAuthentication();
     },
   },
 };
</script>