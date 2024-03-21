<template>
    <div>
        <h2>Resident Details</h2>
        <div v-if="selectedResident">
            <div class="form-group">
                <label for="residentID">Resident ID:</label>
                <input type="text" id="residentID" :value="selectedResident.residentid" disabled>
            </div>
            <div class="form-group">
                <label for="precinctID">Precinct ID:</label>
                <input type="text" id="precinctID" :value="selectedResident.precinctid" disabled>
            </div>
            <div class="form-group">
                <label for="lastName">Last Name:</label>
                <input type="text" id="lastName" :value="selectedResident.lastname" disabled>
            </div>
            <div class="form-group">
                <label for="firstName">First Name:</label>
                <input type="text" id="firstName" :value="selectedResident.firstname" disabled>
            </div>
            <div class="form-group">
                <label for="middleName">Middle Name:</label>
                <input type="text" id="middleName" :value="selectedResident.middlename" disabled>
            </div>
            <div class="form-group">
                <label for="address">Address:</label>
                <input type="text" id="address" :value="selectedResident.addressline1" disabled>
            </div>
            <div class="form-group">
                <label for="barangay">Barangay:</label>
                <input type="text" id="barangay" :value="selectedResident.baranggay" disabled>
            </div>
            <div class="form-group">
                <label for="birthday">Birthday:</label>
                <input type="text" id="birthday" :value="selectedResident.bday" disabled>
            </div>
        </div>
        <div v-else>
            <p>No resident selected.</p>
        </div>
    </div>


</template>

<script setup>
import { defineProps } from 'vue';

// Define props for the component
const props = defineProps(['selectedResident']);

// Access selectedResident through props
const { value: selectedResident } = props;
</script>

<style>
/* Add your custom styles here */
.form-group {
    margin-bottom: 10px;
}
</style>
