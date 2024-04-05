<template>
    <div class="container">
        <h1>Scanner Module</h1>

        <label for="inputValue">Enter value:</label>
        <input type="text" id="inputValue" v-model="inputValue" />
        <button @click="generateQR">Generate QR</button>

        <div v-if="qrCodeData" class="mt-4">
            <qrcode-vue :value="qrCodeData" :size="150" :bg-color="'#ffffff'" />
        </div>

        <button @click="openCamera">Open Camera</button>

        <label>Scanned output</label>
        <input type="text" v-model="scannedOutput" />
    </div>
</template>

<script setup>
import { ref } from 'vue';
import QrcodeVue from 'qrcode.vue';
import jsQR from 'jsqr';

const inputValue = ref('');
const qrCodeData = ref('');
const scannedOutput = ref('');

const generateQR = () => {
    if (inputValue.value.trim() !== '') {
        qrCodeData.value = inputValue.value;
    } else {
        alert('Please enter a value.');
    }
};

const openCamera = async () => {
    try {
        const stream = await navigator.mediaDevices.getUserMedia({ video: true });
        const video = document.createElement('video');
        document.body.appendChild(video);
        video.srcObject = stream;
        await video.play();

        const canvas = document.createElement('canvas');
        const context = canvas.getContext('2d');
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;

        const scanQR = () => {
            context.drawImage(video, 0, 0, canvas.width, canvas.height);
            const imageData = context.getImageData(0, 0, canvas.width, canvas.height);
            const code = jsQR(imageData.data, imageData.width, imageData.height, {
                inversionAttempts: 'dontInvert',
            });

            if (code) {
                scannedOutput.value = code.data;
            }

            requestAnimationFrame(scanQR);
        };

        scanQR();
    } catch (error) {
        console.error('Error accessing camera:', error);
    }
};
</script>

<style>
/* Add any custom styles here */
</style>
