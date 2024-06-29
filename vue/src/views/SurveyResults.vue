<template>
    <PageComponent>
        <template v-slot:header>
            <div class="flex items-center justify-between">
                <h1 class="text-3xl font-bold text-gray-900">
                    Survey Result for: Survey Title
                </h1>
                <!-- <div class="flex">
                    
                    <a
                        :href="`/view/survey/${model.slug}`"
                        target="_blank"
                        v-if="model.slug"
                        class="flex py-2 px-4 border border-transparent text-sm rounded-md text-indigo-500 hover:bg-indigo-700 hover:text-white transition-colors focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-2"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"
                            />
                        </svg>
                        View Public link
                    </a>
                    <button
                        v-if="route.params.id"
                        type="button"
                        @click="deleteSurvey()"
                        class="py-2 px-3 text-white bg-red-500 rounded-md hover:bg-red-600"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5 -mt-1 inline-block"
                            viewBox="0 0 20 20"
                            fill="currentColor"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                clip-rule="evenodd"
                            />
                        </svg>
                        Delete Survey
                    </button>
                </div> -->
            </div>
        </template>
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
                        <div>419</div>
                    </div>
                    <div class="flex justify-between text-sm mb-1">
                        <div>Respondent Groups:</div>
                        <div>Student, Faculty</div>
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
                            Edit Survey
                        </router-link>

                        <button
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
                            View Answers
                        </button>
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

                        <button
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
                        </button>
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
                        <div>Student, Faculty</div>
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
import PageComponent from "../components/PageComponent.vue";
</script>

<style></style>
