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
            <div
                v-for="(data, key) in infoFieldChartData"
                :key="key"
                class="shadow-md sm:rounded-md sm:overflow-hidden mt-6"
            >
                <h2 class="text-2xl font-bold">{{ key }}</h2>
                <Radar v-if="data" :data="data" :options="RadarChartOptions" />
            </div>
        </div>
    </PageComponent>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useRoute } from "vue-router";
import axiosClient from "../axios";
import PageComponent from "../components/PageComponent.vue";
import { Bar, PolarArea, Radar } from "vue-chartjs";
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
    PointElement,
    LineElement,
    Filler,
} from "chart.js";

ChartJS.register(
    Title,
    Tooltip,
    Legend,
    ArcElement,
    RadialLinearScale,
    BarElement,
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    Filler
);

const route = useRoute();

const responseChartData = ref(null);
const respondentTypeChartData = ref(null);
const respondentCategoryChartData = ref(null);
const infoFieldChartData = ref({});
const RadarChartOptions = {
    responsive: true,
    maintainAspectRatio: true,
    scales: {
        r: {
            beginAtZero: true,
            ticks: {
                stepSize: 1,
            },
        },
    },
};

const PolarAreachartOptions = {
    responsive: true,
    maintainAspectRatio: true,
};

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

const createBarChartData = (counts, labels) => {
    const categories = Object.keys(counts);
    const datasets = labels.map((label) => ({
        label: mapScaleToLabel(label), // Use descriptive labels
        backgroundColor: `#${Math.floor(Math.random() * 16777215).toString(
            16
        )}`, // Generate random color
        data: categories.map((category) => counts[category][label]),
    }));
    return {
        labels: categories,
        datasets: datasets,
    };
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

        respondentTypeChartData.value = createBarChartData(typeCounts, labels);
        respondentCategoryChartData.value = createBarChartData(
            categoryCounts,
            labels
        );

        // Process data for info_field charts
        const infoFieldCounts = {};
        data.forEach((item) => {
            for (const [key, value] of Object.entries(item.info_field)) {
                if (!infoFieldCounts[key]) {
                    infoFieldCounts[key] = {};
                }
                if (!infoFieldCounts[key][value]) {
                    infoFieldCounts[key][value] = {
                        1: 0,
                        2: 0,
                        3: 0,
                        4: 0,
                        5: 0,
                    };
                }
                infoFieldCounts[key][value][item.answer_scale]++;
            }
        });

        // Create chart data for each info field
        for (const [key, valueCounts] of Object.entries(infoFieldCounts)) {
            const datasets = [];
            for (const [value, counts] of Object.entries(valueCounts)) {
                datasets.push({
                    label: value,
                    data: labels.map((label) => counts[label]),
                    backgroundColor: `rgba(${Math.floor(
                        Math.random() * 255
                    )}, ${Math.floor(Math.random() * 255)}, ${Math.floor(
                        Math.random() * 255
                    )}, 0.2)`,
                    borderColor: `rgba(${Math.floor(
                        Math.random() * 255
                    )}, ${Math.floor(Math.random() * 255)}, ${Math.floor(
                        Math.random() * 255
                    )}, 1)`,
                    borderWidth: 1,
                });
            }

            infoFieldChartData.value[key] = {
                labels: labelDescriptions,
                datasets: datasets,
            };
        }
    } catch (error) {
        console.error("Error fetching survey data:", error);
    }
};

onMounted(fetchQuestionData);
</script>

<style scoped>
/* Add any relevant styles here */
</style>
