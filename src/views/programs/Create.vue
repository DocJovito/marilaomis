<template>
  <div class="container mt-4">
    <h4>Create Program</h4>
    <form @submit.prevent="createProgram">
      <div class="mb-3">
        <label for="programName" class="form-label">Program Name</label>
        <input type="text" class="form-control" id="programName" v-model="newProgram.programname" required>
      </div>
      <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <input type="text" class="form-control" id="description" v-model="newProgram.description" required>
      </div>
      <div class="mb-3">
        <div class="form-group">
          <label for="barangayScope">Barangay Scope:</label><br>
          <div class="row">
            <div class="col-md-3" v-for="barangay in barangays" :key="barangay">
              <label>
                <input type="checkbox" :value="barangay" v-model="selectedBarangays"> {{ barangay }}
              </label>
            </div>
          </div>
        </div>
      </div>
      <div class="mb-3">
        <label for="eventDate" class="form-label">Event Date</label>
        <input type="date" class="form-control" id="eventDate" v-model="newProgram.eventDate" required>
      </div>
      <div class="mb-3">
        <label for="isActive" class="form-label">Is Active</label>
        <input type="text" class="form-control" id="isActive" :value="isActiveValue" disabled>
      </div>
      <div class="mb-3">
        <label for="createdBy" class="form-label">Created By</label>
        <input type="text" class="form-control" id="createdBy" :value=userName disabled>
      </div>
      <div class="mb-3">
        <label for="createdAt" class="form-label">Created At</label>
        <input type="date" class="form-control" id="createdAt" v-model="newProgram.createdat" disabled>
      </div>
      <button type="submit" class="btn btn-primary">Create Program</button>
    </form>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';
import { useStore } from 'vuex';

const isActiveValue = "Yes"; // it is for isActive

// Function to get current date in YYYY-MM-DD format
function getCurrentDate() {
  const date = new Date();
  const year = date.getFullYear();
  const month = String(date.getMonth() + 1).padStart(2, '0'); // Months are zero-based
  const day = String(date.getDate()).padStart(2, '0');
  return `${year}-${month}-${day}`;
}

const store = useStore();
const userId = computed(() => store.state.user.id);
const userName = computed(() => store.state.user.name);

const selectedBarangays = ref([]);
const barangays = [
  "Abangan Norte",
  "Abangan Sur",
  "Ibayo",
  "Lambakin",
  "Lias",
  "Loma de Gato",
  "Nagbalon",
  "Patubig",
  "Poblacion 1st",
  "Poblacion 2nd",
  "Prenza 1st",
  "Prenza 2nd",
  "Santa Rosa 1st",
  "Santa Rosa 2nd",
  "Saog",
  "Tabing-ilog"
];

const newProgram = ref({
  programname: '',
  description: '',
  barangayscope: '',
  eventDate: getCurrentDate(),
  isactive: '1',  // wala na
  createdby: '',
  createdat: getCurrentDate()  // wala na
});

const router = useRouter();

const createProgram = () => {
  // Ensure userId.value is used to capture the current userId value
  newProgram.value.createdby = userId.value;

  const programData = {
    action: 'create',
    ...newProgram.value,
    barangayscope: selectedBarangays.value.join(', ') // Concatenate selected barangays
  };

  axios.post('https://rjprint10.com/marilaomis/backend/programapi.php', programData)
    .then(response => {
      console.log('Program created successfully:', response.data);
      alert("Program Created");
      router.push('/programs/view'); // Redirect to program management page
    })
    .catch(error => {
      console.error('Error creating program:', error);
      alert("Failed to create program. Please try again.");
    });
};
</script>