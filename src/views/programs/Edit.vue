<template>
    <div class="container mt-4">
      <div class="card">
        <div class="card-header">
          <h4>Edit Program</h4>
        </div>
        <div class="card-body">
          <div class="form-group">
            <label for="programName">Program Name:</label><br>
            <input type="text" id="programName" class="form-control" v-model="programName">
          </div>
          <div class="form-group">
            <label for="description">Description:</label><br>
            <textarea id="description" class="form-control" rows="3" v-model="description"></textarea>
          </div>
          <div class="form-group">
            <label for="barangayScope">Barangay Scope:</label><br>
            <input type="text" id="barangayScope" class="form-control" v-model="barangayScope">
          </div>
          <div class="form-group">
            <label for="eventDate">Event Date:</label><br>
            <input type="date" id="eventDate" class="form-control" v-model="eventDate">
          </div>
          <div class="form-group">
            <label for="isActive">Is Active:</label><br>
            <input type="checkbox" id="isActive" v-model="isActive" true-value="1" false-value="0">
        </div>

          <!-- Add more form fields for other program details as needed -->
        </div>
      </div>
      <button type="button" class="btn btn-primary" @click="updateProgram">Update</button>
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue';
  import axios from 'axios';
  import { useRoute, useRouter } from 'vue-router';
  
  const id = ref('');
  const programName = ref('');
  const description = ref('');
  const barangayScope = ref('');
  const eventDate = ref('');
  const isActive = ref(false); // Assuming isActive is a boolean
  
  const route = useRoute();
  id.value = route.params.id;
  
  const router = useRouter();
  
  onMounted(() => {
    axios.get(`https://rjprint10.com/marilaomis/backend/programapi.php?action=get_by_id&id=` + id.value)
      .then(response => {
        const program = response.data;
        programName.value = program.programname;
        description.value = program.description;
        barangayScope.value = program.barangayscope;
        eventDate.value = program.eventDate;
        isActive.value = program.isactive;
        // Assign other program details to respective variables
      })
      .catch(error => {
        console.error('Error fetching program data:', error);
      });
  });
  
  const updateProgram = () => {
    const updatedProgram = {
      action: 'update',
      id: id.value,
      programname: programName.value,
      description: description.value,
      barangayscope: barangayScope.value,
      eventDate: eventDate.value,
      isactive: isActive.value,
      id: id.value
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
  </script>
  
  <style>
  /* Your CSS styles here */
  </style>
  