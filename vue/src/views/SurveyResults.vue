<template>
    <PageComponent>
        <template v-slot:header>
            <div class="flex items-center justify-between">
                <h1 class="text-3xl font-bold text-gray-900">
                    Survey Result for: {{ model.title }}
                </h1>
            </div>
        </template>
        <!-- <pre>{{ model }}</pre> -->
        <!-- <pre>{{ totalResponse }}</pre>
        <pre>{{ totalAnswerScale }}</pre>
        <pre>{{ averageAnswerScale }}</pre> -->
        <!-- <pre>{{ Object.keys(surveyData.responses).length }}</pre> -->
        <div
            class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 text-gray-700"
        >
            <div
                class="row-span-2 animate-fade-in-down order-3 lg:order-1 bg-white shadow-md p-4"
            >
                <h3 class="text-2xl font-semibold">About The Survey</h3>
                <div>
                    <h3 class="font-bold text-xl mb-3">Survey Title</h3>
                    <div class="flex justify-between text-sm mb-1">
                        <div>Total Responses:</div>
                        <div>{{ totalResponses }}</div>
                    </div>
                    <div class="flex justify-between text-sm mb-1">
                        <div>Respondent Groups:</div>
                        <div
                            v-for="(group, i) of model.respondent_groups"
                            :key="i"
                        >
                            {{ group.type }}
                        </div>
                    </div>
                    <div class="flex justify-between text-sm mb-1">
                        <div>Status:</div>
                        <div v-if="model.status == true">Active</div>
                        <div v-else>Not active</div>
                    </div>
                    <div class="flex justify-between text-sm mb-1">
                        <div>Overall Mean:</div>
                        <div>{{ averageAnswerScale }}</div>
                    </div>

                    <div class="flex justify-between">
                        <router-link
                            :to="{
                                name: 'ResultsTally',
                                params: { id: model.id },
                            }"
                            class="flex py-2 px-4 border border-transparent text-sm rounded-md text-spccolor-600 hover:bg-spccolor-600 hover:text-white transition-colors focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                                class="size-6"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z"
                                />
                            </svg>

                            Tallied Responses
                        </router-link>

                        <router-link
                            :to="{
                                name: 'ResultsDescriptive',
                            }"
                            class="flex py-2 px-4 border border-transparent text-sm rounded-md text-spccolor-600 hover:bg-spccolor-600 hover:text-white transition-colors focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5 mr-2"
                                viewBox="0 0 20 20"
                                fill="currentColor"
                            >
                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                <path
                                    fill-rule="evenodd"
                                    d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                            View Analysis
                        </router-link>
                    </div>
                </div>
            </div>
            <div
                class="row-span-2 animate-fade-in-down order-3 lg:order-1 bg-white shadow-md p-4"
            >
                <h3 class="text-2xl font-semibold">
                    View Survey Data Visualization
                </h3>
                <div>
                    <div class="flex justify-between">
                        <router-link
                            :to="{
                                name: 'ResultsVisualQuestions',
                                params: { id: model.id },
                            }"
                            class="flex py-2 px-4 border border-transparent text-sm rounded-md text-spccolor-600 hover:bg-spccolor-600 hover:text-white transition-colors focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                                class="size-6"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 5.25h.008v.008H12v-.008Z"
                                />
                            </svg>

                            Questions
                        </router-link>

                        <router-link
                            :to="{
                                name: 'ResultsVisual',
                                params: { id: model.id },
                            }"
                            class="flex py-2 px-4 border border-transparent text-sm rounded-md text-spccolor-600 hover:bg-spccolor-600 hover:text-white transition-colors focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                                class="size-6"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M7.5 14.25v2.25m3-4.5v4.5m3-6.75v6.75m3-9v9M6 20.25h12A2.25 2.25 0 0 0 20.25 18V6A2.25 2.25 0 0 0 18 3.75H6A2.25 2.25 0 0 0 3.75 6v12A2.25 2.25 0 0 0 6 20.25Z"
                                />
                            </svg>

                            Charts
                        </router-link>
                    </div>
                </div>
            </div>
            <div
                class="row-span-2 animate-fade-in-down order-3 lg:order-1 bg-white shadow-md p-4"
            >
                <h3 class="text-2xl font-semibold">Survey PCA results</h3>
                <div>
                    <h3 class="font-bold text-xl mb-3">Survey Title: {{model.title}}</h3>
                    <div class="flex justify-between text-sm mb-1">
                        <div>Respondent Groups:</div>
                        <div
                            v-for="(group, i) of model.respondent_groups"
                            :key="i"
                        >
                            {{ group.type }}
                        </div>
                    </div>
                    <div class="flex justify-between text-sm mb-1">
                        <div>Status:</div>
                        <div>Active</div>
                    </div>
                    

                    <div class="flex justify-between">
                        <button
                            @click="logIdAndExportCsv"
                            class="flex py-2 px-4 border border-transparent text-sm rounded-md text-spccolor-600 hover:bg-spccolor-600 hover:text-white transition-colors focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                                class="size-6"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M3.375 19.5h17.25m-17.25 0a1.125 1.125 0 0 1-1.125-1.125M3.375 19.5h7.5c.621 0 1.125-.504 1.125-1.125m-9.75 0V5.625m0 12.75v-1.5c0-.621.504-1.125 1.125-1.125m18.375 2.625V5.625m0 12.75c0 .621-.504 1.125-1.125 1.125m1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125m0 3.75h-7.5A1.125 1.125 0 0 1 12 18.375m9.75-12.75c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125m19.5 0v1.5c0 .621-.504 1.125-1.125 1.125M2.25 5.625v1.5c0 .621.504 1.125 1.125 1.125m0 0h17.25m-17.25 0h7.5c.621 0 1.125.504 1.125 1.125M3.375 8.25c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125m17.25-3.75h-7.5c-.621 0-1.125.504-1.125 1.125m8.625-1.125c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125m-17.25 0h7.5m-7.5 0c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125M12 10.875v-1.5m0 1.5c0 .621-.504 1.125-1.125 1.125M12 10.875c0 .621.504 1.125 1.125 1.125m-2.25 0c.621 0 1.125.504 1.125 1.125M13.125 12h7.5m-7.5 0c-.621 0-1.125.504-1.125 1.125M20.625 12c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125m-17.25 0h7.5M12 14.625v-1.5m0 1.5c0 .621-.504 1.125-1.125 1.125M12 14.625c0 .621.504 1.125 1.125 1.125m-2.25 0c.621 0 1.125.504 1.125 1.125m0 1.5v-1.5m0 0c0-.621.504-1.125 1.125-1.125m0 0h7.5"
                                />
                            </svg>

                            CSV Data
                        </button>

                        <router-link
                            :to="{
                                name: 'ResultsPCA',
                                params: { id: model.id },
                            }"
                            class="flex py-2 px-4 border border-transparent text-sm rounded-md text-spccolor-600 hover:bg-spccolor-600 hover:text-white transition-colors focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5 mr-2"
                                viewBox="0 0 20 20"
                                fill="currentColor"
                            >
                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                <path
                                    fill-rule="evenodd"
                                    d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                            PCA Scatter Plot
                        </router-link>
                    </div>
                </div>
            </div>
        </div>
    </PageComponent>
