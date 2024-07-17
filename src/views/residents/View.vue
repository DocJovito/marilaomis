<template>
    <div class="print-hide container mt-4">
        <p>Resident Management</p>
        <div class="form-group" v-if="userType == 'Admin' || userType == 'Municipal Staff'">
            <RouterLink to="/residents/create" class="btn btn-success ">Add Resident</RouterLink>
        </div>

        <form @submit.prevent="fetchResident" class="mt-2">
            <div class="form-group">
                <div class="row">
                    <div class="col-8">
                        <div class="form-group">
                            <input type="text" id="search" class="form-control" v-model="searchKey"
                                placeholder="Search by Complete Surname Only">
                        </div>


                    </div>
                    <div class="col-2">
                        <div class="form-group">
                            <select id="barangay" class="form-control" v-model="searchBarangay" v-if="address = 'ALL'">
                                <option value="ALL">ALL</option>
                                <option value="ABANGAN NORTE">ABANGAN NORTE</option>
                                <option value="ABANGAN SUR">ABANGAN SUR</option>
                                <option value="IBAYO">IBAYO</option>
                                <option value="LAMBAKIN">LAMBAKIN</option>
                                <option value="LIAS">LIAS</option>
                                <option value="LOMA DE GATO">LOMA DE GATO</option>
                                <option value="NAGBALON">NAGBALON</option>
                                <option value="PATUBIG">PATUBIG</option>
                                <option value="POBLACION 1ST">POBLACION 1ST</option>
                                <option value="POBLACION 2ND">POBLACION 2ND</option>
                                <option value="PRENZA 1ST">PRENZA 1ST</option>
                                <option value="PRENZA 2ND">PRENZA 2ND</option>
                                <option value="SANTA ROSA 1ST">SANTA ROSA 1ST</option>
                                <option value="SANTA ROSA 2ND">SANTA ROSA 2ND</option>
                                <option value="SAOG">SAOG</option>
                                <option value="TABING-ILOG">TABING-ILOG</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-2">
                        <button type="submit" class="btn btn-primary ">Search</button>
                    </div>
                </div>
            </div>

        </form>

        <div class="table-responsive">
            <table class="table table-hover ">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Resident ID</th>
                        <th scope="col">Precint ID</th>
                        <th scope="col">is member</th>
                        <th scope="col">Last Name</th>
                        <th scope="col">First Name</th>
                        <!-- <th scope="col">Middle Name</th> -->
                        <th scope="col">Address</th>
                        <th scope="col">Barangay</th>
                        <th scope="col">Birthday</th>
                        <th v-if="userType == 'Admin' || userType == 'Municipal Staff'" scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="( resident, index ) in  paginatedResidents " :key="resident.residentid">
                        <th scope="row">{{ (currentPage - 1) * pageSize + index + 1 }}</th>
                        <td>{{ resident.residentid }}</td>
                        <td>{{ resident.precintid }}</td>
                        <td>{{ resident.ismember }}</td>
                        <td>{{ unHash(resident.lastname) }}</td>
                        <td>{{ unHash(resident.firstname) }}</td>
                        <!-- <td>{{ unHash(resident.middlename) }}</td> -->
                        <td>{{ unHash(resident.addressline1) }}</td>
                        <td>{{ resident.barangay }}</td>
                        <td>{{ resident.bday }}</td>
                        <td v-if="userType == 'Admin' || userType == 'Municipal Staff'">
                            <RouterLink :to="'/residents/' + resident.residentid + '/edit'" class="btn btn-success">
                                Edit
                            </RouterLink>
                            <button class="btn btn-primary" @click="showModal(resident.residentid)">Print</button>
                            <!-- <RouterLink :to="'/residents/' + resident.residentid + '/idcard'" class="btn btn-primary">
                                Print
                            </RouterLink> -->
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

        <div class="form-group d-flex justify-content-center"
            v-if="userType == 'Admin' || userType == 'Municipal Staff'">
            <button class="btn btn-primary" @click="downloadTemplate">Download Template</button>
            <button class="btn btn-success" @click="exportexcel">Export to Excel</button>
            <button class="btn btn-danger" @click="readexcel">Import From Excel</button>
        </div>
    </div>

    <!-- <button @click="showModal">Open Modal</button> -->
    <div>
        <MyModal v-if="isModalVisible" :isVisible="isModalVisible" :title="modalResidentID" @close="hideModal">
            <!-- <p>This is the content of the modal.</p> -->
            <ModalCard :residentIDParam="modalResidentID" />
        </MyModal>
    </div>

    <!-- <button @click="printID">aaaaaaa</button> -->
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';
import * as XLSX from 'xlsx';
import { saveAs } from 'file-saver';
import { useStore } from 'vuex';

import MyModal from '@/components/MyModal.vue';
import ModalCard from '@/components/ModalCard.vue';
const isModalVisible = ref(false);
const modalResidentID = ref();

const showModal = (modalResID) => {
    modalResidentID.value = modalResID;
    // console.log(modalResID)
    isModalVisible.value = true;
};

const hideModal = () => {
    isModalVisible.value = false;
};


// search
const searchKey = ref('');
const searchBarangay = ref('ALL');

const residents = ref([]);
const pageSize = 10;
const currentPage = ref(1);

const store = useStore();
const address = computed(() => store.state.user.address);
const userType = computed(() => store.state.user.userType);

const paginatedResidents = computed(() => {
    const startIndex = (currentPage.value - 1) * pageSize;
    return residents.value.slice(startIndex, startIndex + pageSize);
});

const totalPages = computed(() => Math.ceil(residents.value.length / pageSize));


const fetchResident = () => {
    const data = {
        action: 'search_resident',
        lastname: searchKey.value,
        searchBarangay: searchBarangay.value,
        barangay: address.value,

    };
    axios.post('https://marilaomis.com/marilaomis/backend/personapi.php', data)
        .then((response) => {
            residents.value = response.data;
            // console.log(data);
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
        axios.post(`https://marilaomis.com/marilaomis/backend/personapi.php`, data)
            .then(response => {
                // console.log('Record Delete Successfully:', response.data);
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
        'precintid': resident.precintid,
        'lastname': unHash(resident.lastname),
        'firstname': unHash(resident.firstname),
        'middlename': unHash(resident.middlename),
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

function downloadTemplate() {
    // Define the headers for the Excel template
    const headers = [
        'leave this column blank',
        'precintid',
        'lastname',
        'firstname',
        'middlename',
        'addressline1',
        'barangay',
        'bday'
    ];

    // Create a blank worksheet with headers only
    const ws = XLSX.utils.aoa_to_sheet([headers]);
    const wb = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(wb, ws, 'Residents');

    // Write the workbook to a binary string
    const wbout = XLSX.write(wb, { bookType: 'xlsx', type: 'binary' });

    // Function to convert binary string to array buffer
    function s2ab(s) {
        const buf = new ArrayBuffer(s.length);
        const view = new Uint8Array(buf);
        for (let i = 0; i < s.length; i++) view[i] = s.charCodeAt(i) & 0xff;
        return buf;
    }

    // Trigger download of the Excel file
    saveAs(new Blob([s2ab(wbout)], { type: 'application/octet-stream' }), 'residents_template.xlsx');
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
        // console.log('Excel data:', excelData);

        //axios here
        importExcel(excelData);

    };
    reader.readAsArrayBuffer(file);
}


function importExcel(excelData) {
    axios.post('https://marilaomis.com/marilaomis/backend/personapi.php', {
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

function printID() {
    window.print();
}
</script>

<style scoped>
@media print {
    .print-hide {
        display: none;
    }
}
</style>