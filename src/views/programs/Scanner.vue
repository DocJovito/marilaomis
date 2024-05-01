<template>
    <div class="container">
        <h1>Program ID: {{ programid }}</h1>
        <h1>Program Name: {{ programname }}</h1>

        <button @click="toggleCamera">{{ cameraActive ? 'Close Camera' : 'Open Camera' }}</button>
        <button @click="switchCameraSource">Switch Camera Source</button>
        <br>
        <label>Scanned output</label>
        <p style="font-weight: bolder; font-size: 48px;">{{ scannedOutput }}</p>
        <div id="vidsize"></div>
        <!-- <img id="" class=".img-fluid" src="/src/assets/images/greenmarilao.png" alt=""> -->
    </div>

</template>

<script setup>
import { ref, onBeforeUnmount, onMounted, watch } from 'vue';
import jsQR from 'jsqr';
import { useRoute } from 'vue-router'
import axios from 'axios';

//program variables
const programid = ref("");
const programname = ref("");
const route = useRoute();
programid.value = route.params.programid;
programname.value = route.params.programname;


//resident variables
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


//userid
const createdby = ref(123);


const scannedOutput = ref('');
const cameraActive = ref(false); // Track camera status
let videoElement = null; // Track video element
let currentCameraSource = 'environment'; // Track current camera source

watch(scannedOutput, async (newValue, oldValue) => {
    if (newValue != "") {

        try {
            // alert(`Scanned value changed from ${oldValue} to ${newValue}`);
            // Wait for getResident to finish before proceeding
            await getResident();

            //add if resident count = 0 then dont insert

            // Call an asynchronous function to insert scan data
            await insertScan();

        } catch (error) {
            console.error('Error:', error);
            // Handle the error (e.g., show a message to the user)

        }
    }
});

async function insertScan() {
    const newRecord = {
        action: 'create',
        id: "p" + programid.value + "r" + residentID.value,
        programid: programid.value,
        residentid: residentID.value,
        barangay: baranggay.value,
        createdby: createdby.value, //user
    };

    try {
        const response = await axios.post('https://rjprint10.com/marilaomis/backend/scanapi.php', newRecord);
        console.log('Record saved successfully:', response.data);
        // closeModal();
    } catch (error) {
        // console.error('Error saving record:', error);
    }
}

async function getResident() {
    try {
        const response = await axios.get(`https://rjprint10.com/marilaomis/backend/personapi.php?action=get_by_rid&id=` + scannedOutput.value);
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
    } catch (error) {
        console.error('Error fetching data:', error);

    }
}


const toggleCamera = async () => {
    if (!cameraActive.value) {
        try {
            const stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: currentCameraSource } });
            const vidmount = document.getElementById('vidsize');
            videoElement = document.createElement('video');
            vidmount.appendChild(videoElement);
            videoElement.srcObject = stream;

            videoElement.addEventListener('loadedmetadata', () => {
                videoElement.play();
                videoElement.width = vidmount.offsetWidth;

                const canvas = document.createElement('canvas');
                const context = canvas.getContext('2d');
                canvas.width = videoElement.videoWidth;
                canvas.height = videoElement.videoHeight;

                const scanQR = () => {
                    context.drawImage(videoElement, 0, 0, canvas.width, canvas.height);
                    const imageData = context.getImageData(0, 0, canvas.width, canvas.height);
                    const code = jsQR(imageData.data, imageData.width, imageData.height, {
                        inversionAttempts: 'dontInvert',
                    });

                    if (code) {
                        scannedOutput.value = code.data;
                    }

                    if (cameraActive.value) {
                        requestAnimationFrame(scanQR);
                    }
                };

                cameraActive.value = true;
                scanQR();
            });

        } catch (error) {
            console.error('Error accessing camera:', error);
        }
    } else {
        cameraActive.value = false;
        if (videoElement) {
            videoElement.srcObject.getTracks().forEach(track => track.stop());
            videoElement.remove();
        }
    }
};

const switchCameraSource = () => {
    currentCameraSource = currentCameraSource === 'environment' ? 'user' : 'environment';
    if (cameraActive.value) {
        toggleCamera();
        setTimeout(toggleCamera, 100);
    }
};

onMounted(() => {
    toggleCamera();
}),

    // Automatically close the camera when changing routes
    onBeforeUnmount(() => {
        if (cameraActive.value) {
            toggleCamera();
        }
    });
</script>
