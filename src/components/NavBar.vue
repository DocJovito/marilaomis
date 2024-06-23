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
    <div v-if="showWarning" class="warning-message">
      You will be logged out due to inactivity in 1 minute.
    </div>
  </div>
</template>


<script setup>
import { computed, ref, onMounted, onBeforeUnmount } from 'vue';
import { useStore } from 'vuex';
import { RouterLink } from 'vue-router';
import Collapse from 'bootstrap/js/dist/collapse';

const store = useStore();
const isLoggedIn = computed(() => store.state.isAuthenticated);
const userLevel = computed(() => store.state.user.userType);
const userName = computed(() => store.state.user.name);

const navbarNavDropdown = ref(null);
let bsCollapse;

const activityTimeout = ref(null);
const warningTimeout = ref(null);
// for testing
// const logoutTime = 50 * 1000; // 20 sec in milliseconds
// const warningTime = logoutTime - 20000; // 20 sec before logout

const logoutTime = 1800 * 1000; // 30 min in milliseconds
const warningTime = logoutTime - 6000; // 1 minute before logout
const userToken = computed(() => store.state.user.token);
const showWarning = ref(false);

const startInactivityTimer = () => {
  resetInactivityTimer();
  window.addEventListener('mousemove', resetInactivityTimer);
  window.addEventListener('keydown', resetInactivityTimer);
  window.addEventListener('beforeunload', handleBrowserClose);
};

const resetInactivityTimer = () => {
  clearTimeout(activityTimeout.value);
  clearTimeout(warningTimeout.value);
  showWarning.value = false;
  warningTimeout.value = setTimeout(showLogoutWarning, warningTime);
  activityTimeout.value = setTimeout(logoutUser, logoutTime);
};

const showLogoutWarning = () => {
  showWarning.value = true;
};

const handleBrowserClose = () => {
  logoutUser();
};

const logoutUser = async () => {
  try {
    await fetch('path/to/your/logout/api', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({ action: 'logout', token: userToken.value })
    });
    store.dispatch('logout'); // Assuming you have a logout action in your Vuex store
    window.location.href = '/login?message=logout'; // Redirect to login page with a message
  } catch (error) {
    console.error('Error logging out:', error);
  }
};

onMounted(() => {
  bsCollapse = new Collapse(navbarNavDropdown.value, {
    toggle: false
  });
  startInactivityTimer();
});

onBeforeUnmount(() => {
  window.removeEventListener('mousemove', resetInactivityTimer);
  window.removeEventListener('keydown', resetInactivityTimer);
  window.removeEventListener('beforeunload', handleBrowserClose);
  clearTimeout(activityTimeout.value);
  clearTimeout(warningTimeout.value);
});

const closeNavbar = () => {
  if (bsCollapse) {
    bsCollapse.hide();
  }
};
</script>


<style scoped>
.bg-greenish {
  background-color: #8dc63f;
}

.active-link {
  font-weight: bold;
}

.warning-message {
  position: fixed;
  bottom: 0;
  left: 0;
  right: 0;
  background-color: yellow;
  color: red;
  text-align: center;
  padding: 10px;
  font-weight: bold;
}
</style>
