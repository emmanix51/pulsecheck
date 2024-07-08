<template>
    <PageComponent>
        <template v-slot:header>
            <div class="flex items-center justify-between">
                <h1 class="text-3xl font-bold text-gray-900">
                    Result Analysis for: Survey1
                </h1>
            </div>
        </template>
        <!-- <pre>{{ testData }}</pre> -->
        <pre>{{ filteredResponses }}</pre>
        <pre>{{ respondentGroups }}</pre>
        <pre>{{ informationFields }}</pre>
        <div class="text-gray-700">
            <div class="shadow-md sm:rounded-md sm:overflow-hidden">
                <div class="px-4 py-5 bg-white space-y-1 sm:p-6">
                    <div>
                        <h1>Filter Data Analysis Here</h1>
                    </div>
                    <div>
                        <div class="mt-2">
                            <div class="mt-1">
                                <span class="mr-2 flex flex-row items-center">
                                    <h3
                                        class="block text-sm font-medium text-gray-700"
                                    >
                                        Filter By:
                                    </h3>
                                    <label>
                                        Respondent group
                                        <input
                                            type="checkbox"
                                            v-model="showRespondentGroupFilter"
                                        />
                                    </label>
                                    <label>
                                        Respondent category
                                        <input
                                            type="checkbox"
                                            v-model="
                                                showRespondentCategoryFilter
                                            "
                                        />
                                    </label>
                                    <label>
                                        Information field
                                        <input
                                            type="checkbox"
                                            v-model="showInformationFieldFilter"
                                        />
                                    </label>

                                    <button @click="applyFilters">Apply</button>
                                </span>
                            </div>
                            <div v-if="showRespondentGroupFilter" class="mt-2">
                                <label>
                                    Select Respondent Group
                                    <div
                                        v-for="group in respondentGroups"
                                        :key="group.id"
                                    >
                                        <input
                                            type="checkbox"
                                            :value="group.type"
                                            v-model="filters.respondent_types"
                                        />
                                        {{ group.type }}
                                    </div>
                                </label>
                            </div>
                            <div
                                v-if="
                                    showRespondentCategoryFilter &&
                                    availableCategories.length
                                "
                                class="mt-2"
                            >
                                <label>
                                    Select Respondent Category
                                    <div
                                        v-for="category in availableCategories"
                                        :key="category"
                                    >
                                        <input
                                            type="checkbox"
                                            :value="category"
                                            v-model="
                                                filters.respondent_categories
                                            "
                                        />
                                        {{ category }}
                                    </div>
                                </label>
                            </div>
                            <div v-if="showInformationFieldFilter" class="mt-2">
                                <label
                                    v-for="field in informationFields"
                                    :key="field.id"
                                >
                                    <div v-if="field.type === 'select'">
                                        {{ field.label }}
                                        <div
                                            v-for="option in field.options"
                                            :key="option"
                                        >
                                            <input
                                                type="checkbox"
                                                :value="option"
                                                :checked="
                                                    isOptionSelected(
                                                        field.label,
                                                        option
                                                    )
                                                "
                                                @change="
                                                    toggleInformationField(
                                                        field.label,
                                                        option
                                                    )
                                                "
                                            />
                                            {{ option }}
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg mt-6">
                <table
                    class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400"
                >
                    <thead
                        class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400"
                    >
                        <tr>
                            <th scope="col" class="px-6 py-3">Response ID</th>
                            <th scope="col" class="px-6 py-3">
                                Respondent Group/s
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Respondent Category/s
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Information Fields
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Response Answer Average
                            </th>
                            <th scope="col" class="px-6 py-3">
                                View Response Answers
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="response in filteredResponses"
                            :key="response.id"
                            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600"
                        >
                            <th
                                scope="row"
                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white"
                            >
                                {{ response.id }}
                            </th>
                            <td class="px-6 py-4">
                                {{ response.respondent_type }}
                            </td>
                            <td class="px-6 py-4">
                                {{ response.respondent_category }}
                            </td>
                            <td class="px-6 py-4">
                                <div
                                    v-for="(value, key) in parseJson(
                                        response.information_fields
                                    )"
                                    :key="key"
                                >
                                    {{ key }}: {{ value }}
                                </div>
                                <!-- <div>wew</div> -->
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div>
                                    {{ calculateAverage(response.answers) }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a
                                    :href="`/survey/responses/${response.id}`"
                                    target="_blank"
                                >
                                    View response answers
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="p-4">
                    Selected Responses Average:
                    {{ averageAnswerScale.toFixed(2) }}
                </div>
            </div>
        </div>
    </PageComponent>
</template>

<script setup>
import { ref, onMounted, watch } from "vue";
import { useRoute } from "vue-router";
import axiosClient from "../axios";
import store from "../store";
import PageComponent from "../components/PageComponent.vue";

const route = useRoute();

const filters = ref({
    respondent_types: [],
    respondent_categories: [],
    information_field: {},
    question_ids: [],
    start_date: null,
    end_date: null,
});

const totalResponses = ref(0);
const totalAnswers = ref(0);
const averageAnswerScale = ref(0);
const filteredResponses = ref([]);

const showRespondentGroupFilter = ref(false);
const showRespondentCategoryFilter = ref(false);
const showInformationFieldFilter = ref(false);
const showQuestionFieldFilter = ref(false);

const respondentGroups = ref([]);
const informationFields = ref([]);
const availableCategories = ref([]);
const questions = ref([]);

const calculateAverage = (answers) => {
    if (!answers || !answers.length) return 0;
    const total = answers.reduce((sum, answer) => sum + answer.answer_scale, 0);
    return (total / answers.length).toFixed(2);
};

const applyFilters = () => {
    const params = { ...filters.value };

    const informationFieldEntries = Object.entries(params.information_field)
        .map(([key, value]) => {
            return value
                .map(
                    (option) =>
                        `information_field[${key}][]=${encodeURIComponent(
                            option
                        )}`
                )
                .join("&");
        })
        .join("&");

    const respondentGroupsParams = params.respondent_types
        .map((group) => `respondent_types[]=${encodeURIComponent(group)}`)
        .join("&");
    const respondentCategoriesParams = params.respondent_categories
        .map(
            (category) =>
                `respondent_categories[]=${encodeURIComponent(category)}`
        )
        .join("&");
    const questionIdsParams = params.question_ids
        .map((id) => `question_ids[]=${encodeURIComponent(id)}`)
        .join("&");

    const queryString = `${respondentGroupsParams}&${respondentCategoriesParams}&${informationFieldEntries}&${questionIdsParams}`;

    console.log(
        "Request URL:",
        `/survey/${route.params.id}/results/descriptive?${queryString}`
    );

    axiosClient
        .get(`/survey/${route.params.id}/results/descriptive?${queryString}`)
        .then((response) => {
            console.log(response);
            const data = response.data;
            totalResponses.value = data.totalResponses;
            totalAnswers.value = data.totalAnswers;
            averageAnswerScale.value = data.averageAnswerScale;
            filteredResponses.value = data.filteredResponses;
        })
        .catch((error) => {
            console.error("Error fetching descriptive analysis:", error);
        });
};

const isOptionSelected = (fieldLabel, option) => {
    return (
        filters.value.information_field[fieldLabel]?.includes(option) || false
    );
};

const toggleInformationField = (fieldLabel, option) => {
    if (!filters.value.information_field[fieldLabel]) {
        filters.value.information_field[fieldLabel] = [];
    }

    const index = filters.value.information_field[fieldLabel].indexOf(option);

    if (index === -1) {
        filters.value.information_field[fieldLabel].push(option);
    } else {
        filters.value.information_field[fieldLabel].splice(index, 1);
    }
};

watch(
    filters.value,
    (newFilters) => {
        updateCategories();
    },
    { deep: true }
);

const updateCategories = () => {
    const selectedGroups = respondentGroups.value.filter((group) =>
        filters.value.respondent_types.includes(group.type)
    );

    const categories = selectedGroups.flatMap((group) =>
        group.category ? group.category.split(",") : []
    );

    availableCategories.value = [...new Set(categories)];
};
const testData = ref({});

const parseJson = (jsonString) => {
    try {
        return JSON.parse(jsonString);
    } catch (error) {
        console.error("Error parsing JSON string:", error);
        return {};
    }
};

onMounted(() => {
    store.dispatch("fetchSurveyDetails", route.params.id).then((data) => {
        testData.value = data;
        filteredResponses.value = data.allResponses;
        respondentGroups.value = data.respondentGroups;
        informationFields.value = data.informationFields.map((field) => {
            field.options = field.options ? field.options.split(",") : [];
            return field;
        });
        questions.value = data.questions;

        // Apply filters to initialize averages
        applyFilters();
    });
});
</script>

<style></style>
