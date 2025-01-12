<template>
    <div>
        <div class="grid grid-cols-1 gap-4 lg:grid-cols-3 lg:gap-8">
            <div
                class="flex flex-col rounded-md py-4 px-6  shadow-lg border-2 bg-white"
            >
                <h1 class="text-lg font-semibold text-spccolor-600 mb-4">Latest Users</h1>
                <ul>
                    <li
                        v-for="(user, userIndex) in users"
                        :key="userIndex"
                        class="rounded-md p-2 border border-gray-200"
                    >
                        <th
                            scope="row"
                            class="font-medium text-sm text-spccolor-600 mb-4 dark:text-white"
                        >
                            {{ user.idnum }}-{{ user.first_name }}
                            {{ user.last_name }}<br />
                            created at: {{ user.created_at }}
                        </th>
                    </li>
                </ul>
            </div>
            <div
                class="flex flex-col rounded-md py-4 px-6  shadow-lg border-2 bg-white"
            >
                <h1 class="text-lg font-semibold text-spccolor-600 mb-4">Latest Surveys</h1>
                <ul>
                    <li
                        v-for="(survey, surveyIndex) in surveys"
                        :key="surveyIndex"
                        class="rounded-md p-2 border border-gray-200"
                    >
                        <th
                            scope="row"
                            class="font-medium text-sm text-spccolor-600 mb-4 dark:text-white"
                        >
                            {{ survey.title }}<br/>
                            <div v-if="survey.status === 0">
                                Survey Status: not active
                            </div>
                            <div v-else>Survey Status: active</div>
                            <!-- <div v-if="survey.status === 0">
                                Survey Status: not active
                            </div> -->
                            <!-- <div v-else>Survey Status: active</div> -->
                        </th>
                    </li>
                </ul>
            </div>
            <div
                class="flex flex-col rounded-md py-4 px-6  shadow-lg border-2 bg-white"
            >
                <h1 class="text-lg font-semibold text-spccolor-600 mb-4">
                    Latest Responses
                </h1>
                <ul>
                    <li
                        v-for="(response, responseIndex) in responses"
                        :key="responseIndex"
                        class="rounded-md p-2 border border-gray-200"
                    >
                        <th
                            scope="row"
                            class="font-medium text-sm text-spccolor-600 mb-4 dark:text-white"
                        >
                            <h6 class="text-xs">
                                Survey: {{ response.survey.title }}<br />
                            </h6>
                            average: {{ response.average_scale }}<br />
                            interpretation: {{ response.interpretation }}
                        </th>
                    </li>
                </ul>
            </div>
        </div>
        
    </div>
</template>

<script setup>
import { computed, onMounted, ref } from "vue";
import { useStore } from "vuex";
import axiosClient from "../axios";

// Access Vuex store
const store = useStore();
const surveys = ref([]);
const responses = ref([]);
let users = ref([]);

// Compute user data from Vuex store
const user = computed(() => store.state.user.data);
onMounted(async () => {
    const user = computed(() => store.state.user.data);

    try {
        const response = await axiosClient.get(`/admin/users`);
        console.log(response.data.users.data);
        users.value = response.data.users.data; // Assuming you want to log the response data
        try {
            const response = await axiosClient.get(`/admin/getsurveys`);
            console.log(response.data.data);
            surveys.value = response.data.data;
            try {
                const response = await axiosClient.get(`/admin/getresponses`);
                console.log(response.data);
                responses.value = response.data.data;
            } catch (error) {} // Assuming you want to log the response data
        } catch (error) {
            console.error("Error fetching descriptive analysis:", error);
        }
    } catch (error) {
        console.error("Error fetching descriptive analysis:", error);
    }
});
</script>

<style></style>
