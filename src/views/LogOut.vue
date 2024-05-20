<template>
    <div class="container">
      <h1>Log Out Success</h1>
    </div>
  </template>
  
  <script setup>
  import { onMounted } from 'vue';
  import { useRouter } from 'vue-router';
  
  const router = useRouter();
  
  // Perform logout actions when the component is mounted
  onMounted(() => {
    // Clear localStorage (remove user data and token)
    localStorage.removeItem('token');
    localStorage.removeItem('userid');
    localStorage.removeItem('email');
    localStorage.removeItem('usertype');
    localStorage.removeItem('name');
    localStorage.removeItem('address');
  
    // Redirect to the login page
    router.push('/login');
  });
  </script>
  