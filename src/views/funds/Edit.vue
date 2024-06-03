<template>
    <div class="container mt-4">
      <h4>Edit Fund Allocation</h4>
      <form @submit.prevent="updateFund">
        <div class="mb-3">
          <label for="budgetfor" class="form-label">Budget For</label>
          <select class="form-control" id="budgetfor" v-model="fund.budgetfor" required>
            <option value="">Select a Program</option>
            <option v-for="program in programs" :key="program.programname" :value="program.programname">
              {{ program.programname }}
            </option>
          </select>
        </div>
        <div class="mb-3">
          <label for="amount" class="form-label">Amount</label>
          <input type="number" class="form-control" id="amount" v-model="fund.amount" required>
        </div>
        <div class="mb-3">
          <label for="userid" class="form-label">User</label>
          <select class="form-control" id="userid" v-model="fund.userid" required>
            <option value="">Select a User</option>
            <option v-for="user in users" :key="user.userid" :value="user.userid">
              {{ user.name }} ({{ user.email }})
            </option>
          </select>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
        <RouterLink to="/funds/view" class="btn btn-secondary ml-2">Cancel</RouterLink>
      </form>
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted, computed } from 'vue';
  import axios from 'axios';
  import { useRouter, useRoute } from 'vue-router';
  import { useStore } from 'vuex';
  
  const store = useStore();
  const userId = computed(() => store.state.user.id);
  const router = useRouter();
  const route = useRoute();
  
  function getCurrentDate() {
    const date = new Date();
    return date.toISOString().slice(0, 19).replace('T', ' ');
  }
  
  const fund = ref({
    fundid: '',
    budgetfor: '',
    amount: '',
    userid: '',
    createdat: '',
    editedby: '',
    editedat: getCurrentDate(),
    isdeleted: 0,
    deletedby: ''
  });
  
  const programs = ref([]);
  const users = ref([]);
  
  const fetchPrograms = async () => {
    try {
      const response = await axios.get('https://rjprint10.com/marilaomis/backend/fundapi.php?action=get_programs');
      programs.value = response.data;
    } catch (error) {
      console.error('Error fetching programs:', error);
    }
  };
  
  const fetchUsers = async () => {
    try {
      const response = await axios.get('https://rjprint10.com/marilaomis/backend/fundapi.php?action=get_users');
      users.value = response.data;
    } catch (error) {
      console.error('Error fetching users:', error);
    }
  };
  
  const fetchFundDetails = async () => {
    try {
      const response = await axios.get(`https://rjprint10.com/marilaomis/backend/fundapi.php?action=get_by_id&fundid=${route.params.fundid}`);
      if (response.data) {
        fund.value = response.data;
      } else {
        console.error('Error: Fund data not found.');
      }
    } catch (error) {
      console.error('Error fetching fund details:', error);
    }
  };
  
  onMounted(() => {
    fetchPrograms();
    fetchUsers();
    fetchFundDetails();
  });
  
  const updateFund = () => {
    // Validation
    if (!fund.value.budgetfor) {
      alert("Please select a program.");
      return;
    }
  
    if (!fund.value.amount || isNaN(fund.value.amount) || fund.value.amount <= 0) {
      alert("Please enter a valid amount.");
      return;
    }
  
    if (!fund.value.userid) {
      alert("Please select a user.");
      return;
    }
  
    // Set the editedby field to the current user's ID
    fund.value.editedby = userId.value;
  
    const data = {
      action: 'update',
      ...fund.value
    };
  
    axios.post('https://rjprint10.com/marilaomis/backend/fundapi.php', data)
      .then(response => {
        console.log(response.data);
        router.push('/funds/view');  // Redirect to funds listing page after successful update
      })
      .catch(error => {
        console.error('Error updating fund:', error);
      });
  };
  </script>
  
  <style scoped>
  /* Add your custom styles here */
  </style>
  