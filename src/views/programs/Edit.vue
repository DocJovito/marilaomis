<template>
  <div class="container mt-4">
    <div class="card">
      <div class="card-header">
        <h4>Edit Program</h4>

      </div>
      <div class="card-body">
        <form @submit.prevent="updateProgram">
          <div class="form-group">
            <label for="programName">Program Name:</label><br>
            <input type="text" id="programName" class="form-control" v-model="program.programname" required>
          </div>
          <div class="form-group">
            <label for="description">Description:</label><br>
            <textarea id="description" class="form-control" rows="3" v-model="program.description" required></textarea>
          </div>
          <div class="form-group">
            <label for="barangayScope">Barangay Scope:</label><br>
            <div class="row">
              <div class="col-md-3" v-for="barangay in barangays" :key="barangay" required>
                <label>
                  <input type="checkbox" :value="barangay" v-model="selectedBarangays"> {{ barangay }}
                </label>
              </div>
            </div>
          </div>
          <div class="mb-3">
            <label for="budgetPerHead" class="form-label">Budget Per Head</label>
            <input type="number" class="form-control" id="budgetPerHead" v-model="program.budgetperhead" required>
          </div>
          <div class="form-group">
            <label for="ismember">Is Member:</label><br>
            <select id="ismember" class="form-control" v-model="program.ismember" required>
              <option value="1">True</option>
              <option value="0">False</option>
            </select>
          </div>
          <div class="form-group">
            <label for="eventDate">Event Date:</label><br>
            <input type="date" id="eventDate" class="form-control" v-model="program.eventDate" required>
          </div>
          <div class="form-group">
            <label for="isActive">Is Active:</label><br>
            <input type="checkbox" id="isActive" class="form-check-input" v-model="program.isactive" true-value="1"
              false-value="0">
          </div>
          <button type="submit" class="btn btn-primary">Update Program</button>
        </form>
      </div>

    </div>

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
  budgetperhead: '',
  ismember: '',
  eventDate: '',
  isactive: false,
});
const selectedBarangays = ref([]);

const route = useRoute();
id.value = route.params.id;

const router = useRouter();

onMounted(() => {
  axios.get(`https://marilaomis.com/marilaomis/backend/programapi.php?action=get_by_id&id=${id.value}`)
    .then(response => {
      program.value = response.data;
      selectedBarangays.value = program.value.barangayscope.split(',').map(b => b.trim());
    })
    .catch(error => {
      console.error('Error fetching program data:', error);
    });
});

function updateProgram() {
  const updatedProgram = {
    action: 'update',
    id: id.value,
    programname: program.value.programname,
    description: program.value.description,
    barangayscope: selectedBarangays.value.join(', '), // Concatenate selected barangays
    budgetperhead: program.value.budgetperhead,
    ismember: program.value.ismember,
    eventDate: program.value.eventDate,
    isactive: program.value.isactive,
  };

  axios.post('https://marilaomis.com/marilaomis/backend/programapi.php', updatedProgram)
    .then(response => {
      console.log('Program updated successfully:', response.data);
      // alert('Program updated successfully');
      router.push('/programs/view'); // Redirect to programs list after successful update
    })
    .catch(error => {
      console.error('Error updating program:', error);
      alert('Failed to update program. Please try again.');
    });
}

// List of barangays
const barangays = [
  "ABANGAN NORTE",
  "ABANGAN SUR",
  "IBAYO",
  "LAMBAKIN",
  "LIAS",
  "LOMA DE GATO",
  "NAGBALON",
  "PATUBIG",
  "POBLACION 1ST",
  "POBLACION 2ND",
  "PRENZA 1ST",
  "PRENZA 2ND",
  "SANTA ROSA 1ST",
  "SANTA ROSA 2ND",
  "SAOG",
  "TABING-ILOG"
];
</script>

<style scoped>
/* Your CSS styles here */
.form-group {
  margin-bottom: 1rem;
}
</style>
