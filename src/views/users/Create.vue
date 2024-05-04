<template>
    <div class="container mt-4">

        <div class="card">
            <div class="card-header">
                <h4>Add User</h4>
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

        <button type="button" class="btn btn-primary" @click="saveRecord">Save</button>

    </div>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router'; // Import Vue Router

const email = ref('');
const password = ref('');
const usertype = ref('');
const name = ref('');
const address = ref('');

const router = useRouter(); // Initialize Vue Router

const saveRecord = () => {
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
            // Redirect to the view page after successful save
            router.push('/users/view'); // Navigate to the desired route
        })
        .catch(error => {
            console.error('Error saving user:', error);
        });
};
</script>

<style>
/* Your CSS styles here */
</style>
