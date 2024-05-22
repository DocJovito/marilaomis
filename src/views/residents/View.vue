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
                        <td>{{ unHash(resident.lastname) }}</td>
                        <td>{{ unHash(resident.firstname) }}</td>
                        <td>{{ resident.middlename }}</td>
                        <td>{{ unHash(resident.addressline1) }}</td>
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
                            <RouterLink to="/residents/view" class="btn btn-danger"
                                @:click="deleterec(resident.residentid)">Delete
                            </RouterLink>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>


        <nav aria-label="Pagination" class="d-flex justify-content-center">
            <ul class="pagination">
                <li class="page-item" :class="{ disabled: currentPage === 1 }">
                    <button class="page-link" @click="currentPage = 1">First Page</button>
                </li>
                <li class="page-item" :class="{ disabled: currentPage === 1 }">
                    <button class="page-link" @click="currentPage -= 1">Previous</button>
                </li>
                <li class="page-item" :class="{ disabled: currentPage === totalPages }">
                    <button class="page-link" @click="currentPage += 1">Next</button>
                </li>
                <li class="page-item" :class="{ disabled: currentPage === totalPages }">
                    <button class="page-link" @click="currentPage = totalPages">Last Page</button>
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
import { useStore } from 'vuex';

const targetid = ref('');
const targetRecord = ref('');
const exportrecords = ref([]);

const residents = ref([]);
const pageSize = 5;
const currentPage = ref(1);

const store = useStore();
const address = computed(() => store.state.user.address);

const paginatedResidents = computed(() => {
    const startIndex = (currentPage.value - 1) * pageSize;
    return residents.value.slice(startIndex, startIndex + pageSize);
});

const totalPages = computed(() => Math.ceil(residents.value.length / pageSize));

//select
const fetchResident = () => {
    const data = {
        action: 'get_resident',
        barangayscope: address.value,
    };
    axios.post('https://rjprint10.com/marilaomis/backend/personapi.php', data)
        .then((response) => {
            residents.value = response.data;
        })
        .catch((error) => {
            console.error('Error fetching data:', error);
        });
};

onMounted(() => {
    fetchResident();
});

//delete
function deleterec(targetid) {
    if (confirm("Are you sure you want to delete this data?")) {
        const data = {
            action: 'delete',
            residentid: targetid,
        };
        axios.post(`https://rjprint10.com/marilaomis/backend/personapi.php`, data)
            .then(response => {
                console.log('Record Delete Successfully:', response.data);
                alert("Record Deleted");
                fetchResident();
            })
            .catch(error => {
                console.error('Error deleting record:', error);
            });
    }
}


function exportexcel() {
    const data = residents.value.map(resident => ({
        'residentid': resident.residentid,
        'precintid': resident.precinctid,
        'lastname': unHash(resident.lastname),
        'firstname': unHash(resident.firstname),
        'middlename': resident.middlename,
        'addressline1': unHash(resident.addressline1),
        'barangay': resident.barangay,
        'bday': resident.bday
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
        const excelData = XLSX.utils.sheet_to_json(worksheet, { header: 1, range: 'B2:' + worksheet['!ref'].split(':')[1] });
        // const excelData = XLSX.utils.sheet_to_json(worksheet);

        //test
        console.log('Excel data:', excelData);

        //axios here
        importExcel(excelData);

    };
    reader.readAsArrayBuffer(file);
}


function importExcel(excelData) {
    axios.post('https://rjprint10.com/marilaomis/backend/personapi.php', {
        action: 'import',
        records: excelData,
    })
        .then(response => {
            // console.log('Import successful:', response.data.message);
            fetchResident();
        })
        .catch(error => {
            console.error('Error importing CSV:', error);
        });

}


function myHash($text) {
    $base64Encoded = base64_encode($text);
    return $base64Encoded;
}

function unHash(hashed) {
    try {
        return atob(hashed);
    } catch (e) {
        console.error('Error decoding from Base64:', e);
        return '';
    }
}

</script>

<style>
/* Add your custom styles here */
</style>