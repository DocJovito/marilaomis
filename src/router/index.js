import { createRouter, createWebHistory } from "vue-router";
import HomeView from "../views/HomeView.vue";

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: "/",
      name: "home",
      component: HomeView,
    },
    {
      path: "/about",
      name: "about",
      component: () => import("@/views/AboutView.vue"),
    },
    {
      path: "/residents/view",
      name: "view",
      component: () => import("@/views/residents/View.vue"),
    },
    {
      path: "/residents/create",
      name: "create",
      component: () => import("@/views/residents/Create.vue"),
    },
    {
      path: "/residents/:id/edit",
      name: "edit",
      component: () => import("@/views/residents/Edit.vue"),
    },
    {
      path: "/residents/:id/idcard",
      name: "idcard",
      component: () => import("@/views/residents/IdCard.vue"),
    },
    {
      path: "/scanner",
      name: "scanner",
      component: () => import("@/views/ScannerView.vue"),
    },
    {
      path: "/users/view",
      name: "userview",
      component: () => import("@/views/users/View.vue"),
    },
    {
      path: "/programs/view",
      name: "programview",
      component: () => import("@/views/programs/View.vue"),
    },
  ],
});

export default router;
