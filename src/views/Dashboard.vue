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
                <thead>
                  <tr>
                    <th>Fund ID</th>
                    <th>Amount</th>
                    <th>User ID</th>
                    <th>Created At</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="allocation in recentAllocations" :key="allocation.fundid">
                    <td>{{ allocation.fundid }}</td>
                    <td>{{ allocation.amount }}</td>
                    <td>{{ allocation.userid }}</td>
                    <td>{{ allocation.createdat }}</td>
                  </tr>
                </tbody>
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
                <thead>
                  <tr>
                    <th>Fund ID</th>
                    <th>Amount</th>
                    <th>Edited By</th>
                    <th>Edited At</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="edit in recentEdits" :key="edit.fundid">
                    <td>{{ edit.fundid }}</td>
                    <td>{{ edit.amount }}</td>
                    <td>{{ edit.editedby }}</td>
                    <td>{{ edit.editedat }}</td>
                  </tr>
                </tbody>
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
const recentAllocations = ref([]);
const recentEdits = ref([]);

const fetchData = async () => {
  try {
    const response = await axios.get('https://marilaomis.com/marilaomis/backend/dashboard.php');
    const data = response.data;

    summary.value = data.summary;
    recentAllocations.value = data.recentAllocations;
    recentEdits.value = data.recentEdits;

    if (data.charts) {
      renderCharts(data.charts);
    }
  } catch (error) {
    console.error('Error fetching dashboard data:', error);
  }
};

const renderCharts = (chartData) => {
  const allocationByProgramCtx = document.getElementById('allocationByProgramChart').getContext('2d');
  new Chart(allocationByProgramCtx, {
    type: 'bar',
    data: {
      labels: chartData.programs.labels,
      datasets: [{
        label: 'Fund Allocation by Program',
        data: chartData.programs.data,
        backgroundColor: 'rgba(75, 192, 192, 0.2)',
        borderColor: 'rgba(75, 192, 192, 1)',
        borderWidth: 1
      }]
    },
    options: {
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
  });

  const allocationOverTimeCtx = document.getElementById('allocationOverTimeChart').getContext('2d');
  new Chart(allocationOverTimeCtx, {
    type: 'line',
    data: {
      labels: chartData.time.labels,
      datasets: [{
        label: 'Fund Allocation Over Time',
        data: chartData.time.data,
        backgroundColor: 'rgba(153, 102, 255, 0.2)',
        borderColor: 'rgba(153, 102, 255, 1)',
        borderWidth: 1
      }]
    },
    options: {
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
  });
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
