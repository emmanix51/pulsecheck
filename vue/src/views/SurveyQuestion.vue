<template>
    <PageComponent>
        <template v-slot:header>
            <div class="flex items-center justify-between">
                <h1 class="text-3xl font-bold text-gray-900">
                    Data Visualization for Question:
                </h1>
            </div>
        </template>
        <div class="text-gray-700">
            <div class="shadow-md sm:rounded-md sm:overflow-hidden">
                <PolarArea
                    v-if="responseChartData"
                    :data="responseChartData"
                    :options="PolarAreachartOptions"
                />
            </div>
            <div class="shadow-md sm:rounded-md sm:overflow-hidden mt-6">
                <Bar
                    v-if="respondentTypeChartData"
                    :data="respondentTypeChartData"
                    :options="BarchartOptionsWithType"
                />
            </div>
            <div class="shadow-md sm:rounded-md sm:overflow-hidden mt-6">
                <Bar
                    v-if="respondentCategoryChartData"
                    :data="respondentCategoryChartData"
                    :options="BarchartOptionsWithCategory"
                />
            </div>
        </div>
    </PageComponent>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useRoute } from "vue-router";
import axiosClient from "../axios";
import PageComponent from "../components/PageComponent.vue";
import { Bar, PolarArea } from "vue-chartjs";
import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    ArcElement,
    RadialLinearScale,
    BarElement,
    CategoryScale,
    LinearScale,
} from "chart.js";

ChartJS.register(
    Title,
    Tooltip,
    Legend,
    ArcElement,
    RadialLinearScale,
    BarElement,
    CategoryScale,
    LinearScale
);

const route = useRoute();

const responseChartData = ref(null);
const respondentTypeChartData = ref(null);
const respondentCategoryChartData = ref(null);

const PolarAreachartOptions = {
    responsive: true,
    maintainAspectRatio: true,
};

// const BarchartOptions = {
//     responsive: true,
//     maintainAspectRatio: true,
//     scales: {
//         y: {
//             beginAtZero: true,
//             title: {
//                 display: true,
//                 text: "Count",
//             },
//         },
//         x: {
//             title: {
//                 display: true,
//                 text: "Categories",
//             },
//         },
//     },
// };

// Function to map numeric scale to descriptive labels
const mapScaleToLabel = (scale) => {
    switch (scale) {
        case 1:
            return "Strongly Disagree";
        case 2:
            return "Disagree";
        case 3:
            return "Neutral";
        case 4:
            return "Agree";
        case 5:
            return "Strongly Agree";
        default:
            return "";
    }
};

// Function to count occurrences of answer scales
const countOccurrences = (data, key) => {
    const counts = {};
    data.forEach((item) => {
        const value = item[key];
        if (counts[value]) {
            counts[value]++;
        } else {
            counts[value] = 1;
        }
    });
    return counts;
};

// Function to count occurrences of answer scales for each category type
const countOccurrencesByCategory = (data, categoryKey, scaleKey) => {
    const counts = {};
    data.forEach((item) => {
        const category = item[categoryKey];
        const scale = item[scaleKey];
        if (!counts[category]) {
            counts[category] = { 1: 0, 2: 0, 3: 0, 4: 0, 5: 0 };
        }
        counts[category][scale]++;
    });
    return counts;
};

const fetchQuestionData = async () => {
    try {
        const response = await axiosClient.get(
            `/survey/question/${route.params.id}`
        );
        const data = response.data.responseAnswersData;

        const responseCounts = countOccurrences(data, "answer_scale");

        // Ensure all answer scales from 1 to 5 are represented in the chart data
        const labels = [1, 2, 3, 4, 5];
        const counts = labels.map((label) => responseCounts[label] || 0);
        const labelDescriptions = labels.map((label) => mapScaleToLabel(label));

        responseChartData.value = {
            labels: labelDescriptions, // Use descriptive labels
            datasets: [
                {
                    label: "Answer Scale",
                    backgroundColor: [
                        "#FF6384",
                        "#36A2EB",
                        "#FFCE56",
                        "#4BC0C0",
                        "#9966FF",
                    ],
                    data: counts,
                },
            ],
        };

        // Process data for Bar charts
        const typeCounts = countOccurrencesByCategory(
            data,
            "respondent_type",
            "answer_scale"
        );
        const categoryCounts = countOccurrencesByCategory(
            data,
            "respondent_category",
            "answer_scale"
        );

        const createBarChartData = (counts) => {
            const categories = Object.keys(counts);
            const datasets = labels.map((label) => ({
                label: mapScaleToLabel(label), // Use descriptive labels
                backgroundColor: `#${Math.floor(
                    Math.random() * 16777215
                ).toString(16)}`, // Generate random color
                data: categories.map((category) => counts[category][label]),
            }));
            return {
                labels: categories,
                datasets: datasets,
            };
        };

        // Set options for Bar charts
        const BarchartOptionsWithType = {
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
                x: {
                    title: {
                        display: true,
                        text: "Respondent Type",
                    },
                },
            },
        };

        const BarchartOptionsWithCategory = {
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
                x: {
                    title: {
                        display: true,
                        text: "Respondent Category",
                    },
                },
            },
        };

        respondentTypeChartData.value = createBarChartData(typeCounts);
        respondentCategoryChartData.value = createBarChartData(categoryCounts);

        // Assign the appropriate options based on the chart type
        BarchartOptions =
            route.params.chartType === "respondentTypeChartData"
                ? BarchartOptionsWithType
                : BarchartOptionsWithCategory;
    } catch (error) {
        console.error("Error fetching survey data:", error);
    }
};

onMounted(fetchQuestionData);
</script>

<style scoped>
/* Add any relevant styles here */
</style>
