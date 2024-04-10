<template>
    <div class="container">
        <h1>Scanner Module</h1>
        <button @click="toggleCamera">{{ cameraActive ? 'Close Camera' : 'Open Camera' }}</button>
        <button @click="switchCameraSource">Switch Camera Source</button>

        <br>

        <label>Scanned output</label>
        <p style="font-weight: bolder; font-size: 48px;">{{ scannedOutput }}</p>
    </div>


</template>

<script setup>
import { ref, onBeforeUnmount } from 'vue';
import QrcodeVue from 'qrcode.vue';
import jsQR from 'jsqr';
import { useRouter } from 'vue-router';

const router = useRouter();

const inputValue = ref('');
const qrCodeData = ref('');
const scannedOutput = ref('');
const cameraActive = ref(false); // Track camera status
let videoElement = null; // Track video element
let currentCameraSource = 'environment'; // Track current camera source

const generateQR = () => {
    if (inputValue.value.trim() !== '') {
        qrCodeData.value = inputValue.value;
    } else {
        alert('Please enter a value.');
    }
};

const toggleCamera = async () => {
    if (!cameraActive.value) {
        try {
            const stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: currentCameraSource } });
            videoElement = document.createElement('video');
            document.body.appendChild(videoElement);
            videoElement.srcObject = stream;
            await videoElement.play();

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

// Automatically close the camera when changing routes
onBeforeUnmount(() => {
    if (cameraActive.value) {
        toggleCamera();
    }
});
</script>
