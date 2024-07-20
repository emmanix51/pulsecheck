<template>
    <div>
        <pre>{{ publicSurveys }}</pre>
        wew
    </div>
</template>

<script setup>
import { computed, onMounted, ref } from "vue";
import { useStore } from "vuex";
import { useRouter } from "vue-router";

const store = useStore();
const router = useRouter();
const token = computed(() => store.state.user.token);
const publicSurveys = ref([]);

onMounted(async () => {
    if (token.value) {
        router.push({ name: "Dashboard" });
    } else {
        try {
            const response = await store.dispatch("getPublicSurveys");
            publicSurveys.value = response;
            console.log(publicSurveys.value); // Log the actual surveys data
        } catch (error) {
            console.error("Error fetching public surveys:", error);
            // Handle error if needed
        }
    }
});
</script>

<style></style>
