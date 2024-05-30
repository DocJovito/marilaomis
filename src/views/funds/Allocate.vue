<template>
    <div class="container mt-4">

        <div class="card">
            <div class="card-header">
                <h4>Add Funding</h4>
            </div>
            <div class="card-body">
                <form @submit.prevent="saveRecord">

                    <div class="form-group">
                        <label for="budgetfor">budgetfor:</label><br>
                        <input type="text" id="budgetfor" class="form-control" v-model="newFunding.budgetfor" required>
                    </div>
                    <div class="form-group">
                        <label for="amount">amount:</label><br>
                        <input type="text" id="amount" class="form-control" v-model="newFunding.amount" required>
                    </div>
                    <div class="form-group">
                        <label for="userid">userid:</label><br>
                        <input type="text" id="userid" class="form-control" v-model="newFunding.userid">
                    </div>
                    <button type="submit" class="btn btn-primary ">Save</button>
                </form>
            </div>
        </div>



    </div>

</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';

const router = useRouter();

const newFunding = ref({
    budgetfor: '',
    amount: '',
    userid: '',
    createdby: '',
    createdat: getCurrentDate(),
    editedby: '',
    editedat: '',
    isdeleted: getCurrentDate(),
    deletedby: getCurrentDate(),
});

function saveRecord() {
    const newFunding = {
        action: 'create',
        budgetfor: newFunding.budgetfor.value,
        amount: newFunding.amount.value,
        userid: newFunding.userid.value,
    };

    axios.post('https://rjprint10.com/marilaomis/backend/fundapi.php', newFunding)
        .then(response => {
            console.log('Record saved successfully:', response.data);
            closeModal();
            router.push('/funds/view');
        })
        .catch(error => {
            console.error('Error saving record:', error);
        });
}

const closeModal = () => {
    // Implement close modal logic if needed
};

function myHash(text) {
    let base64Encoded = btoa(text);
    return base64Encoded;
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
/* Your CSS styles here */
</style>
