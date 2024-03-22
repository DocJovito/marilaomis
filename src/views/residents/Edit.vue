<template>
    <div class="container mt-4">

        <div class="card">
            <div class="card-header">
                <h4>Edit Resident</h4>
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
                    <input type="text" id="barangay" class="form-control" v-model="baranggay">
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

const id = ref('');
const residentID = ref('');
const precinctID = ref('');
const lastName = ref('');
const firstName = ref('');
const middleName = ref('');
const address = ref('');
const baranggay = ref('');
const birthday = ref('');

//use route to get target id from params
const route = useRoute();
id.value = route.params.id;
console.log(route.params.id);

onMounted(() => {
    axios.get(`https://rjprint10.com/marilaomis/backend/personapi.php?action=get_by_id&id=` + id.value)
        .then((response) => {
            residents.value = response.data;
            id.value = residents.value.id;
            residentID.value = residents.value.residentid;
            precinctID.value = residents.value.precinctid;
            lastName.value = residents.value.lastname;
            firstName.value = residents.value.firstname;
            middleName.value = residents.value.middlename;
            address.value = residents.value.addressline1;
            baranggay.value = residents.value.baranggay;
            birthday.value = residents.value.bday;
        })
        .catch((error) => {
            console.error('Error fetching data:', error);
        });
});

const UpdateRecord = () => {
    const newRecord = {
        action: 'update',
        id: id.value,
        residentid: residentID.value,
        precinctid: precinctID.value,
        lastname: lastName.value,
        firstname: firstName.value,
        middlename: middleName.value,
        addressline1: address.value,
        baranggay: barangay.value,
        bday: birthday.value,
        id: id.value
    };

    axios.post('https://rjprint10.com/marilaomis/backend/personapi.php', newRecord)
        .then(response => {
            console.log('Record saved successfully:', response.data);
        })
        .catch(error => {
            console.error('Error saving record:', error);
        });
};
</script>

<style>
/* Your CSS styles here */
</style>
