<template>
    <div id="printable-container" class="container mt-4">

        <div class="row">
            <div class="card"
                style="width:350px; height:220px; background-image: url('https://png.pngtree.com/thumb_back/fh260/background/20210824/pngtree-yellow-green-background-stock-images-wallpaper-image_769660.jpg');">
                <div class="card-body">
                    <div class="row">
                        <div class="col-3">
                            <img src="@/assets/images/greenmarilao.png" alt="MIS" style="width:100%; height:100%;">
                        </div>
                        <div class="col-8">
                            <h5 style="display: inline-block; vertical-align: top;">Marilao Resident ID</h5>
                        </div>
                    </div>
                    <div class="row">
                        <!-- QR Code Section -->
                        <div class="col-5">
                            <div id="qr">

                                <div v-if="residentID" class="mt-2">
                                    <qrcode-vue :value="hResidentID" style="width: 120px; height: 120px;" />
                                </div>
                            </div>
                        </div>
                        <!-- Data Section -->
                        <div class="col-7">
                            <div id="data">
                                <p>Resident Number: {{ residentID }}</p>
                                <p>precintID: {{ precintID }}</p>
                                <p>lastName: {{ unHash(lastName) }}</p>
                                <p>firstName: {{ unHash(firstName) }}</p>
                                <p>middleName: {{ unHash(middleName) }}</p>
                                <p>address: {{ unHash(address) }}</p>
                                <p>barangay: {{ barangay }}</p>
                                <p>birthday: {{ birthday }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>




            <div class="card" style="width:350px; height:220px;">
                <!-- <img class="card-img-top" src="@/assets/images/greenmarilao.png" alt="MIS"> -->
                <div class="card-body">
                    <h5 style="text-align: center;">ID Back</h5>
                    <ul>
                        <li>Lorem ipsum dolor sit amet consectetur</li>
                        <li>Lorem ipsum dolor sit amet consectetur</li>
                        <li>Lorem ipsum dolor sit amet consectetur</li>
                        <li>Lorem ipsum dolor sit amet consectetur</li>
                        <li>Lorem ipsum dolor sit amet consectetur</li>
                        <li>Lorem ipsum dolor sit amet consectetur</li>
                        <li>Lorem ipsum dolor sit amet consectetur</li>
                        <li>Lorem ipsum dolor sit amet consectetur</li>
                    </ul>
                    <p style="text-align: center;" class="mt-4">Signature</p>
                </div>

            </div>
        </div>



    </div>

    <br>
    <div style="text-align: center;">
        <button type="button" class="btn btn-primary" @click="printID">Print</button>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useRoute } from 'vue-router';
import QrcodeVue from 'qrcode.vue';

const residents = ref([]);

const id = ref('');
const residentID = ref('');
const precintID = ref('');
const lastName = ref('');
const firstName = ref('');
const middleName = ref('');
const address = ref('');
const barangay = ref('');
const birthday = ref('');

const hResidentID = ref('');

//use route to get target id from params
const route = useRoute();
residentID.value = route.params.residentid;
console.log(route.params.residentid);

onMounted(() => {
    axios.get(`https://marilaomis.com/marilaomis/backend/personapi.php?action=get_by_id&residentid=` + residentID.value)
        .then((response) => {
            residents.value = response.data;
            id.value = residents.value.id;
            residentID.value = residents.value.residentid;
            precintID.value = residents.value.precintid;
            lastName.value = residents.value.lastname;
            firstName.value = residents.value.firstname;
            middleName.value = residents.value.middlename;
            address.value = residents.value.addressline1;
            barangay.value = residents.value.barangay;
            birthday.value = residents.value.bday;

            hResidentID.value = myhash(residents.value.residentid);
        })
        .catch((error) => {
            console.error('Error fetching data:', error);
        });
});

function printID() {
    window.print();
}

// function printID() {
//     const printableContainer = document.getElementById('printable-container');
//     const originalContents = document.body.innerHTML;
//     const printContents = printableContainer.innerHTML;

//     document.body.innerHTML = printContents;
//     window.print();
//     document.body.innerHTML = originalContents;
// }

function myhash(text) {
    let base64Encoded = btoa(text).slice(0, 14);
    while (base64Encoded.length < 14) {
        base64Encoded += '=';
    }
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

<style scoped>
p {
    font-size: 10px;
    margin: 0;
}

li {
    font-size: 10px;
    margin: 0;
}

/* Additional styles as needed */
</style>
