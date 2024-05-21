<template>
  <div>
    <nav class="navbar navbar-expand-lg bg-greenish">
      <div class="container-fluid">
        <a class="navbar-brand" href="#">Marilao MIS</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown"
          aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
          <ul class="navbar-nav">
            <li class="nav-item">
              <RouterLink to="/" active-class="active-link" class="nav-link">Home</RouterLink>
            </li>
            <li class="nav-item">
              <RouterLink to="/about" active-class="active-link" class="nav-link">About</RouterLink>
            </li>
            <li class="nav-item">
              <RouterLink v-if="userLevel === 'Admin'" class="nav-link" active-class="active-link" to="/users/view">
                Users
              </RouterLink>
            </li>
            <li class="nav-item">
              <RouterLink v-if="userLevel === 'Admin' || userLevel === 'Municipal Staff' || userLevel === 'Area Leader'" to="/residents/view"
                active-class="active-link" class="nav-link">Residents</RouterLink>
            </li>
            <li class="nav-item">
              <RouterLink to="/programs/view" active-class="active-link" class="nav-link">Programs</RouterLink>
            </li>           
          </ul>          
          <ul class="navbar-nav ms-auto"> 
            <li class="nav-link"> {{ userName }}</li>             
            <li v-if="!isLoggedIn" class="nav-item">             
              <RouterLink class="nav-link" active-class="active-link" to="/login">Log In</RouterLink>
            </li>
            <li v-else class="nav-item">         
              <RouterLink class="nav-link" active-class="active-link" to="/logout">Log Out</RouterLink>              
            </li>
          </ul>
        </div>
      </div>
    </nav>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { useStore } from 'vuex';
import { RouterLink } from 'vue-router';

const store = useStore();
const isLoggedIn = computed(() => store.state.isAuthenticated);
const userLevel = computed(() => store.state.user.userType);
const userName = computed(() => store.state.user.name);
const userId = computed(() => store.state.user.id);
</script>

<style scoped>
.bg-greenish {
  background-color: #8dc63f;
}

.active-link {
  font-weight: bold;
}
</style>
