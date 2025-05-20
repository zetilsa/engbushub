import { createRouter, createWebHistory } from "vue-router";

import Home from "../pages/Home.vue";
import Login from "../pages/Login.vue";
import Register from "../pages/Register.vue";
import ProductList from "../pages/ProductList.vue";
import MyProduct from "../pages/MyProduct.vue";
import MyServices from "../pages/MyServices.vue";
import ProductCreate from "../pages/ProductCreate.vue";
import ForgotPassword from "../pages/ForgotPassword.vue";
import ResetPassword from "../pages/ResetPassword.vue";
import OTPVerification from "../pages/OTPVerification.vue";
import VerifyOtpLogin from "../pages/VerifyOtpLogin.vue";
import ProductDetail from "../pages/ProductDetail.vue";

const routes = [
    {
        path: '/',
        name: 'Home',
        component: Home,
        meta : {title : 'Home'}
    },
    {
        path: '/login',
        name: 'Login',
        component: Login,
        meta : {requiresAuth : false, title : "Login"}
    },
    {
        path: '/register',
        name: 'Register',
        component: Register,
        meta : {requiresAuth : false, title : "Daftar"}
    },
    {
        path: '/products',
        name: 'ProductList',
        component: ProductList,
        meta: {title : "Produk"}
    },
    {
        path: '/myproducts',
        name: 'myAdvertising',
        component: MyProduct,
        meta : {requiresAuth : true, title : "Dashboard Produk"}
    },
    {
        path: '/myservices',
        name: 'myServices',
        component: MyServices,
        meta : {requiresAuth : true, title : "Dashboard Jasa"}
    },
    {
        path: '/product/create',
        name: 'createProduct',
        component: ProductCreate,
        meta : {requiresAuth : true, title : "Upload Produk"}
    },
    {
        path: '/forgot-password',
        component: ForgotPassword,
        meta : {title : "Forgot Password"}
    },
    {
        path: '/verify-otp',
        component: OTPVerification,
        meta : {title : "Verifikasi OTP"}
    },
    {
        path: '/reset-password',
        component: ResetPassword,
        meta : {title : "Reset Password"}
    },
    {
        path: '/verify-otp',
        name: 'VerifyOtp',
        component: VerifyOtpLogin,
        meta : {title : "Verifikasi OTP"}
    },
    {
        path: '/produk/:slug',
        name: 'ProductDetail',
        component: ProductDetail
    }
      
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach((to, from, next) => {
    const isAuthenticated = !!localStorage.getItem('token'); // Cek token di localStorage
    const baseTitle = 'Engineering Business Hub'

    if (to.meta.title) {
        document.title = `${to.meta.title} - ${baseTitle}`
    } else {
        document.title = baseTitle
    }

    if (to.meta.requiresAuth && !isAuthenticated) {
        if (to.path !== '/login') {
            next('/login'); // Redirect ke login hanya jika belum login
        } else {
            next(); // Hindari redirect loop jika sudah di /login
        }
    } else if (!to.meta.requiresAuth && isAuthenticated && to.path === '/login') {
        next('/'); // Jika sudah login, jangan kembali ke login, arahkan ke home
    } else {
        next(); // Lanjutkan navigasi jika tidak ada masalah
    }
    next()
});

export default router