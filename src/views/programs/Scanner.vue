<template>
  <div class="container">
    <!-- <h3>Program ID: {{ programid }}</h3> -->
    <h3>Program Name: {{ programname }}</h3>
    <h3>Program Budget per Head: {{ program.budgetperhead }}</h3>
    <h3 v-if="ismember">For members only: Yes</h3>
    <h3 v-else>For members only:: No</h3>

    <button @click="toggleCamera">{{ cameraActive ? 'Close Camera' : 'Open Camera' }}</button>
    <button @click="switchCameraSource">Switch Camera Source</button>
    <br>
    <!-- <label>Scanned output</label> -->
    <!-- <p style="font-weight: bolder; font-size: 48px;">{{ scannedOutput }}</p> -->
    <p style="font-weight: bolder; font-size: 48px;">{{ messageResult }}</p>
    <div id="vidsize"></div>
  </div>
</template>

<script setup>
import { ref, computed, onBeforeUnmount, onMounted, watch } from 'vue';
import jsQR from 'jsqr';
import { useRoute } from 'vue-router';
import axios from 'axios';
import { useStore } from 'vuex';

const store = useStore();
const userId = computed(() => store.state.user.id); // Correct use of computed

//output variable
const messageResult = ref('Scan QR');

// Program variables
const programid = ref('');
const programname = ref('');
const budgetperhead = ref('');
const route = useRoute();
programid.value = route.params.programid || '';
programname.value = route.params.programname || '';
const pismember = ref('');

// Resident variables
const residents = ref([]);
const residentID = ref('');
const precinctID = ref('');
const lastName = ref('');
const firstName = ref('');
const middleName = ref('');
const address = ref('');
const barangay = ref('');
const birthday = ref('');
const ismember = ref('');

// Program scope
const program = ref([]);
const selectedBarangays = ref([]);

// User ID
const createdby = ref(userId.value);

const scannedOutput = ref('');
const cameraActive = ref(false); // Track camera status
let videoElement = null; // Track video element
let currentCameraSource = 'environment'; // Track current camera source

watch(scannedOutput, async (newValue) => {
  if (newValue) {
    try {
      await getResident();
      if (residents.value.length === 0) {
        messageResult.value = 'No Record Found.';
      } else {
        // Convert selectedBarangays and barangay to uppercase
        const upperSelectedBarangays = selectedBarangays.value.map(b => b.toUpperCase());
        const upperBarangay = barangay.value.toUpperCase();

        if (!upperSelectedBarangays.includes(upperBarangay)) {
          messageResult.value = "Resident doesn't belong in the Program.";
        } else if (pismember.value == 1 && ismember.value == 0) {
          messageResult.value = "The program is for Members Only.";
        } else {
          await insertScan();
        }
      }
    } catch (error) {
      console.error('Error:', error);
    }
  }
});

async function insertScan() {
  const newRecord = {
    action: 'create',
    id: `p${programid.value}r${residentID.value}`,
    programid: programid.value,
    residentid: residentID.value,
    barangay: barangay.value,
    createdby: createdby.value,
    budgetperhead: budgetperhead.value,
  };

  try {
    const response = await axios.post('https://marilaomis.com/marilaomis/backend/scanapi.php', newRecord);
    // console.log('Record saved successfully:', response.data);
    messageResult.value = (response.data + unHash(lastName.value));
    // alert(response.data + " \nResident Name:" + unHash(lastName.value));
  } catch (error) {
    // console.error('aaaError saving record:', error);
  }
}


async function getResident() {
  try {
    const response = await axios.get(`https://marilaomis.com/marilaomis/backend/personapi.php?action=get_by_id&residentid=${scannedOutput.value}`);
    if (response.data) {
      const residentData = response.data;
      residents.value = [residentData];
      residentID.value = residentData.residentid;
      precinctID.value = residentData.precinctid;
      lastName.value = residentData.lastname;
      firstName.value = residentData.firstname;
      middleName.value = residentData.middlename;
      address.value = residentData.addressline1;
      barangay.value = residentData.barangay;
      birthday.value = residentData.bday;
      ismember.value = residentData.ismember;
    } else {
      residents.value = [];
    }
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
        //const context = canvas.getContext('2d');              
        const context = canvas.getContext('2d', { willReadFrequently: true });
        canvas.width = videoElement.videoWidth;
        canvas.height = videoElement.videoHeight;

        const scanQR = () => {
          context.drawImage(videoElement, 0, 0, canvas.width, canvas.height);
          const imageData = context.getImageData(0, 0, canvas.width, canvas.height);
          const code = jsQR(imageData.data, imageData.width, imageData.height, {
            inversionAttempts: 'dontInvert',
          });

          if (code) {
            scannedOutput.value = unHash(code.data);
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
      videoElement.srcObject.getTracks().forEach((track) => track.stop());
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
});

onBeforeUnmount(() => {
  if (cameraActive.value) {
    toggleCamera();
  }
});

async function getProgram() {
  try {
    const response = await axios.get(`https://marilaomis.com/marilaomis/backend/programapi.php?action=get_by_id&id=${route.params.programid}`);
    if (response.data) {
      const programData = response.data;
      program.value = programData;
      selectedBarangays.value = programData.barangayscope.split(',').map((b) => b.trim());
      budgetperhead.value = programData.budgetperhead;
      pismember.value = programData.ismember;
    } else {
      program.value = {};
      selectedBarangays.value = [];
    }
  } catch (error) {
    console.error('Error fetching program data:', error);
  }
}

function unHash(hashed) {
  const unpaddedHash = hashed.replace(/=+$/, '');
  try {
    return atob(unpaddedHash);
  } catch (e) {
    console.error('Error decoding from Base64:', e);
    return '';
  }
}


</script>

<style scoped>
/* Your styles here */
</style>