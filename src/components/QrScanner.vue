<template>
    <div class="container">
        <h1>Scanner Module</h1>

        <label for="inputValue">Enter value:</label>
        <input type="text" id="inputValue" v-model="inputValue" />
        <button @click="generateQR">Generate QR</button>

        <div v-if="qrCodeData">
            <qrcode-vue :value="qrCodeData" :size="150" :bg-color="'#ffffff'" />
        </div>

        <button @click="openCamera">Open Camera</button>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import QrcodeVue from 'qrcode.vue';
import jsQR from 'jsqr';

const inputValue = ref('');
const qrCodeData = ref('');

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
        video.srcObject = stream;
        video.setAttribute('playsinline', 'true');
        video.play();

        const canvas = document.createElement('canvas');
        const ctx = canvas.getContext('2d');
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;

        const scanQRCode = () => {
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
            const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
            const code = jsQR(imageData.data, imageData.width, imageData.height);
            if (code) {
                qrCodeData.value = code.data;
                stream.getTracks().forEach((track) => track.stop());
                video.remove();
                canvas.remove();
            } else {
                requestAnimationFrame(scanQRCode);
            }
        };

        video.addEventListener('loadedmetadata', scanQRCode);
        document.body.appendChild(video);
        document.body.appendChild(canvas);
    } catch (error) {
        console.error('Error accessing camera:', error);
    }
};
</script>

<style>
/* Add any custom styles here */
</style>