import { createRouter, createWebHistory } from "vue-router";
import HomeView from "../views/HomeView.vue";
import ResidentsView from "@/views/ResidentsView.vue";
import ResidentView from "@/views/ResidentView.vue";
import AboutView from "@/views/AboutView.vue";

//from crud
import View from "@/views/residents/View.vue";
import Create from "@/views/residents/Create.vue";
import Edit from "@/views/residents/Edit.vue";

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
      component: AboutView,
    },
    {
      path: "/residents/view",
      name: "view",
      component: View,
    },
    {
      path: "/residents/create",
      name: "create",
      component: Create,
    },
    {
      path: "/residents/:id/edit",
      name: "edit",
      component: Edit,
    },
  ],
});

export default router;
