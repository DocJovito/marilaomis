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
    <div class="container">
      <div class="row">
        <div class="col-md-8 offset-md-2">
          <div class="card mt-5">
            <div class="card-body">
              <h2 class="card-title text-center">About Marilao Municipality MIS</h2>
              <hr>
              <p class="card-text">The Marilao Municipality Management Information System (MIS) is a digital platform designed to streamline various administrative and operational processes within the municipality. It provides a centralized system for managing resident information, tracking municipal programs and initiatives, generating resident identification cards, and more.</p>
              <p class="card-text">Our mission is to enhance efficiency, transparency, and accountability in municipal governance through the effective use of technology. By leveraging the MIS, we aim to improve service delivery, facilitate citizen engagement, and promote sustainable development in Marilao Municipality.</p>
              <p class="card-text">For inquiries or feedback, please contact us at <a href="mailto:info@marilao-municipality-mis.com">info@marilao-municipality-mis.com</a>.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
</template>

<style scoped>
  .card {
    margin-top: 20px;
    border-radius: 15px;
  }

  .card-title {
    font-size: 2.5rem;
    margin-bottom: 20px;
  }

  .card-text {
    font-size: 1.2rem;
    line-height: 1.6;
  }
</style>
