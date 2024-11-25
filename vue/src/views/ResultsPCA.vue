<template>
    <PageComponent>
        <template v-slot:header>
            <h1 v-if="res" class="text-3xl font-bold text-gray-900">
                PCA for Survey: {{ surveyTitle }}
            </h1>
            <h1 v-else class="text-3xl font-bold text-gray-900">Loading</h1>
        </template>
        <!-- <pre>{{ topContributors }}</pre> -->
        <div class="text-gray-700">
            <div class="shadow-md sm:rounded-md sm:overflow-hidden">
                <canvas id="pcaChart"></canvas>
            </div>
            <div class="mt-4">
                <h2 class="text-xl font-bold">Explained Variance</h2>
                <p>PC1: {{ explainedVariance[0] }}%</p>
                <p>PC2: {{ explainedVariance[1] }}%</p>
                <p>
                    <strong>Interpretation:</strong>
                    {{ explainedVarianceInterpretation }}
                </p>
            </div>
            <div class="mt-4">
                <h2 class="text-xl font-bold">Top Contributors</h2>
                <ul>
                    <li
                        v-for="(contributorList, pc) in topContributors"
                        :key="pc"
                    >
                        <strong>{{ pc }}:</strong>
                        <ul>
                            <li
                                v-for="(item, index) in contributorList"
                                :key="index"
                            >
                                {{ item.question_text }} (Weight:
                                {{ item.weight.toFixed(2) }})
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
            <div class="mt-4">
                <h2 class="text-xl font-bold mb-4">PCA Data Details</h2>
                <table
                    class="w-full text-sm text-gray-500 border-separate border-spacing-0"
                >
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="px-4 py-2 text-left">Response ID</th>
                            <th class="px-4 py-2 text-left">Respondent Type</th>
                            <th class="px-4 py-2 text-left">
                                Respondent Category
                            </th>
                            <th class="px-4 py-2 text-left">PC1 Value</th>
                            <th class="px-4 py-2 text-left">PC2 Value</th>
                            <th class="px-4 py-2 text-left">Interpretation</th>
                            <th class="px-4 py-2 text-left">
                                Information Fields
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="item in pcaData"
                            :key="item.response_id"
                            class="border-t hover:bg-gray-50"
                        >
                            <td class="px-4 py-3">{{ item.response_id }}</td>
                            <td class="px-4 py-3">
                                {{ item.respondent_type }}
                            </td>
                            <td class="px-4 py-3">
                                {{ item.respondent_category }}
                            </td>
                            <td class="px-4 py-3">{{ item.PC1 }}</td>
                            <td class="px-4 py-3">{{ item.PC2 }}</td>
                            <td class="px-4 py-3">
                                <span
                                    v-if="item.PC1 > 0 && item.PC2 > 0"
                                    class="text-green-600 font-semibold"
                                    >High satisfaction</span
                                >
                                <span
                                    v-else-if="item.PC1 < 0 && item.PC2 < 0"
                                    class="text-red-600 font-semibold"
                                    >Low satisfaction</span
                                >
                                <span
                                    v-else
                                    class="text-yellow-600 font-semibold"
                                    >Neutral satisfaction</span
                                >
                            </td>
                            <td class="px-4 py-3">
                                <ul class="list-disc pl-5">
                                    <li
                                        v-for="(value, key) of JSON.parse(
                                            item.information_fields
                                        )"
                                        :key="key"
                                    >
                                        <strong>{{ key }}:</strong> {{ value }}
                                    </li>
                                </ul>
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
import { Chart, registerables } from "chart.js";

Chart.register(...registerables);

const route = useRoute();
const surveyTitle = ref("");
const explainedVariance = ref([]);
const explainedVarianceInterpretation = ref("");
const topContributors = ref({});
const pcaData = ref([]);
const res = ref(null);

onMounted(async () => {
    const { id } = route.params;
    const response = await axiosClient.get(`/survey/${id}/pca`);
    res.value = response;
    surveyTitle.value = response.data.surveyTitle;
    explainedVariance.value = response.data.explainedVariance.map((variance) =>
        (variance * 100).toFixed(2)
    );
    topContributors.value = response.data.topContributors;
    console.log(response.data);

    explainedVarianceInterpretation.value =
        explainedVariance.value[0] > 50
            ? "PC1 explains most of the variation, indicating it reflects the primary drivers of satisfaction."
            : "PC1 and PC2 together provide a balanced explanation of the survey patterns.";
    pcaData.value = response.data.pcaData;
    renderChart();
});

function renderChart() {
    const ctx = document.getElementById("pcaChart").getContext("2d");
    new Chart(ctx, {
        type: "scatter",
        data: {
            datasets: [
                {
                    label: "PCA Data",
                    data: pcaData.value.map((item) => ({
                        x: item.PC1,
                        y: item.PC2,
                    })),
                    backgroundColor: "rgba(103,9,9,1)",
                },
            ],
        },
        options: {
            scales: {
                x: { type: "linear", position: "bottom" },
            },
        },
    });
}
</script>

<style scoped>
/* Target Chart.js tooltips */
.chartjs-tooltip {
    background-color: rgba(0, 0, 0, 0.8) !important; /* Background color */
    border-radius: 4px !important; /* Rounded corners */
    padding: 10px !important; /* Padding inside the tooltip */
    max-width: 300px !important; /* Max width of the tooltip */
    font-size: 12px !important; /* Font size */
    line-height: 1.5 !important; /* Line height for better spacing */
    white-space: normal !important; /* Allow text to wrap */
    word-break: break-word; /* Break long words */
}
</style>
