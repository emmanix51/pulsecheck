<template>
    <PageComponent>
        <template v-slot:header>
            <div v-if="survey" class="flex justify-center">
                <div class="flex justify-center flex-col">
                    <h1 class="text-3xl font-bold text-gray-900">
                        {{ survey.title }}
                    </h1>
                    <h3 class="text-lg text-center font-bold text-gray-800">
                        {{ survey.description }}
                    </h3>
                </div>
            </div>
            <div v-else class="text-3xl font-bold text-gray-900">
                Loading...
            </div>
        </template>
        <div class="mb-4" v-if="survey">
            <p v-if="survey.instruction">
                Instruction: {{ survey.instruction }}
            </p>
        </div>
        <!-- <pre>{{ question_sections }}</pre> -->
        <!-- <pre>{{ question_groups }}</pre> -->
        <!-- <pre>{{ infoFields }}</pre> -->
        <!-- <pre>{{ selectedGroupType }}</pre> -->
        <!-- <pre>{{ selectedCategory }}</pre> -->
        <!-- <pre>{{ answers }}</pre> -->

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
                    required
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
                    required
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
                        required
                    />
                </div>
                <div v-else-if="field.type === 'number'" class="mt-1">
                    <input
                        type="number"
                        :id="'field-' + field.id"
                        v-model="infoFields[field.id]"
                        class="block w-full px-3 py-2 border rounded-md shadow-sm"
                        required
                    />
                </div>
                <div v-else-if="field.type === 'select'" class="mt-1">
                    <select
                        :id="'field-' + field.id"
                        v-model="infoFields[field.id]"
                        class="block w-full px-3 py-2 border rounded-md shadow-sm"
                        required
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

            <!-- Question Sections -->
            <div
                v-for="(section, sectionIndex) in question_sections"
                :key="sectionIndex"
                class="mb-6"
            >
                <!-- Display section label and instruction -->
                <div>
                    <h1 class="text-2xl mb-2 font-bold">
                        {{ section.section_label }}
                    </h1>
                    <h3 class="text-lg text-gray-600 mb-4 whitespace-pre-wrap">
                        {{ section.section_instruction }}
                    </h3>
                </div>

                <!-- Iterate over Question Groups -->
                <div
                    v-for="(
                        question_group, groupIndex
                    ) in section.question_groups"
                    :key="groupIndex"
                    class="mb-6"
                >
                    <h2 class="mb-2 font-semibold text-xl">
                        {{ question_group.number }}. {{ question_group.label }}
                    </h2>

                    <table class="table-auto w-full border-collapse border">
                        <thead>
                            <tr>
                                <th
                                    v-if="question_group.category_label"
                                    class="p-2 border"
                                >
                                    {{ question_group.category_label }}
                                </th>
                                <th class="p-2 border">Question</th>
                                <th class="p-2 border">Answer</th>
                                <th
                                    v-if="hasRadioQuestion(question_group)"
                                    class="p-2 border"
                                >
                                    Comment/Complaint
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Iterate over Questions in the Group -->
                            <!-- Display questions with categories -->
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
                                    <!-- Display the category once for each group of questions -->
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

                                    <!-- Display the question text -->
                                    <td class="border px-4 py-2">
                                        {{ question.question }}
                                    </td>

                                    <!-- Display the answer input field based on question type -->
                                    <td class="border px-4 py-2">
                                        <!-- Radio Question -->
                                        <div
                                            v-if="
                                                question.question_type ===
                                                'radio'
                                            "
                                            class="flex space-x-2"
                                        >
                                            <div
                                                v-for="option in question.data"
                                                :key="option"
                                                class="flex items-center border-r border-gray-300 px-2"
                                            >
                                                <input
                                                    type="radio"
                                                    :value="option"
                                                    :name="
                                                        'question-' +
                                                        question.id
                                                    "
                                                    v-model="
                                                        answers[question.id]
                                                    "
                                                    class="mr-2"
                                                    required
                                                />
                                                {{ option }}
                                            </div>
                                        </div>

                                        <!-- Text Question -->
                                        <div
                                            v-else-if="
                                                question.question_type ===
                                                'text'
                                            "
                                        >
                                            <input
                                                type="text"
                                                v-model="answers[question.id]"
                                                class="block w-full px-3 py-2 border rounded-md shadow-sm"
                                                required
                                            />
                                        </div>
                                    </td>
                                    <td
                                        class="border px-4 py-2"
                                        v-if="
                                            question.question_type === 'radio'
                                        "
                                    >
                                        <input
                                            type="text"
                                            class="block w-full px-3 py-2 border rounded-md shadow-sm"
                                        />
                                    </td>
                                </tr>
                            </template>

                            <!-- Display questions without a category -->
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

                                    <!-- Display the question text -->
                                    <td class="border px-4 py-2">
                                        {{ question.question }}
                                    </td>

                                    <!-- Display the answer input field based on question type -->
                                    <td class="border px-4 py-2">
                                        <!-- Radio Question -->
                                        <div
                                            v-if="
                                                question.question_type ===
                                                'radio'
                                            "
                                            class="flex space-x-2"
                                        >
                                            <div
                                                v-for="option in question.data"
                                                :key="option"
                                                class="flex items-center border-r border-gray-300 px-2"
                                            >
                                                <input
                                                    type="radio"
                                                    :value="option"
                                                    :name="
                                                        'question-' +
                                                        question.id
                                                    "
                                                    v-model="
                                                        answers[question.id]
                                                    "
                                                    class="mr-2"
                                                    required
                                                />
                                                {{ option }}
                                            </div>
                                        </div>

                                        <!-- Text Question -->
                                        <div
                                            v-else-if="
                                                question.question_type ===
                                                'text'
                                            "
                                        >
                                            <input
                                                type="text"
                                                v-model="answers[question.id]"
                                                class="block w-full px-3 py-2 border rounded-md shadow-sm"
                                                required
                                            />
                                        </div>
                                    </td>
                                    <td
                                        class="border px-4 py-2"
                                        v-if="
                                            question.question_type === 'radio'
                                        "
                                    >
                                        <input
                                            type="text"
                                            class="block w-full px-3 py-2 border rounded-md shadow-sm"
                                        />
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- /Question Sections -->

            <!-- Instructions -->
            <div v-if="survey.question_instruction" class="mb-6">
                <h1 class="block text-sm font-medium text-gray-700">
                    Instruction
                </h1>
                <p>
                    {{ survey.question_instruction }}
                </p>
            </div>
            <!-- /Instructions -->

            <div
                v-for="(question_group, number) in question_groups"
                :key="number"
                class="mt-4 mb-2"
            >
                <h1 class="mb-2">
                    {{ question_group.number }}. {{ question_group.label }}
                </h1>
                <table class="table-auto border-collapse border">
                    <thead>
                        <tr>
                            <th v-if="question_group.category_label">
                                {{ question_group.category_label }}
                            </th>
                            <th>Question</th>
                            <!-- <th>Question Description</th> -->
                            <th>Answer</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Iterate over grouped questions by category -->
                        <template
                            v-for="(
                                questions, category
                            ) in groupedQuestionsByGroup[
                                question_group.number
                            ] || {}"
                            :key="category"
                        >
                            <!-- Display the category name and questions -->
                            <tr
                                v-for="(question, index) in questions"
                                :key="question.id"
                            >
                                <td
                                    v-if="
                                        question_group.category_label &&
                                        index === 0
                                    "
                                    :rowspan="questions.length"
                                >
                                    {{
                                        question.category
                                            ? question.category
                                            : " "
                                    }}
                                </td>
                                <td>{{ question.question }}</td>
                                <!-- <td>{{ question.description }}</td> -->
                                <td>
                                    <div
                                        v-if="
                                            question.question_type === 'radio'
                                        "
                                        class="mt-1 flex flex-row"
                                    >
                                        <div
                                            v-for="option in question.data"
                                            :key="option"
                                            class="flex items-center border-x border-gray-200 py-1 px-2 w-full"
                                        >
                                            <input
                                                class="mr-2"
                                                type="radio"
                                                :name="
                                                    'question-' + question.id
                                                "
                                                :value="option"
                                                v-model="answers[question.id]"
                                                required
                                            />
                                            {{ option }}
                                        </div>
                                    </div>
                                    <div
                                        v-else-if="
                                            question.question_type === 'text'
                                        "
                                        class="mt-1"
                                    >
                                        <input
                                            type="text"
                                            :id="'question-' + question.id"
                                            v-model="answers[question.id]"
                                            class="block w-full px-3 py-2 border rounded-md shadow-sm"
                                            required
                                        />
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
            <!-- Survey Questions
            <table class="table-auto border-collapse border">
                <thead>
                    <tr>
                        <th>Question</th>
                        <th>Question Description</th>
                        <th>Answer</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="(question, index) in survey.questions"
                        :key="question.id"
                        class="border"
                    >
                        <td>{{ index + 1 }}. {{ question.question }}</td>
                        <td>{{ question.description }}</td>
                        <td>
                            <div
                                v-if="question.question_type === 'radio'"
                                class="mt-1 flex flex-row"
                            >
                                <div
                                    v-for="option in question.data"
                                    :key="option"
                                    class="flex items-center border-x border-gray-200 py-1 px-2 w-full"
                                >
                                    <input
                                        class="mr-2"
                                        type="radio"
                                        :name="'question-' + question.id"
                                        :value="option"
                                        v-model="answers[question.id]"
                                        required
                                    />
                                    {{ option }}
                                </div>
                            </div>
                            <div
                                v-else-if="question.question_type === 'text'"
                                class="mt-1"
                            >
                                <input
                                    type="text"
                                    :id="'question-' + question.id"
                                    v-model="answers[question.id]"
                                    class="block w-full px-3 py-2 border rounded-md shadow-sm"
                                    required
                                />
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table> -->

            <!-- <div
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
            </div> -->

            <button
                type="submit"
                class="mt-4 py-2 px-4 bg-spccolor-600 text-white rounded-md hover:bg-spccolor-500"
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
const question_sections = ref([
    {
        section_number: null,
        section_label: "",
        section_instruction: "",
        question_groups: [
            {
                section_number: null,
                number: null,
                label: "",
                question_instruction: "",
                category_label: "",
                question_categories: [],
                questions: [],
            },
        ],
    },
]);
const question_groups = ref([]);
const groupedQuestionsByGroup = ref({});

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
        question_sections.value = survey.value.question_sections;
        // processQuestions(survey.value.questions);
    } else if (response.status === 401) {
        router.push({ name: "Login" });
    } else if (response.status === 403) {
        alert(response.error.error);
        router.push({ name: "Dashboard" });
    } else if (response.status === 405) {
        alert(response.error.error);
        router.push({ name: "ErrorPage" }); // or any other route for not found
    }

    survey.value = response.data.data;
    console.log(survey);
    question_sections.value = survey.value.question_sections;

    // Parse question data if it's a radio question
    survey.value.question_sections.forEach((section) => {
        section.question_groups.forEach((group) => {
            group.question_categories = JSON.parse(group.question_categories);
            group.questions.forEach((question) => {
                console.log(question.data);

                if (question.question_type === "radio") {
                    question.data = JSON.parse(question.data);
                }

                answers.value[question.id] = "";
            });
        });
    });

    survey.value.information_fields.forEach((field) => {
        infoFields.value[field.id] = "";
    });
});

