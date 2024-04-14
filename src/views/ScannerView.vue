<template>
    <div class="container">
        <h1>Scanner Module</h1>
        <button @click="toggleCamera">{{ cameraActive ? 'Close Camera' : 'Open Camera' }}</button>
        <button @click="switchCameraSource">Switch Camera Source</button>
        <br>
        <label>Scanned output</label>
        <p style="font-weight: bolder; font-size: 48px;">{{ scannedOutput }}</p>
        <div id="vidsize"></div>
    </div>

</template>

<script setup>
import { ref, onBeforeUnmount, onMounted } from 'vue';
import jsQR from 'jsqr';

const scannedOutput = ref('');
const cameraActive = ref(false); // Track camera status
let videoElement = null; // Track video element
let currentCameraSource = 'environment'; // Track current camera source

const toggleCamera = async () => {
    if (!cameraActive.value) {
        try {
            const stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: currentCameraSource } });
            const vidmount = document.getElementById('vidsize');
            // Create the video element
            videoElement = document.createElement('video');
            // Append the video element to the vidmount div
            vidmount.appendChild(videoElement);
            // Set the video stream to the video element
            videoElement.srcObject = stream;
            await videoElement.play();
            // Center the vidmount div on the screen
            vidmount.style.position = 'fixed';
            vidmount.style.top = '50%';
            vidmount.style.left = '50%';
            vidmount.style.transform = 'translate(-50%, -50%)';


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
