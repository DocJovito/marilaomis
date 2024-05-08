<template>
  <main>
    <div class="container">
      <div class="jumbotron text-center">
        <h1 class="display-4">Marilao Municipality MIS</h1>
        <p class="lead">Welcome to the Marilao Municipality Management Information System.</p>
        <hr class="my-4">
        <p>This system provides comprehensive management and analysis tools for various municipal operations and services.</p>
        <a class="btn btn-primary btn-lg" href="#" role="button">Get Started</a>
      </div>

      <div class="row feature-box">
        <div class="col-md-4">
          <div class="card text-center mb-4">
            <div class="card-body">
              <i class="fas fa-users feature-icon mb-3"></i>
              <h3 class="feature-title">Residents</h3>
              <p class="feature-description">Access information about the residents of Marilao Municipality.</p>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card text-center mb-4">
            <div class="card-body">
              <i class="fas fa-calendar-alt feature-icon mb-3"></i>
              <h3 class="feature-title">Program</h3>
              <p class="feature-description">Explore various programs and initiatives organized by the municipality.</p>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card text-center mb-4">
            <div class="card-body">
              <i class="fas fa-id-card feature-icon mb-3"></i>
              <h3 class="feature-title">Resident ID</h3>
              <p class="feature-description">Generate or manage resident identification cards.</p>
            </div>
          </div>
        </div>
      </div>

      <div class="row justify-content-center mt-4">
        <button class="btn btn-primary btn-lg">Scan ID for Program</button>
      </div>
    </div>
  </main>
</template>

<script setup>
  import { ref } from 'vue';
  import TestComp from '@/components/TestComp.vue';
  import QrScanner from '@/components/QrScanner.vue';

  const inputValue = ref('');
</script>

<style scoped>
  .jumbotron {
    background-color: #b7dab8; /* Green background color */
    color: #024110; /* White text color */
    padding: 100px 0;
    border-radius: 15px;
  }

  .jumbotron h1 {
    font-weight: bold;
    font-size: 3.5rem;
    margin-bottom: 20px;
  }

  .jumbotron p {
    font-size: 1.5rem;
    margin-bottom: 30px;
  }

  .jumbotron a {
    font-size: 1.2rem;
  }

  .feature-box .card {
    border: none;
    transition: all 0.3s ease;
  }

  .feature-box .card:hover {
    transform: translateY(-5px);
    box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.1);
  }

  .feature-icon {
    font-size: 3rem;
    color: #007bff;
  }

  .feature-title {
    font-size: 1.5rem;
    margin-bottom: 20px;
  }

  .feature-description {
    font-size: 1.1rem;
    color: #6c757d;
  }
</style>
