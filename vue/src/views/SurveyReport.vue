<template>
    <div>
        <h1 class="text-xl font-semibold mb-4">
            Survey Report for {{ survey.title }}
        </h1>

        <div
            v-for="(section, sectionIndex) in questionSections"
            :key="sectionIndex"
            class="mt-4"
        >
            <h1>SECTION {{ sectionIndex + 1 }}</h1>
            <div
                v-for="(question_group, groupIndex) in section.question_groups"
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
                            <th class="border px-4 py-2">Mean</th>
                            <th class="border px-4 py-2">Interpretation</th>
                        </tr>
                    </thead>
                    <tbody>
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
                                    {{ question.average }}
                                </td>
                                <td class="border px-4 py-2">
                                    {{ interpretMean(question.average) }}
                                </td>
                            </tr>
                        </template>

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
                                <td>{{ question.average }}</td>
                                <td>{{ interpretMean(question.average) }}</td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>

        <button @click="downloadDoc" class="btn btn-primary">
            Download as DOC
        </button>
        <pre>{{ testData }}</pre>
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useRoute } from "vue-router";
import { useStore } from "vuex"; // Import useStore if using Vuex
import axiosClient from "../axios";

const survey = ref({});
const questionSections = ref([]);
const testData = ref({});
const route = useRoute();
const store = useStore();

const interpretMean = (mean) => {
    if (mean >= 4) return "Very Satisfied";
    if (mean >= 3) return "Satisfied";
    if (mean >= 2) return "Neutral";
    return "Dissatisfied";
};

const downloadDoc = () => {
    const reportContent = generateReportContent();
    const blob = new Blob([reportContent], { type: "application/msword" });
    const link = document.createElement("a");
    link.href = URL.createObjectURL(blob);
    link.download = `${survey.value.title}_Report.doc`;
    link.click();
};

const generateReportContent = () => {
    let content = `Survey Report for ${survey.value.title}\n\n`;
    questionSections.value.forEach((section, sectionIndex) => {
        content += `Section ${sectionIndex + 1}: ${section.section_label}\n`;
        section.question_groups.forEach((group) => {
            content += `Group ${group.number}: ${group.label}\n`;
            group.questions.forEach((question) => {
                content += `${question.question}: Mean = ${
                    question.average
                }, Interpretation = ${interpretMean(question.average)}\n`;
            });
        });
    });
    return content;
};

onMounted(() => {
    store
        .dispatch("fetchSurveyDetails", route.params.surveyId)
        .then((data) => {
            testData.value = data; // Storing complete data for debug
            survey.value = data.survey; // Storing survey details
            questionSections.value = data.questionSections; // Storing question sections

            // Parse question data and categories
            questionSections.value.forEach((section) => {
                section.question_groups.forEach((group) => {
                    group.question_categories = JSON.parse(
                        group.question_categories
                    ); // Parse question categories
                    group.questions.forEach((question) => {
                        if (question.question_type === "radio") {
                            question.data = JSON.parse(question.data); // Parse radio question data
                        }
                        question.average =
                            data.averageScores[question.id] || null; // Set average score or null
                    });
                });
            });
        })
        .catch((error) => {
            console.error("Error fetching survey details:", error);
        });
});
</script>
