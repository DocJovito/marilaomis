<template>
    <div class="container mt-4">
        <h1>Program Report</h1>
        <p>Total Number of Programs: {{ arrayCount }}</p>

        <!-- filters -->
        <!-- if voter -->
        <!-- by barangay -->
        <!-- drop down/ all -->
        <!-- Date start and end -->


        <div class="form-group">
            <div class="row">
                <div class="col-9">
                    <input type="text" id="search" class="form-control" v-model="searchKey"
                        placeholder="Search by Complete Surname Only">
                </div>
                <div class="col-3">
                    <button class="btn btn-primary" @click="fetchData">Search</button>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover ">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">programid</th>
                        <th scope="col">programname</th>
                        <th scope="col">description</th>
                        <th scope="col">barangayscope</th>
                        <th scope="col">eventDate</th>
                        <th scope="col">isactive</th>
                        <th scope="col">createdby</th>
                        <th scope="col">createdat</th>
                        <th scope="col">isdeleted</th>
                        <th scope="col">budgetperhead</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="( Data, index ) in  paginatedArrayData " :key="Data.residentid">
                        <th scope="row">{{ (currentPage - 1) * pageSize + index + 1 }}</th>
                        <td>{{ Data.programid }}</td>
                        <td>{{ Data.programname }}</td>
                        <td>{{ Data.description }}</td>
                        <td>{{ Data.barangayscope }}</td>
                        <td>{{ Data.eventDate }}</td>
                        <td>{{ Data.isactive }}</td>
                        <td>{{ Data.createdby }}</td>
                        <td>{{ Data.createdat }}</td>
                        <td>{{ Data.isdeleted }}</td>
                        <td>{{ Data.budgetperhead }}</td>
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

const pageSize = 5;
const currentPage = ref(1);

const searchKey = ref('');

const arrayData = ref([]);
const arrayCount = ref('0');

const paginatedArrayData = computed(() => {
    const startIndex = (currentPage.value - 1) * pageSize;
    return arrayData.value.slice(startIndex, startIndex + pageSize);
});

const totalPages = computed(() => Math.ceil(arrayData.value.length / pageSize));


function fetchData() {
    const data = {
        action: 'search_program',
    };
    axios.post('https://rjprint10.com/marilaomis/backend/programapi.php', data)
        .then((response) => {
            arrayData.value = response.data;
            arrayCount.value = arrayData.value.length;
        })
        .catch((error) => {
            console.error('Error fetching data:', error);
        });
};

onMounted(() => {
    fetchData();
});



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