<template>
    <PageComponent>
        <template v-slot:header>
            <div class="flex justify-between items-center">
                <h1 class="text-3xl font-bold text-gray-900">Survey Results</h1>
            </div>
        </template>

        <!-- Results Table -->
        <section class="container mx-auto p-6 font-mono">
            <!-- <div class="mt-3 mb-2 flex max-w-md gap-x-4">
                <input
                    id="email-address"
                    name="email"
                    type="email"
                    autocomplete="email"
                    required=""
                    class="min-w-0 flex-auto rounded-md border-0 bg-white/5 px-3.5 py-2 text-black shadow ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6"
                    placeholder="Search here"
                />
                <button
                    type="submit"
                    class="flex-none rounded-md bg-indigo-500 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-400 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500"
                >
                    Search
                </button>
            </div> -->

            <div class="w-full mb-8 overflow-hidden rounded-lg shadow-lg">
                <div class="w-full overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr
                                class="text-md font-semibold tracking-wide text-left text-gray-900 bg-gray-100 uppercase border-b border-gray-600"
                            >
                                <th class="px-4 py-3">Title</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3">IsPublic</th>
                                <th class="px-4 py-3">View Results</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            <tr
                                v-for="survey in surveys"
                                :key="survey.id"
                                class="text-gray-700"
                            >
                                <td class="px-4 py-3 border">
                                    <div class="flex items-center text-sm">
                                        <div>
                                            <p class="font-semibold text-black">
                                                {{ survey.title }}
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td
                                    class="px-4 py-3 text-ms font-semibold border"
                                    v-if="survey.status != 1"
                                >
                                    Inactive
                                </td>
                                <td
                                    class="px-4 py-3 text-ms font-semibold border"
                                    v-else
                                >
                                    Active
                                </td>
                                <td
                                    class="px-4 py-3 text-xs border"
                                    v-if="survey.is_public != 1"
                                >
                                    Not Public
                                </td>
                                <td class="px-4 py-3 text-xs border" v-else>
                                    Public
                                </td>
                                <td class="px-4 py-3 text-sm border">
                                    <router-link
                                        :to="{
                                            name: 'SurveyResults',
                                            params: { id: survey.id },
                                        }"
                                    >
                                        View Results
                                    </router-link>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <!-- Pagination -->
        <div
            class="max-w-full md:max-w-screen-md lg:max-w-screen-lg xl:max-w-screen-xl mx-auto bg-white p-6 rounded-lg shadow-sm"
        >
            <div class="flex justify-center">
                <nav class="flex space-x-2" aria-label="Pagination">
                    <!-- Previous Button -->
                    <a
                        href="#"
                        @click.prevent="fetchSurveys(currentPage - 1)"
                        class="cursor-pointer relative inline-flex items-center px-4 py-2 text-sm bg-spccolor-600 hover:border-violet-100 text-white font-semibold cursor-pointer leading-5 rounded-md transition duration-150 ease-in-out focus:outline-none focus:shadow-outline-blue focus:border-blue-300 focus:z-10"
                        :class="{
                            'opacity-50 cursor-not-allowed': !prevPageUrl,
                        }"
                        :disabled="!prevPageUrl"
                    >
                        Previous
                    </a>

                    <!-- Page Links -->
                  <div v-for="(link, i) of pageLinks" :key="i">
                        <a
                            :href="link.url"
                            class="cursor-pointer relative inline-flex items-center px-4 py-2 text-sm bg-spccolor-600 hover:border-violet-100 text-white font-semibold leading-5 rounded-md transition duration-150 ease-in-out focus:outline-none focus:shadow-outline-blue focus:border-blue-300 focus:z-10"
                            :class="{
                                ' cursor-not-allowed':
                                    !link.url ||
                                    parseInt(link.label) === currentPage,
                                'bg-blue-500': link.active,
                            }"
                            :disabled="
                                !link.url ||
                                parseInt(link.label) === currentPage
                            "
                            @click.prevent="
                                !(
                                    !link.url ||
                                    parseInt(link.label) === currentPage
                                ) && fetchSurveys(parseInt(link.label))
                            "
                        >
                            {{ link.label }}
                        </a>
                    </div>

                    <!-- Next Button -->
                    <a
                        href="#"
                        @click.prevent="fetchSurveys(currentPage + 1)"
                        class="cursor-pointer relative inline-flex items-center px-4 py-2 text-sm bg-spccolor-600 hover:border-violet-100 text-white font-semibold cursor-pointer leading-5 rounded-md transition duration-150 ease-in-out focus:outline-none focus:shadow-outline-blue focus:border-blue-300 focus:z-10"
                        :class="{
                            'opacity-50 cursor-not-allowed': !nextPageUrl,
                        }"
                        :disabled="!nextPageUrl"
                    >
                        Next
                    </a>
                </nav>
                <!-- <pre>{{pageLinks}}</pre> -->

            </div>
        </div>
    </PageComponent>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import PageComponent from "../components/PageComponent.vue";
import store from "../store";

const currentPage = ref(1);
const pageLinks = ref([]);
const surveys = ref([]);
const prevPageUrl = ref(null);
const nextPageUrl = ref(null);

const fetchSurveys = async (page = 1) => {
    try {
        const response = await store.dispatch("getAllResults", page);
        surveys.value = response.surveys.data;
        const links = response.surveys.links;

         if (links.length > 0) {
            pageLinks.value = links.slice(1, -1);
        }
        currentPage.value = page;

        prevPageUrl.value = response.surveys.prev_page_url;
        nextPageUrl.value = response.surveys.next_page_url;
    } catch (error) {
        console.error("Error fetching surveys:", error);
    }
};

onMounted(() => {
    fetchSurveys(currentPage.value);
});
</script>
