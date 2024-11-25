<template>
  <div class="mx-4">
    <h1 class="text-xl font-semibold mb-4">
      Survey Report for {{ survey.title }}
    </h1>

    <div v-for="(section, sectionIndex) in questionSections" :key="sectionIndex" class="mt-4">
      <h1>SECTION {{ sectionIndex + 1 }}</h1>
      <div v-for="(question_group, groupIndex) in section.question_groups" :key="groupIndex" class="mt-4">
        <h3 class="text-lg font-semibold mb-4">Preview for Group {{ question_group.number }}</h3>
        <h1 class="text-md font-semibold mb-4">
          {{ question_group.number }}. {{ question_group.label }}
        </h1>

        <CustomIntervalInput v-model="question_group.userIntervals" />

        <table class="table-auto border-collapse border w-full text-left">
          <thead>
            <tr>
              <th v-if="question_group.category_label" class="border px-4 py-2">
                {{ question_group.category_label }}
              </th>
              <th class="border px-4 py-2">Question</th>
              <th class="border px-4 py-2">Mean</th>
              <th class="border px-4 py-2">Interpretation</th>
            </tr>
          </thead>
          <tbody>
            <template v-for="(category, categoryIndex) in question_group.question_categories" :key="categoryIndex">
              <tr v-for="(question, questionIndex) in question_group.questions.filter((q) => q.category === category)" :key="questionIndex">
                <td
                  v-if="questionIndex === 0"
                  :rowspan="question_group.questions.filter((q) => q.category === category).length"
                  class="border px-4 py-2"
                >
                  {{ category }}
                </td>
                <td class="border px-4 py-2">{{ question.question }}</td>
                <td class="border px-4 py-2">{{ question.average }}</td>
                <td v-if="question.question_type === 'radio'" class="border px-4 py-2">
                  {{ interpretMean(question.average, question_group.userIntervals) }}
                </td>
              </tr>
            </template>

            <template v-for="(question, questionIndex) in question_group.questions.filter((q) => !q.category)" :key="questionIndex">
              <tr>
                <td
                  v-if="questionIndex === 0 && question_group.category_label"
                  class="border px-4 py-2"
                ></td>
                <td>{{ question.question }}</td>
                <td>{{ question.average }}</td>
                <td v-if="question.question_type === 'radio'">
                  {{ interpretMean(question.average, question_group.userIntervals) }}
                </td>
                <td v-else>Not Likert scale data</td>
              </tr>
            </template>
          </tbody>
        </table>
      </div>
    </div>

    <table class="table-auto border-collapse border w-full text-left mt-4">
      <thead>
        <tr>
          <th class="border px-4 py-2">Overall Mean</th>
          <th class="border px-4 py-2">Interpretation</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td class="border px-4 py-2">{{ overallMean }}</td>
          <td class="border px-4 py-2">{{ overallInterpretation }}</td>
        </tr>
      </tbody>
    </table>

    <button
      @click="downloadDoc"
      class="mt-4 px-2 py-1 text-s font-medium rounded-full bg-blue-100 text-blue-800"
    >
      Download as DOC
    </button>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import { useRoute } from "vue-router";
import { useStore } from "vuex";
import CustomIntervalInput from "../components/CustomIntervalInput.vue";

const survey = ref({});
const questionSections = ref([]);
const route = useRoute();
const store = useStore();

const defaultIntervals = [
  { range: [4.21, 5.0], description: "Very Highly Satisfied" },
  { range: [3.41, 4.2], description: "Highly Satisfied" },
  { range: [2.61, 3.4], description: "Averagely Satisfied" },
  { range: [1.81, 2.6], description: "Little Satisfied" },
  { range: [1.0, 1.8], description: "Not Satisfied at all" },
];

const interpretMean = (mean, userIntervals = []) => {
  const intervals = userIntervals && userIntervals.length ? userIntervals : defaultIntervals;
  for (const interval of intervals) {
    if (mean >= interval.range[0] && mean <= interval.range[1]) {
      return interval.description;
    }
  }
  return "No Interpretation Available";
};

