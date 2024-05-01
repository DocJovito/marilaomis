<template>
    <div class="container">
        <h1>Scan Reports</h1>
        <h1>Program ID: {{ programid }}</h1>
        <h1>Program Name: {{ programname }}</h1>

        <h1>Total Scanned: {{ scanResult.length }}</h1>

        <p>search</p>
        <input type="text" v-model="searchQuery" placeholder="Enter Last Name or ID" class="form-control">
        <select v-model="selectedBarangay" class="form-control">
            <option value="">Select Barangay</option>
            <option value="barangay1">Barangay 1</option>
            <option value="barangay2">Barangay 2</option>
            <option value="barangay3">Barangay 3</option>
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
                        <th scope="col">Scanned By</th>
                        <th scope="col">Scan Date</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(scanResult, index) in scanResults" :key="scanResult.id">
                        <th scope="row">{{ index + 1 }}</th>
                        <td>{{ resident.id }}</td>
                        <td>{{ resident.programid }}</td>
                        <td>{{ resident.residentid }}</td>
                        <td>{{ resident.lastname }}, {{ resident.firstname }}</td>
                        <td>{{ resident.barangay }}</td>
                        <td>{{ resident.createdby }}</td>
                        <td>{{ resident.createdat }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>

</template>


<script setup>
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router'
import axios from 'axios';

//program variables
const programid = ref("");
const programname = ref("");
const route = useRoute();
programid.value = route.params.programid;
programname.value = route.params.programname;


const scanResults = ref([]);

onMounted(() => {
    const qryData = {
        action: 'getscan',
        residentid: residentID.value,
        lastname: lastName.value,
        barangay: firstName.value,
        programid: precinctID.value,
    };
    axios.post('https://rjprint10.com/marilaomis/backend/scanapi.php', qryData)
        .then((response) => {
            scanResults.value = response.data;
        })
        .catch((error) => {
            console.error('Error fetching data:', error);
        });
});

</script>
