<template>
    <div class="container mt-4">
        <h1>Funding Report</h1>
        <p>Total Number of Funds Allocate Data: {{ arrayCount }}</p>
        <p>Total Number of Amount of Funds Allocated: Php{{ formatNumber(totalFund) }}</p>

        <!-- Date start and end -->
        <!-- name instead of id -->
        <!-- format numeric to money -->
        <!-- total scan from programs -->

        <!-- by user id -->
        <!-- by date -->


        <form @submit.prevent="fetchData">
            <div>
                <div class="form-group">
                    <label for="dateStart">Start Date:</label><br>
                    <input type="date" id="dateStart" v-model="dateStart" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="dateEnd">End Date:</label><br>
                    <input type="date" id="dateEnd" v-model="dateEnd" class="form-control" required>
                </div>
            </div>
            <div class="form-group">
                <label for="userID" class="form-label">User</label>
                <select class="form-control" id="userID" v-model="userID" required>
                    <option value="All">All</option>
                    <option v-for="user in users" :key="user.userid" :value="user.userid">
                        {{ user.name }} ({{ user.email }})
                    </option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>

        <div class="table-responsive">
            <table class="table table-hover ">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <!-- <th scope="col">fundid</th> -->
                        <th scope="col">Project Name</th>
                        <th scope="col">Amount Allocated</th>
                        <th scope="col">Distributed to User</th>
                        <th scope="col">Allocated By</th>
                        <th scope="col">Allocate Date</th>
                        <th scope="col">Edited By</th>
                        <th scope="col">Edit Date</th>
                        <!-- <th scope="col">isdeleted</th> -->
                        <!-- <th scope="col">deletedby</th> -->
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="( Data, index ) in  paginatedArrayData " :key="Data.residentid">
                        <th scope="row">{{ (currentPage - 1) * pageSize + index + 1 }}</th>
                        <!-- <td>{{ Data.fundid }}</td> -->
                        <td>{{ Data.budgetfor }}</td>
                        <td>{{ formatNumber(Data.amount) }}</td>
                        <td>{{ Data.userid }}</td>
                        <td>{{ Data.createdby }}</td>
                        <td>{{ Data.createdat }}</td>
                        <td>{{ Data.editedby }}</td>
                        <td>{{ Data.editedat }}</td>
                        <!-- <td>{{ Data.isdeleted }}</td> -->
                        <!-- <td>{{ Data.deletedby }}</td> -->
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

    </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';
import { useStore } from 'vuex';

const store = useStore();

const pageSize = 10;
const currentPage = ref(1);

//search related
const startDate = new Date('1/2/2024').toISOString().split('T')[0];
const today = new Date().toISOString().split('T')[0];
const dateStart = ref(startDate);
const dateEnd = ref(today);
const userID = ref('All');

const arrayData = ref([]);
const arrayCount = ref(0);
const totalFund = ref(0);

const paginatedArrayData = computed(() => {
    const startIndex = (currentPage.value - 1) * pageSize;
    return arrayData.value.slice(startIndex, startIndex + pageSize);
});

const totalPages = computed(() => Math.ceil(arrayData.value.length / pageSize));

function formatNumber(value) {
    if (value === null || value === undefined) return '';
    return value.toLocaleString('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    });
}


function fetchData() {
    const data = {
        action: 'search_funds',
        datestart: dateStart.value,
        dateend: dateEnd.value,
        userid: userID.value,
    };
    axios.post('https://marilaomis.com/marilaomis/backend/fundapi.php', data)
        .then((response) => {
            arrayData.value = response.data;
            arrayCount.value = arrayData.value.length;
            totalFund.value = 0;
            for (let index = 0; index < arrayData.value.length; index++) {
                if (arrayData.value[index].hasOwnProperty('amount')) {
                    totalFund.value += parseFloat(arrayData.value[index].amount);
                }
            }
        })
        .catch((error) => {
            console.error('Error fetching data:', error);
        });
};

onMounted(() => {
    fetchData();
    fetchUsers();
});

const users = ref([]);

const fetchUsers = async () => {
    try {
        const response = await axios.get('https://marilaomis.com/marilaomis/backend/userapi.php?action=get_users');
        users.value = response.data;
    } catch (error) {
        console.error('Error fetching users:', error);
    }
};



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