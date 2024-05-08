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
const residentID = ref('');
const precinctID = ref('');
const lastName = ref('');
const firstName = ref('');
const middleName = ref('');
const address = ref('');
const barangay = ref('');
const birthday = ref('');

//program scope
const program = ref([]);
const selectedBarangays = ref([]);


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
            if (residents.value === false) {
                alert("No Record Found.");
            }
            else {
                //add if resident address not in array
                // console.log(barangay.value)
                if (selectedBarangays.value.includes(barangay.value)) {
                    // alert("you belong with me");
                    await insertScan();
                }
                else {
                    alert("Resident doesn't belong in the Program.");
                }
                // Call an asynchronous function to insert scan data
            }
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
        barangay: barangay.value,
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
        const response = await axios.get(`https://rjprint10.com/marilaomis/backend/personapi.php?action=get_by_id&residentid=` + scannedOutput.value);
        residents.value = response.data;
        residentID.value = residents.value.residentid;
        precinctID.value = residents.value.precinctid;
        lastName.value = residents.value.lastname;
        firstName.value = residents.value.firstname;
        middleName.value = residents.value.middlename;
        address.value = residents.value.addressline1;
        barangay.value = residents.value.barangay;
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
    getProgram();
}),

    // Automatically close the camera when changing routes
    onBeforeUnmount(() => {
        if (cameraActive.value) {
            toggleCamera();
        }
    });



function getProgram() {
    axios.get(`https://rjprint10.com/marilaomis/backend/programapi.php?action=get_by_id&id=${route.params.programid}`)
        .then(response => {
            program.value = response.data;
            selectedBarangays.value = program.value.barangayscope.split(',').map(b => b.trim());
        })
        .catch(error => {
            console.error('Error fetching program data:', error);
        });
}
</script>
