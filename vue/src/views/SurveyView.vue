<template>
    <PageComponent title="View or Create a Survey">
        <template v-slot:header>
            <div class="flex items-center justify-between">
                <h1 class="text-3xl font-bold text-gray-900">
                    {{ route.params.id ? model.title : "Create a Survey" }}
                </h1>
                <div class="flex">
                    <!-- <pre>{{ model }}</pre> -->
                    <a
                        :href="`/view/survey/${model.slug}`"
                        target="_blank"
                        v-if="model.slug"
                        class="flex py-2 px-4 border border-transparent text-sm rounded-md text-indigo-500 hover:bg-indigo-700 hover:text-white transition-colors focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-2"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"
                            />
                        </svg>
                        View Public link
                    </a>
                    <button
                        v-if="route.params.id"
                        type="button"
                        @click="deleteSurvey()"
                        class="py-2 px-3 text-white bg-red-500 rounded-md hover:bg-red-600"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5 -mt-1 inline-block"
                            viewBox="0 0 20 20"
                            fill="currentColor"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                clip-rule="evenodd"
                            />
                        </svg>
                        Delete Survey
                    </button>
                    <div v-if="route.params.id">
                        <router-link :to="{ name: 'SurveyResults' }">
                            Survey Result
                        </router-link>
                    </div>
                </div>
            </div>
        </template>
        <pre>{{ model }}</pre>
        <!-- <pre>{{ model }}</pre>
        <pre>{{ store.state.user.data }}</pre>
        <pre>{{ model.questions }}</pre> -->
        <form @submit.prevent="saveSurvey">
            <div class="shadow sm:rounded-md sm:overflow-hidden">
                <!-- Survey Fields -->
                <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
                    <!-- Title -->
                    <div>
                        <label
                            for="title"
                            class="block text-sm font-medium text-gray-700"
                            >Title</label
                        >
                        <input
                            type="text"
                            name="title"
                            id="title"
                            v-model="model.title"
                            autocomplete="survey_title"
                            class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                        />
                    </div>
                    <!--/ Title -->

                    <!-- Description -->
                    <div>
                        <label
                            for="about"
                            class="block text-sm font-medium text-gray-700"
                        >
                            Description
                        </label>
                        <div class="mt-1">
                            <textarea
                                id="description"
                                name="description"
                                rows="3"
                                v-model="model.description"
                                autocomplete="survey_description"
                                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md"
                                placeholder="Describe your survey"
                            />
                        </div>
                    </div>
                    <!-- Description -->

                    <!-- Expire Date -->
                    <div>
                        <label
                            for="expire_date"
                            class="block text-sm font-medium text-gray-700"
                            >Expire Date</label
                        >
                        <input
                            type="date"
                            name="expire_date"
                            id="expire_date"
                            v-model="model.expire_date"
                            class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                        />
                    </div>
                    <!--/ Expire Date -->

                    <!-- Status -->
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input
                                id="status"
                                name="status"
                                type="checkbox"
                                :checked="model.status === 1"
                                v-model="model.status"
                                class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded"
                            />
                        </div>
                        <div class="ml-3 text-sm">
                            <label
                                for="status"
                                class="font-medium text-gray-700"
                                >Active</label
                            >
                        </div>
                    </div>
                    <!--/ Status -->
                    <!-- Public access -->
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input
                                id="status"
                                name="status"
                                type="checkbox"
                                :checked="model.is_public === 1"
                                v-model="model.is_public"
                                class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded"
                            />
                        </div>
                        <div class="ml-3 text-sm">
                            <label
                                for="status"
                                class="font-medium text-gray-700"
                                >Public survey</label
                            >
                        </div>
                    </div>
                    <!-- /Public access -->
                </div>
                <!-- /Survey Fields -->

                <!-- Respondents -->
                <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
                    <h3
                        class="text-2xl font-semibold flex items-center justify-between"
                    >
                        Respondents

                        <!-- Add Respondent groups -->
                        <button
                            type="button"
                            @click="addRespondentGroup()"
                            class="flex items-center text-sm py-1 px-4 rounded-sm text-white bg-gray-600 hover:bg-gray-700"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-4 w-4"
                                viewBox="0 0 20 20"
                                fill="currentColor"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                            Add Respondent Group
                        </button>
                        <!--/ Add Respondent groups -->
                    </h3>
                    <div
                        v-if="!model.respondent_groups.length"
                        class="text-center text-gray-600"
                    >
                        You don't have any respondent group selected
                    </div>
                    <div
                        v-for="(group, index) in model.respondent_groups"
                        :key="index"
                    >
                        <label class="block text-sm font-medium text-gray-700">
                            Respondent Group {{ index + 1 }}
                        </label>
                        <select
                            v-model="group.type"
                            class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                        >
                            <option value="student">Student</option>
                            <option value="faculty">Faculty</option>
                            <option value="staff">Staff</option>
                            <option value="stakeholder">Stakeholder</option>
                        </select>
                        <div class="mt-2">
                            <label
                                class="block text-sm font-medium text-gray-700"
                            >
                                Categories (comma-separated)
                            </label>
                            <input
                                type="text"
                                v-model="group.category"
                                placeholder="Enter categories separated by commas"
                                class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                            />
                        </div>
                        <button
                            type="button"
                            @click="removeRespondentGroup(index)"
                            class="mt-2 text-red-500 hover:text-red-700"
                        >
                            Remove
                        </button>
                    </div>
                </div>
                <!-- /Respondents -->

                <!-- Respondent Information -->
                <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
                    <h3
                        class="text-2xl font-semibold flex items-center justify-between"
                    >
                        Respondent Information

                        <!-- Add new information field -->
                        <button
                            type="button"
                            @click="addInformationField()"
                            class="flex items-center text-sm py-1 px-4 rounded-sm text-white bg-gray-600 hover:bg-gray-700"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-4 w-4"
                                viewBox="0 0 20 20"
                                fill="currentColor"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                            Add Information Field
                        </button>
                        <!--/ Add new information field -->
                    </h3>
                    <div
                        v-if="!model.information_fields.length"
                        class="text-center text-gray-600"
                    >
                        You don't have any information fields created
                    </div>
                    <div
                        v-for="(field, index) in model.information_fields"
                        :key="index"
                    >
                        <label class="block text-sm font-medium text-gray-700">
                            Information Field {{ index + 1 }}
                        </label>
                        <input
                            type="text"
                            v-model="field.label"
                            placeholder="Field Label (e.g., Name, Address)"
                            class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                        />
                        <select
                            v-model="field.type"
                            class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                        >
                            <option value="text">Text</option>
                            <option value="textarea">Textarea</option>
                            <option value="select">Select</option>
                        </select>
                        <div v-if="field.type === 'select'" class="mt-2">
                            <label
                                class="block text-sm font-medium text-gray-700"
                            >
                                Options (comma-separated)
                            </label>
                            <input
                                type="text"
                                v-model="field.options"
                                placeholder="e.g., Male,Female,Other"
                                class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                            />
                        </div>
                        <button
                            type="button"
                            @click="removeInformationField(index)"
                            class="mt-2 text-red-500 hover:text-red-700"
                        >
                            Remove
                        </button>
                    </div>
                </div>
                <!-- Respondent Information -->

                <!-- Questions field -->
                <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
                    <h3
                        class="text-2xl font-semibold flex items-center justify-between"
                    >
                        Questions

                        <!-- Add new question -->
                        <button
                            type="button"
                            @click="addQuestion"
                            class="flex items-center text-sm py-1 px-4 rounded-sm text-white bg-gray-600 hover:bg-gray-700"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-4 w-4"
                                viewBox="0 0 20 20"
                                fill="currentColor"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                            Add Question
                        </button>
                        <!--/ Add new question -->
                    </h3>
                    <div
                        v-if="!model.questions.length"
                        class="text-center text-gray-600"
                    >
                        You don't have any questions created
                    </div>
                    <div
                        v-for="(question, index) in model.questions"
                        :key="index"
                    >
                        <label class="block text-sm font-medium text-gray-700">
                            Question {{ index + 1 }}
                        </label>
                        <select
                            v-model="question.question_type"
                            class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                        >
                            <option value="text">Text</option>
                            <option value="radio">Likert Scale (1-5)</option>
                        </select>
                        <input
                            type="text"
                            v-model="question.question"
                            placeholder="Question"
                            class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                        />
                        <textarea
                            v-model="question.description"
                            placeholder="Description"
                            class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                        />
                        <div
                            v-if="question.question_type === 'radio'"
                            class="mt-2"
                        >
                            <label
                                class="block text-sm font-medium text-gray-700"
                            >
                                Likert Scale Options (1-5)
                            </label>
                            <div class="mt-1">
                                <span
                                    v-for="option in [1, 2, 3, 4, 5]"
                                    :key="option"
                                    class="mr-2"
                                >
                                    <input
                                        type="checkbox"
                                        :value="option"
                                        v-model="question.data"
                                        checked
                                    />
                                    {{ option }}
                                </span>
                            </div>
                        </div>
                        <button
                            type="button"
                            @click="removeQuestion(index)"
                            class="mt-2 text-red-500 hover:text-red-700"
                        >
                            Remove
                        </button>
                    </div>
                </div>
                <!-- Questions field -->
            </div>
            <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                <!-- <button @click="saveAsTemplate" type="button">
                    Save as template
                </button> -->
                <button
                    type="submit"
                    class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                    Save
                </button>
            </div>
        </form>
    </PageComponent>
