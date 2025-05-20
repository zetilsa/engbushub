<template>
        <div class="d-flex">
    <!-- Sidebar -->
    <div class="p-3 vh-100" style="width: 250px; background-color: blue; border-right: 2px solid #0a58ca;">
        <h5 class="fw-bold text-white text-center mt-4">Menu</h5>
        <ul class="list-group list-group-flush mt-5">
            <router-link class="list-group-item text-white border border-light rounded bg-primary sidebar-item" to="/myproducts">
                <i class="bi bi-cart"></i>
                <a class="text-white text-decoration-none">   Produk</a>
            </router-link>
            <li class="list-group-item text-white border border-light rounded bg-primary mt-2 sidebar-item">
                <i class="bi bi-tools"></i>
                <a href="#" class="text-white text-decoration-none">   Jasa</a>
            </li>
            <li class="list-group-item text-white border border-light rounded bg-primary mt-2 sidebar-item">
                <i class="bi bi-suitcase-lg"></i>
                <a href="#" class="text-white text-decoration-none">  Lowongan Kerja</a>
            </li>
            <li class="list-group-item text-white border border-light rounded bg-primary mt-2 sidebar-item">
                <i class="bi bi-buildings"></i>
                <a href="#" class="text-white text-decoration-none"> Workshop</a>
            </li>
        </ul>
    </div>

    <!-- Konten Utama -->
    <div class="p-4 flex-grow-1">
        <div class="container-fluid mt-3">
        <div class="d-flex justify-content-between align-items-center">
        <h2 class="fw-bold">Daftar Jasa</h2>
        <div class="d-flex gap-2">
            <button class="btn btn-success">
                + Tambah Jasa
            </button>
        </div>
    </div>
    </div>

    </div>
</div>
</template>