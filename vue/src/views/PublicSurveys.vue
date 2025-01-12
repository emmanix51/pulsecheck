<template>
    <div>
        <!-- <pre>{{ publicSurveys }}</pre> -->
        <div
            class="relative flex min-h-screen flex-col justify-center overflow-hidden bg-gray- py-6 sm:py-12"
        >
            <div class="mx-auto max-w-screen-xl px-4 w-full">
                <h2 class="mb-4 font-bold text-xl text-gray-600">
                    Pulse Check Available Public Surveys
                </h2>
                <div class="grid w-full sm:grid-cols-2 xl:grid-cols-4 gap-6">
                    <div
                        v-for="(survey, index) of publicSurveys"
                        :key="index"
                        class="relative flex flex-col shadow-md rounded-xl overflow-hidden hover:shadow-lg hover:-translate-y-1 transition-all duration-300 max-w-sm"
                    >
                        <div class="h-auto overflow-hidden">
                            <div class="h-44 overflow-hidden relative">
                                <img
                                    :src="`https://api.qrserver.com/v1/create-qr-code/?data=${encodeURIComponent(
                                        `${baseUrl}/public/survey/${survey.slug}`
                                    )}&size=100x100`"
                                    alt="QR Code for {{ survey.title }}"
                                    class="mt-2 w-[50%] mx-auto"
                                />
                            </div>
                        </div>
                        <div class="bg-white py-4 px-3">
                            <a
                                :href="`/public/survey/${survey.slug}`"
                                target="_blank"
                                v-if="survey.slug"
                                class="text-xs mb-2 font-medium"
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
                                {{ survey.title }}
                            </a>
                            <div class="flex justify-between items-center">
                                <p class="text-xs text-gray-400">
                                    Expired at: {{ survey.expire_date }}
                                </p>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Pagination -->
        <div
            class="max-w-full md:max-w-screen-md lg:max-w-screen-lg xl:max-w-screen-xl mx-auto bg-white p-6 rounded-lg shadow-sm"
        >
            <div class="flex justify-center">
                <nav class="flex space-x-2" aria-label="Pagination">
                    <a
                        href="#"
                        @click.prevent="fetchSurveys(currentPage - 1)"
                        class="relative inline-flex items-center px-4 py-2 text-sm bg-gradient-to-r from-violet-300 to-indigo-300 border border-fuchsia-100 hover:border-violet-100 text-white font-semibold cursor-pointer leading-5 rounded-md transition duration-150 ease-in-out focus:outline-none focus:shadow-outline-blue focus:border-blue-300 focus:z-10"
                        :class="{
                            'opacity-50 cursor-not-allowed': currentPage === 1,
                        }"
                        :disabled="currentPage === 1"
                    >
                    </a>
                    <a
                        v-for="(link, i) of pageLinks"
                        :key="i"
                        :disabled="!link.url"
                        v-html="link.label"
                        @click.prevent="fetchSurveys(link.label)"
                    ></a>
                    <a
                        href="#"
                        @click.prevent="fetchSurveys(currentPage + 1)"
                        class="relative inline-flex items-center px-4 py-2 text-sm bg-gradient-to-r from-violet-300 to-indigo-300 border border-fuchsia-100 hover:border-violet-100 text-white font-semibold cursor-pointer leading-5 rounded-md transition duration-150 ease-in-out focus:outline-none focus:shadow-outline-blue focus:border-blue-300 focus:z-10"
                        :class="{
                            'opacity-50 cursor-not-allowed': !hasNextPage,
                        }"
                        :disabled="!hasNextPage"
                    >
                    </a>
                </nav>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed, onMounted, ref } from "vue";
import { useStore } from "vuex";
import { useRouter } from "vue-router";
import { TailwindPagination } from "laravel-vue-pagination";

const store = useStore();
const router = useRouter();
const token = computed(() => store.state.user.token);
const publicSurveys = ref([]);
const pageLinks = ref([]);
const currentPage = ref(1);

const baseUrl = import.meta.env.VITE_BASE_URL;
console.log(baseUrl);

onMounted(async () => {
    if (token.value) {
        router.push({ name: "Dashboard" });
    } else {
        try {
            const response = await store.dispatch("getPublicSurveys", 1);
            publicSurveys.value = response.public_surveys.data;
            pageLinks.value = response.public_surveys.links;
            currentPage.value = 1;
            // console.log(publicSurveys.value); // Log the actual surveys data
            console.log(response); // Log the actual surveys data
        } catch (error) {
            console.error("Error fetching public surveys:", error);
            // Handle error if needed
        }
    }
});
async function fetchSurveys(page) {
    try {
        const response = await store.dispatch("getPublicSurveys", page); // Pass the page number
        publicSurveys.value = response.public_surveys.data; // Update the public surveys with new data
        currentPage.value = page;
    } catch (error) {
        console.error("Error fetching public surveys:", error);
        // Handle error if needed
    }
}
</script>

<style></style>
