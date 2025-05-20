<template>
    <nav class="navbar navbar-expand-lg bg-primary" data-bs-theme="dark">
 <div class="container-fluid">
   <a class="navbar-brand" href="/">EBH</a>
   <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
     <span class="navbar-toggler-icon"></span>
   </button>
   <div class="collapse navbar-collapse" id="navbarSupportedContent">
     <ul class="navbar-nav me-auto mb-2 mb-lg-0">
       <li class="nav-item">
         <router-link to="/" class="nav-link active">Home</router-link>
       </li>
       <li class="nav-item">
         <a class="nav-link" href="#">Berita</a>
       </li>
       <li class="nav-item">
         <a class="nav-link" aria-disabled="true">Hubungi kami</a>
       </li>
     </ul>
     <form class="d-flex" role="search">
       <input class="form-control me-1" type="search" placeholder="Search" aria-label="Search">
       <button class="btn btn-success" type="submit">Search</button>
     </form>
     <template v-if="!isAuthenticated">
       <div class="nav-item">
         <router-link to="/login" class="btn btn-outline-light me-2 ms-4">Login</router-link>
       </div>
       <div class="nav-item">
         <router-link to="/register" class="btn btn-light me-2">Register</router-link>
       </div>
     </template>
     <template v-else>
 <ul class="navbar-nav ms-auto">
   <li class="nav-item dropdown">
     <a
       class="nav-link dropdown-toggle text-white"
       href="#"
       id="profileDropdown"
       role="button"
       data-bs-toggle="dropdown"
       aria-expanded="false"
     >
       <i class="bi bi-person-circle fs-3"></i>
     </a>
     <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
       <li>
         <router-link class="dropdown-item" to="/profile">Profile</router-link>
       </li>
       <li>
         <router-link class="dropdown-item" to="/help">Help Center</router-link>
       </li>
       <li>
         <router-link class="dropdown-item" to="/settings">Settings</router-link>
       </li>
       <li v-if="isAuthenticated">
         <router-link class="dropdown-item" to="/myproducts">Dashboard</router-link>
       </li>
       <li><hr class="dropdown-divider"></li>
       <li>
         <button class="dropdown-item" @click="logout">Logout</button>
       </li>
     </ul>
   </li>
 </ul>
</template>

   </div>
 </div>
</nav>
</template>
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