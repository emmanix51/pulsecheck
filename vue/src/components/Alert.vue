<template>
    <div
        v-if="alert.show"
        class="fixed w-[300px] left-4 bottom-4 py-2 px-4 text-white animate-fade-in-down"
        :class="[alert.type === 'success' ? 'bg-emerald-500' : 'bg-red-500']"
    >
        {{ alert.message }}
    </div>
</template>

<script setup>
import { computed } from "vue";
import { useStore } from "vuex";

const store = useStore();

const alert = computed(() => store.state.alert);
</script>

<style></style>
