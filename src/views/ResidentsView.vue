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
const residents = [
  {
    residentid: 1,
    precinctid: 101,
    lastname: 'Doe',
    firstname: 'John',
    middlename: 'Smith',
    addressline1: '123 Main St',
    baranggay: 'Barangay A',
    bday: '1990-01-01'
  },
  {
    residentid: 2,
    precinctid: 102,
    lastname: 'Smith',
    firstname: 'Jane',
    middlename: 'Doe',
    addressline1: '456 Elm St',
    baranggay: 'Barangay B',
    bday: '1985-05-15'
  },
  {
    residentid: 3,
    precinctid: 103,
    lastname: 'Johnson',
    firstname: 'Robert',
    middlename: 'Johnson',
    addressline1: '789 Oak St',
    baranggay: 'Barangay C',
    bday: '1978-11-30'
  }
];

// const data = ref(null)

// fetch('...')
//   .then((res) => res.json())
//   .then((json) => (data.value = json))
//   .catch((err) => (console.log(err.message)))


const selectResident = (resident) => {
  console.log(resident);
};
</script>