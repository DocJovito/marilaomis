<template>
    <div class="container mt-4">
        <h4>User Management</h4>
        <RouterLink to="/users/create" class="btn btn-success ">Add User</RouterLink>

        <div class="table-responsive">
            <table class="table table-hover ">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">userid</th>
                        <th scope="col">email</th>
                        <th scope="col">password</th>
                        <th scope="col">usertype</th>
                        <th scope="col">name</th>
                        <th scope="col">address</th>
                        <!-- <th scope="col">createdat</th> -->
                        <!-- <th scope="col">isdeleted</th> -->


                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(user, index) in paginatedUsers" :key="user.userid">
                        <th scope="row">{{ (currentPage - 1) * pageSize + index + 1 }}</th>
                        <td>{{ user.userid }}</td>
                        <td>{{ user.email }}</td>
                        <!-- <td>{{ user.password }}</td> -->
                        <td> ******** </td>
                        <td>{{ user.usertype }}</td>
                        <td>{{ user.name }}</td>
                        <td>{{ user.address }}</td>
                        <!-- <td>{{ user.createdat }}</td> -->
                        <!-- <td>{{ user.isdeleted }}</td> -->
                        <td>
                            <!-- <button @click="selectResident(resident)">Select</button> -->
                            <RouterLink :to="'/users/' + user.userid + '/edit'" class="btn btn-success"> Edit
                            </RouterLink>
                            <RouterLink to="/" class="btn btn-danger" @:click="deleterec(user.userid)">Delete
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

const users = ref([]);
const pageSize = 5;
const currentPage = ref(1);

const paginatedUsers = computed(() => {
    const startIndex = (currentPage.value - 1) * pageSize;
    return users.value.slice(startIndex, startIndex + pageSize);
});

const totalPages = computed(() => Math.ceil(users.value.length / pageSize));

onMounted(() => {
    axios.get('https://rjprint10.com/marilaomis/backend/userapi.php?action=get_all')
        .then((response) => {
            users.value = response.data;
        })
        .catch((error) => {
            console.error('Error fetching data:', error);
        });
});

//delete



// function exportexcel() {
//     const data = residents.value.map(resident => ({
//         'Resident ID': resident.residentid,
//         'Precinct ID': resident.precinctid,
//         'Last Name': resident.lastname,
//         'First Name': resident.firstname,
//         'Middle Name': resident.middlename,
//         'Address': resident.addressline1,
//         'Barangay': resident.baranggay,
//         'Birthday': resident.bday
//     }));

//     const ws = XLSX.utils.json_to_sheet(data);
//     const wb = XLSX.utils.book_new();
//     XLSX.utils.book_append_sheet(wb, ws, 'Residents');

//     const wbout = XLSX.write(wb, { bookType: 'xlsx', type: 'binary' });

//     function s2ab(s) {
//         const buf = new ArrayBuffer(s.length);
//         const view = new Uint8Array(buf);
//         for (let i = 0; i < s.length; i++) view[i] = s.charCodeAt(i) & 0xff;
//         return buf;
//     }

//     saveAs(new Blob([s2ab(wbout)], { type: 'application/octet-stream' }), 'residents.xlsx');
// }


// function readexcel() {
//     const input = document.createElement('input');
//     input.type = 'file';
//     input.accept = '.xlsx, .xls';
//     input.addEventListener('change', handleFile);
//     input.click();
// }

// function handleFile(event) {
//     const file = event.target.files[0];
//     const reader = new FileReader();
//     reader.onload = (e) => {
//         const data = new Uint8Array(e.target.result);
//         const workbook = XLSX.read(data, { type: 'array' });
//         const sheetName = workbook.SheetNames[0];
//         const worksheet = workbook.Sheets[sheetName];
//         const excelData = XLSX.utils.sheet_to_json(worksheet, { header: 1 });
//         console.log('Excel data:', excelData);
//     };
//     reader.readAsArrayBuffer(file);
// }


</script>

<style>
/* Add your custom styles here */
</style>