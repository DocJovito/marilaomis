<template>
    <div class="container mt-4">
        <h4>Fund Management</h4>

        <RouterLink to="/funds/fundallocate" class="btn btn-success">
            Allocate Fund
        </RouterLink>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">fundid</th>
                        <th scope="col">budgetfor</th>
                        <th scope="col">amount</th>
                        <th scope="col">userid</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(fund, index) in paginatedFunds" :key="funds.fundid">
                        <th scope="row">{{ (currentPage - 1) * pageSize + index + 1 }}</th>
                        <td>{{ fund.fundid }}</td>
                        <td>{{ fund.budgetfor }}</td>         
                        <td>{{ fund.amount }}</td>
                        <td>{{ fund.userid }}</td>   
                        <td>
                            <RouterLink :to="'/funds/' + fund.fundid + '/edit'" class="btn btn-success">
                                Edit
                            </RouterLink>
                            <RouterLink to="/funds/view" class="btn btn-danger"
                                @:click="deleterec(fund.fundid)">Delete
                            </RouterLink>

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
    </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';
import { useStore } from 'vuex';
import { useRouter } from 'vue-router';

const store = useStore();
const userId = computed(() => store.state.user.id);

const funds = ref([]);
const pageSize = 5;
const currentPage = ref(1);

const paginatedFunds = computed(() => {
    const startIndex = (currentPage.value - 1) * pageSize;
    return funds.value.slice(startIndex, startIndex + pageSize);
});

const totalPages = computed(() => Math.ceil(funds.value.length / pageSize));

onMounted(() => {
    fetchFunds();
});

const fetchFunds = () => {
    const data = {
        action: 'fetch_funds',
    };
    axios.post('https://rjprint10.com/marilaomis/backend/fundapi.php', data)
        .then((response) => {
            funds.value = response.data;
        })
        .catch((error) => {
            console.error('Error fetching data:', error);
        });
};



const changePage = (pageNumber) => {
    if (pageNumber > 0 && pageNumber <= totalPages.value) {
        currentPage.value = pageNumber;
    }
};

const deleterec = (fundid) => {
  if (confirm('Are you sure you want to delete this record?')) {
    const data = {
      action: 'delete',
      fundid: fundid,
      deletedby: userId.value // Sending the current user's ID as deletedby
    };
    axios.post('https://rjprint10.com/marilaomis/backend/fundapi.php', data)
      .then(response => {
        console.log(response.data);
        fetchFunds(); // Refresh the funds list after deletion
      })
      .catch(error => {
        console.error('Error deleting fund:', error);
      });
  }
};

</script>




<style scoped>


/* Add your custom styles here */
</style>