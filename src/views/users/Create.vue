<template>
    <div class="container mt-4">
      <div class="card">
        <div class="card-header">
          <h4>Add User</h4>
        </div>
        <div class="card-body">
          <div class="form-group">
            <label for="email">Email:</label><br>
            <input type="email" id="email" class="form-control" v-model="email" @blur="checkEmail">
            <small v-if="emailError" class="text-danger">{{ emailError }}</small>
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
      <button type="button" class="btn btn-primary mt-3" @click="saveRecord">Save</button>
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue';
  import axios from 'axios';
  import { useRouter } from 'vue-router';
  
  const email = ref('');
  const password = ref('');
  const usertype = ref('');
  const name = ref('');
  const address = ref('');
  const emailError = ref('');
  
  const router = useRouter();
  
  const checkEmail = () => {
    if (email.value) {
      // Check email format
      const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailPattern.test(email.value)) {
        emailError.value = 'Invalid email format';
        return;
      }
      // Check email existence in database
      axios.get(`https://rjprint10.com/marilaomis/backend/checkEmail.php?email=${email.value}`)
        .then(response => {
          if (response.data.exists) {
            emailError.value = 'Email already exists';
          } else {
            emailError.value = '';
          }
        })
        .catch(error => {
          console.error('Error checking email:', error);
        });
    }
  };
  
  const saveRecord = () => {
    if (emailError.value) return; // Do not proceed if there is an email error
    const newUser = {
      action: 'create',
      email: email.value,
      password: password.value,
      usertype: usertype.value,
      name: name.value,
      address: address.value
    };
    axios.post('https://rjprint10.com/marilaomis/backend/userapi.php', newUser)
      .then(response => {
        console.log('User saved successfully:', response.data);
        router.push('/users/view');
      })
      .catch(error => {
        console.error('Error saving user:', error);
      });
  };
  </script>
  
  <style>
  /* Your CSS styles here */
  </style>
  