</template>

<script setup>
import { computed, ref } from "vue";
import { useRoute, useRouter } from "vue-router";
import store from "../store";

import PageComponent from "../components/PageComponent.vue";

const route = useRoute();
const router = useRouter();

let model = ref({
    id: 0,
    title: "",
    slug: "",
    status: null,
    is_public: null,
    description: null,
    respondent_groups: [],
    expire_date: null,
    information_fields: [],
    questions: [],
    responses: [],
});

let totalResponses = ref(0);
let totalAnswerScale = ref(0);
let averageAnswerScale = ref(0);

const logIdAndExportCsv = () => {
    console.log("Survey ID:", model.value.id); // Log the ID
    console.log("Type of Survey ID:", typeof model.value.id); // Log the type of the ID
    responseCsv(Number(model.value.id)); // Ensure it is passed as a number
};
const responseCsv = (id) => {
    store.dispatch("responseCsv", id).then((data) => {
        console.log(data);
    });
};

if (route.params.id) {
    store.dispatch("fetchSurveyResultData", route.params.id).then((data) => {
        totalResponses.value = data.totalResponses;
        totalAnswerScale.value = data.totalAnswerScale;
        averageAnswerScale.value = data.averageAnswerScale;
        model.value = data.survey;
    });
}
</script>

<style></style>
