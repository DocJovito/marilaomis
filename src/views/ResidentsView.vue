<template>
  <table class="table table-hover">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">Resident ID</th>
        <th scope="col">Precinct ID</th>
        <th scope="col">Last Name</th>
        <th scope="col">First Name</th>
        <th scope="col">Middle Name</th>
        <th scope="col">Address</th>
        <th scope="col">Barangay</th>
        <th scope="col">Birthday</th>
        <th scope="col">Actions</th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="(resident, index) in residents" :key="resident.residentid">
        <th scope="row">{{ index + 1 }}</th>
        <td>{{ resident.residentid }}</td>
        <td>{{ resident.precinctid }}</td>
        <td>{{ resident.lastname }}</td>
        <td>{{ resident.firstname }}</td>
        <td>{{ resident.middlename }}</td>
        <td>{{ resident.addressline1 }}</td>
        <td>{{ resident.baranggay }}</td>
        <td>{{ resident.bday }}</td>
        <td>
          <button @click="selectResident(resident)">Select</button>
        </td>
      </tr>
    </tbody>
  </table>
</template>


<script setup>
import { ref, onMounted } from 'vue';

const residents = ref([]);

//select
const selectResident = (resident) => {
  console.log(resident);
};

onMounted(() => {
  fetch('http://localhost:3000/residents')
    .then((res) => res.json())
    .then((json) => (residents.value = json))
    .catch((err) => (console.log(err.message)))
});
</script>