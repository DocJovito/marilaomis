<template>
    <div id="printable-container" class="container mt-4">

        <div class="row">
            <div class="card"
                style="width:408px; height:224px; background-image: url('https://png.pngtree.com/thumb_back/fh260/background/20210824/pngtree-yellow-green-background-stock-images-wallpaper-image_769660.jpg');">
                <div class="card-body">
                    <div class="row">
                        <!-- QR Code Section -->
                        <div class="col-4">
                            <div id="qr">
                                <img class="card-img-top" src="@/assets/images/greenmarilao.png" alt="MIS">
                                <div v-if="residentID" class="mt-2">
                                    <qrcode-vue :value="residentID" style="width: 120px; height: 120px;" />
                                </div>
                            </div>
                        </div>

                        <!-- Data Section -->
                        <div class="col-8">
                            <div id="data">
                                <h5>Marilao Resident ID</h5>
                                <p>residentID: {{ residentID }}</p>
                                <p>precinctID: {{ precinctID }}</p>
                                <p>lastName: {{ lastName }}</p>
                                <p>firstName: {{ firstName }}</p>
                                <p>middleName: {{ middleName }}</p>
                                <p>address: {{ address }}</p>
                                <p>barangay: {{ baranggay }}</p>
                                <p>birthday: {{ birthday }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>




            <div class="card" style="width:408px; height:224px;">
                <!-- <img class="card-img-top" src="@/assets/images/greenmarilao.png" alt="MIS"> -->
                <div class="card-body">
                    <h5 style="text-align: center;">ID Back</h5>
                    <ul>
                        <li>Lorem ipsum dolor sit amet consectetur adipisicing elit. Molestias?</li>
                        <li>Lorem ipsum dolor sit amet consectetur adipisicing elit. Molestias?</li>
                        <li>Lorem ipsum dolor sit amet consectetur adipisicing elit. Molestias?</li>
                        <li>Lorem ipsum dolor sit amet consectetur adipisicing elit. Molestias?</li>
                        <li>Lorem ipsum dolor sit amet consectetur adipisicing elit. Molestias?</li>
                        <li>Lorem ipsum dolor sit amet consectetur adipisicing elit. Molestias?</li>
                        <li>Lorem ipsum dolor sit amet consectetur adipisicing elit. Molestias?</li>
                        <li>Lorem ipsum dolor sit amet consectetur adipisicing elit. Molestias?</li>
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
