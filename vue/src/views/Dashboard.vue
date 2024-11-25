<template>
    <div>
        <PageComponent title="Dashboard">
            <template v-slot:header>
                <div class="flex justify-between items-center mb-4">
                    <h1 class="text-3xl font-bold text-spccolor-600">
                        Welcome Back {{ user.first_name }}!
                    </h1>
                </div>
            </template>
            <div v-if="user.role == 'admin'">
                <!-- <pre>{{ dashboardData }}</pre> -->
                <AdminDashboard />
            </div>
            <div v-if="user.role == 'surveymaker'">
                <SurveymakerDashboard />
            </div>

            <div v-if="user.role == 'respondent'">
                <RespondentDashboard />
                <!-- <pre>{{ dashboardData }}</pre> -->
            </div>
        </PageComponent>
    </div>
</template>

<script setup>
import { computed, onMounted, ref } from "vue";
import PageComponent from "../components/PageComponent.vue";
import AdminDashboard from "../components/AdminDashboard.vue";
import RespondentDashboard from "../components/RespondentDashboard.vue";
import SurveymakerDashboard from "../components/SurveymakerDashboard.vue";
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
