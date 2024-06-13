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
    <button type="button" class="btn btn-primary" @click="updateUser">Update</button>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useRoute, useRouter } from 'vue-router'; // Import useRouter from Vue Router

const id = ref('');
const email = ref('');
const password = ref('');
const usertype = ref('');
const name = ref('');
const address = ref('');

// Use route to get target id from params
const route = useRoute();
id.value = route.params.id;

// Define router object
const router = useRouter();

onMounted(() => {
  axios.get(`https://marilaomis.com/marilaomis/backend/userapi.php?action=get_by_id&id=` + id.value)
    .then((response) => {
      const user = response.data;
      email.value = user.email;
      password.value = unHash(user.password);
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
    password: myHash(password.value),
    usertype: usertype.value,
    name: name.value,
    address: address.value,
    id: id.value
  };

  axios.post('https://marilaomis.com/marilaomis/backend/userapi.php', updatedUser)
    .then(response => {
      console.log('User updated successfully:', response.data);

      router.push('/users/view'); // Use router.push to navigate to the desired route
    })
    .catch(error => {
      console.error('Error updating user:', error);
    });
};


function myHash(text) {
  let base64Encoded = btoa(text);
  return base64Encoded;
}

function unHash(hashed) {
  try {
    return atob(hashed);
  } catch (e) {
    console.error('Error decoding from Base64:', e);
    return '';
  }
}
</script>

<style>
/* Your CSS styles here */
</style>
