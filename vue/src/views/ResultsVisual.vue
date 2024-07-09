<template>
    <PageComponent>
        <template v-slot:header>
            <div class="flex items-center justify-between">
                <h1 class="text-3xl font-bold text-gray-900">
                    Result Analysis for: {{ surveyTitle }}
                </h1>
            </div>
        </template>
        <pre>{{ filteredResponses }}</pre>
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
                <Bar
                    id="average-chart"
                    :data="chartData"
                    :options="chartOptions"
                />
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
import { Bar } from "vue-chartjs";
import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    BarElement,
    CategoryScale,
    LinearScale,
} from "chart.js";

ChartJS.register(
    Title,
    Tooltip,
    Legend,
    BarElement,
    CategoryScale,
    LinearScale
);

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

const chartData = ref({
    labels: [],
    datasets: [
        {
            label: "Average Answer Scale",
            backgroundColor: "#42A5F5",
            data: [], // Initial data will be updated in updateChartData
        },
    ],
});

const chartOptions = ref({
    responsive: true,
    maintainAspectRatio: false,
    scales: {
        y: {
            beginAtZero: true,
            title: {
                display: true,
                text: "Average Answer Scale",
            },
        },
    },
});

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

            updateChartData();
        })
        .catch((error) => {
            console.error("Error fetching descriptive analysis:", error);
        });
};

const updateChartData = () => {
    chartData.value.labels = questions.value.map((q) => q.title);
    chartData.value.datasets[0].data = questions.value.map((q) => {
        const responses = filteredResponses.value.filter((response) =>
            response.answers.some((answer) => answer.question_id === q.id)
        );
        if (responses.length === 0) {
            return 0;
        }
        const totalScale = responses.reduce((sum, response) => {
            const answer = response.answers.find(
                (answer) => answer.question_id === q.id
            );
            return sum + answer.answer_scale;
        }, 0);
        return (totalScale / responses.length).toFixed(2);
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

const surveyTitle = ref("");

onMounted(() => {
    store.dispatch("fetchSurveyDetails", route.params.id).then((data) => {
        surveyTitle.value = data.survey.title;
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

<style scoped></style>
