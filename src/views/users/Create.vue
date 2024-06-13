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
          <label for="usertype">User Level:</label><br>
          <select id="usertype" class="form-control" v-model="usertype">
            <option value="Admin">Admin</option>
            <option value="Municipal Staff">Municipal Staff</option>
            <option value="Area Leader">Area Leader</option>
            <!-- Add more options as needed -->
          </select>
        </div>
        <div class="form-group">
          <label for="name">Name:</label><br>
          <input type="text" id="name" class="form-control" v-model="name">
        </div>
        <div class="form-group">
          <label for="barangay">Address Scope:</label><br>
          <select id="barangay" class="form-control" v-model="address">
            <option value="All">All</option>
            <option value="Abangan Norte">Abangan Norte</option>
            <option value="Abangan Sur">Abangan Sur</option>
            <option value="Ibayo">Ibayo</option>
            <option value="Lambakin">Lambakin</option>
            <option value="Lias">Lias</option>
            <option value="Loma de Gato">Loma de Gato</option>
            <option value="Nagbalon">Nagbalon</option>
            <option value="Patubig">Patubig</option>
            <option value="Poblacion 1st">Poblacion 1st</option>
            <option value="Poblacion 2nd">Poblacion 2nd</option>
            <option value="Prenza 1st">Prenza 1st</option>
            <option value="Prenza 2nd">Prenza 2nd</option>
            <option value="Santa Rosa 1st">Santa Rosa 1st</option>
            <option value="Santa Rosa 2nd">Santa Rosa 2nd</option>
            <option value="Saog">Saog</option>
            <option value="Tabing-ilog">Tabing-ilog</option>
            <!-- Add more options as needed -->
          </select>
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
    axios.get(`https://marilaomis.com/marilaomis/backend/checkEmail.php?email=${email.value}`)
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
  axios.post('https://marilaomis.com/marilaomis/backend/userapi.php', newUser)
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