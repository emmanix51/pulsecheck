<template>
    <PageComponent>
        <template v-slot:header>
            <div class="flex items-center justify-between">
                <h1 class="text-3xl font-bold text-gray-900">
                    Result Analysis for: {{ surveyTitle }}
                </h1>
            </div>
        </template>
        <div class="text-gray-700">
            <div class="shadow-md sm:rounded-md sm:overflow-hidden"></div>
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg mt-6">
                <div class="flex flex-wrap justify-between">
                    <div class="w-full lg:w-1/2 px-4 mb-8">
                        <h2 class="text-2xl font-semibold mb-4">
                            Respondent Type Distribution
                        </h2>
                        <Bar
                            v-if="respondentTypeChartData"
                            :data="respondentTypeChartData"
                            :options="chartOptions"
                        />
                    </div>
                    <div class="w-full lg:w-1/2 px-4 mb-8">
                        <h2 class="text-2xl font-semibold mb-4">
                            Respondent Category Distribution
                        </h2>
                        <Doughnut
                            v-if="respondentCategoryChartData"
                            :data="respondentCategoryChartData"
                            :options="chartOptions"
                        />
                    </div>
                </div>

                <div class="flex flex-wrap justify-between">
                    <div
                        v-for="(field, index) in informationFields"
                        :key="field.name"
                        class="w-full lg:w-1/2 px-4 mb-8 relative"
                    >
                        <button
                            @click="closeChart(index)"
                            class="shadow-md px-2 absolute top-2 right-2 text-red-500 hover:px-4 hover:transition-all"
                        >
                            close
                        </button>
                        <h2 class="text-2xl font-semibold mb-4">
                            {{ field.name }} Distribution
                        </h2>
                        <Bar
                            v-if="field.chartData"
                            :data="field.chartData"
                            :options="chartOptions"
                        />
                    </div>
                </div>
            </div>
        </div>
    </PageComponent>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useRoute } from "vue-router";
import axiosClient from "../axios";
import PageComponent from "../components/PageComponent.vue";
import { Bar, Doughnut } from "vue-chartjs";
import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    BarElement,
    CategoryScale,
    LinearScale,
    ArcElement,
} from "chart.js";

ChartJS.register(
    Title,
    Tooltip,
    Legend,
    BarElement,
    CategoryScale,
    LinearScale,
    ArcElement
);

const route = useRoute();

const surveyTitle = ref("");
const respondentTypeChartData = ref(null);
const respondentCategoryChartData = ref(null);
const informationFields = ref([]);

const BarchartOptions = {
    responsive: true,
    maintainAspectRatio: true,
    scales: {
        y: {
            beginAtZero: true,
            title: {
                display: true,
                text: "Count",
            },
        },
    },
};
const DoughnutchartOptions = {
    responsive: true,
    maintainAspectRatio: true,
};

// Function to count occurrences of respondent types
const countOccurrences = (details, key) => {
    const counts = {};
    details.forEach((detail) => {
        const value = detail[key];
        if (counts[value]) {
            counts[value]++;
        } else {
            counts[value] = 1;
        }
    });
    return counts;
};
// Function to count occurrences of information fields
const countInformationFieldOccurrences = (details, fieldName) => {
    const counts = {};
    details.forEach((detail) => {
        const fieldValues = detail.information_fields;
        if (fieldValues && fieldValues[fieldName]) {
            const value = fieldValues[fieldName];
            if (counts[value]) {
                counts[value]++;
            } else {
                counts[value] = 1;
            }
        }
    });
    return counts;
};

// Function to get unique responses by response_id
const getUniqueResponses = (details) => {
    const uniqueResponses = [];
    const responseIds = new Set();

    details.forEach((detail) => {
        if (!responseIds.has(detail.response_id)) {
            responseIds.add(detail.response_id);
            uniqueResponses.push(detail);
        }
    });

    return uniqueResponses;
};

const closeChart = (index) => {
    informationFields.value.splice(index, 1);
};

const fetchSurveyData = async () => {
    try {
        const response = await axiosClient.get(
            `/survey/${route.params.id}/results/visualization`
        );
        const data = response.data;

        surveyTitle.value = data.survey.title || "";

        // Filter unique responses
        const uniqueResponseDetails = getUniqueResponses(data.responseDetails);

        // Process respondent types
        const respondentTypeCounts = countOccurrences(
            uniqueResponseDetails,
            "respondent_type"
        );
        respondentTypeChartData.value = {
            labels: Object.keys(respondentTypeCounts),
            datasets: [
                {
                    label: "Count",
                    backgroundColor: "#42A5F5",
                    data: Object.values(respondentTypeCounts),
                },
            ],
        };

        // Process respondent categories
        const respondentCategoryCounts = countOccurrences(
            uniqueResponseDetails,
            "respondent_category"
        );
        respondentCategoryChartData.value = {
            labels: Object.keys(respondentCategoryCounts),
            datasets: [
                {
                    label: "Count",
                    backgroundColor: [
                        "#FF6384",
                        "#36A2EB",
                        "#FFCE56",
                        "#4BC0C0",
                        "#9966FF",
                        "#FF9F40",
                    ],
                    data: Object.values(respondentCategoryCounts),
                },
            ],
        };

        // Process information fields
        const allInformationFields = Object.keys(
            uniqueResponseDetails[0].information_fields
        );
        informationFields.value = allInformationFields.map((field) => {
            const fieldCounts = countInformationFieldOccurrences(
                uniqueResponseDetails,
                field
            );
            return {
                name: field,
                chartData: {
                    labels: Object.keys(fieldCounts),
                    datasets: [
                        {
                            label: "Count",
                            backgroundColor: "#FFA726",
                            data: Object.values(fieldCounts),
                        },
                    ],
                },
            };
        });
    } catch (error) {
        console.error("Error fetching survey data:", error);
    }
};

onMounted(fetchSurveyData);
</script>
<style scoped>
/* Add any relevant styles here */
</style>
