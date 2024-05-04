<script setup>
import { RouterLink } from 'vue-router'
</script>


<template>
  <div>
    <nav class="navbar navbar-expand-lg bg-greenish">
      <div class="container-fluid">
        <!-- <img src="../assets/images/greenmarilao.png" alt="MIS" width="30" height="24"> -->
        <img src="../assets/images/greenmarilao.png" alt="MIS" width="75" class="mx-2">
        <a class="navbar-brand" href="#">Marilao MIS</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown"
          aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
          <ul class="navbar-nav">
            <li class="nav-item active">
              <RouterLink to="/" active-class="active-link" class="nav-link">Home</RouterLink>
            </li>
            <li class="nav-item">
              <RouterLink class="nav-link" active-class="active-link" to="/about">About</RouterLink>
            </li>
            <li class="nav-item">
              <RouterLink class="nav-link" active-class="active-link" to="/users/view">Users</RouterLink>
            </li>
            <li class="nav-item">
              <RouterLink class="nav-link" active-class="active-link" to="/residents/view">Residents</RouterLink>
            </li>
            <!-- <li class="nav-item">
              <RouterLink class="nav-link" active-class="active-link" to="/scanner">Scanner</RouterLink>
            </li> -->
            <li class="nav-item">
              <RouterLink class="nav-link" active-class="active-link" to="/programs/view">Programs</RouterLink>
            </li>
            <!-- <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                aria-expanded="false">
                Dropdown link
              </a>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#">Action</a></li>
                <li><a class="dropdown-item" href="#">Another action</a></li>
                <li><a class="dropdown-item" href="#">Something else here</a></li>
              </ul>
            </li> -->

          </ul>
          <ul class="navbar-nav ms-auto">
            <li class="nav-item">
              <RouterLink class="nav-link" active-class="active-link" to="/login">Log In</RouterLink>
            </li>
            <li class="nav-item">
              <RouterLink class="nav-link" active-class="active-link" to="/login">Log Out</RouterLink>
            </li>
          </ul>


        </div>
      </div>
    </nav>
  </div>
</template>


<style scoped>
.bg-greenish {
  background-color: #8dc63f;
  /* Adjust this color as needed */
}

.active-link {
  font-weight: bold;
}
</style>
