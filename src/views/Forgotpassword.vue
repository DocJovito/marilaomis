<template>
    <div class="container">
      <div class="row justify-content-center mt-5">
        <div class="col-md-6">
          <div class="card">
            <div class="card-header">
              <h1 class="text-center">Forgot Password</h1>
            </div>
            <div class="card-body">
              <form @submit.prevent="sendResetLink">
                <div class="form-group">
                  <label for="email">Email Address</label>
                  <input type="email" class="form-control" id="email" v-model="email" required>
                </div>
                <br>
                <div class="form-group text-center">
                  <button type="submit" class="btn btn-primary btn-block">Send Reset Link</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  import { ref } from 'vue';
  import axios from 'axios';
  
  export default {
    setup() {
      const email = ref('');
  
      const sendResetLink = () => {
        if (!email.value) {
          alert('Please enter your email address.');
          return;
        }
  
        axios.post('https://rjprint10.com/marilaomis/backend/forgotpassword.php', { email: email.value })
          .then(response => {
            if (response.data.success) {
              alert('Password reset link has been sent to your email.');
            } else {
              alert(response.data.message || 'Failed to send reset link. Please check your email address.');
            }
          })
          .catch(error => {
            console.error(error);
            alert('An error occurred. Please try again later.');
          });
      };
  
      return {
        email,
        sendResetLink
      };
    }
  };
  </script>
  
  <style scoped>
  /* Add any additional styles here */
  </style>
  