<template>
    <div>
        <PageComponent title="Dashboard">
            <div v-if="user.role == 'admin'">
                <pre>{{ dashboardData }}</pre>
            </div>
            <div v-if="user.role == 'surveymaker'">
                <pre>{{ dashboardData }}</pre>
            </div>

            <template v-if="user.role == 'respondent'" v-slot:header>
                <div class="flex justify-between items-center">
                    <h1 class="text-3xl font-bold text-gray-900">
                        Hi respondent!
                    </h1>
                </div>
                <pre>{{ dashboardData }}</pre>
            </template>
        </PageComponent>
    </div>
</template>

<script setup>
import { computed, onMounted, ref } from "vue";
import PageComponent from "../components/PageComponent.vue";
import store from "../store";
// Declare user as a reactive computed property
const user = computed(() => store.state.user.data);
const dashboardData = ref([]);
// Use onMounted hook to log user data
onMounted(() => {
    store.dispatch("fetchDashboard", user.value.id).then((data) => {
        console.log(data);
        if (data.role === "respondent") {
            dashboardData.value = data;
        } else if (data.role === "surveymaker") {
            dashboardData.value = data;
        } else {
            dashboardData.value = data;

            console.log("pisti");
        }
    });
});

// Export user to make it accessible in the template
</script>

<style></style>
