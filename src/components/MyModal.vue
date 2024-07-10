<template>
    <div v-if="isVisible" class="modal-overlay" @click="closeModal">
        <div class="modal1 print-modal" @click.stop>
            <div class="modal-header">
                <!-- <h3>{{ title }}</h3> -->
            </div>
            <div class="modal-body">
                <slot></slot>
            </div>
            <div class="modal-footer">
                <button class="print-hide" @click="closeModal">Close</button>
                <button class="print-hide" @click="printID">Print</button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { defineProps, defineEmits } from 'vue';

const props = defineProps({
    isVisible: {
        type: Boolean,
        required: true,
    },
    title: {
        type: String,
        default: 'Modal Title',
    },
});

const emit = defineEmits(['close']);

const closeModal = () => {
    emit('close');
};

function printID() {
    window.print();
}
</script>

<style scoped>
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
}

.modal1 {
    background: white;
    padding: 20px;
    border-radius: 8px;
    width: 500px;
    max-width: 100%;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-body {
    margin: 20px 0;
}

.modal-footer {
    display: flex;
    justify-content: flex-end;
}

/* Print-specific styles */
@media print {
    body * {
        visibility: hidden;
        height: 0;
    }

    .print-modal,
    .print-modal * {
        visibility: visible;
        height: auto;
    }

    .print-modal {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: auto;
    }

    .print-hide {
        display: none;
    }
}
</style>
