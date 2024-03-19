<script setup>
import TestComp2 from '@/components/TestComp2.vue';
import TestComp3 from '@/components/TestComp3.vue';
import { ref } from 'vue';

const value = ref('');
const value2 = ref(2);


function addev(inputValue) {

  value2.value = value2.value + inputValue;
}

function updateValue(inputValue) {

  value.value = inputValue;
}

function tryevent() {
  value2.value++;
}


</script>

<template>
  <main>
    <h1>Hello World About</h1>
    <p>Child to Parent</p>
    <p>{{ value }}</p>
    <p>{{ value2 }}</p>
    <TestComp2 @change-value="updateValue" />
    <TestComp3 @some-event="addev" @event-without-param="tryevent" />
  </main>
</template>
