<template>
    <div class="container mt-4">
      <div class="card">
        <div class="card-header">
          <h4>Edit User</h4>
        </div>
        <div class="card-body">
          <div class="form-group">
            <label for="email">Email:</label><br>
            <input type="email" id="email" class="form-control" v-model="email">
          </div>
          <div class="form-group">
            <label for="password">Password:</label><br>
            <input type="password" id="password" class="form-control" v-model="password">
          </div>
          <div class="form-group">
            <label for="usertype">User Type:</label><br>
            <input type="text" id="usertype" class="form-control" v-model="usertype">
          </div>
          <div class="form-group">
            <label for="name">Name:</label><br>
            <input type="text" id="name" class="form-control" v-model="name">
          </div>
          <div class="form-group">
            <label for="address">Address:</label><br>
            <input type="text" id="address" class="form-control" v-model="address">
          </div>
        </div>
      </div>
      <button type="button" class="btn btn-primary" @click="updateUser">Update</button>
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue';
  import axios from 'axios';
  import { useRoute } from 'vue-router'
  
  const id = ref('');
  const email = ref('');
  const password = ref('');
  const usertype = ref('');
  const name = ref('');
  const address = ref('');
  
  // Use route to get target id from params
  const route = useRoute();
  id.value = route.params.id;
  
  onMounted(() => {
    axios.get(`https://rjprint10.com/marilaomis/backend/userapi.php?action=get_by_id&id=` + id.value)
      .then((response) => {
        const user = response.data;
        email.value = user.email;
        password.value = user.password;
        usertype.value = user.usertype;
        name.value = user.name;
        address.value = user.address;
      })
      .catch((error) => {
        console.error('Error fetching user data:', error);
      });
  });
  
  const updateUser = () => {
    const updatedUser = {
      action: 'update',
      id: id.value,
      email: email.value,
      password: password.value,
      usertype: usertype.value,
      name: name.value,
      address: address.value,
      id: id.value
    };
  
    axios.post('https://rjprint10.com/marilaomis/backend/userapi.php', updatedUser)
      .then(response => {
        console.log('User updated successfully:', response.data);
        
        router.push('/users/view');
      })
      .catch(error => {
        console.error('Error updating user:', error);
      });
  };
  </script>
  
  <style>
  /* Your CSS styles here */
  </style>
  