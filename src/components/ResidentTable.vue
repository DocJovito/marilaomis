<template>
    <div>
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
                <tr v-for="(resident, index) in paginatedResidents" :key="resident.residentid">
                    <th scope="row">{{ (currentPage - 1) * pageSize + index + 1 }}</th>
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

        <nav aria-label="Pagination" class="d-flex justify-content-center">
            <ul class="pagination">
                <li class="page-item" :class="{ disabled: currentPage === 1 }">
                    <button class="page-link" @click="currentPage -= 1">Previous</button>
                </li>
                <li class="page-item" v-for="pageNumber in totalPages" :key="pageNumber"
                    :class="{ active: pageNumber === currentPage }">
                    <button class="page-link" @click="currentPage = pageNumber">{{ pageNumber }}</button>
                </li>
                <li class="page-item" :class="{ disabled: currentPage === totalPages }">
                    <button class="page-link" @click="currentPage += 1">Next</button>
                </li>
            </ul>
        </nav>
    </div>


</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';

const residents = ref([]);
const pageSize = 5;
const currentPage = ref(1);

const paginatedResidents = computed(() => {
    const startIndex = (currentPage.value - 1) * pageSize;
    return residents.value.slice(startIndex, startIndex + pageSize);
});

const totalPages = computed(() => Math.ceil(residents.value.length / pageSize));

//select
const selectResident = (resident) => {
    console.log(resident);
};

// onMounted(() => {
//   fetch('http://localhost:3000/residents')
//     // fetch('http://localhost:8080/marilaomis/backend/personapi.php?action=get_all')
//     .then((res) => res.json())
//     .then((json) => (residents.value = json))
//     .catch((err) => console.log(err.message));
// });

onMounted(() => {
    axios.get('https://rjprint10.com/marilaomis/backend/personapi.php?action=get_all')
        .then((response) => {
            residents.value = response.data;
        })
        .catch((error) => {
            console.error('Error fetching data:', error);
        });
});
</script>

<style>
/* Add your custom styles here */
</style>