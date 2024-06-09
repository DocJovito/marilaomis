<template>
    <div class="container mt-4">
        <h1>Program Report</h1>
        <p>Total Number of Programs: {{ arrayCount }}</p>

        <!-- filters -->
        <!-- by barangay --> <!-- drop down/ all -->
        <!-- Date start and end -->



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
                <label for="barangay">Barangay:</label><br>
                <select id="barangay" class="form-control" v-model="barangay">
                    <option value="">Select Barangay</option>
                    <option value="All">All</option>
                    <option value="Abangan Norte">Abangan Norte</option>
                    <option value="Abangan Sur">Abangan Sur</option>
                    <option value="Ibayo">Ibayo</option>
                    <option value="Lambakin">Lambakin</option>
                    <option value="Lias">Lias</option>
                    <option value="Loma de Gato">Loma de Gato</option>
                    <option value="Nagbalon">Nagbalon</option>
                    <option value="Patubig">Patubig</option>
                    <option value="Poblacion 1st">Poblacion 1st</option>
                    <option value="Poblacion 2nd">Poblacion 2nd</option>
                    <option value="Prenza 1st">Prenza 1st</option>
                    <option value="Prenza 2nd">Prenza 2nd</option>
                    <option value="Santa Rosa 1st">Santa Rosa 1st</option>
                    <option value="Santa Rosa 2nd">Santa Rosa 2nd</option>
                    <option value="Saog">Saog</option>
                    <option value="Tabing-ilog">Tabing-ilog</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>

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

const pageSize = 10;
const currentPage = ref(1);

//search var
const searchKey = ref('');
const startDate = new Date('1/2/2024').toISOString().split('T')[0];
const today = new Date().toISOString().split('T')[0];
const dateStart = ref(startDate);
const dateEnd = ref(today);
const barangay = ref('All');

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
        datestart: dateStart.value,
        dateend: dateEnd.value,
        barangay: barangay.value,

    };
    axios.post('https://marilaomis.com/marilaomis/backend/programapi.php', data)
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