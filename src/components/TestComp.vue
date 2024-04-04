<script setup>
const props = defineProps(['value']);
</script>

<template>
  <div>
    <h1>Hello TestComp Child</h1>
    <p>{{ value }}</p>
  </div>
</template>