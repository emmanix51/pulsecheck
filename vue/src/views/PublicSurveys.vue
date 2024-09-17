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
                <div
                    class="grid w-full sm:grid-cols-2 xl:grid-cols-4 gap-6"
                    v-for="(survey, index) of publicSurveys"
                    :key="index"
                >
                    <div
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
                            <h3 class="text-xs mb-2 font-medium">
                                {{ survey.title }}
                            </h3>
                            <div class="flex justify-between items-center">
                                <p class="text-xs text-gray-400">
                                    Expired at: {{ survey.expire_date }}
                                </p>
                                <div
                                    class="relative z-40 flex items-center gap-2"
                                >
                                    <a
                                        class="text-orange-600 hover:text-blue-500"
                                        :href="`public/survey/${survey.slug}`"
                                        target="_blank"
                                    >
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke-width="1.5"
                                            stroke="currentColor"
                                            class="w-6 h-6"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3"
                                            />
                                        </svg>
                                    </a>
                                    <a
                                        class="text-orange-600 hover:text-blue-500"
                                        href=""
                                    >
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke-width="1.5"
                                            stroke="currentColor"
                                            class="w-6 h-6"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0"
                                            />
                                        </svg>
                                    </a>
                                </div>
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
