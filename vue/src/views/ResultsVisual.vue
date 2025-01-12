<template>
    <div>
        <!-- Header -->
        <div class="flex items-center justify-between">
            <h1 class="text-3xl font-bold text-gray-900">
                Result Analysis for: {{ surveyTitle }}
            </h1>
        </div>

        <!-- Content -->
        <div class="text-gray-700">
            <!-- Main Container -->
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg mt-6">
                <div class="flex flex-wrap justify-between">
                    <!-- Respondent Type Distribution -->
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

                    <!-- Respondent Category Distribution -->
                    <div class="w-full lg:w-1/2 px-4 mb-8">
                        <h2 class="text-2xl font-semibold mb-4">
                            Respondent Category Distribution
                        </h2>
                        <Doughnut
                            v-if="respondentCategoryChartData"
                            :data="respondentCategoryChartData"
                            :options="doughnutChartOptions"
                        />
                    </div>
                </div>

                <!-- Dynamic Fields -->
                <div class="flex flex-wrap justify-between">
                    <div
                        v-for="(field, index) in informationFields"
                        :key="field.name"
                        class="w-full lg:w-1/2 px-4 mb-8 relative"
                    >
                        <!-- Close Button -->
                        <button
                            @click="closeChart(index)"
                            class="shadow-md px-2 absolute top-2 right-2 text-red-500 hover:px-4 hover:transition-all"
                        >
                            close
                        </button>

                        <!-- Field Title -->
                        <h2 class="text-2xl font-semibold mb-4">
                            {{ field.name }} Distribution
                        </h2>

                        <!-- Field Chart -->
                        <Bar
                            v-if="field.chartData"
                            :data="field.chartData"
                            :options="chartOptions"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useRoute } from "vue-router";
import axiosClient from "../axios";
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
import ChartDataLabels from "chartjs-plugin-datalabels";

ChartJS.register(
    Title,
    Tooltip,
    Legend,
    BarElement,
    CategoryScale,
    LinearScale,
    ArcElement,
    ChartDataLabels // Register DataLabels plugin
);

// Vue State Variables
const route = useRoute();
const surveyTitle = ref("");
const respondentTypeChartData = ref(null);
const respondentCategoryChartData = ref(null);
const informationFields = ref([]);

// Color Palette
const colorPalette = [
    "#FF6384",
    "#36A2EB",
    "#FFCE56",
    "#4BC0C0",
    "#9966FF",
    "#FF9F40",
    "#42A5F5",
    "#FFA726",
    "#66BB6A",
    "#FF5722",
    "#E91E63",
    "#3F51B5",
];

// Chart Options for Bar Charts
const chartOptions = {
    responsive: true,
    maintainAspectRatio: true,
    plugins: {
        datalabels: {
            anchor: "end",
            align: "top",
            formatter: (value, context) => {
                const total = context.dataset.data.reduce(
                    (sum, val) => sum + val,
                    0
                );
                const percentage = ((value / total) * 100).toFixed(1);
                return `${value} (${percentage}%)`; // Show count and percentage
            },
            color: "#000", // Label text color
            font: {
                weight: "bold",
                size: 12,
            },
        },
    },
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

// Chart Options for Doughnut Charts
const doughnutChartOptions = {
    responsive: true,
    maintainAspectRatio: true,
    layout: {
        padding: {
            top: 20, // Top padding
        },
    },
    plugins: {
        datalabels: {
            anchor: "end", // Position labels at the end of each segment
            align: "center", // Align the labels outside the chart
            offset: 20, // Add spacing to move labels further out
            formatter: (value, context) => {
                const total = context.dataset.data.reduce(
                    (sum, val) => sum + val,
                    0
                );
                const percentage = ((value / total) * 100).toFixed(1);
                return `${percentage}%`;
            },
            color: "#000", // Label text color
            font: {
                weight: "bold",
                size: 12,
            },
        },
    },
    tooltip: {
        callbacks: {
            label: function (context) {
                const total = context.dataset.data.reduce(
                    (sum, val) => sum + val,
                    0
                );
                const percentage = ((context.raw / total) * 100).toFixed(1);
                return `${context.label}: ${context.raw} (${percentage}%)`;
            },
        },
    },
};

// Helper Functions
const countOccurrences = (details, key) => {
    const counts = {};
    details.forEach((detail) => {
        const value = detail[key];
        counts[value] = (counts[value] || 0) + 1;
    });
    return counts;
};

const countInformationFieldOccurrences = (details, fieldName) => {
    const counts = {};
    details.forEach((detail) => {
        const fieldValues = detail.information_fields;
        if (fieldValues && fieldValues[fieldName]) {
            const value = fieldValues[fieldName];
            counts[value] = (counts[value] || 0) + 1;
        }
    });
    return counts;
};

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

// Fetch Survey Data
const fetchSurveyData = async () => {
    try {
        const response = await axiosClient.get(
            `/survey/${route.params.id}/results/visualization`
        );
        const data = response.data;

        surveyTitle.value = data.survey.title || "";

        const uniqueResponseDetails = getUniqueResponses(data.responseDetails);

        // Respondent Type Chart
        const respondentTypeCounts = countOccurrences(
            uniqueResponseDetails,
            "respondent_type"
        );
        respondentTypeChartData.value = {
            labels: Object.keys(respondentTypeCounts),
            datasets: [
                {
                    label: "Count",
                    backgroundColor: colorPalette.slice(
                        0,
                        Object.keys(respondentTypeCounts).length
                    ),
                    data: Object.values(respondentTypeCounts),
                },
            ],
        };

        // Respondent Category Chart
        const respondentCategoryCounts = countOccurrences(
            uniqueResponseDetails,
            "respondent_category"
        );
        respondentCategoryChartData.value = {
            labels: Object.keys(respondentCategoryCounts),
            datasets: [
                {
                    label: "Count",
                    backgroundColor: colorPalette.slice(
                        0,
                        Object.keys(respondentCategoryCounts).length
                    ),
                    data: Object.values(respondentCategoryCounts),
                },
            ],
        };

        // Information Field Charts
        const allInformationFields = Object.keys(
            uniqueResponseDetails[0].information_fields
        );
        informationFields.value = allInformationFields.map((field, idx) => {
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
                            backgroundColor:
                                colorPalette[idx % colorPalette.length],
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

// Lifecycle Hook
onMounted(fetchSurveyData);
</script>

<style scoped>
/* Add styles if needed */
</style>
