<template>
    <PageComponent title="Create Respondent Group">
        <form @submit.prevent="createRespondentGroup" class="space-y-6">
            <div>
                <label
                    for="name"
                    class="block text-sm font-medium text-gray-700"
                    >Name</label
                >
                <input
                    type="text"
                    name="name"
                    id="name"
                    v-model="newGroup.name"
                    class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                    required
                />
            </div>

            <div>
                <label
                    for="type"
                    class="block text-sm font-medium text-gray-700"
                    >Type</label
                >
                <select
                    name="type"
                    id="type"
                    v-model="newGroup.type"
                    class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                    required
                >
                    <option value="" disabled>Select a type</option>
                    <option value="student">Student</option>
                    <option value="faculty">Faculty</option>
                    <option value="staff">Staff</option>
                    <option value="stakeholder">Stakeholder</option>
                </select>
            </div>

            <div v-if="newGroup.type" class="mt-4">
                <label
                    for="category"
                    class="block text-sm font-medium text-gray-700"
                >
                    {{
                        newGroup.type === "student"
                            ? "Student Category"
                            : newGroup.type === "faculty"
                            ? "Faculty Department"
                            : "Category"
                    }}
                </label>
                <input
                    type="text"
                    name="category"
                    id="category"
                    v-model="newGroup.category"
                    class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                    placeholder="e.g. Undergraduate, Graduate, Science, Arts"
                />
            </div>

            <div class="flex justify-end">
                <button
                    type="submit"
                    class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                    Create Group
                </button>
            </div>
        </form>

        <div class="mt-10">
            <h2 class="text-xl font-semibold">Existing Respondent Groups</h2>
            <ul class="mt-4">
                <li
                    v-for="group in respondentGroups"
                    :key="group.id"
                    class="py-2 border-b"
                >
                    <strong>{{ group.name }}</strong> - {{ group.type
                    }}{{ group.category ? ` (${group.category})` : "" }}
                </li>
            </ul>
        </div>
    </PageComponent>
</template>

<script setup>
import { ref, onMounted } from "vue";
import axiosClient from "../axios";
import PageComponent from "../components/PageComponent.vue";

const newGroup = ref({ name: "", type: "", category: "" });
const respondentGroups = ref([]);

const fetchRespondentGroups = async () => {
    try {
        const response = await axiosClient.get("/respondent-groups");
        respondentGroups.value = response.data;
    } catch (error) {
        console.error("Error fetching respondent groups:", error);
    }
};

const createRespondentGroup = async () => {
    try {
        const response = await axiosClient.post(
            "/respondent-groups",
            newGroup.value
        );
        respondentGroups.value.push(response.data);
        newGroup.value = { name: "", type: "", category: "" };
    } catch (error) {
        console.error("Error creating respondent group:", error);
    }
};

onMounted(() => {
    fetchRespondentGroups();
});
</script>

<style scoped>
/* Your styles here */
</style>
