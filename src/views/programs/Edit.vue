<template>
  <div class="container mt-4">
    <div class="card">
      <div class="card-header">
        <h4>Edit Program</h4>
      </div>
      <div class="card-body">
        <div class="form-group">
          <label for="programName">Program Name:</label><br>
          <input type="text" id="programName" class="form-control" v-model="program.programname">
        </div>
        <div class="form-group">
          <label for="description">Description:</label><br>
          <textarea id="description" class="form-control" rows="3" v-model="program.description"></textarea>
        </div>
        <div class="form-group">
          <label for="barangayScope">Barangay Scope:</label><br>
          <div class="dropdown">
            <button class="btn btn-success dropdown-toggle" type="button" id="barangayDropdown" data-bs-toggle="dropdown" aria-expanded="false">
              Select
            </button>
            <ul class="dropdown-menu" aria-labelledby="barangayDropdown">
              <li v-for="barangay in barangays" :key="barangay">
                <label>
                  <input type="checkbox" :value="barangay" v-model="selectedBarangays"> {{ barangay }}
                </label>
              </li>
            </ul>
          </div>
          <label for="selectedBarangays" class="form-label">Selected Barangays:</label>
          <div id="selectedBarangays">
            {{ selectedBarangays.join(', ') }}
          </div>
        </div>
        <div class="form-group">
          <label for="eventDate">Event Date:</label><br>
          <input type="date" id="eventDate" class="form-control" v-model="program.eventDate">
        </div>
        <div class="form-group">
          <label for="isActive">Is Active:</label><br>
          <input type="checkbox" id="isActive" class="form-check-input" v-model="program.isactive" true-value="1" false-value="0">
        </div>
        <!-- Add more form fields for other program details as needed -->
      </div>
    </div>
    <button type="button" class="btn btn-primary mt-3" @click="updateProgram">Update Program</button>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useRoute, useRouter } from 'vue-router';

const id = ref('');
const program = ref({
  programname: '',
  description: '',
  barangayscope: '',
  eventDate: '',
  isactive: false,
});
const selectedBarangays = ref([]);

const route = useRoute();
id.value = route.params.id;

const router = useRouter();

onMounted(() => {
  axios.get(`https://rjprint10.com/marilaomis/backend/programapi.php?action=get_by_id&id=${id.value}`)
    .then(response => {
      program.value = response.data;
      selectedBarangays.value = program.value.barangayscope.split(', ');
    })
    .catch(error => {
      console.error('Error fetching program data:', error);
    });
});

const updateProgram = () => {
  const updatedProgram = {
    action: 'update',
    id: id.value,
    programname: program.value.programname,
    description: program.value.description,
    barangayscope: selectedBarangays.value.join(', '), // Concatenate selected barangays
    eventDate: program.value.eventDate,
    isactive: program.value.isactive,
  };

  axios.post('https://rjprint10.com/marilaomis/backend/programapi.php', updatedProgram)
    .then(response => {
      console.log('Program updated successfully:', response.data);
      alert('Program updated successfully');
      router.push('/programs/view'); // Redirect to programs list after successful update
    })
    .catch(error => {
      console.error('Error updating program:', error);
      alert('Failed to update program. Please try again.');
    });
};

// You can define the list of barangays here
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
</script>

<style>
/* Your CSS styles here */
</style>
