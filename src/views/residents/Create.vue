<template>
    <div class="container mt-4">

        <div class="card">
            <div class="card-header">
                <h4>Add Resident</h4>
            </div>
            <div class="card-body">

                <!-- <div class="form-group">
                    <label for="residentID">Resident ID:</label><br>
                    <input type="text" id="residentID" class="form-control" v-model="residentID">
                </div> -->
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
                    <select id="barangay" class="form-control" v-model="barangay">
                        <option value="Abangan Norte">Abangan Norte</option>
                        <option value="Abangan Sur">Abangan Sur</option>
                        <option value="Ibayo">Ibayo</option>
                        <option value="Lambakin">Lambakin</option>
                        <option value="Lias">Lias</option>
                        <option value="Loma de Gato">Loma de Gato</option>
                        <option value="Nagbalon">Nagbalon</option>
                        <option value="Patubig">Patubig</option>
                        <option value="Poblacion 1st">Poblacion 1st</option>
                        <option value="Poblacion 2nd">Poblacion 2nd</option>
                        <option value="Prenza 1st">Prenza 1st</option>
                        <option value="Prenza 2nd">Prenza 2nd</option>
                        <option value="Santa Rosa 1st">Santa Rosa 1st</option>
                        <option value="Santa Rosa 2nd">Santa Rosa 2nd</option>
                        <option value="Saog">Saog</option>
                        <option value="Tabing-ilog">Tabing-ilog</option>
                        <!-- Add more options as needed -->
                    </select>
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
import { useRouter } from 'vue-router';

const router = useRouter();

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
        precinctid: precinctID.value,
        lastname: myHash(lastName.value),
        firstname: myHash(firstName.value),
        middlename: middleName.value,
        addressline1: myHash(address.value),
        barangay: barangay.value,
        bday: birthday.value
    };

    console.log(newRecord);

    axios.post('https://rjprint10.com/marilaomis/backend/personapi.php', newRecord)
        .then(response => {
            console.log('Record saved successfully:', response.data);
            closeModal();
            router.push('/residents/view');
        })
        .catch(error => {
            console.error('Error saving record:', error);
        });
};

const closeModal = () => {

};


function myHash(text) {
    let base64Encoded = btoa(text);
    return base64Encoded;
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

<style>
/* Your CSS styles here */
</style>
