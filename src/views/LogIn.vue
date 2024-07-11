<template>
  <div class="container">
    <div class="row justify-content-center mt-5">
      <div class="col-md-6">
        <div class="card">
          <div class="card-header">
            <h1 class="text-center">Log In</h1>
          </div>
          <div class="card-body">
            <div v-if="logoutMessage" class="alert alert-warning">
              You have been logged out due to inactivity.
            </div>
            <form v-if="!otpSent" @submit.prevent="login">
              <div class="form-group">
                <label for="email">Email Address</label>
                <input type="text" class="form-control" id="email" name="email" v-model="email" required>
              </div>
              <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" v-model="password" required>
                <a href="#" @click.prevent="forgotPassword" class="d-block mt-2">Forgot Password?</a>
              </div>
              <br>
              <div class="form-group text-center">
                <button type="submit" class="btn btn-primary btn-block">Log In</button>
              </div>
              <p>Version 0.07</p>
            </form>

            <form v-if="otpSent" @submit.prevent="verifyOtp">
              <div class="form-group">
                <label for="otp">OTP</label>
                <input type="text" class="form-control" id="otp" name="otp" v-model="otp" required>
              </div>
              <br>
              <div class="form-group text-center">
                <button type="submit" class="btn btn-primary btn-block">Verify OTP</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useRouter, useRoute } from 'vue-router';
import { useStore } from 'vuex';

const email = ref('');
const password = ref('');
const otp = ref('');
const otpSent = ref(false);
const logoutMessage = ref(false);

const router = useRouter();
const route = useRoute();
const store = useStore();

onMounted(() => {
  if (route.query.message === 'logout') {
    logoutUser();
    logoutMessage.value = true;
    router.push({ name: 'logout' }); // Redirect to the logout route
  }
});

const login = () => {
  const data = {
    action: 'login',
    email: email.value,
    password: myHash(password.value),
  };

  axios.post('https://marilaomis.com/marilaomis/backend/loginapi.php', data)
    .then(response => {
      try {
        const cleanedResponse = response.data.trim().replace(/^\/\/\s*/, ''); // Clean response
        const responseData = JSON.parse(cleanedResponse); // Parse cleaned response

        if (responseData.success === true) {
          otpSent.value = true;
          alert(`OTP sent to your email. Please check your email and enter the OTP.`);
        } else {
          alert(responseData.error || 'Invalid email or password');
        }
      } catch (error) {
        console.error('Error parsing response:', error);
        alert('Invalid server response');
      }
    })
    .catch(error => {
      console.error('Error logging in:', error);
      alert('Error logging in');
    });
};

const verifyOtp = () => {
  const data = {
    action: 'verify_otp',
    email: email.value,
    otp: otp.value
  };

  axios.post('https://marilaomis.com/marilaomis/backend/loginapi.php', data)
    .then(response => {
      try {
        const cleanedResponse = response.data.trim().replace(/^\/\/\s*/, ''); // Clean response
        const responseData = JSON.parse(cleanedResponse); // Parse cleaned response

        if (responseData.success === true) {
          const user = responseData.user; // Access user data from responseData
          store.dispatch('logIn', {
            id: user.userid,
            email: user.email,
            userType: user.usertype,
            name: user.name,
            address: user.address,
            token: responseData.token // Access token from responseData
          });

          // Save user data in local storage
          localStorage.setItem('token', responseData.token);
          localStorage.setItem('userid', user.userid);
          localStorage.setItem('email', user.email);
          localStorage.setItem('usertype', user.usertype);
          localStorage.setItem('name', user.name);
          localStorage.setItem('address', user.address);

          alert('OTP verified successfully. Log in successful');
          router.push('/');
        } else {
          alert(responseData.error || 'Invalid OTP');
        }
      } catch (error) {
        console.error('Error parsing response:', error);
        alert('Invalid server response');
      }
    })
    .catch(error => {
      console.error('Error verifying OTP:', error);
      alert('Error verifying OTP');
    });
};

const forgotPassword = () => {
  router.push('/forgotpassword');
};

const logoutUser = () => {
  // Clear relevant items from local storage
  localStorage.removeItem('token');
  localStorage.removeItem('userid');
  localStorage.removeItem('email');
  localStorage.removeItem('usertype');
  localStorage.removeItem('name');
  localStorage.removeItem('address');
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
