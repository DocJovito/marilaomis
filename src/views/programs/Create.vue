<template>
    <div class="container mt-4">

        <div class="card">
            <div class="card-header">
                <h4>Add Resident</h4>
            </div>
            <div class="card-body">

                <div class="form-group">
                    <label for="residentID">Resident ID:</label><br>
                    <input type="text" id="residentID" class="form-control" v-model="residentID">
                </div>
                <div class="form-group">
                    <label for="precinctID">Precinct ID:</label><br>
                    <input type="text" id="precinctID" class="form-control" v-model="precinctID">
                </div>
                <div class="form-group">
                    <label for="lastName">Last Name:</label><br>
                    <input type="text" id="lastName" class="form-control" v-model="lastName">
                </div>
                <div class="form-group">
                    <label for="firstName">First Name:</label><br>
                    <input type="text" id="firstName" class="form-control" v-model="firstName">
                </div>
                <div class="form-group">
                    <label for="middleName">Middle Name:</label><br>
                    <input type="text" id="middleName" class="form-control" v-model="middleName">
                </div>
                <div class="form-group">
                    <label for="address">Address:</label><br>
                    <input type="text" id="address" class="form-control" v-model="address">
                </div>
                <div class="form-group">
                    <label for="barangay">Barangay:</label><br>
                    <input type="text" id="barangay" class="form-control" v-model="barangay">
                </div>
                <div class="form-group">
                    <label for="birthday">Birthday:</label><br>
                    <input type="date" id="birthday" class="form-control" v-model="birthday">
                </div>
            </div>
        </div>

        <button type="button" class="btn btn-primary" @click="saveRecord">Save</button>

    </div>

</template>


<script setup>
import { ref } from 'vue';
import axios from 'axios';

const residentID = ref('');
const precinctID = ref('');
const lastName = ref('');
const firstName = ref('');
const middleName = ref('');
const address = ref('');
const barangay = ref('');
const birthday = ref('');

const saveRecord = () => {
    const newRecord = {
        action: 'create',
        residentid: residentID.value,
        precinctid: precinctID.value,
        lastname: lastName.value,
        firstname: firstName.value,
        middlename: middleName.value,
        addressline1: address.value,
        baranggay: barangay.value,
        bday: birthday.value
    };

    console.log(newRecord);

    axios.post('https://rjprint10.com/marilaomis/backend/personapi.php', newRecord)
        .then(response => {
            console.log('Record saved successfully:', response.data);
            closeModal();
        })
        .catch(error => {
            console.error('Error saving record:', error);
        });
};

const closeModal = () => {

};
</script>

<style>
/* Your CSS styles here */
</style>
