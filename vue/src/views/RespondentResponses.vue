<template>
    <div>
        <PageComponent>
            <template v-slot:header>
                <div class="flex justify-between items-center">
                    <h1 class="text-3xl font-bold text-gray-900">
                        Your Responses
                    </h1>
                </div>
            </template>
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                <table
                    class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400"
                >
                    <thead
                        class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400"
                    >
                        <tr>
                            <th scope="col" class="px-6 py-3">Survey</th>
                            <th scope="col" class="px-6 py-3">
                                Date Responded
                            </th>
                            <th scope="col" class="px-6 py-3">Response</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700"
                            v-for="response in responses"
                            :key="response.id"
                        >
                            <th
                                scope="row"
                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white"
                            >
                                {{ response.survey.title }}
                            </th>
                            <td class="px-6 py-4">{{ response.updated_at }}</td>
                            <td class="px-6 py-4">
                                {{ response.average_answer_scale }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </PageComponent>
    </div>
</template>
<script setup>
import { computed, onMounted, ref } from "vue";
import { useRoute, useRouter } from "vue-router";
import PageComponent from "../components/PageComponent.vue";
import store from "../store";
import axiosClient from "../axios";
const route = useRoute();
const router = useRouter();
const responses = ref([]);
// store.dispatch("getSurveys");
onMounted(async () => {
    const user = computed(() => store.state.user.data);

    try {
        const response = await axiosClient.get(
            `/my-responses/${user.value.id}`
        );
        console.log(response.data);
        responses.value = response.data; // Assuming you want to log the response data
    } catch (error) {
        console.error("Error fetching descriptive analysis:", error);
    }
});
</script>
