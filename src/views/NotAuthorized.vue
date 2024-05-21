<template>
    <div class="not-authorized">
      <h1>Not Authorized</h1>
      <p>You do not have permission to access this page.</p>
      <RouterLink to="/views/LogIn.vue">LogIn</RouterLink>
    </div>
  </template>
  
  <script setup>
  import { onMounted, computed } from 'vue';
  import { useRouter } from 'vue-router';
  import { useStore } from 'vuex';
  
  const router = useRouter();
  const store = useStore();
  const userName = computed(() => store.state.user.name);
  
  onMounted(() => {
    localStorage.removeItem('token');
    localStorage.removeItem('userid');
    localStorage.removeItem('email');
    localStorage.removeItem('usertype');
    localStorage.removeItem('name');
    localStorage.removeItem('address');
  
    // Trigger a reactivity update in NavBar
    store.commit('clearUser');
  
    // Use setTimeout to ensure the state is updated before navigating
    setTimeout(() => {
      router.push('/not-authorized');
    }, 0);
  });
  </script>
  
  <style scoped>
  .logout-success {
    text-align: center;
    margin-top: 50px;
  }
  </style>
  