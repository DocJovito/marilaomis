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
                  <input type="text" class="form-control" id="email" name="email" v-model="email" required>
                </div>
                <div class="form-group">
                  <label for="password">Password</label>
                  <input type="password" class="form-control" id="password" name="password" v-model="password" required>
                </div>
                <br>
                <div class="form-group text-center">
                  <button type="submit" class="btn btn-primary btn-block">Log In</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue';
  import axios from 'axios';
  import { useRouter } from 'vue-router';
  
  const email = ref('');
  const password = ref('');
  const router = useRouter();
  
  function login() {
    const data = {
        action: 'login',
        email: email.value,
        password: password.value
    };

    axios.post('https://rjprint10.com/marilaomis/backend/loginapi.php', data)
        .then(response => {
            const userData = response.data;
            localStorage.setItem('token', userData.token);

            if (userData.user && userData.user.length === 1) {
                alert("Log in successful");
                router.push('/residents/view'); // Redirect to the About page upon successful login
            } else {
                alert("Invalid email or password asdf");
            }
        })
        .catch(error => {
            console.error(error);
            alert("Error logging in");
        });
}
  </script>
  