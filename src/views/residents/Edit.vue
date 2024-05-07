<template>
    <div class="container mt-4">

        <div class="card">
            <div class="card-header">
                <h4>Edit Resident</h4>
            </div>
            <div class="card-body">
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
                <button type="button" class="btn btn-primary" @click="UpdateRecord">Update</button>
            </div>
        </div>
    </div>
</template>


<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useRoute } from 'vue-router'

const residents = ref([]);


const residentid = ref('');
const precinctID = ref('');
const lastName = ref('');
const firstName = ref('');
const middleName = ref('');
const address = ref('');
const barangay = ref('');
const birthday = ref('');

//use route to get target id from params
const route = useRoute();
residentid.value = route.params.residentid;
console.log(route.params.residentid);

onMounted(() => {
    axios.get(`https://rjprint10.com/marilaomis/backend/personapi.php?action=get_by_id&residentid=` + residentid.value)
        .then((response) => {
            residents.value = response.data;
            residentid.value = residents.value.residentid;
            precinctID.value = residents.value.precinctid;
            lastName.value = residents.value.lastname;
            firstName.value = residents.value.firstname;
            middleName.value = residents.value.middlename;
            address.value = residents.value.addressline1;
            barangay.value = residents.value.barangay;
            birthday.value = residents.value.bday;
        })
        .catch((error) => {
            console.error('Error fetching data:', error);
        });
});

const UpdateRecord = () => {
    const newRecord = {
        action: 'update',
        residentid: residentid.value,
        precinctid: precinctID.value,
        lastname: lastName.value,
        firstname: firstName.value,
        middlename: middleName.value,
        addressline1: address.value,
        barangay: barangay.value,
        bday: birthday.value,

    };

    axios.post('https://rjprint10.com/marilaomis/backend/personapi.php', newRecord)
        .then(response => {
            console.log('Record saved successfully:', response.data);
        })
        .catch(error => {
            console.error('Error saving record:', error);
        });
};

function loadSelect() {

}
</script>

<style>
/* Your CSS styles here */
</style>
