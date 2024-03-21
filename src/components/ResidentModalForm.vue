<template>
    <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
        Add New Resident
    </button>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Resident Info</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div>
                        <div class="form-group">
                            <label for="residentID">Resident ID:</label><br>
                            <input type="text" id="residentID" maxlength="10" class="form-control" v-model="residentID">
                        </div>
                        <div class="form-group">
                            <label for="precinctID">Precinct ID:</label><br>
                            <input type="text" id="precinctID" maxlength="10" class="form-control" v-model="precinctID">
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
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" @click="saveRecord">Save</button>
                </div>
            </div>
        </div>
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

    axios.post('https://rjprint10.com/marilaomis/backend/personapi.php', newRecord)
        .then(response => {
            console.log('Record saved successfully:', response.data);
            console.log(newRecord);
            console.log(response.data);
            // Optionally, you can close the modal or show a success message here
            closeModal();
        })
        .catch(error => {
            console.error('Error saving record:', error);
        });
};

const closeModal = () => {
    // Function to close the modal
    // You can implement this based on your modal library or framework
};
</script>

<style>
/* Your CSS styles here */
</style>
