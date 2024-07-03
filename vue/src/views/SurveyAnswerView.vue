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
        <pre>{{ infoFields }}</pre>
        <pre>{{ selectedGroupType }}</pre>
        <pre>{{ selectedCategory }}</pre>
        <pre>{{ answers }}</pre>
        <form v-if="survey" @submit.prevent="submitAnswers">
            <!-- Respondent Group Selection -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700">
                    Select Your Respondent Group Type
                </label>
                <select
                    v-model="selectedGroupType"
                    @change="updateCategories"
                    class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm"
                >
                    <option
                        v-for="group in survey.respondent_groups"
                        :key="group.id"
                        :value="group.type"
                    >
                        {{ group.type }}
                    </option>
                </select>
            </div>

            <!-- Respondent Category Selection -->
            <div class="mb-6" v-if="selectedGroupType">
                <label class="block text-sm font-medium text-gray-700">
                    Select Your Category
                </label>
                <select
                    v-model="selectedCategory"
                    class="mt-1 block w-full px-3 py-2 border rounded-md shadow-sm"
                >
                    <option
                        v-for="cat in selectedCategories"
                        :key="cat"
                        :value="cat.trim()"
                    >
                        {{ cat.trim() }}
                    </option>
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
                    {{ index + 1 }}. {{ question.question }}
                </label>
                <div
                    v-if="question.question_type === 'radio'"
                    class="mt-1 gap-2 flex flex-row"
                >
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
import { ref, computed, onMounted } from "vue";
import { useRoute, useRouter } from "vue-router";
import store from "../store";
import PageComponent from "../components/PageComponent.vue";

const route = useRoute();
const router = useRouter();
const survey = ref(null);
const answers = ref({});
const infoFields = ref({});
const selectedGroupType = ref(null);
const selectedCategory = ref(null);

const user = computed(() => store.state.user.data);

onMounted(async () => {
    const slug = route.params.slug;
    const isPublic = route.path.includes("/public/");
    let response = null;
    if (isPublic) {
        response = await store.dispatch("fetchPublicSurveyBySlug", slug);
    } else {
        response = await store.dispatch("fetchSurveyBySlug", slug);
    }

    // console.log(response);
    if (response.status === 200) {
        survey.value = response.data.data;
    } else if (response.status === 401) {
        router.push({ name: "Login" });
    } else if (response.status === 403) {
        alert(response.error.error);
        router.push({ name: "Dashboard" });
    } else if (response.status === 404) {
        alert(response.error.error);
        router.push({ name: "ErrorPage" }); // or any other route for not found
    }

    survey.value = response.data.data;
    console.log(survey.value);

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

const selectedCategories = computed(() => {
    const group = survey.value.respondent_groups.find(
        (g) => g.type === selectedGroupType.value
    );
    return group ? group.category.split(",") : [];
});

function updateCategories() {
    selectedCategory.value = null;
}

function submitAnswers() {
    const id = survey.value.id;
    const userId = user.value ? user.value.id : null;

    store
        .dispatch("submitSurveyAnswers", {
            id,
            userId,
            answers: answers.value,
            infoFields: infoFields.value,
            selectedGroupType: selectedGroupType.value,
            selectedCategory: selectedCategory.value,
        })
        .then(() => {
            alert("Your answers have been submitted successfully!");
        });
}
</script>

<style scoped>
/* Add your styles here if needed */
</style>
