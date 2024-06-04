<template>
    <div class="container mt-4">
      <h2 style="color: lightgreen;">Dashboard</h2>
      
      <!-- Summary Statistics -->
      <div class="row mb-4">
        <div class="col-sm-3">
          <div class="stat-card bg-light">
            <h3>Total Funds Allocated</h3>
            <p>{{ summary?.totalFundsAllocated }}</p>
          </div>
        </div>
        <div class="col-sm-3">
          <div class="stat-card bg-light">
            <h3>Total Funds Available</h3>
            <p>{{ summary?.totalFundsAvailable }}</p>
          </div>
        </div>
        <div class="col-sm-3">
          <div class="stat-card bg-light">
            <h3>Active Programs</h3>
            <p>{{ summary?.activePrograms }}</p>
          </div>
        </div>
        <div class="col-sm-3">
          <div class="stat-card bg-light">
            <h3>Users</h3>
            <p>{{ summary?.users }}</p>
          </div>
        </div>
      </div>
      
      <!-- Recent Activity -->
      <div class="row mb-4">
        <div class="col-sm-6">
          <h4>Recent Fund Allocations</h4>
          <table class="table table-hover">
            <!-- Table structure for recent fund allocations -->
          </table>
        </div>
        <div class="col-sm-6">
          <h4>Recent Edits</h4>
          <table class="table table-hover">
            <!-- Table structure for recent edits -->
          </table>
        </div>
      </div>
      
      <!-- Charts (using Chart.js) -->
      <div class="row">
        <div class="col-sm-6">
          <h4>Fund Allocation by Program</h4>
          <canvas id="allocationByProgramChart"></canvas>
        </div>
        <div class="col-sm-6">
          <h4>Fund Allocation Over Time</h4>
          <canvas id="allocationOverTimeChart"></canvas>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue';
  import axios from 'axios';
  import Chart from 'chart.js/auto';
  
  const summary = ref(null);
  
  const fetchData = async () => {
      try {
          const response = await axios.get('https://rjprint10.com/marilaomis/backend/dashboard.php');
          const data = response.data;
  
          summary.value = data.summary;
  
          if (data.charts) {
              renderCharts(data.charts);
          }
      } catch (error) {
          console.error('Error fetching dashboard data:', error);
      }
  };
  
  const renderCharts = (chartData) => {
      // Render charts
  };
  
  onMounted(() => {
      fetchData();
  });
  </script>
  
  <style scoped>
  .stat-card {
    background: lightgreen;
    border-radius: 5px;
    padding: 20px;
    text-align: center;
    margin-bottom: 20px;
  }
  
  .bg-light {
    background-color: #f8f9fa;
  }
  </style>
  