</template>

<script setup>
import { ref, onMounted, watch } from "vue";
import { useRoute, useRouter } from "vue-router";
import store from "../store";
import PageComponent from "../components/PageComponent.vue";

const route = useRoute();
const router = useRouter();

let model = ref({
    image: "",
    title: "",
    slug: "",
    status: null,
    is_public: null,
    description: null,
    respondent_groups: [],
    expire_date: null,
    information_fields: [],
    questions: [],
});

if (route.params.id) {
    store.dispatch("fetchSurvey", route.params.id).then((surveyData) => {
        surveyData.questions.forEach((question) => {
            question.data = question.data;
        });
        model.value = surveyData;
    });
}

const addRespondentGroup = () => {
    model.value.respondent_groups.push({
        type: "student",
        category: "",
    });
};

const removeRespondentGroup = (index) => {
    model.value.respondent_groups.splice(index, 1);
};

const selectCategory = (group) => {
    group.category = "";
};

const addInformationField = () => {
    model.value.information_fields.push({
        label: "",
        type: "text",
        options: "",
    });
};

const removeInformationField = (index) => {
    model.value.information_fields.splice(index, 1);
};

const addQuestion = () => {
    model.value.questions.push({
        question_type: "",
        question: "",
        description: "",
        data: [1, 2, 3, 4, 5], // Default options for Likert scale questions
    });
};

