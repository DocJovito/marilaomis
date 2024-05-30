import { createRouter, createWebHistory } from 'vue-router';
import store from '../store/store'; // Import the Vuex store
import HomeView from '../views/HomeView.vue';
import NotAuthorized from '../views/NotAuthorized.vue'; // Import the NotAuthorized component

const routes = [
  {
    path: '/',
    name: 'home',
    component: HomeView,
    meta: { requiresAuth: true, restrictedTo: ['Admin', 'Municipal Staff', 'Area Leader'] },
  },
  {
    path: '/about',
    name: 'about',
    component: () => import('@/views/AboutView.vue'),
    meta: { requiresAuth: true, restrictedTo: ['Admin', 'Municipal Staff', 'Area Leader'] },
  },
  {
    path: '/residents/view',
    name: 'view',
    component: () => import('@/views/residents/View.vue'),
    meta: { requiresAuth: true, restrictedTo: ['Admin', 'Municipal Staff', 'Area Leader'] },
  },
  {
    path: '/residents/create',
    name: 'create',
    component: () => import('@/views/residents/Create.vue'),
    meta: { requiresAuth: true, restrictedTo: ['Admin', 'Municipal Staff', 'Area Leader'] },
  },
  {
    path: '/residents/:residentid/edit',
    name: 'edit',
    component: () => import('@/views/residents/Edit.vue'),
    meta: { requiresAuth: true, restrictedTo: ['Admin', 'Municipal Staff', 'Area Leader'] },
  },
  {
    path: '/residents/:residentid/idcard',
    name: 'idcard',
    component: () => import('@/views/residents/IdCard.vue'),
    meta: { requiresAuth: true, restrictedTo: ['Admin', 'Municipal Staff', 'Area Leader'] },
  },
  {
    path: '/scanner',
    name: 'scanner',
    component: () => import('@/views/ScannerView.vue'),
    meta: { requiresAuth: true, restrictedTo: ['Admin', 'Municipal Staff', 'Area Leader'] },
  },
  {
    path: '/users/view',
    name: 'userview',
    component: () => import('@/views/users/View.vue'),
    meta: { requiresAuth: true, restrictedTo: ['Admin'] },
  },
  {
    path: '/users/create',
    name: 'usercreate',
    component: () => import('@/views/users/Create.vue'),
    meta: { requiresAuth: true, restrictedTo: ['Admin'] },
  },
  {
    path: '/users/:id/edit',
    name: 'useredit',
    component: () => import('@/views/users/Edit.vue'),
    meta: { requiresAuth: true, restrictedTo: ['Admin'] },
  },
  {
    path: '/programs/view',
    name: 'programview',
    component: () => import('@/views/programs/View.vue'),
    meta: { requiresAuth: true, restrictedTo: ['Admin', 'Municipal Staff', 'Area Leader'] },
  },
  {
    path: '/programs/create',
    name: 'programscreate',
    component: () => import('@/views/programs/Create.vue'),
    meta: { requiresAuth: true, restrictedTo: ['Admin', 'Municipal Staff'] },
  },
  {
    path: '/programs/:id/edit',
    name: 'programsedit',
    component: () => import('@/views/programs/Edit.vue'),
    meta: { requiresAuth: true, restrictedTo: ['Admin', 'Municipal Staff'] },
  },
  {
    path: '/programs/:programid/:programname/scanner',
    name: 'programscanner',
    component: () => import('@/views/programs/Scanner.vue'),
    meta: { requiresAuth: true },
  },
  {
    path: '/programs/:programid/:programname/scanreport',
    name: 'scanreport',
    component: () => import('@/views/programs/ScanReport.vue'),
    meta: { requiresAuth: true },
  },
  {
    path: '/users/profile',
    name: 'profile',
    component: () => import('@/views/users/Profile.vue'),
    meta: { requiresAuth: true, restrictedTo: ['Admin', 'Municipal Staff', 'Area Leader'] },
  },
  {
    path: '/forgotpassword',
    name: 'forgotpassword',
    component: () => import('@/views/Forgotpassword.vue'),
    // meta: { requiresAuth: true, restrictedTo: ['Admin', 'Municipal Staff', 'Area Leader'] },
  },
  {
    path: '/login',
    name: 'login',
    component: () => import('@/views/LogIn.vue'),
  },
  {
    path: '/logout',
    name: 'logout',
    component: () => import('@/views/LogOut.vue'),
  },
  {
    path: '/not-authorized',
    name: 'NotAuthorized',
    component: NotAuthorized,
  },
];

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
});

router.beforeEach((to, from, next) => {
  const token = localStorage.getItem('token');
  const userType = store.state.user.userType; // Assuming userType is stored in Vuex

  // console.log('Navigating to:', to.path);
  // console.log('Token:', token);
  // console.log('User Type:', userType);

  if (to.meta.requiresAuth) {
    if (!token) {
      next('/login');
    } else if (to.meta.restrictedTo && !to.meta.restrictedTo.includes(userType)) {
      next('/not-authorized');
    } else {
      next();
    }
  } else {
    next(); // Proceed to non-restricted routes
  }
});

export default router;
