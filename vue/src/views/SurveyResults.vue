<template>
    <PageComponent>
        <template v-slot:header>
            <div class="flex items-center justify-between">
                <h1 class="text-3xl font-bold text-gray-900">
                    Survey Result for: {{ model.title }}
                </h1>
            </div>
        </template>
        <pre>{{ model }}</pre>
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
                <button @click="logIdAndExportCsv">Response CSV</button>
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
                            class="flex py-2 px-4 border border-transparent text-sm rounded-md text-indigo-500 hover:bg-indigo-700 hover:text-white transition-colors focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5 mr-2"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"
                                />
                            </svg>
                            Tallied Responses
                        </router-link>

                        <router-link
                            :to="{
                                name: 'ResultsDescriptive',
                            }"
                            class="flex py-2 px-4 border border-transparent text-sm rounded-md text-indigo-500 hover:bg-indigo-700 hover:text-white transition-colors focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
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
                                name: 'SurveyView',
                                params: { id: 2 },
                            }"
                            class="flex py-2 px-4 border border-transparent text-sm rounded-md text-indigo-500 hover:bg-indigo-700 hover:text-white transition-colors focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5 mr-2"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"
                                />
                            </svg>
                            Interpretation
                        </router-link>

                        <router-link
                            :to="{
                                name: 'ResultsVisual',
                                params: { id: model.id },
                            }"
                            class="flex py-2 px-4 border border-transparent text-sm rounded-md text-indigo-500 hover:bg-indigo-700 hover:text-white transition-colors focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
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
                    <h3 class="font-bold text-xl mb-3">Survey Title</h3>
                    <div class="flex justify-between text-sm mb-1">
                        <div>Variance Explained:</div>
                        <div>PC1 = 80%, PC2= 15%</div>
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
                        <div>Active</div>
                    </div>
                    <div class="flex justify-between text-sm mb-1">
                        <div>Questions:</div>
                        <div>100</div>
                    </div>

                    <div class="flex justify-between">
                        <router-link
                            :to="{
                                name: 'SurveyView',
                                params: { id: 2 },
                            }"
                            class="flex py-2 px-4 border border-transparent text-sm rounded-md text-indigo-500 hover:bg-indigo-700 hover:text-white transition-colors focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
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