const removeQuestion = (index) => {
    model.value.questions.splice(index, 1);
};

const saveAsTemplate = () => {
    const formData = JSON.parse(JSON.stringify(model.value));
    formData.questions.forEach((question) => {
        if (Array.isArray(question.data)) {
            question.data = JSON.stringify(question.data);
        }
        if (question.question_type === "text") {
            question.data = null;
        }
    });

    const templateData = {
        title: formData.title,
        description: formData.description,
        data: JSON.stringify(formData),
    };

    store
        .dispatch("saveAsTemplate", templateData)
        .then(() => {
            alert("Template saved successfully");
        })
        .catch((error) => {
            console.error(error);
            alert("Error saving template");
        });
};

const saveSurvey = () => {
    const surveyData = JSON.parse(JSON.stringify(model.value)); // Deep copy to avoid mutating the original model

    surveyData.questions.forEach((question) => {
        if (Array.isArray(question.data)) {
            question.data = JSON.stringify(question.data);
        }
        if (question.question_type === "text") {
            question.data = null;
        }
    });

    store.dispatch("saveSurvey", surveyData).then(({ data }) => {
        router.push({
            name: "SurveyView",
            params: { id: data.id },
        });
    });
};

const deleteSurvey = () => {
    store.dispatch("deleteSurvey", model.value.id).then(() => {
        router.push({ name: "Surveys" });
    });
};
</script>
