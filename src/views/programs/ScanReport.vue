<template>
    <div class="container">
        <h1>Scan Reports</h1>
        <h2>Program ID: {{ programid }}</h2>
        <h2>Program Name: {{ programname }}</h2>

        <h2>Total Scanned: {{ scanResults.length }}</h2>
        <h2>Total Amount Distributed: {{ totalFund }}</h2>

        <!-- <p>search</p>
        <input type="text" v-model="searchQuery" placeholder="Enter Last Name or ID" class="form-control">
        <select v-model="selectedBarangay" class="form-control">
            <option value="">All</option>
            <option value="Barangay A">Barangay A</option>
            <option value="Barangay B">Barangay B</option>
            <option value="Barangay C">Barangay C</option>
        </select> -->


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
                        <th scope="col">Amount</th>
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
                        <td>{{ unHash(scanResult.lastname) }}, {{ unHash(scanResult.firstname) }}</td>
                        <td>{{ scanResult.barangay }}</td>
                        <td>{{ unHash(scanResult.addressline1) }}</td>
                        <td>{{ scanResult.budgetperhead }}</td>
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
const totalFund = ref();

// Fetch data function
const fetchData = () => {
    const qryData = {
        action: 'getscan',
        residentid: searchQuery.value,
        lastname: searchQuery.value,
        barangay: selectedBarangay.value,
        programid: programid.value,
    };
    axios.post('https://marilaomis.com/marilaomis/backend/scanapi.php', qryData)
        .then((response) => {
            scanResults.value = response.data;
            totalFund.value = 0;
            for (let index = 0; index < scanResults.value.length; index++) {
                if (scanResults.value[index].hasOwnProperty('budgetperhead')) {
                    totalFund.value += parseFloat(scanResults.value[index].budgetperhead);
                }
            }
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


function myHash($text) {
    $base64Encoded = base64_encode($text);
    return $base64Encoded;
}

function unHash(hashed) {
    try {
        return atob(hashed);
    } catch (e) {
        console.error('Error decoding from Base64:', e);
        return '';
    }
}
</script>
