<script setup>
import ResidentTable from '@/components/ResidentTable.vue';
import ResidentModalForm from '@/components/ResidentModalForm.vue';
</script>

<template>

  <div class="container">
    <div class="d-flex justify-content-end">
      <ResidentModalForm />
    </div>
    <ResidentTable />
  </div>
</template>
