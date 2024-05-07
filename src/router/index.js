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
      path: "/residents/:residentid/edit",
      name: "edit",
      component: () => import("@/views/residents/Edit.vue"),
    },
    {
      path: "/residents/:residentid/idcard",
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
      path: "/users/create",
      name: "usercreate",
      component: () => import("@/views/users/Create.vue"),
    },
    {
      path: "/users/:id/edit",
      name: "useredit",
      component: () => import("@/views/users/Edit.vue"),
    },
    {
      path: "/programs/view",
      name: "programview",
      component: () => import("@/views/programs/View.vue"),
    },
    {
      path: "/programs/create",
      name: "programscreate",
      component: () => import("@/views/programs/Create.vue"),
    },
    {
      path: "/programs/:id/edit",
      name: "programsedit",
      component: () => import("@/views/programs/Edit.vue"),
    },
    {
      path: "/programs/:programid/:programname/scanner",
      name: "programscanner",
      component: () => import("@/views/programs/Scanner.vue"),
    },
    {
      path: "/programs/:programid/:programname/scanreport",
      name: "scanreport",
      component: () => import("@/views/programs/ScanReport.vue"),
    },
    {
      path: "/login",
      name: "login",
      component: () => import("@/views/LogIn.vue"),
    },
  ],
});

export default router;
