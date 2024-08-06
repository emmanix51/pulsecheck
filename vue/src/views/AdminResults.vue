<template>
    <PageComponent>
        <template v-slot:header>
            <div class="flex justify-between items-center">
                <h1 class="text-3xl font-bold text-gray-900">Survey Results</h1>
                <button
                    @click="showAddUserForm = true"
                    class="py-2 px-3 text-white bg-emerald-500 rounded-md hover:bg-emerald-600"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-4 w-4 -mt-1 inline-block"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 4v16m8-8H4"
                        />
                    </svg>
                    Add User
                </button>
            </div>
        </template>

        <!-- Results Table -->
        <section class="container mx-auto p-6 font-mono">
            <div class="mt-3 mb-2 flex max-w-md gap-x-4">
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
            </div>
            <div class="w-full mb-8 overflow-hidden rounded-lg shadow-lg">
                <div class="w-full overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr
                                class="text-md font-semibold tracking-wide text-left text-gray-900 bg-gray-100 uppercase border-b border-gray-600"
                            >
                                <th class="px-4 py-3">Name</th>
                                <th class="px-4 py-3">IdNum</th>
                                <th class="px-4 py-3">Role</th>
                                <th class="px-4 py-3">Action</th>
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
                                                test
                                            </p>
                                            <p class="text-xs text-gray-600">
                                                test
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td
                                    class="px-4 py-3 text-ms font-semibold border"
                                >
                                    test
                                </td>
                                <td class="px-4 py-3 text-xs border">test</td>
                                <td class="px-4 py-3 text-sm border">
                                    <router-link> </router-link>
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
                    <a
                        href="#"
                        @click.prevent="fetchSurveys(currentPage - 1)"
                        class="cursor-pointer relative inline-flex items-center px-4 py-2 text-sm bg-gradient-to-r from-violet-300 to-indigo-300 border border-fuchsia-100 hover:border-violet-100 text-white font-semibold cursor-pointer leading-5 rounded-md transition duration-150 ease-in-out focus:outline-none focus:shadow-outline-blue focus:border-blue-300 focus:z-10"
                        :class="{
                            'opacity-50 cursor-not-allowed': currentPage === 1,
                        }"
                        :disabled="currentPage === '1'"
                    >
                        Previous
                    </a>
                    <div v-for="(link, i) of pageLinks" :key="i">
                        <a
                            :href="link.url"
                            class="cursor-pointer relative inline-flex items-center px-4 py-2 text-sm bg-gradient-to-r from-violet-300 to-indigo-300 border border-fuchsia-100 hover:border-violet-100 text-white font-semibold leading-5 rounded-md transition duration-150 ease-in-out focus:outline-none focus:shadow-outline-blue focus:border-blue-300 focus:z-10"
                            :class="{
                                'opacity-50 cursor-not-allowed':
                                    !link.url ||
                                    parseInt(link.label) === currentPage,
                                'bg-blue-500': link.active, // Optional: Apply active page styling
                            }"
                            :disabled="
                                !link.url ||
                                parseInt(link.label) === currentPage
                            "
                            @click.prevent="
                                !(
                                    !link.url ||
                                    parseInt(link.label) === currentPage
                                ) && fetchUsers(parseInt(link.label))
                            "
                        >
                            {{ link.label }}
                        </a>
                    </div>
                    <a
                        href="#"
                        @click.prevent="fetchUsers(currentPage + 1)"
                        class="cursor-pointer relative inline-flex items-center px-4 py-2 text-sm bg-gradient-to-r from-violet-300 to-indigo-300 border border-fuchsia-100 hover:border-violet-100 text-white font-semibold cursor-pointer leading-5 rounded-md transition duration-150 ease-in-out focus:outline-none focus:shadow-outline-blue focus:border-blue-300 focus:z-10"
                        :class="{
                            'opacity-50 cursor-not-allowed': !hasNextPage,
                        }"
                        :disabled="!hasNextPage"
                    >
                        Next
                    </a>
                </nav>
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

onMounted(async () => {
    try {
        const response = await store.dispatch("getAllResults");
        // const links = response.users.links;

        // if (links.length > 0) {
        //     // Assuming the first link is "Previous" and the last is "Next"

        //     pageLinks.value = links.slice(1, -1); // Exclude the first and last elements
        // }
        // // pageLinks.value = response.users.links;
        // currentPage.value = 1;
        console.log(response);
    } catch (error) {}
});
</script>
