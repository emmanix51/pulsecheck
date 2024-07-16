<template>
    <PageComponent>
        <template v-slot:header>
            <div class="flex items-center justify-between">
                <h1 class="text-3xl font-bold text-gray-900">
                    PCA for survey: {{ surveyTitle }}
                </h1>
            </div>
        </template>
        <div class="text-gray-700">
            <div class="shadow-md sm:rounded-md sm:overflow-hidden">
                <canvas id="pcaChart"></canvas>
            </div>
            <div class="mt-4">
                <h2 class="text-xl font-bold">Explained Variance</h2>
                <p>PC1: {{ explainedVariance[0] }}%</p>
                <p>PC2: {{ explainedVariance[1] }}%</p>
            </div>
            <div class="mt-4">
                <h2 class="text-xl font-bold">Component Weights</h2>
                <!-- <pre>{{ pairedComponentWeights }}</pre> -->
                <div
                    v-for="(weights, index) in pairedComponentWeights"
                    :key="index"
                >
                    <div>
                        <strong>Question {{ index }}:</strong>
                        <br />
                    </div>
                    <br />
                </div>
            </div>
            <div class="mt-4">
                <h2 class="text-xl font-bold">PCA Data Details</h2>
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
                                View Response Answers
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="item in pcaData"
                            :key="item.response_id"
                            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600"
                        >
                            <th
                                scope="row"
                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white"
                            >
                                {{ item.response_id }}
                            </th>
                            <td class="px-6 py-4">
                                {{ item.respondent_type }}
                            </td>
                            <td class="px-6 py-4">
                                {{ item.respondent_category }}
                            </td>
                            <td class="px-6 py-4">
                                <ul
                                    v-for="(value, key) of JSON.parse(
                                        item.information_fields
                                    )"
                                    :key="key"
                                >
                                    <li>
                                        <strong>{{ key }}:</strong> {{ value }}
                                    </li>
                                </ul>
                            </td>

                            <td class="px-6 py-4 text-right">
                                <a
                                    :href="`/survey/responses/${item.response_id}`"
                                    target="_blank"
                                >
                                    View response answers
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </PageComponent>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useRoute } from "vue-router";
import axiosClient from "../axios";
import PageComponent from "../components/PageComponent.vue";
import { Chart, registerables } from "chart.js";

Chart.register(...registerables);

const route = useRoute();
const surveyTitle = ref("");
const explainedVariance = ref([]);
const componentWeights = ref([]);
const pairedComponentWeights = ref([]);
const pcaData = ref([]);

onMounted(async () => {
    const { id } = route.params;
    const response = await axiosClient.get(`/survey/${id}/pca`);
    surveyTitle.value = response.data.surveyTitle;
    explainedVariance.value = response.data.explainedVariance.map((variance) =>
        (variance * 100).toFixed(2)
    ); // Convert to percentage
    pairedComponentWeights.value = response.data.pairedComponentWeights;
    componentWeights.value = response.data.componentWeights;
    pcaData.value = response.data.pcaData.map((item) => ({
        PC1: parseFloat(item.PC1),
        PC2: parseFloat(item.PC2),
        response_id: item.response_id,
        respondent_type: item.respondent_type,
        respondent_category: item.respondent_category,
        information_fields: item.information_fields,
    }));
    console.log("PCA Data (Parsed):", pcaData.value); // Log PCA data
    renderChart();
});

function addScatterEffect(value, range) {
    return value + Math.random() * range - range / 2;
}

function renderChart() {
    const ctx = document.getElementById("pcaChart").getContext("2d");
    new Chart(ctx, {
        type: "scatter",
        data: {
            datasets: [
                {
                    label: "PCA Data",
                    data: pcaData.value.map((item) => ({
                        x: addScatterEffect(item.PC1, 0.02),
                        y: addScatterEffect(item.PC2, 0.02),
                        label: `ID: ${item.response_id}, Type: ${item.respondent_type}, Category: ${item.respondent_category}, Info Fields: ${item.information_fields}`,
                    })),
                    backgroundColor: "rgba(75, 192, 192, 0.6)",
                },
            ],
        },
        options: {
            scales: {
                x: {
                    type: "linear",
                    position: "bottom",
                },
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function (context) {
                            const data = context.raw;
                            return [
                                `PC1: ${data.x}`,
                                `PC2: ${data.y}`,
                                data.label,
                            ];
                        },
                    },
                },
            },
        },
    });
}
</script>
