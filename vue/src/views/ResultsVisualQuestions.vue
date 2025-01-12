<template>
    <PageComponent>
        <template v-slot:header>
            <div class="flex items-center justify-between">
                <h1 class="text-3xl font-bold text-gray-900">
                    Result Analysis for: {{ surveyTitle }}
                </h1>
            </div>
        </template>
        <!-- <pre>{{ questions }}</pre> -->
        <div>
            <form class="max-w-lg mx-auto">
                <div class="flex">
                    <!-- <div class="relative w-full">
                        <input
                            type="search"
                            id="search-dropdown"
                            class="block p-2.5 w-full z-20 text-sm text-gray-900 bg-gray-50 rounded-e-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-s-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:border-blue-500"
                            placeholder="Search Mockups, Logos, Design Templates..."
                            required
                        />
                        <button
                            type="submit"
                            class="absolute top-0 end-0 p-2.5 text-sm font-medium h-full text-white bg-blue-700 rounded-e-lg border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                        >
                            <svg
                                class="w-4 h-4"
                                aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 20 20"
                            >
                                <path
                                    stroke="currentColor"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"
                                />
                            </svg>
                            <span class="sr-only">Search</span>
                        </button>
                    </div> -->
                </div>
            </form>
        </div>
        <div class="text-gray-700">
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg mt-6">
                <table
                    class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400"
                >
                    <thead
                        class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400"
                    >
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                Question Number
                            </th>
                            <th scope="col" class="px-6 py-3">Question</th>
                            <th scope="col" class="px-6 py-3">
                                Question Description
                            </th>
                            <th scope="col" class="px-6 py-3">Question Type</th>
                            <th scope="col" class="px-6 py-3">
                                Question Visuals
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(question, i) of questions"
                            :key="i"
                            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600"
                        >
                            <th
                                scope="row"
                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white"
                            >
                                {{ i + 1 }}
                            </th>
                            <td class="px-6 py-4">
                                {{ question.question }}
                            </td>
                            <td class="px-6 py-4">
                                <div>{{ question.description }}</div>
                            </td>
                            <td class="px-6 py-4">
                                {{ question.question_type }}
                            </td>

                            <td class="px-6 py-4 text-right">
                                <a
                                    :href="`/survey/question/${question.id}`"
                                    target="_blank"
                                >
                                    View questions visuals
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </PageComponent>
</template>

<script setup>
import { ref, onMounted, watch } from "vue";
import { useRoute } from "vue-router";
import axiosClient from "../axios";
import store from "../store";
import PageComponent from "../components/PageComponent.vue";

const route = useRoute();

const questions = ref([]);

const surveyTitle = ref("");

const fetchSurveyData = async () => {
    try {
        const response = await axiosClient.get(
            `/survey/${route.params.id}/results/visualization/questions`
        );
        const data = response.data;
        questions.value = data.questions;
        surveyTitle.value = data.surveyTitle;
        console.log(data);
        // console.log(questions);
    } catch (error) {
        console.error("Error fetching survey data:", error);
    }
};

onMounted(() => {
    fetchSurveyData();
});
</script>

<style></style>
