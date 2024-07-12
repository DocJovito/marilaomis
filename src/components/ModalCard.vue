<template>
    <div class="container">
        <div class="row">
            <div class="card" style="width:325px; height:206px;">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
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
                            <div id="data" class="mx-1">
                                <p>Resident Number: {{ residentID }}</p>
                                <p>Precint ID: {{ precintID }}</p>
                                <p>Last Name: {{ unHash(lastName) }}</p>
                                <p>First Name: {{ unHash(firstName) }}</p>
                                <p>Middle Name: {{ unHash(middleName) }}</p>
                                <p>Address: {{ unHash(address) }}</p>
                                <p>Barangay: {{ barangay }}</p>
                                <p>Birthday: {{ birthday }}</p>
                                <!-- <p>Member: {{ isMember }}</p> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card"
                style="width:325px; height:206px; background-image: url('https://png.pngtree.com/thumb_back/fh260/background/20210824/pngtree-yellow-green-background-stock-images-wallpaper-image_769660.jpg');">
                <div class="card-body" style="display: flex; align-items: center; justify-content: center;">
                    <img src="@/assets/images/greenmarilao.png" alt="MIS" style="width:90%; height:90%;">
                </div>
            </div>
        </div>
    </div>

</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { defineEmits } from 'vue';
import { defineProps } from 'vue';
import QrcodeVue from 'qrcode.vue';

const props = defineProps({
    residentIDParam: {
        type: String,
        required: true,
    },
});



const emit = defineEmits(['toggle-nav']);

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
const isMember = ref('');

const hResidentID = ref('');

residentID.value = props.residentIDParam;

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
            isMember.value = residents.value.ismember == 1 ? 'Yes' : 'No';

            hResidentID.value = myhash(residents.value.residentid);
        })
        .catch((error) => {
            console.error('Error fetching data:', error);
        });
});


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
    font-size: 11px;
    margin: 0;
}

li {
    font-size: 10px;
    margin: 0;
}

h5 {
    font-size: 18px;
}


@media print {
    body * {
        visibility: hidden;
    }

    .print-modal {
        visibility: visible;
        position: absolute;
        top: 0;
        left: 0;
    }


    .print-hide {
        display: none;
    }

}
</style>
