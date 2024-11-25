<template>
    <div>
        <PageComponent>
            <template v-slot:header>
                <div class="flex justify-between items-center">
                    <h1 class="text-3xl font-bold text-gray-900">
                        Surveys For you
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
                            <th scope="col" class="px-6 py-3">Status</th>
                            <th scope="col" class="px-6 py-3">
                                Expiration Date
                            </th>
                            <th scope="col" class="px-6 py-3">Responded</th>
                            <th scope="col" class="px-6 py-3">Survey Link</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="survey in surveys"
                            :key="survey.id"
                            class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700"
                        >
                            <th
                                scope="row"
                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white"
                            >
                                {{ survey.title }}
                            </th>
                            <td class="px-6 py-4">
                                <div v-if="survey.status">Active</div>
                                <div v-else>Not active</div>
                            </td>
                            <td class="px-6 py-4">{{ survey.expire_date }}</td>
                            <td class="px-6 py-4">
                                <div v-if="survey.responded">Yes</div>
                                <div v-else>No</div>
                            </td>

                            <td class="px-6 py-4">
                                <div v-if="survey.status">
                                    <router-link
                                        v-if="!survey.is_public"
                                        :to="`/survey/${survey.slug}`"
                                        class="font-medium text-blue-600 dark:text-blue-500 hover:underline"
                                        >Answer Survey</router-link
                                    >
                                    <router-link
                                        v-else
                                        :to="`/public/survey/${survey.slug}`"
                                        class="font-medium text-blue-600 dark:text-blue-500 hover:underline"
                                        >Answer Survey</router-link
                                    >
                                </div>
                                <div v-else>Not active</div>
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
const surveys = ref([]);
// store.dispatch("getSurveys");
onMounted(async () => {
    const user = computed(() => store.state.user.data);

    try {
        const response = await axiosClient.get(`/my-surveys/${user.value.id}`);
        console.log(response.data);
        surveys.value = response.data; // Assuming you want to log the response data
    } catch (error) {
        console.error("Error fetching descriptive analysis:", error);
    }
});
</script>

<style></style>
