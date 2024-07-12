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
        </div>
    </PageComponent>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useRoute } from "vue-router";
import axiosClient from "../axios";
import PageComponent from "../components/PageComponent.vue";
import { Chart } from "chart.js";

const route = useRoute();
const surveyTitle = ref("");
const pcaData = ref([]);

onMounted(async () => {
    const { id } = route.params;
    const response = await axiosClient.get(`/survey/${id}/pca`);
    surveyTitle.value = response.data.surveyTitle;
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
                    label: "PCA Result",
                    data: pcaData.value.map((item) => ({
                        x: item.PC1,
                        y: item.PC2,
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
        },
    });
}
</script>

<style scoped>
/* Add any relevant styles here */
</style>
