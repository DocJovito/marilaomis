<template>
  <div class="container mt-4">
    <h2 style="color: lightgreen;">Dashboard</h2>

    <!-- Summary Statistics -->
    <div class="row mb-4">
      <div class="col-lg-3 col-md-6">
        <div class="card bg-light">
          <div class="card-body">
            <h5 class="card-title">Total Funds Allocated</h5>
            <p class="card-text">{{ summary?.totalFundsAllocated }}</p>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-6">
        <div class="card bg-light">
          <div class="card-body">
            <h5 class="card-title">Total Funds Available</h5>
            <p class="card-text">{{ summary?.totalFundsAvailable }}</p>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-6">
        <div class="card bg-light">
          <div class="card-body">
            <h5 class="card-title">Active Programs</h5>
            <p class="card-text">{{ summary?.activePrograms }}</p>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-6">
        <div class="card bg-light">
          <div class="card-body">
            <h5 class="card-title">Total Users</h5>
            <p class="card-text">{{ summary?.users }}</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Charts -->
    <div class="row mb-4">
      <div class="col-lg-6">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Fund Allocation by Program</h5>
            <canvas id="allocationByProgramChart"></canvas>
          </div>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Fund Allocation Over Time</h5>
            <canvas id="allocationOverTimeChart"></canvas>
          </div>
        </div>
      </div>
    </div>

    <!-- Recent Activity -->
    <div class="row">
      <div class="col-lg-6">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Recent Fund Allocations</h5>
            <div class="table-responsive">
              <table class="table table-hover">
                <!-- Table structure for recent fund allocations -->
              </table>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Recent Edits</h5>
            <div class="table-responsive">
              <table class="table table-hover">
                <!-- Table structure for recent edits -->
              </table>
            </div>
          </div>
        </div>
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
    const response = await axios.get('https://marilaomis.com/marilaomis/backend/dashboard.php');
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
.card {
  margin-bottom: 20px;
}
</style>