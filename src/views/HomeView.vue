<script setup>
import { ref } from 'vue';
import TestComp from '@/components/TestComp.vue';
import QrScanner from '@/components/QrScanner.vue';
const inputValue = ref('');
</script>


<template>
  <main>
    <h1>Hello World Home</h1>
    <p>Parent to Child</p>
    <input v-model="inputValue">
    <TestComp :value="inputValue" />

    <hr>
    <QrScanner />


  </main>
</template>
