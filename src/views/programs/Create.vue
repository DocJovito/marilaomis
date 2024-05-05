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
          <label for="barangayScope" class="form-label">Barangay Scope</label>
          <div class="dropdown">
            <button class="btn btn-success dropdown-toggle" type="button" id="barangayDropdown" data-bs-toggle="dropdown" aria-expanded="false">
              Select
            </button>
            <ul class="dropdown-menu" aria-labelledby="barangayDropdown">
              <li v-for="barangay in barangays" :key="barangay">
                <label>
                  <input type="checkbox" :value="barangay" v-model="selectedBarangays">
                  {{ barangay }}
                </label>
              </li>
            </ul>
          </div>
          <label for="selectedBarangays" class="form-label">Selected Barangays:</label>
          <div id="selectedBarangays">
            {{ selectedBarangays.join(', ') }}
          </div>
        </div>
        <div class="mb-3">
          <label for="eventDate" class="form-label">Event Date</label>
          <input type="date" class="form-control" id="eventDate" v-model="newProgram.eventDate" required>
        </div>
        <div class="mb-3">
          <label for="isActive" class="form-label">Is Active</label>
          <select class="form-select" id="isActive" v-model="newProgram.isactive" required>
            <option value="1">Yes</option>
            <option value="0">No</option>
          </select>
        </div>
        <div class="mb-3">
          <label for="createdBy" class="form-label">Created By</label>
          <input type="text" class="form-control" id="createdBy" v-model="newProgram.createdby" required>
        </div>
        <div class="mb-3">
          <label for="createdAt" class="form-label">Created At</label>
          <input type="date" class="form-control" id="createdAt" v-model="newProgram.createdat" required>
        </div>
        <button type="submit" class="btn btn-primary">Create Program</button>
      </form>
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue';
  import axios from 'axios';
  import { useRouter } from 'vue-router';
  
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
    eventDate: '',
    isactive: '',
    createdby: '',
    createdat: ''
  });
  
  const router = useRouter();
  
  const createProgram = () => {
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
  