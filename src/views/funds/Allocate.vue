<template>
  <div class="container mt-4">
    <h4>Allocate Fund</h4>
    <form @submit.prevent="allocateFund">
      <div class="mb-3">
        <label for="programid" class="form-label">Budget For</label>
        <select class="form-control" id="programid" v-model="newFunding.programid" required>
          <option value="">Select a Program</option>
          <option v-for="program in programs" :key="program.programid" :value="program.programid">
            {{ program.programname }}
          </option>
        </select>
      </div>
      <div class="mb-3">
        <label for="amount" class="form-label">Amount</label>
        <input type="number" class="form-control" id="amount" v-model="newFunding.amount" required>
      </div>
      <div class="mb-3">
        <label for="userid" class="form-label">User</label>
        <select class="form-control" id="userid" v-model="newFunding.userid" required>
          <option value="">Select a User</option>
          <option v-for="user in users" :key="user.userid" :value="user.userid">
            {{ user.name }} ({{ user.email }})
          </option>
        </select>
      </div>
      <button type="submit" class="btn btn-primary">Allocate</button>
      <router-link to="/funds/view" class="btn btn-secondary ml-2">Cancel</router-link>
    </form>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';
import { useStore } from 'vuex';

const store = useStore();
const userId = computed(() => store.state.user.id);
const router = useRouter();

function getCurrentDate() {
  const date = new Date();
  return date.toISOString().slice(0, 19).replace('T', ' ');
}

const newFunding = ref({
  programid: '',
  amount: '',
  userid: '',
  createdby: '',
  createdat: getCurrentDate(),
  editedby: '',
  editedat: '',
  isdeleted: 0,
  deletedby: ''
});

const programs = ref([]);
const users = ref([]);

const fetchPrograms = async () => {
  try {
    const response = await axios.get('https://marilaomis.com/marilaomis/backend/fundapi.php?action=get_programs');
    programs.value = response.data;
  } catch (error) {
    console.error('Error fetching programs:', error);
  }
};

const fetchUsers = async () => {
  try {
    const response = await axios.get('https://marilaomis.com/marilaomis/backend/fundapi.php?action=get_users');
    users.value = response.data;
  } catch (error) {
    console.error('Error fetching users:', error);
  }
};

onMounted(() => {
  fetchPrograms();
  fetchUsers();
});

const allocateFund = () => {
  // Validation
  if (!newFunding.value.programid) {
    alert("Please select a program.");
    return;
  }

  if (!newFunding.value.amount || isNaN(newFunding.value.amount) || newFunding.value.amount <= 0) {
    alert("Please enter a valid amount.");
    return;
  }

  if (!newFunding.value.userid) {
    alert("Please select a user.");
    return;
  }

  // Set the createdby field to the current user's ID
  newFunding.value.createdby = userId.value;

  const data = {
    action: 'create',
    ...newFunding.value
  };

  axios.post('https://marilaomis.com/marilaomis/backend/fundapi.php', data)
    .then(response => {
      console.log(response.data);
      router.push('/funds/view');  // Redirect to funds listing page after successful allocation
    })
    .catch(error => {
      console.error('Error allocating fund:', error);
    });
};
</script>

<style scoped>
/* Add your custom styles here */
</style>
