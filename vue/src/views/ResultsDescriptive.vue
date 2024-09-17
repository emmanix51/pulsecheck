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
        <!-- <pre>{{ questionSections }}</pre> -->
        <!-- PREVIEW -->
        <div>
            <div>
                <h1 class="text-xl font-semibold">Survey Preview</h1>
            </div>
            <div
                v-for="(section, sectionIndex) of questionSections"
                :key="sectionIndex"
                class="mt-4"
            >
                <h1>SECTION {{ sectionIndex + 1 }}</h1>
                <div
                    v-for="(
                        question_group, groupIndex
                    ) of section.question_groups"
                    :key="groupIndex"
                    class="mt-4"
                >
                    <h3 class="text-lg font-semibold mb-4">
                        Preview for Group {{ question_group.number }}
                    </h3>
                    <h1 class="text-md font-semibold mb-4">
                        {{ question_group.number }}. {{ question_group.label }}
                    </h1>
                    <table
                        class="table-auto border-collapse border w-full text-left"
                    >
                        <thead>
                            <tr>
                                <th
                                    v-if="question_group.category_label"
                                    class="border px-4 py-2"
                                >
                                    {{ question_group.category_label }}
                                </th>
                                <th class="border px-4 py-2">Question</th>

                                <th class="border px-4 py-2">Answer</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Display questions with categories -->
                            <template
                                v-for="(
                                    category, categoryIndex
                                ) in question_group.question_categories"
                                :key="categoryIndex"
                            >
                                <tr
                                    v-for="(
                                        question, questionIndex
                                    ) in question_group.questions.filter(
                                        (q) => q.category === category
                                    )"
                                    :key="questionIndex"
                                >
                                    <!-- Display the category once for each group of questions -->
                                    <td
                                        v-if="questionIndex === 0"
                                        :rowspan="
                                            question_group.questions.filter(
                                                (q) => q.category === category
                                            ).length
                                        "
                                        class="border px-4 py-2"
                                    >
                                        {{ category }}
                                    </td>
                                    <td class="border px-4 py-2">
                                        {{ question.question }}
                                    </td>

                                    <td class="border px-4 py-2">
                                        <!-- Preview answer fields based on question type -->
                                        <!-- Likert Scale (1-5) preview -->
                                        <div
                                            v-if="
                                                question.question_type ===
                                                'radio'
                                            "
                                            class="flex space-x-2"
                                        >
                                            <div
                                                v-for="option in question.data"
                                                :key="option"
                                                class="flex items-center border-r border-gray-300 px-2"
                                            >
                                                <input
                                                    type="radio"
                                                    :value="option"
                                                    :name="
                                                        'preview-question-' +
                                                        questionIndex
                                                    "
                                                    disabled
                                                    class="mr-2"
                                                />
                                                {{ option }}
                                            </div>
                                        </div>
                                        <!-- Text question preview -->
                                        <div
                                            v-else-if="
                                                question.question_type ===
                                                'text'
                                            "
                                        >
                                            <input
                                                type="text"
                                                class="block w-full border px-3 py-2 rounded-md shadow-sm"
                                                placeholder="Answer"
                                                disabled
                                            />
                                        </div>
                                    </td>
                                </tr>
                            </template>

                            <!-- Display questions without a category -->
                            <template
                                v-for="(
                                    question, questionIndex
                                ) in question_group.questions.filter(
                                    (q) => !q.category
                                )"
                                :key="questionIndex"
                            >
                                <tr>
                                    <td
                                        v-if="
                                            questionIndex === 0 &&
                                            question_group.category_label
                                        "
                                        class="border px-4 py-2"
                                    ></td>
                                    <td>{{ question.question }}</td>

                                    <td>
                                        <!-- Likert Scale (1-5) preview -->
                                        <div
                                            v-if="
                                                question.question_type ===
                                                'radio'
                                            "
                                            class="mt-1 flex flex-row"
                                        >
                                            <div
                                                v-for="option in question.data"
                                                :key="option"
                                                class="flex items-center border-x border-gray-200 py-1 px-2 w-full"
                                            >
                                                <input
                                                    class="mr-2"
                                                    type="radio"
                                                    :name="
                                                        'question-' +
                                                        questionIndex
                                                    "
                                                    :value="option"
                                                    disabled
                                                />
                                                {{ option }}
                                            </div>
                                        </div>
                                        <!-- Text question preview -->
                                        <div
                                            v-else-if="
                                                question.question_type ===
                                                'text'
                                            "
                                            class="mt-1"
                                        >
                                            <input
                                                type="text"
                                                class="block w-full px-3 py-2 border rounded-md shadow-sm"
                                                disabled
                                            />
                                        </div>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- /PREVIEW -->
        <div class="text-gray-700">
            <div class="shadow-md sm:rounded-md sm:overflow-hidden">
                <div class="px-4 py-5 bg-white space-y-1 sm:p-6">
                    <div>
                        <h1>Filter Data Analysis Here</h1>
                    </div>
                    <div>
                        <div class="mt-2">
                            <div class="mt-1">
                                <span
                                    class="mr-2 gap-4 flex flex-row items-center"
                                >
                                    <h3
                                        class="block text-sm font-medium text-gray-700"
                                    >
                                        Filter By:
                                    </h3>
                                    <label>
                                        <input
                                            type="checkbox"
                                            v-model="showRespondentGroupFilter"
                                        />
                                        Respondent group
                                    </label>
                                    <label>
                                        <input
                                            type="checkbox"
                                            v-model="
                                                showRespondentCategoryFilter
                                            "
                                        />
                                        Respondent category
                                    </label>
                                    <label>
                                        <input
                                            type="checkbox"
                                            v-model="showInformationFieldFilter"
                                        />
                                        Information field
                                    </label>
                                    <label>
                                        <input
                                            type="checkbox"
                                            v-model="showQuestionFieldFilter"
                                        />
                                        Questions
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
                                    {{ field.label }}
                                    <div v-if="field.type === 'select'">
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
                            <div v-if="showQuestionFieldFilter" class="mt-2">
                                <label>
                                    Select Question
                                    <div
                                        v-for="question in questions"
                                        :key="question.id"
                                    >
                                        <input
                                            type="checkbox"
                                            :value="question.id"
                                            v-model="filters.question_ids"
                                        />
                                        {{ question.question }}
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-4">
                <h2 class="text-xl font-bold">Analysis Results</h2>
                <p>Number of Responses: {{ totalResponses }}</p>
                <p>Total Answers: {{ totalAnswers }}</p>
                <p>Average Answer Scale: {{ averageAnswerScale.toFixed(2) }}</p>
                <!-- Render filtered responses here -->
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
const questionSections = ref([]);

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
onMounted(() => {
    store.dispatch("fetchSurveyDetails", route.params.id).then((data) => {
        testData.value = data;
        respondentGroups.value = data.respondentGroups;
        informationFields.value = data.informationFields.map((field) => {
            field.options = field.options ? field.options.split(",") : [];
            return field;
        });
        questions.value = data.questions;
        questionSections.value = data.questionSections;
        // Parse question data if it's a radio question
        questionSections.value.forEach((section) => {
            section.question_groups.forEach((group) => {
                console.log(group.question_categories);
                group.question_categories = JSON.parse(
                    group.question_categories
                );
                console.log(group.question_categories);
                group.questions.forEach((question) => {
                    console.log(question.data);

                    if (question.question_type === "radio") {
                        question.data = JSON.parse(question.data);
                    }
                });
            });
        });
    });
});
</script>

<style></style>
