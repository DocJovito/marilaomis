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
                <input type="password" class="form-control" id="currentPassword" name="currentPassword"
                  v-model="currentPassword" required>
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
                <input type="password" class="form-control" id="newPassword" name="newPassword" v-model="newPassword"
                  required>
              </div>
              <div class="form-group">
                <label for="confirmPassword">Confirm Password</label>
                <input type="password" class="form-control" id="confirmPassword" name="confirmPassword"
                  v-model="confirmPassword" required>
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
import { ref, computed } from 'vue';
import { useStore } from 'vuex';
import { useRouter } from 'vue-router';
import axios from 'axios';

const currentPassword = ref('');
const newPassword = ref('');
const confirmPassword = ref('');
const otp = ref('');
const otpSent = ref(false);
const router = useRouter();
const store = useStore();
const userEmail = computed(() => store.state.user.email);

const sendOTP = () => {
  // Ensure that currentPassword and userEmail are correctly defined
  currentPassword.value = myHash(currentPassword.value);
  if (!currentPassword.value) {
    alert('Current password is not provided');
    return;
  }

  if (!userEmail.value) {
    alert('User email is not provided');
    return;
  }


  axios.post('https://marilaomis.com/marilaomis/backend/check_password.php', { email: userEmail.value, currentPassword: currentPassword.value })
    .then(response => {
      if (response.data.success === true) {
        axios.post('https://marilaomis.com/marilaomis/backend/send_otp.php', { email: userEmail.value })
          .then(response => {
            const responseData = response.data.trim().replace(/^\/\/\s*/, ''); // Remove leading '//'
            const parsedData = JSON.parse(responseData);
            if (parsedData.success === true) {
              otpSent.value = true;
              alert('OTP sent successfully');
            } else {
              alert(response.data.error || 'Failed to send OTP vue');
            }
          })
          .catch(error => {
            console.error('Error sending OTP:', error);
            alert('Error sending OTP');
          });
      } else {
        alert(response.data.error || 'Current password is incorrect');
      }
    })
    .catch(error => {
      console.error('Error checking current password:', error);
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
    newPassword: myHash(newPassword.value),
    email: userEmail.value
  };

  axios.post('https://marilaomis.com/marilaomis/backend/change_password.php', data)
    .then(response => {
      if (response.data.success) {
        alert('Password changed successfully');
        router.push('/');
      } else {
        alert(response.data.error || 'Failed to change password');
      }
    })
    .catch(error => {
      console.error('Error changing password:', error);
      alert('Error changing password');
    });
};

function myHash(text) {
  let base64Encoded = btoa(text);
  return base64Encoded;
}

</script>

<style scoped>
/* Your styles here */
</style>
