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
                <!-- <pre>{{ templates }}</pre> -->
                <!-- <pre>{{ surveys }}</pre> -->
                <div
                    v-for="template in templates"
                    :key="template.id"
                    class="flex flex-col py-4 px-6 shadow-md bg-white hover:bg-gray-50 h-[200px]"
                >
                    <router-link
                        :to="{
                            name: 'TemplateView',
                            params: { id: template.id },
                        }"
                        class="mt-4 text-lg font-bold"
                        >{{ template.name }}</router-link
                    >
                    <div class="flex justify-between items-center mt-3">
                        <router-link
                            :to="{
                                name: 'TemplateView',
                                params: { id: template.id },
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
                        <button
                            v-if="template.id"
                            type="button"
                            @click="deleteTemplate(template)"
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

function deleteTemplate(template) {
    if (confirm(`you sure you want to delete ${template.name} bruh?`)) {
        //delete survey

        store.dispatch("deleteTemplate", template.id).then(() => {
            alert("Template Deleted Successfully.");
            // Directly remove the template from the local state
            const index = templates.value.findIndex(
                (t) => t.id === template.id
            );
            if (index !== -1) {
                templates.value.splice(index, 1);
            }
        });
    } else {
        alert("okii");
    }
}
</script>

<style></style>
