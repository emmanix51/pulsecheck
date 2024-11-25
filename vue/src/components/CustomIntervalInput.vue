<template>
  <div>
    <h2 class="text-lg font-semibold mb-4">Custom Intervals</h2>
    <div v-for="(interval, index) in intervals" :key="index" class="flex mb-2">
      <input type="number" v-model.number="interval.range[0]" placeholder="Min" class="mr-2 border p-1" />
      <input type="number" v-model.number="interval.range[1]" placeholder="Max" class="mr-2 border p-1" />
      <input type="text" v-model="interval.description" placeholder="Interpretation" class="mr-2 border p-1" />
      <button @click="removeInterval(index)" class="ml-2 px-2 bg-red-500 text-white rounded">Remove</button>
    </div>
    <button @click="addInterval" class="mt-2 px-2 bg-green-500 text-white rounded">Add Interval</button>
  </div>
</template>

<script setup>
import { ref, watch, toRefs } from 'vue';

const props = defineProps(['modelValue']);
const emit = defineEmits(['update:modelValue']);

const intervals = ref([{ range: [0, 1], description: 'Default Interpretation' }]);

watch(intervals, (newIntervals) => {
  emit('update:modelValue', newIntervals);
}, { deep: true });

const addInterval = () => {
  intervals.value.push({ range: [0, 1], description: '' });
};

const removeInterval = (index) => {
  intervals.value.splice(index, 1);
};

// Initialize intervals if provided through props
if (props.modelValue.length) {
  intervals.value = props.modelValue;
}
</script>

<style scoped>
/* Add any specific styles you want here */
</style>
