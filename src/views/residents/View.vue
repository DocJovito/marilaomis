<template>
    <div class="container mt-4">
        <p>Resident Management</p>
        <RouterLink to="/residents/create" class="btn btn-success ">Add Resident</RouterLink>

        <div class="table-responsive">
            <table class="table table-hover ">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Resident ID</th>
                        <th scope="col">Precinct ID</th>
                        <th scope="col">Last Name</th>
                        <th scope="col">First Name</th>
                        <th scope="col">Middle Name</th>
                        <th scope="col">Address</th>
                        <th scope="col">Barangay</th>
                        <th scope="col">Birthday</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(resident, index) in paginatedResidents" :key="resident.residentid">
                        <th scope="row">{{ (currentPage - 1) * pageSize + index + 1 }}</th>
                        <td>{{ resident.residentid }}</td>
                        <td>{{ resident.precinctid }}</td>
                        <td>{{ resident.lastname }}</td>
                        <td>{{ resident.firstname }}</td>
                        <td>{{ resident.middlename }}</td>
                        <td>{{ resident.addressline1 }}</td>
                        <td>{{ resident.barangay }}</td>
                        <td>{{ resident.bday }}</td>
                        <td>
                            <!-- <button @click="selectResident(resident)">Select</button> -->
                            <RouterLink :to="'/residents/' + resident.residentid + '/edit'" class="btn btn-success">
                                Edit
                            </RouterLink>
                            <RouterLink :to="'/residents/' + resident.residentid + '/idcard'" class="btn btn-primary">
                                Print
                            </RouterLink>
                            <RouterLink to="/" class="btn btn-danger" @:click="deleterec(resident.residentid)">Delete
                            </RouterLink>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <nav aria-label="Pagination" class="d-flex justify-content-center">
            <ul class="pagination">
                <li class="page-item" :class="{ disabled: currentPage === 1 }">
                    <button class="page-link" @click="currentPage -= 1">Previous</button>
                </li>
                <li class="page-item" v-for="pageNumber in totalPages" :key="pageNumber"
                    :class="{ active: pageNumber === currentPage }">
                    <button class="page-link" @click="currentPage = pageNumber">{{ pageNumber }}</button>
                </li>
                <li class="page-item" :class="{ disabled: currentPage === totalPages }">
                    <button class="page-link" @click="currentPage += 1">Next</button>
                </li>
            </ul>
        </nav>

        <button class="btn btn-success" @click="exportexcel">Export to Excel</button>
        <button class="btn btn-danger" @click="readexcel">Import From Excel</button>
    </div>



</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';
import * as XLSX from 'xlsx';
import { saveAs } from 'file-saver';

const targetid = ref('');
const targetRecord = ref('');
const exportrecords = ref([]);

const residents = ref([]);
const pageSize = 5;
const currentPage = ref(1);

const paginatedResidents = computed(() => {
    const startIndex = (currentPage.value - 1) * pageSize;
    return residents.value.slice(startIndex, startIndex + pageSize);
});

const totalPages = computed(() => Math.ceil(residents.value.length / pageSize));

//select
const selectResident = (resident) => {
    console.log(resident);
};

// onMounted(() => {
//   fetch('http://localhost:3000/residents')
//     // fetch('http://localhost:8080/marilaomis/backend/personapi.php?action=get_all')
//     .then((res) => res.json())
//     .then((json) => (residents.value = json))
//     .catch((err) => console.log(err.message));
// });

onMounted(() => {
    axios.get('https://rjprint10.com/marilaomis/backend/personapi.php?action=get_all')
        .then((response) => {
            residents.value = response.data;
        })
        .catch((error) => {
            console.error('Error fetching data:', error);
        });
});

//delete
function deleterec(targetid) {
    if (confirm("Are you sure you want to delete this data?")) {
        const targetRecord = {
            action: 'delete',
            residentid: targetid
        };
        axios.delete(`https://rjprint10.com/marilaomis/backend/personapi.php`, { data: targetRecord })
            .then(response => {
                console.log('Record Delete Successfully:', response.data);
                alert("Record Deleted");

            })
            .catch(error => {
                console.error('Error deleting record:', error);
            });
    }
}


function exportexcel() {
    const data = residents.value.map(resident => ({
        'Resident ID': resident.residentid,
        'Precinct ID': resident.precinctid,
        'Last Name': resident.lastname,
        'First Name': resident.firstname,
        'Middle Name': resident.middlename,
        'Address': resident.addressline1,
        'Barangay': resident.barangay,
        'Birthday': resident.bday
    }));

    const ws = XLSX.utils.json_to_sheet(data);
    const wb = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(wb, ws, 'Residents');

    const wbout = XLSX.write(wb, { bookType: 'xlsx', type: 'binary' });

    function s2ab(s) {
        const buf = new ArrayBuffer(s.length);
        const view = new Uint8Array(buf);
        for (let i = 0; i < s.length; i++) view[i] = s.charCodeAt(i) & 0xff;
        return buf;
    }

    saveAs(new Blob([s2ab(wbout)], { type: 'application/octet-stream' }), 'residents.xlsx');
}


function readexcel() {
    const input = document.createElement('input');
    input.type = 'file';
    input.accept = '.xlsx, .xls';
    input.addEventListener('change', handleFile);
    input.click();
}

function handleFile(event) {
    const file = event.target.files[0];
    const reader = new FileReader();
    reader.onload = (e) => {
        const data = new Uint8Array(e.target.result);
        const workbook = XLSX.read(data, { type: 'array' });
        const sheetName = workbook.SheetNames[0];
        const worksheet = workbook.Sheets[sheetName];
        const excelData = XLSX.utils.sheet_to_json(worksheet, { header: 1 });
        console.log('Excel data:', excelData);
    };
    reader.readAsArrayBuffer(file);
}


</script>

<style>
/* Add your custom styles here */
</style>