<template>
    <div>
        <div
            class="w-full grid grid-cols-1 gap-5 sm:grid-cols-2 md:grid-cols-2"
        >
            <div
                class="flex flex-col rounded-md py-4 px-6 shadow-lg border-2 bg-white"
            >
                <h1 class="text-lg font-semibold text-spccolor-600">
                    Available Surveys
                </h1>
                <ul>
                    <li
                        v-for="(survey, surveyIndex) in surveys.slice(-4)"
                        :key="surveyIndex"
                        class="rounded-md p-2 border shadow-md mb-2 border-gray-200"
                    >
                        <th
                            scope="row"
                            class="font-medium text-sm  text-spccolor-600 dark:text-white"
                        >
                            {{ survey.title }}
                            <div v-if="survey.status === 0">
                                Survey Status: not active
                            </div>
                            <div v-else>Survey Status: active</div>
                        </th>
                    </li>
                </ul>
            </div>
            <div
                class="flex flex-col rounded-md py-4 px-6 shadow-lg border-2 bg-white"
            >
                <h1 class="text-lg font-semibold text-spccolor-600">
                    My Survey Responses
                </h1>
                <ul>
                    <li
                        class="rounded-md p-2 border shadow-md mb-2 border-gray-200"
                        v-for="response in responses.slice(-4)"
                        :key="response.id"
                    >
                        <th
                            scope="row"
                            class="font-medium text-sm text-spccolor-600 whitespace-normal dark:text-white"
                        >
                            {{ response.survey.title }} <br />Total Average
                            Scale:
                            {{ response.average_answer_scale }}
                        </th>
                        <td class="place-content-end"></td>
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

// Compute user data from Vuex store
const user = computed(() => store.state.user.data);
onMounted(async () => {
    const user = computed(() => store.state.user.data);

    try {
        const response = await axiosClient.get(`/my-surveys/${user.value.id}`);
        console.log(response.data);
        surveys.value = response.data; // Assuming you want to log the response data
        try {
            const response = await axiosClient.get(
                `/my-responses/${user.value.id}`
            );
            console.log(response.data);
            responses.value = response.data; // Assuming you want to log the response data
        } catch (error) {
            console.error("Error fetching descriptive analysis:", error);
        }
    } catch (error) {
        console.error("Error fetching descriptive analysis:", error);
    }
});
</script>

<style></style>
