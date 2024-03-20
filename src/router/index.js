import { createRouter, createWebHistory } from "vue-router";
import HomeView from "../views/HomeView.vue";
import ResidentsView from "@/views/ResidentsView.vue";
import ResidentView from "@/views/ResidentView.vue";
import AboutView from "@/views/AboutView.vue";

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
      // route level code-splitting
      // this generates a separate chunk (About.[hash].js) for this route
      // which is lazy-loaded when the route is visited.
      component: AboutView,
      //component: () => import('../views/AboutView.vue')
    },
    {
      path: "/residents",
      name: "residents",
      component: ResidentsView,
    },
    {
      path: "/resident",
      name: "resident",
      component: ResidentView,
    },
  ],
});

export default router;
