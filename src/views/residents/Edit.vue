<template>
    <div class="container mt-4">

        <div class="card">
            <div class="card-header">
                <h4>Edit Resident</h4>
            </div>
            <div class="card-body">

                <div class="form-group">
                    <label for="residentID">Resident ID:</label><br>
                    <input type="text" id="residentID" class="form-control" v-model="residents.residentID">
                </div>
                <div class="form-group">
                    <label for="precinctID">Precinct ID:</label><br>
                    <input type="text" id="precinctID" class="form-control" v-model="residents.precinctID">
                </div>
                <div class="form-group">
                    <label for="lastName">Last Name:</label><br>
                    <input type="text" id="lastName" class="form-control" v-model="residents.lastName">
                </div>
                <div class="form-group">
                    <label for="firstName">First Name:</label><br>
                    <input type="text" id="firstName" class="form-control" v-model="residents.firstName">
                </div>
                <div class="form-group">
                    <label for="middleName">Middle Name:</label><br>
                    <input type="text" id="middleName" class="form-control" v-model="residents.middleName">
                </div>
                <div class="form-group">
                    <label for="address">Address:</label><br>
                    <input type="text" id="address" class="form-control" v-model="residents.address">
                </div>
                <div class="form-group">
                    <label for="barangay">Barangay:</label><br>
                    <input type="text" id="barangay" class="form-control" v-model="residents.barangay">
                </div>
                <div class="form-group">
                    <label for="birthday">Birthday:</label><br>
                    <input type="date" id="birthday" class="form-control" v-model="residents.birthday">
                </div>
            </div>
        </div>

        <button type="button" class="btn btn-primary" @click="saveRecord">Save</button>

    </div>

</template>


<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const residents = ref([]);

const residentID = ref('');
const precinctID = ref('');
const lastName = ref('');
const firstName = ref('');
const middleName = ref('');
const address = ref('');
const barangay = ref('');
const birthday = ref('');


onMounted(() => {
    axios.get('https://rjprint10.com/marilaomis/backend/personapi.php?action=get_by_id&id=3')
        .then((response) => {
            residents.value = response.data;
            console.log("This is the value")
            console.log(residents.value);
            console.log("The value of resident.lastname is " + residents.lastName);
        })
        .catch((error) => {
            console.error('Error fetching data:', error);
        });
});

// const saveRecord = () => {
//     const newRecord = {
//         action: 'create',
//         residentid: residentID.value,
//         precinctid: precinctID.value,
//         lastname: lastName.value,
//         firstname: firstName.value,
//         middlename: middleName.value,
//         addressline1: address.value,
//         baranggay: barangay.value,
//         bday: birthday.value
//     };

//     console.log(newRecord);

//     axios.post('https://rjprint10.com/marilaomis/backend/personapi.php', newRecord)
//         .then(response => {
//             console.log('Record saved successfully:', response.data);
//             closeModal();
//         })
//         .catch(error => {
//             console.error('Error saving record:', error);
//         });
// };

// const closeModal = () => {

// };
</script>

<style>
/* Your CSS styles here */
</style>
