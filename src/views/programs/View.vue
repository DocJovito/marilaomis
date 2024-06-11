<template>
  <div class="container mt-4">
    <h4>Program Management</h4>
    <!-- <RouterLink to="/programs/create" class="btn btn-success">Create Program</RouterLink> -->

    <RouterLink v-if="isAllowedForProgram" to="/programs/create" class="btn btn-success">
      Create Program
    </RouterLink>
    <div class="table-responsive">
      <table class="table table-hover">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">Program ID</th>
            <th scope="col">Program Name</th>
            <!-- <th scope="col">Description</th> -->
            <th scope="col">Barangay Scope</th>
            <th scope="col">budgetperhead</th>
            <th scope="col">ismember</th>
            <th scope="col">Event Date</th>
            <!-- <th scope="col">Is Active</th>
            <th scope="col">Created By</th>
            <th scope="col">Created At</th> -->
            <th scope="col">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(program, index) in paginatedPrograms" :key="program.programid">
            <th scope="row">{{ (currentPage - 1) * pageSize + index + 1 }}</th>
            <td>{{ program.programid }}</td>
            <td>{{ program.programname }}</td>
            <!-- <td>{{ program.description }}</td> -->
            <td>{{ program.barangayscope }}</td>
            <td>{{ program.budgetperhead }}</td>
            <td>{{ program.ismember }}</td>
            <td>{{ program.eventDate }}</td>
            <!-- <td>{{ program.isactive }}</td>
            <td>{{ program.createdby }}</td>
            <td>{{ program.createdat }}</td> -->
            <td>
              <RouterLink :to="'/programs/' + program.programid + '/' + program.programname + '/Scanner'"
                class="btn btn-success ctr">Open Scanner</RouterLink>
              <br>
              <RouterLink :to="'/programs/' + program.programid + '/' + program.programname + '/ScanReport'"
                class="btn btn-warning ctr">Scanned List</RouterLink>
              <br>
              <!-- <RouterLink :to="'/programs/' + program.programid + '/edit'"
                            class="btn btn-primary ctr">Edit</RouterLink>
                <button class="btn btn-danger ctr" @click="deleteProgram(program.programid)">Delete</button> -->
              <RouterLink v-if="isAllowedForProgram" :to="'/programs/' + program.programid + '/edit'"
                class="btn btn-primary ctr">
                Edit Program
              </RouterLink>
              <!-- <button v-if="isAllowedForProgram" class="btn btn-danger ctr" @click="deleteProgram(program.programid)">
                Delete
              </button> -->
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <nav aria-label="Pagination" class="d-flex justify-content-center">
      <ul class="pagination">
        <li class="page-item" :class="{ disabled: currentPage === 1 }">
          <button class="page-link" @click="changePage(currentPage - 1)">Previous</button>
        </li>
        <li class="page-item" v-for="pageNumber in totalPages" :key="pageNumber"
          :class="{ active: pageNumber === currentPage }">
          <button class="page-link" @click="changePage(pageNumber)">{{ pageNumber }}</button>
        </li>
        <li class="page-item" :class="{ disabled: currentPage === totalPages }">
          <button class="page-link" @click="changePage(currentPage + 1)">Next</button>
        </li>
      </ul>
    </nav>

    <button class="btn btn-success" @click="exportExcel">Export to Excel</button>
    <button class="btn btn-danger" @click="importExcel">Import From Excel</button>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';
import * as XLSX from 'xlsx';
import { saveAs } from 'file-saver';
import { useStore } from 'vuex';
import { RouterLink } from 'vue-router';

const store = useStore();

// Computed property to check if the user is allowed to create a program
const isAllowedForProgram = computed(() => {
  return store.state.user.userType !== 'Area Leader';
});

// Ensure userState is initialized and has at least one element
const userState = computed(() => store.state.user.userState);
const address = computed(() => store.state.user.address);

const programs = ref([]);
const pageSize = 5;
const currentPage = ref(1);

const paginatedPrograms = computed(() => {
  const startIndex = (currentPage.value - 1) * pageSize;
  return programs.value.slice(startIndex, startIndex + pageSize);
});

const totalPages = computed(() => Math.ceil(programs.value.length / pageSize));

onMounted(() => {
  fetchPrograms();
});

const fetchPrograms = () => {
  const data = {
    action: 'get_programs',
    barangayscope: address.value,
  };
  axios.post('https://marilaomis.com/marilaomis/backend/programapi.php', data)
    .then((response) => {
      programs.value = response.data;
    })
    .catch((error) => {
      console.error('Error fetching data:', error);
    });
};

const deleteProgram = (programId) => {
  if (confirm("Are you sure you want to delete this program?")) {
    const targetRecord = {
      action: 'delete',
      id: programId,
    };
    axios.delete('https://marilaomis.com/marilaomis/backend/programapi.php', { data: targetRecord })
      .then(() => {
        programs.value = programs.value.filter(program => program.programid !== programId);
        console.log('Program deleted successfully');
        alert("Program Deleted");
      })
      .catch((error) => {
        console.error('Error deleting program:', error);
      });
  }
};

const exportExcel = () => {
  const data = programs.value.map(program => ({
    'Program ID': program.programid,
    'Program Name': program.programname,
    'Description': program.description,
    'Barangay Scope': program.barangayscope,
    'Event Date': program.eventDate,
    'Is Active': program.isactive,
    'Created By': program.createdby,
    'Created At': program.createdat,
  }));

  const ws = XLSX.utils.json_to_sheet(data);
  const wb = XLSX.utils.book_new();
  XLSX.utils.book_append_sheet(wb, ws, 'Programs');

  const wbout = XLSX.write(wb, { bookType: 'xlsx', type: 'binary' });

  function s2ab(s) {
    const buf = new ArrayBuffer(s.length);
    const view = new Uint8Array(buf);
    for (let i = 0; i < s.length; i++) view[i] = s.charCodeAt(i) & 0xff;
    return buf;
  }

  saveAs(new Blob([s2ab(wbout)], { type: 'application/octet-stream' }), 'programs.xlsx');
};

const importExcel = () => {
  const input = document.createElement('input');
  input.type = 'file';
  input.accept = '.xlsx, .xls';
  input.addEventListener('change', handleFile);
  input.click();
};

const handleFile = (event) => {
  const file = event.target.files[0];
  const reader = new FileReader();
  reader.onload = (e) => {
    const data = new Uint8Array(e.target.result);
    const workbook = XLSX.read(data, { type: 'array' });
    const sheetName = workbook.SheetNames[0];
    const worksheet = workbook.Sheets[sheetName];
    const excelData = XLSX.utils.sheet_to_json(worksheet, { header: 1 });
    console.log('Excel data:', excelData);
    // Process the imported data as needed
  };
  reader.readAsArrayBuffer(file);
};

const changePage = (pageNumber) => {
  if (pageNumber > 0 && pageNumber <= totalPages.value) {
    currentPage.value = pageNumber;
  }
};
</script>

<style scoped>
.ctr {
  width: 130px;
}

.bg-greenish {
  background-color: #8dc63f;
}

.active-link {
  font-weight: bold;
}

/* Add your custom styles here */
</style>