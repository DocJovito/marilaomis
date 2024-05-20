<template>
  <div>
    <nav class="navbar navbar-expand-lg bg-greenish">
      <div class="container-fluid">
        <!-- Navbar brand and toggle button -->
        <a class="navbar-brand" href="#">Marilao MIS</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown"
          aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <!-- Navbar links -->
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
          <ul class="navbar-nav">
            <li class="nav-item">
              <RouterLink to="/" active-class="active-link" class="nav-link">Home</RouterLink>
            </li>
            <li class="nav-item">
              <RouterLink to="/about" active-class="active-link" class="nav-link">About</RouterLink>
            </li>
            <li class="nav-item">
              <RouterLink v-if="userLevel === 'Admin'" class=" nav-link" active-class="active-link" to="/users/view">
                Users
              </RouterLink>
            </li>
            <li class="nav-item">
              <RouterLink v-if="userLevel === 'Admin' || userLevel === 'Municipal Staff'" to="/residents/view"
                active-class="active-link" class="nav-link">Residents</RouterLink>
            </li>
            <li class="nav-item">
              <RouterLink to="/programs/view" active-class="active-link" class="nav-link">Programs</RouterLink>
            </li>
          </ul>
          <!-- Right-aligned navbar links -->
          <ul class="navbar-nav ms-auto">
            <li class="nav-link">Welcome User : {{ userName }}</li>
            <li v-if="userId === '111'" class="nav-item">
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
import { RouterLink } from 'vue-router'
import { useStore } from 'vuex';

//jovy
const store = useStore();
// const userId = store.state.userState[0].userID;
// const userLevel = store.state.userState[0].userLevel;
const userId = localStorage.getItem('userid');
const userLevel = localStorage.getItem('usertype');
const userName = localStorage.getItem('name');

// Check if user is logged in
const isLoggedIn = store.state.isAuthenticated;
</script>

<style scoped>
.bg-greenish {
  background-color: #8dc63f;
  /* Adjust this color as needed */
}

.active-link {
  font-weight: bold;
}
</style>
