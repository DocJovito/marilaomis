<template>
  <div>
    <nav class="navbar navbar-expand-lg bg-greenish">
      <div class="container-fluid">
        <a class="navbar-brand" href="#">
          <img src="/src/assets/images/greenmarilao.png" width="30" height="30" class="d-inline-block align-top" alt="">
        </a>
        <a class="navbar-brand" href="#">Marilao MIS</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown"
          aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown" ref="navbarNavDropdown">
          <ul class="navbar-nav">
            <li class="nav-item" v-if="userLevel === 'Admin' || userLevel === 'Municipal Staff'">
              <RouterLink to="/dashboard" active-class="active-link" class="nav-link" @click.native="closeNavbar">
                Dashboard</RouterLink>
            </li>
            <li class="nav-item"
              v-if="userLevel === 'Admin' || userLevel === 'Municipal Staff' || userLevel === 'Area Leader'">
              <RouterLink to="/" active-class="active-link" class="nav-link" @click.native="closeNavbar">Home
              </RouterLink>
            </li>
            <li class="nav-item"
              v-if="userLevel === 'Admin' || userLevel === 'Municipal Staff' || userLevel === 'Area Leader'">
              <RouterLink to="/about" active-class="active-link" class="nav-link" @click.native="closeNavbar">About
              </RouterLink>
            </li>
            <li class="nav-item">
              <RouterLink v-if="userLevel === 'Admin'" class="nav-link" active-class="active-link" to="/users/view"
                @click.native="closeNavbar">
                Users
              </RouterLink>
            </li>
            <li class="nav-item">
              <RouterLink v-if="userLevel === 'Admin' || userLevel === 'Municipal Staff'" class="nav-link"
                active-class="active-link" to="/funds/view" @click.native="closeNavbar">
                Fundings
              </RouterLink>
            </li>
            <li class="nav-item"
              v-if="userLevel === 'Admin' || userLevel === 'Municipal Staff' || userLevel === 'Area Leader'">
              <RouterLink to="/residents/view" active-class="active-link" class="nav-link" @click.native="closeNavbar">
                Residents</RouterLink>
            </li>
            <li class="nav-item"
              v-if="userLevel === 'Admin' || userLevel === 'Municipal Staff' || userLevel === 'Area Leader'">
              <RouterLink to="/programs/view" active-class="active-link" class="nav-link" @click.native="closeNavbar">
                Programs</RouterLink>
            </li>
            <li class="nav-item dropdown" v-if="userLevel === 'Admin' || userLevel === 'Municipal Staff'">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown"
                aria-expanded="false">
                Reports
              </a>
              <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                <li class="nav-item">
                  <RouterLink to="/reports/residents" active-class="active-link" class="nav-link"
                    @click.native="closeNavbar">Residents</RouterLink>
                </li>
                <li class="nav-item">
                  <RouterLink to="/reports/programs" active-class="active-link" class="nav-link"
                    @click.native="closeNavbar">Programs</RouterLink>
                </li>
                <li class="nav-item">
                  <RouterLink to="/reports/funds" active-class="active-link" class="nav-link"
                    @click.native="closeNavbar">Funds</RouterLink>
                </li>
                <li class="nav-item">
                  <RouterLink to="/reports/scans" active-class="active-link" class="nav-link"
                    @click.native="closeNavbar">Scans</RouterLink>
                </li>
              </ul>
            </li>
          </ul>
          <ul class="navbar-nav ms-auto">
            <li class="nav-link"> {{ userName }}</li>
            <li v-if="isLoggedIn && userLevel !== 'Admin'" class="nav-item">
              <RouterLink to="/users/profile" active-class="active-link" class="nav-link" @click.native="closeNavbar">
                Profile</RouterLink>
            </li>
            <li v-if="!isLoggedIn" class="nav-item">
              <RouterLink class="nav-link" active-class="active-link" to="/login" @click.native="closeNavbar">Log In
              </RouterLink>
            </li>
            <li v-else class="nav-item">
              <RouterLink class="nav-link" active-class="active-link" to="/logout" @click.native="closeNavbar">Log Out
              </RouterLink>
            </li>
          </ul>
        </div>
      </div>
    </nav>
  </div>
</template>


<script setup>
import { computed, ref, onMounted } from 'vue';
import { useStore } from 'vuex';
import { RouterLink } from 'vue-router';

// Bootstrap 5 Collapse API
import Collapse from 'bootstrap/js/dist/collapse';

const store = useStore();
const isLoggedIn = computed(() => store.state.isAuthenticated);
const userLevel = computed(() => store.state.user.userType);
const userName = computed(() => store.state.user.name);

const navbarNavDropdown = ref(null);
let bsCollapse;

onMounted(() => {
  bsCollapse = new Collapse(navbarNavDropdown.value, {
    toggle: false
  });
});

function closeNavbar() {
  if (bsCollapse) {
    bsCollapse.hide();
  }
}
</script>


<style scoped>
.bg-greenish {
  background-color: #8dc63f;
}

.active-link {
  font-weight: bold;
}
</style>
