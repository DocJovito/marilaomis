<template>
    <div class="container">
      <div class="row justify-content-center mt-5">
        <div class="col-md-6">
          <div class="card">
            <div class="card-header">
              <h1 class="text-center">Change Password</h1>
            </div>
            <div class="card-body">
              <form @submit.prevent="sendOTP">
                <div class="form-group">
                  <label for="currentPassword">Current Password</label>
                  <input type="password" class="form-control" id="currentPassword" name="currentPassword" v-model="currentPassword" required>
                </div>
                <br>
                <div class="form-group text-center">
                  <button type="submit" class="btn btn-primary btn-block">Send OTP</button>
                </div>
              </form>
  
              <form v-if="otpSent" @submit.prevent="changePassword">
                <div class="form-group">
                  <label for="otp">OTP</label>
                  <input type="text" class="form-control" id="otp" name="otp" v-model="otp" required>
                </div>
                <div class="form-group">
                  <label for="newPassword">New Password</label>
                  <input type="password" class="form-control" id="newPassword" name="newPassword" v-model="newPassword" required>
                </div>
                <div class="form-group">
                  <label for="confirmPassword">Confirm Password</label>
                  <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" v-model="confirmPassword" required>
                </div>
                <br>
                <div class="form-group text-center">
                  <button type="submit" class="btn btn-primary btn-block">Change Password</button>
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
  import { useStore } from 'vuex';
  import axios from 'axios';
  
  const currentPassword = ref('');
  const newPassword = ref('');
  const confirmPassword = ref('');
  const otp = ref('');
  const otpSent = ref(false);
  
  const store = useStore();
  const useremail = ref('');
  
  const sendOTP = () => {
    // Check if the current password is correct
    axios.post('https://rjprint10.com/marilaomis/backend/change_password.php', { currentPassword: currentPassword.value })
      .then(response => {
        if (response.data.correct) {
          // Current password is correct, send OTP
          axios.post('https://rjprint10.com/marilaomis/backend/send_otp.php', { email: useremail.value }) // Replace with the user's email
            .then(response => {
              if (response.data.success) {
                otpSent.value = true;
                alert('OTP sent successfully');
              } else {
                alert(response.data.error || 'Failed to send OTP');
              }
            })
            .catch(error => {
              console.error(error);
              alert('Error sending OTP');
            });
        } else {
          alert('Current password is incorrect');
        }
      })
      .catch(error => {
        console.error(error);
        alert('Error checking current password');
      });
  };
  
  const changePassword = () => {
    if (newPassword.value !== confirmPassword.value) {
      alert('New passwords do not match');
      return;
    }
  
    const data = {
      otp: otp.value,
      newPassword: newPassword.value
    };
  
    axios.post('https://rjprint10.com/marilaomis/backend/change_password.php', data)
      .then(response => {
        if (response.data.success) {
          alert('Password changed successfully');
        } else {
          alert(response.data.error || 'Failed to change password');
        }
      })
      .catch(error => {
        console.error(error);
        alert('Error changing password');
      });
  };
  </script>
  