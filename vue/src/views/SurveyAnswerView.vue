<template>
    <PageComponent>
        <template v-slot:header>
            <h1 v-if="survey" class="text-3xl font-bold text-gray-900">
                {{ survey.title }}
            </h1>
            <div v-else class="text-3xl font-bold text-gray-900">
                Loading...
            </div>
        </template>
        <pre>{{ survey }}</pre>
        <pre>{{ answers }}</pre>
        <form v-if="survey" @submit.prevent="submitAnswers">
            <!-- Respondent Group Selection -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700">
                    Select Your Category
                </label>
                <select
                    v-model="selectedCategory"
                    class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm"
                >
                    <option
                        v-for="group in survey.respondent_groups"
                        :key="group.id"
                        disabled
                    >
                        {{ group.type }}
                        <!-- Displaying group type as a disabled header -->
                    </option>
                    <template
                        v-for="group in survey.respondent_groups"
                        :key="group.id"
                    >
                        <template
                            v-for="(cat, index) in group.category.split(',')"
                            :key="index"
                        >
                            <option v-if="cat.trim()" :value="cat.trim()">
                                {{ cat.trim() }}
                            </option>
                        </template>
                    </template>
                </select>
            </div>

            <!-- Information Fields -->
            <div
                v-for="field in survey.information_fields"
                :key="field.id"
                class="mb-6"
            >
                <label
                    :for="'field-' + field.id"
                    class="block text-sm font-medium text-gray-700"
                >
                    {{ field.label }}
                </label>
                <div v-if="field.type === 'text'" class="mt-1">
                    <input
                        type="text"
                        :id="'field-' + field.id"
                        v-model="infoFields[field.id]"
                        class="block w-full px-3 py-2 border rounded-md shadow-sm"
                    />
                </div>
                <div v-else-if="field.type === 'select'" class="mt-1">
                    <select
                        :id="'field-' + field.id"
                        v-model="infoFields[field.id]"
                        class="block w-full px-3 py-2 border rounded-md shadow-sm"
                    >
                        <option
                            v-for="option in field.options.split(',')"
                            :key="option"
                            :value="option"
                        >
                            {{ option }}
                        </option>
                    </select>
                </div>
            </div>

            <!-- Survey Questions -->
            <div
                v-for="(question, index) in survey.questions"
                :key="question.id"
                class="mb-6"
            >
                <label
                    :for="'question-' + question.id"
                    class="block text-sm font-medium text-gray-700"
                >
                    {{ question.question }}
                </label>
                <div v-if="question.question_type === 'radio'" class="mt-1">
                    <div v-for="option in question.data" :key="option">
                        <input
                            type="radio"
                            :name="'question-' + question.id"
                            :value="option"
                            v-model="answers[question.id]"
                        />
                        {{ option }}
                    </div>
                </div>
                <div v-else-if="question.question_type === 'text'" class="mt-1">
                    <input
                        type="text"
                        :id="'question-' + question.id"
                        v-model="answers[question.id]"
                        class="block w-full px-3 py-2 border rounded-md shadow-sm"
                    />
                </div>
            </div>

            <button
                type="submit"
                class="mt-4 py-2 px-4 bg-indigo-500 text-white rounded-md hover:bg-indigo-600"
            >
                Submit
            </button>
        </form>
        <div v-else>Loading survey...</div>
    </PageComponent>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useRoute } from "vue-router";
import store from "../store";
import PageComponent from "../components/PageComponent.vue";

const route = useRoute();
const survey = ref(null);
const answers = ref({});
const infoFields = ref({});
const selectedCategory = ref(null);

onMounted(() => {
    const slug = route.params.slug;
    store.dispatch("fetchSurveyBySlug", slug).then((data) => {
        survey.value = data.data;

        // Parse question data if it's a radio question
        survey.value.questions.forEach((question) => {
            if (question.question_type === "radio") {
                question.data = JSON.parse(question.data);
            }
            answers.value[question.id] = "";
        });

        survey.value.information_fields.forEach((field) => {
            infoFields.value[field.id] = "";
        });
    });
});

function submitAnswers() {
    const slug = route.params.slug;
    store
        .dispatch("submitSurveyAnswers", {
            slug,
            answers: answers.value,
            infoFields: infoFields.value,
            selectedCategory: selectedCategory.value.split(","),
        })
        .then(() => {
            alert("Your answers have been submitted successfully!");
        });
}
</script>

<style scoped>
/* Add your styles here if needed */
</style>
