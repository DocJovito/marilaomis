<template>
    <button @click="changeValue">Change the value of store</button>
</template>

<script setup>
import { useStore } from 'vuex';
import { computed } from 'vue';

const store = useStore();

const userId = computed(() => store.state.userState[0].userID);

function changeValue() {
    store.commit('setUserID', '9999');
    console.log(userId.value);
}
</script>