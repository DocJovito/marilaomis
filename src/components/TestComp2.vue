<script setup>
import { ref } from 'vue';

const inputValue = ref('');

</script>

<template>
    <div>
        <h1>Hello TestComp 2</h1>
        <input v-model="inputValue" @input="$emit('changeValue', inputValue)">



    </div>
</template>
