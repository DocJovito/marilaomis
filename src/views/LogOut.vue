<template>
    <div>
      <h1>Log Out Success</h1>
      <p>Logged out as {{ userName }}</p>
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
    store.dispatch('logOut');
  
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
      router.push('/login');
    }, 0);
  });
  </script>
  