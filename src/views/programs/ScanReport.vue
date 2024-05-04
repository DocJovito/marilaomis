<template>
    <div class="container">
        <h1>Scan Reports</h1>
        <h1>Program ID: {{ programid }}</h1>
        <h1>Program Name: {{ programname }}</h1>

        <h1>Total Scanned: {{ scanResults.length }}</h1>

        <p>search</p>
        <input type="text" v-model="searchQuery" placeholder="Enter Last Name or ID" class="form-control">
        <select v-model="selectedBarangay" class="form-control">
            <option value="">All</option>
            <option value="Barangay A">Barangay A</option>
            <option value="Barangay B">Barangay B</option>
            <option value="Barangay C">Barangay C</option>
            <!-- Add more barangay options as needed -->
        </select>


        <div class="table-responsive">
            <table class="table table-hover ">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Scan ID</th>
                        <th scope="col">Program ID</th>
                        <th scope="col">Resident ID</th>
                        <th scope="col">Resident Name</th>
                        <th scope="col">Barangay</th>
                        <th scope="col">Address</th>
                        <th scope="col">Scanned By</th>
                        <th scope="col">Scan Date</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(scanResult, index) in scanResults" :key="scanResult.id">
                        <th scope="row">{{ index + 1 }}</th>
                        <td>{{ scanResult.id }}</td>
                        <td>{{ scanResult.programid }}</td>
                        <td>{{ scanResult.residentid }}</td>
                        <td>{{ scanResult.lastname }}, {{ scanResult.firstname }}</td>
                        <td>{{ scanResult.barangay }}</td>
                        <td>{{ scanResult.addressline1 }}</td>
                        <td>{{ scanResult.createdby }}</td>
                        <td>{{ scanResult.createdat }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>

</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import { useRoute } from 'vue-router'
import axios from 'axios';

// Program variables
const programid = ref("");
const programname = ref("");
const residentID = ref("");
const lastName = ref("");
const searchQuery = ref(""); // Added searchQuery variable
const selectedBarangay = ref("");

const route = useRoute();
programid.value = route.params.programid;
programname.value = route.params.programname;

const scanResults = ref([]);

// Fetch data function
const fetchData = () => {
    const qryData = {
        action: 'getscan',
        residentid: searchQuery.value,
        lastname: searchQuery.value,
        barangay: selectedBarangay.value,
        programid: programid.value,
    };
    axios.post('https://rjprint10.com/marilaomis/backend/scanapi.php', qryData)
        .then((response) => {
            scanResults.value = response.data;
        })
        .catch((error) => {
            console.error('Error fetching data:', error);
        });
};

// Watch for changes in searchQuery and selectedBarangay, and call fetchData on change
watch([searchQuery, selectedBarangay], () => {
    fetchData();

});

// Call fetchData on component mount
onMounted(() => {
    fetchData();
});
</script>
