<template>
    <div>
        <PageComponent title="Dashboard">
            <div v-if="user.role == 'admin'">
                <!-- <pre>{{ dashboardData }}</pre> -->
                <div class="grid grid-cols-1 gap-4 lg:grid-cols-3 lg:gap-8">
                    <div class="h-32 rounded-lg bg-gray-200 p-4">
                        <div class="flex flex-col items-center">
                            <h1 class="font-bold text-lg">Latest Users</h1>
                            <ul>
                                <li>w</li>
                                <li>w</li>
                                <li>w</li>
                            </ul>
                        </div>
                    </div>
                    <div class="h-32 rounded-lg bg-gray-200 p-4">
                        <div class="flex flex-col items-center">
                            <h1 class="font-bold text-lg">Latest Surveys</h1>
                            <ul>
                                <li>w</li>
                                <li>w</li>
                                <li>w</li>
                            </ul>
                        </div>
                    </div>
                    <div class="h-32 rounded-lg bg-gray-200 p-4">
                        <div class="flex flex-col items-center">
                            <h1 class="font-bold text-lg">Survey Results</h1>
                            <ul>
                                <li>w</li>
                                <li>w</li>
                                <li>w</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div v-if="user.role == 'surveymaker'">
                <pre>{{ dashboardData }}</pre>
            </div>

            <template v-if="user.role == 'respondent'" v-slot:header>
                <RespondentDashboard />
                <!-- <pre>{{ dashboardData }}</pre> -->
            </template>
        </PageComponent>
    </div>
</template>

<script setup>
import { computed, onMounted, ref } from "vue";
import PageComponent from "../components/PageComponent.vue";
import RespondentDashboard from "../components/RespondentDashboard.vue";
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