function processQuestions(questions) {
    // Initialize groupedQuestionsByGroup
    const grouped = {};

    // Group questions by their group number
    questions.forEach((question) => {
        const groupNumber = question.group;
        if (!grouped[groupNumber]) {
            grouped[groupNumber] = {};
        }
        if (!grouped[groupNumber][question.category]) {
            grouped[groupNumber][question.category] = [];
        }
        grouped[groupNumber][question.category].push(question);
    });

    groupedQuestionsByGroup.value = grouped;
}

const groupedQuestions = computed(() => {
    const grouped = {};

    // Initialize categories
    survey.value.question_categories = JSON.parse(
        survey.value.question_categories
    );
    survey.value.question_categories.forEach((category) => {
        grouped[category] = [];
    });

    // Group questions by category
    survey.value.questions.forEach((question) => {
        if (grouped[question.category]) {
            grouped[question.category].push(question);
        }
    });

    return grouped;
});

const hasRadioQuestion = (question_group) => {
    return question_group.questions.some(
        (question) => question.question_type === "radio"
    );
};

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

    // Prepare infoFieldsData with labels instead of IDs
    const infoFieldsData = {};
    for (const key in infoFields.value) {
        if (infoFields.value.hasOwnProperty(key)) {
            const field = survey.value.information_fields.find(
                (field) => field.id === parseInt(key)
            );
            if (field) {
                infoFieldsData[field.label] = infoFields.value[key];
            }
        }
    }

    store
        .dispatch("submitSurveyAnswers", {
            id,
            userId,
            answers: answers.value,
            infoFields: infoFieldsData,
            selectedGroupType: selectedGroupType.value,
            selectedCategory: selectedCategory.value,
        })
        .then(() => {
            alert("Your answers have been submitted successfully!");
            router.push({ name: "Dashboard" });
        });
}
</script>

<style scoped>
.table-auto {
    border-collapse: collapse;
    width: 100%;
}

.table-auto th,
.table-auto td {
    border: 2px solid #a3a3a3;
    padding: 8px;
}

.table-auto th {
    background-color: #cccccc;
}
</style>
