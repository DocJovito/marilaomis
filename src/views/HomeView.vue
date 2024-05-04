<script setup>
import { ref } from 'vue';
import TestComp from '@/components/TestComp.vue';
import QrScanner from '@/components/QrScanner.vue';



const inputValue = ref('');

</script>


<template>
  <main>
    <h1>Home</h1>


    <!-- <p>Parent to Child</p>
    <input v-model="inputValue">
    <TestComp :value="inputValue" />
    <button @click="nextpage">load next page</button> -->

  </main>
</template>
