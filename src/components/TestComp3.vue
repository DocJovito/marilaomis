<script setup>
import { ref } from 'vue';
const inputValue = ref(1);
</script>

<template>
    <div>
        <h1>Hello TestComp 3 Child</h1>
        <button @click="$emit('someEvent', inputValue)">click me - eventWithParam</button>
        <button @click="$emit('eventWithoutParam')">click me - eventWithoutParam</button>
    </div>
</template>
