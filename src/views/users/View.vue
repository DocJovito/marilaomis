<template>
    <div class="container mt-4">
        <h4>User Management</h4>
        <RouterLink to="/users/create" class="btn btn-success">Add User</RouterLink>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">userid</th>
                        <th scope="col">email</th>
                        <th scope="col">password</th>
                        <th scope="col">usertype</th>
                        <th scope="col">name</th>
                        <th scope="col">address</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(user, index) in paginatedUsers" :key="user.userid">
                        <th scope="row">{{ (currentPage - 1) * pageSize + index + 1 }}</th>
                        <td>{{ user.userid }}</td>
                        <td>{{ user.email }}</td>
                        <td> ******** </td>
                        <td>{{ user.usertype }}</td>
                        <td>{{ user.name }}</td>
                        <td>{{ user.address }}</td>
                        <td>
                            <RouterLink :to="'/users/' + user.userid + '/edit'" class="btn btn-success">Edit</RouterLink>
                            <button class="btn btn-danger" @click="deleteUser(user.userid)">Delete</button>
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

        <button class="btn btn-success" @click="exportExcel">Export to Excel</button>
        <button class="btn btn-danger" @click="importExcel">Import From Excel</button>
    </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';
import * as XLSX from 'xlsx';
import { saveAs } from 'file-saver';
import { useRouter } from 'vue-router';

const users = ref([]);
const currentPage = ref(1);
const pageSize = 5;
const router = useRouter();

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

const deleteUser = (userId) => {
    if (confirm("Are you sure you want to delete this user?")) {
        const targetRecord = {
            action: 'delete',
            id: userId
        };
        axios.delete(`https://rjprint10.com/marilaomis/backend/userapi.php`, { data: targetRecord })
            .then(() => {
                users.value = users.value.filter(user => user.userid !== userId);
                console.log('User deleted successfully');
                alert("User Deleted");
            })
            .catch((error) => {
                console.error('Error deleting user:', error);
            });
    }
};

const exportExcel = () => {
    const data = users.value.map(user => ({
        'User ID': user.userid,
        'Email': user.email,
        'Password': '********', // Masking the password
        'User Type': user.usertype,
        'Name': user.name,
        'Address': user.address
    }));

    const ws = XLSX.utils.json_to_sheet(data);
    const wb = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(wb, ws, 'Users');

    const wbout = XLSX.write(wb, { bookType: 'xlsx', type: 'binary' });

    function s2ab(s) {
        const buf = new ArrayBuffer(s.length);
        const view = new Uint8Array(buf);
        for (let i = 0; i < s.length; i++) view[i] = s.charCodeAt(i) & 0xff;
        return buf;
    }

    saveAs(new Blob([s2ab(wbout)], { type: 'application/octet-stream' }), 'users.xlsx');
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

</script>

<style>
/* Add your custom styles here */
</style>
