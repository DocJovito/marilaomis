<template>
    <div class="container mt-4">

        <div class="card">
            <div class="card-header">
                <h4>Edit Resident</h4>
            </div>
            <div class="card-body">
                <form @submit.prevent="UpdateRecord">
                    <div class="form-group">
                        <label for="precintID">Precint ID:</label><br>
                        <input type="text" id="precintID" class="form-control" v-model="precintID">
                    </div>
                    <div class="form-group">
                        <label for="isMember">Is Member:</label><br>
                        <select id="isMember" class="form-control" v-model="isMember" required>
                            <option value="1">True</option>
                            <option value="0">False</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="lastName">Last Name:</label><br>
                        <input type="text" id="lastName" class="form-control" v-model="lastName" required>
                    </div>
                    <div class="form-group">
                        <label for="firstName">First Name:</label><br>
                        <input type="text" id="firstName" class="form-control" v-model="firstName" required>
                    </div>
                    <div class="form-group">
                        <label for="middleName">Middle Name:</label><br>
                        <input type="text" id="middleName" class="form-control" v-model="middleName">
                    </div>
                    <div class="form-group">
                        <label for="address">Address:</label><br>
                        <input type="text" id="address" class="form-control" v-model="address" required>
                    </div>
                    <div class="form-group">
                        <label for="barangay">Barangay:</label><br>
                        <select id="barangay" class="form-control" v-model="barangay" required>
                            <option value="ABANGAN NORTE">ABANGAN NORTE</option>
                            <option value="ABANGAN SUR">ABANGAN SUR</option>
                            <option value="IBAYO">IBAYO</option>
                            <option value="LAMBAKIN">LAMBAKIN</option>
                            <option value="LIAS">LIAS</option>
                            <option value="LOMA DE GATO">LOMA DE GATO</option>
                            <option value="NAGBALON">NAGBALON</option>
                            <option value="PATUBIG">PATUBIG</option>
                            <option value="POBLACION 1ST">POBLACION 1ST</option>
                            <option value="POBLACION 2ND">POBLACION 2ND</option>
                            <option value="PRENZA 1ST">PRENZA 1ST</option>
                            <option value="PRENZA 2ND">PRENZA 2ND</option>
                            <option value="SANTA ROSA 1ST">SANTA ROSA 1ST</option>
                            <option value="SANTA ROSA 2ND">SANTA ROSA 2ND</option>
                            <option value="SAOG">SAOG</option>
                            <option value="TABING-ILOG">TABING-ILOG</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="birthday">Birthday:</label><br>
                        <input type="date" id="birthday" class="form-control" v-model="birthday">
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
</template>


<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useRoute, useRouter } from 'vue-router'

const residents = ref([]);


const residentid = ref('');
const precintID = ref('');
const isMember = ref('');
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

// Define router object
const router = useRouter();

onMounted(() => {
    axios.get(`https://marilaomis.com/marilaomis/backend/personapi.php?action=get_by_id&residentid=` + residentid.value)
        .then((response) => {
            residents.value = response.data;
            residentid.value = residents.value.residentid;
            precintID.value = residents.value.precintid;
            isMember.value = residents.value.ismember;
            lastName.value = unHash(residents.value.lastname);
            firstName.value = unHash(residents.value.firstname);
            middleName.value = unHash(residents.value.middlename);
            address.value = unHash(residents.value.addressline1);
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
        precintid: precintID.value,
        ismember: isMember.value,
        lastname: myHash(lastName.value),
        firstname: myHash(firstName.value),
        middlename: myHash(middleName.value),
        addressline1: myHash(address.value),
        barangay: barangay.value,
        bday: birthday.value,

    };

    axios.post('https://marilaomis.com/marilaomis/backend/personapi.php', newRecord)
        .then(response => {
            console.log('Record saved successfully:', response.data);
            router.push('/residents/view');
        })
        .catch(error => {
            console.error('Error saving record:', error);
        });
};

function loadSelect() {

}

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
