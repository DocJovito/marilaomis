<template>
    <div>
        <label for="residentid">residentid</label>
        <input type="text" id="residentid" v-model="residentid" @input="handleInput">
        <p>Hashed: {{ hashedid }}</p>
        <p>Original: {{ originaltext }}</p>
    </div>
</template>

<script setup>
import { ref } from 'vue';

// Define reactive variables
const residentid = ref("");
const hashedid = ref("");
const originaltext = ref("");

// Simple reversible encoding function for demonstration
function myHash(text) {
    // Base64 encode the input text and slice to ensure it fits a fixed length of 14 characters
    let base64Encoded = btoa(text).slice(0, 14);
    // Pad with '=' to ensure it is exactly 14 characters long
    while (base64Encoded.length < 14) {
        base64Encoded += '=';
    }
    return base64Encoded;
}

// Function to convert the hashed (encoded) text back to the original text
function unHash(hashed) {
    // Remove any padding before decoding
    let unpaddedHash = hashed.replace(/=+$/, '');
    // Decode from Base64
    try {
        return atob(unpaddedHash);
    } catch (e) {
        console.error('Error decoding from Base64:', e);
        return '';
    }
}

// Function to handle input event for resident ID
function handleInput() {
    hashedid.value = myHash(residentid.value);
    originaltext.value = unHash(hashedid.value);
}

</script>