const overallMean = computed(() => {
  const totalScores = [];
  questionSections.value.forEach((section) => {
    section.question_groups.forEach((group) => {
      group.questions.forEach((question) => {
        if (question.average) {
          totalScores.push(question.average);
        }
      });
    });
  });
  return totalScores.length
    ? totalScores.reduce((acc, score) => acc + score, 0) / totalScores.length
    : 0;
});

const overallInterpretation = computed(() => interpretMean(overallMean.value));

const downloadDoc = () => {
  const reportContent = generateReportContent();
  const blob = new Blob([reportContent], { type: "application/msword" });
  const link = document.createElement("a");
  link.href = URL.createObjectURL(blob);
  link.download = `${survey.value.title}_Report.doc`;
  link.click();
};

const generateReportContent = () => {
  let content = `
    <html xmlns:w="urn:schemas-microsoft-com:office:word">
    <head>
        <meta charset="utf-8">
        <style>
            body {
                font-family: Arial, sans-serif;
            }
            table {
                border-collapse: collapse;
                width: 100%;
                margin-bottom: 20px;
            }
            th, td {
                border: 1px solid black;
                padding: 8px;
                text-align: left;
            }
            th {
                background-color: #f2f2f2;
            }
            h1, h2, h3 {
                margin: 16px 0;
            }
        </style>
    </head>
    <body>
        <h1>Survey Report for ${survey.value.title}</h1>
  `;

  // Loop through sections and question groups
  questionSections.value.forEach((section, sectionIndex) => {
    content += `<h2>Section ${sectionIndex + 1}: ${section.section_label || ''}</h2>`;
    
    section.question_groups.forEach((group) => {
      content += `
        <h3>Group ${group.number}: ${group.label || ''}</h3>
        <table>
          <thead>
            <tr>
              ${group.category_label ? `<th>${group.category_label}</th>` : ''}
              <th>Question</th>
              <th>Mean</th>
              <th>Interpretation</th>
            </tr>
          </thead>
          <tbody>
      `;

      // Render questions by category
      group.question_categories.forEach((category) => {
        const filteredQuestions = group.questions.filter((q) => q.category === category);
        filteredQuestions.forEach((question, questionIndex) => {
          if (questionIndex === 0) {
            content += `
              <tr>
                <td rowspan="${filteredQuestions.length}">${category}</td>
                <td>${question.question}</td>
                <td>${question.average}</td>
                <td>${interpretMean(question.average, group.userIntervals)}</td>
              </tr>
            `;
          } else {
            content += `
              <tr>
                <td>${question.question}</td>
                <td>${question.average}</td>
                <td>${interpretMean(question.average, group.userIntervals)}</td>
              </tr>
            `;
          }
        });
      });

      // Render uncategorized questions
      const uncategorizedQuestions = group.questions.filter((q) => !q.category);
      uncategorizedQuestions.forEach((question) => {
        content += `
          <tr>
            ${group.category_label ? `<td></td>` : ''}
            <td>${question.question}</td>
            <td>${question.average}</td>
            <td>${interpretMean(question.average, group.userIntervals)}</td>
          </tr>
        `;
      });

      content += `</tbody></table>`;
    });
  });

  // Add Overall Summary
  const overallMeanValue = overallMean.value.toFixed(2);
  const overallInterpretationValue = overallInterpretation.value;
  content += `
    <h2>Overall Summary</h2>
    <table>
      <thead>
        <tr>
          <th>Overall Mean</th>
          <th>Interpretation</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>${overallMeanValue}</td>
          <td>${overallInterpretationValue}</td>
        </tr>
      </tbody>
    </table>
  `;

  // Close the HTML document
  content += `
    </body>
    </html>
  `;

  return content;
};


onMounted(() => {
  store.dispatch("fetchSurveyDetails", route.params.surveyId).then((data) => {
    survey.value = data.survey;
    questionSections.value = data.questionSections;

    questionSections.value.forEach((section) => {
      section.question_groups.forEach((group) => {
        group.userIntervals = group.userIntervals || [];
        group.question_categories = JSON.parse(group.question_categories || "[]");
        group.questions.forEach((question) => {
          question.average = data.averageScores[question.id] || null;
        });
      });
    });
  });
});
</script>

<style scoped>
/* Add specific styles here */
</style>
