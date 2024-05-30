<template>
    <div class="container">
      <div class="row justify-content-center mt-5">
        <div class="col-md-6">
          <div class="card">
            <div class="card-header">
              <h1 class="text-center">Forgot Password</h1>
            </div>
            <div class="card-body">
              <form v-if="!otpSent" @submit.prevent="requestPasswordReset">
                <div class="form-group">
                  <label for="email">Email Address</label>
                  <input type="email" class="form-control" id="email" v-model="email" required>
                </div>
                <br>
                <div class="form-group text-center">
                  <button type="submit" class="btn btn-primary btn-block">Request Password Reset</button>
                </div>
              </form>
  
              <form v-if="otpSent" @submit.prevent="resetPassword">
                <div class="form-group">
                  <label for="otp">Enter OTP</label>
                  <input type="text" class="form-control" id="otp" v-model="otp" required>
                </div>
                <div class="form-group">
                  <label for="newPassword">New Password</label>
                  <input type="password" class="form-control" id="newPassword" v-model="newPassword" required>
                </div>
                <br>
                <div class="form-group text-center">
                  <button type="submit" class="btn btn-primary btn-block">Reset Password</button>
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
      const otp = ref('');
      const newPassword = ref('');
      const otpSent = ref(false);
  
      const requestPasswordReset = () => {
        if (!email.value) {
          alert('Please enter your email address.');
          return;
        }
  
        axios.post('https://rjprint10.com/marilaomis/backend/forgotpassword.php', { email: email.value })
          .then(response => {
            if (response.data.success) {
              alert('An OTP has been sent to your email. Please check your inbox.');
              otpSent.value = true;
            } else {
              alert(response.data.error || 'Failed to request password reset. Please try again later.');
            }
          })
          .catch(error => {
            console.error(error);
            alert('An error occurred. Please try again later.');
          });
      };
  
      const resetPassword = () => {
        if (!otp.value || !newPassword.value) {
          alert('Please enter OTP and new password.');
          return;
        }
  
        axios.post('https://rjprint10.com/marilaomis/backend/resetpassword.php', { otp: otp.value, newPassword: newPassword.value })
          .then(response => {
            if (response.data.success) {
              alert('Password reset successfully. You can now log in with your new password.');
              // Redirect to login page or perform any other action
            } else {
              alert(response.data.error || 'Failed to reset password. Please try again later.');
            }
          })
          .catch(error => {
            console.error(error);
            alert('An error occurred. Please try again later.');
          });
      };
  
      return {
        email,
        otp,
        newPassword,
        otpSent,
        requestPasswordReset,
        resetPassword
      };
    }
  };
  </script>
  
  <style scoped>
  /* Add any additional styles here */
  </style>
  