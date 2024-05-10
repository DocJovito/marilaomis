<template>
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h1 class="text-center">Log In</h1>
                    </div>
                    <div class="card-body">
                        <form @submit.prevent="login">
                            <div class="form-group">
                                <label for="email">Email Address</label>
                                <input type="text" class="form-control" id="email" name="email" v-model="email"
                                    required>
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="" class="form-control" id="password" name="password" v-model="password"
                                    required>
                            </div>
                            <br>
                            <div class="form-group text-center">
                                <button type="submit" class="btn btn-primary btn-block">Log In</button>
                            </div>
                        </form>
                        <!-- <div class="text-center">
                            <a href="#" class="text-muted">Forgot Password?</a>
                        </div> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { createStore } from "vuex";
import { ref } from 'vue';
import axios from 'axios';

const email = ref('');
const password = ref('');

const currentuser = ref([]);

function login() {
    const data = {
        action: 'login',
        email: email.value,
        password: password.value
    }
    const response = axios.post('https://rjprint10.com/marilaomis/backend/userapi.php', data)
        .then(response => {
            currentuser.value = response.data;

            //maki store kay store.js
            // localStorage.setItem('token', response.data.token);

            if (response.data.length == 1) {
                alert("log in success");
            }
            else {
                alert("invalid user or pass");
            }
            // console.log(response.data.length);
            // console.log(currentuser);
        })
        .catch(error => {
            console.error(error);
        });

}
</script>