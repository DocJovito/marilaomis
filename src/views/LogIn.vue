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
import { useStore } from 'vuex';

const email = ref('');
const password = ref('');
const router = useRouter();
const store = useStore();

const login = () => {
  const data = {
    action: 'login',
    email: email.value,
    password: password.value
  };

  axios.post('https://rjprint10.com/marilaomis/backend/loginapi.php', data)
    .then(response => {
      const user = response.data.user;
      store.dispatch('logIn', {
        id: user.userid,
        email: user.email,
        userType: user.usertype,
        name: user.name,
        address: user.address,
        token: response.data.token
      });

      // Save user data in local storage
      localStorage.setItem('token', response.data.token);
      localStorage.setItem('userid', user.userid);
      localStorage.setItem('email', user.email);
      localStorage.setItem('usertype', user.usertype);
      localStorage.setItem('name', user.name);
      localStorage.setItem('address', user.address);

      if (response.data.token) {
        alert('Log in successful');
        router.push('/');
      } else {
        alert('Invalid email or password');
      }
    })
    .catch(error => {
      console.error(error);
      alert('Error logging in');
    });
};
</script>
