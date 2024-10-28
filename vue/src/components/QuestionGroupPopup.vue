<!-- QuestionGroupPopup.vue -->
<template>
    <div v-if="isVisible" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white p-6 rounded-md">
            <h2 class="text-lg font-bold mb-4">Select Question Group Type</h2>
            <div class="flex flex-col">
                <button @click="selectType('withoutCategory')" class="py-2 px-4 mb-2 bg-blue-500 text-white rounded">Questionnaire Without Category</button>
                <button @click="selectType('withCategory')" class="py-2 px-4 mb-2 bg-blue-500 text-white rounded">Questionnaire With Category</button>
                <button @click="selectType('commentSection')" class="py-2 px-4 mb-2 bg-blue-500 text-white rounded">Comment Section</button>
            </div>
            <button @click="close" class="mt-4 text-red-500">Cancel</button>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';

defineProps(['isVisible']);
const emit = defineEmits(['onClose']);

const selectType = (type) => {
    emit('onClose', type);
};

const close = () => {
    emit('onClose', null);
};
</script>
