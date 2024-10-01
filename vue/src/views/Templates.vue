<template>
    <div>
        <PageComponent>
            <template v-slot:header>
                <div class="flex justify-between items-center">
                    <h1 class="text-3xl font-bold text-gray-900">
                        My templates
                    </h1>
                </div>
            </template>
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 md:grid-cols-3">
                <pre>{{ templates }}</pre>
                <!-- <pre>{{ surveys }}</pre> -->
                <div
                    v-for="survey in surveys"
                    :key="survey.id"
                    class="flex flex-col py-4 px-6 shadow-md bg-white hover:bg-gray-50 h-[200px]"
                >
                    <router-link
                        :to="{
                            name: 'SurveyView',
                            params: { id: survey.id },
                        }"
                        class="mt-4 text-lg font-bold"
                        >{{ survey.title }}</router-link
                    >
                    <div
                        v-html="survey.description"
                        class="overflow-hidden flex-1"
                    ></div>
                    <div class="flex justify-between items-center mt-3">
                        <router-link
                            :to="{
                                name: 'SurveyView',
                                params: { id: survey.id },
                            }"
                            class="flex py-2 px-4 border border-transparent text-sm rounded-md text-white bg-spccolor-600 hover:bg-spccolor-500 focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
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
                            Edit
                        </router-link>
                        <router-link
                            :to="{
                                name: 'SurveyResults',
                                params: { id: survey.id },
                            }"
                            class="flex py-2 px-4 border border-transparent text-sm rounded-md text-white bg-spccolor-600 hover:bg-spccolor-500 focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
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
                                    d="M15.75 15.75V18m-7.5-6.75h.008v.008H8.25v-.008Zm0 2.25h.008v.008H8.25V13.5Zm0 2.25h.008v.008H8.25v-.008Zm0 2.25h.008v.008H8.25V18Zm2.498-6.75h.007v.008h-.007v-.008Zm0 2.25h.007v.008h-.007V13.5Zm0 2.25h.007v.008h-.007v-.008Zm0 2.25h.007v.008h-.007V18Zm2.504-6.75h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V13.5Zm0 2.25h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V18Zm2.498-6.75h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V13.5ZM8.25 6h7.5v2.25h-7.5V6ZM12 2.25c-1.892 0-3.758.11-5.593.322C5.307 2.7 4.5 3.65 4.5 4.757V19.5a2.25 2.25 0 0 0 2.25 2.25h10.5a2.25 2.25 0 0 0 2.25-2.25V4.757c0-1.108-.806-2.057-1.907-2.185A48.507 48.507 0 0 0 12 2.25Z"
                                />
                            </svg>

                            Results
                        </router-link>
                        <button
                            v-if="survey.id"
                            type="button"
                            @click="deleteSurvey(survey)"
                            class="h-8 w-8 flex items-center justify-center rounded-full border border-transparent text-sm text-red-500 focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5"
                                viewBox="0 0 20 20"
                                fill="currentColor"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                        </button>
                        <button
                            type="button"
                            @click="distributeSurvey(survey)"
                            class="h-8 w-8 flex items-center justify-center rounded-full border border-transparent text-sm text-emerald-500 focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500"
                        >
                            Distribute
                        </button>
                    </div>
                </div>
            </div>
        </PageComponent>
    </div>
</template>

<script setup>
import { computed, onMounted, ref } from "vue";
import { useRoute, useRouter } from "vue-router";
import PageComponent from "../components/PageComponent.vue";
import store from "../store";
const route = useRoute();
const router = useRouter();
const templates = ref({});

onMounted(async () => {
    // const userId = computed(() => store.state.user.data.id);
    try {
        const response = await store.dispatch("getTemplates");
        templates.value = response.data;
        currentPage.value = page;

        // if (links.length > 0) {
        //     pageLinks.value = links.slice(1, -1);
        // }
        // // pageLinks.value = response.users.links;
        // currentPage.value = 1;
        // console.log(pageLinks);
    } catch (error) {
        console.error("Error fetching your templates:", error);
    }
    // store.dispatch("getTemplates");
});

function deleteSurvey(survey) {
    if (confirm(`you sure you want to delete ${survey.title} survey bruh?`)) {
        //delete survey

        store.dispatch("deleteSurvey", survey.id).then(() => {
            router.push({ name: "Surveys" });
            alert("Survey Deleted Successfully.");
            store.dispatch("getSurveys");
        });
    } else {
        alert("okii");
    }
}
</script>

<style></style>
