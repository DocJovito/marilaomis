<script setup>
import { ref } from 'vue'
import { RouterView } from 'vue-router'
import NavBar from './components/NavBar.vue'

const showNav = ref(true)

const toggleNav = () => {
  showNav.value = !showNav.value
}
</script>

<template>
  <NavBar v-if="showNav" />
  <RouterView @toggle-nav="toggleNav" />
</template>

<style scoped></style>
