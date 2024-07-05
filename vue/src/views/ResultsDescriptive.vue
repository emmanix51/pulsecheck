<template>
    <PageComponent>
        <template v-slot:header>
            <div class="flex items-center justify-between">
                <h1 class="text-3xl font-bold text-gray-900">
                    Response Analysis for {{ surveyTitle }}
                </h1>
            </div>
        </template>
        <div class="text-gray-700">
            <div class="shadow-md sm:rounded-md sm:overflow-hidden">
                <div class="px-4 py-5 bg-white space-y-1 sm:p-6">
                    <div>
                        <h1>Filter Data Analysis Here</h1>
                    </div>
                    <div>
                        <div class="mt-2">
                            <div class="mt-1">
                                <span class="mr-2 flex flex-row items-center">
                                    <h3
                                        class="block text-sm font-medium text-gray-700"
                                    >
                                        Filter By:
                                    </h3>
                                    <label>
                                        Respondent group
                                        <input
                                            type="checkbox"
                                            v-model="showRespondentGroupFilter"
                                        />
                                    </label>
                                    <label>
                                        Respondent category
                                        <input
                                            type="checkbox"
                                            v-model="
                                                showRespondentCategoryFilter
                                            "
                                        />
                                    </label>
                                    <label>
                                        Information field
                                        <input
                                            type="checkbox"
                                            v-model="showInformationFieldFilter"
                                        />
                                    </label>
                                    <label>
                                        Questions
                                        <input
                                            type="checkbox"
                                            v-model="showInformationFieldFilter"
                                        />
                                    </label>
                                    <label>
                                        Date
                                        <input
                                            type="date"
                                            v-model="filters.start_date"
                                        />
                                        <input
                                            type="date"
                                            v-model="filters.end_date"
                                        />
                                    </label>
                                    <button @click="applyFilters">Apply</button>
                                </span>
                            </div>
                            <div v-if="showRespondentGroupFilter" class="mt-2">
                                <label>
                                    Select Respondent Group
                                    <div
                                        v-for="group in respondentGroups"
                                        :key="group.id"
                                    >
                                        <input
                                            type="checkbox"
                                            :value="group.type"
                                            v-model="filters.respondent_groups"
                                        />
                                        {{ group.type }}
                                    </div>
                                </label>
                            </div>
                            <div
                                v-if="
                                    showRespondentCategoryFilter &&
                                    availableCategories.length
                                "
                                class="mt-2"
                            >
                                <label>
                                    Select Respondent Category
                                    <div
                                        v-for="category in availableCategories"
                                        :key="category"
                                    >
                                        <input
                                            type="checkbox"
                                            :value="category"
                                            v-model="
                                                filters.respondent_categories
                                            "
                                        />
                                        {{ category }}
                                    </div>
                                </label>
                            </div>
                            <div v-if="showInformationFieldFilter" class="mt-2">
                                <label
                                    v-for="field in informationFields"
                                    :key="field.id"
                                >
                                    {{ field.label }}
                                    <div v-if="field.type === 'select'">
                                        <div
                                            v-for="option in field.options"
                                            :key="option"
                                        >
                                            <input
                                                type="checkbox"
                                                :value="option"
                                                :checked="
                                                    isOptionSelected(
                                                        field.label,
                                                        option
                                                    )
                                                "
                                                @change="
                                                    toggleInformationField(
                                                        field.label,
                                                        option
                                                    )
                                                "
                                            />
                                            {{ option }}
                                        </div>
                                    </div>
                                    <input
                                        v-else
                                        type="text"
                                        v-model="
                                            filters.information_field[
                                                field.label
                                            ]
                                        "
                                    />
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-4">
                <h2 class="text-xl font-bold">Analysis Results</h2>
                <p>Number of Responses: {{ totalResponses }}</p>
                <p>Total Answer Scale: {{ totalAnswerScale }}</p>
                <p>Average Answer Scale: {{ averageAnswerScale.toFixed(2) }}</p>
                <!-- Render filtered responses here -->
            </div>
        </div>
    </PageComponent>
</template>

<script setup>
import { ref, onMounted, watch } from "vue";
import { useRoute } from "vue-router";
import store from "../store";
import PageComponent from "../components/PageComponent.vue";

const route = useRoute();

const filters = ref({
    respondent_groups: [],
    respondent_categories: [],
    information_field: {},
    start_date: null,
    end_date: null,
});

const surveyTitle = ref("");

const totalResponses = ref(0);
const totalAnswerScale = ref(0);
const averageAnswerScale = ref(0);
const filteredResponses = ref([]);

const showRespondentGroupFilter = ref(false);
const showRespondentCategoryFilter = ref(false);
const showInformationFieldFilter = ref(false);

const respondentGroups = ref([]);
const informationFields = ref([]);
const availableCategories = ref([]);

const applyFilters = () => {
    const params = { ...filters.value };
    store
        .dispatch("fetchResultDescriptiveData", { id: route.params.id, params })
        .then((data) => {
            totalResponses.value = data.totalResponses;
            totalAnswerScale.value = data.totalAnswerScale;
            averageAnswerScale.value = data.averageAnswerScale;
            filteredResponses.value = data.filteredResponses;
        });
};

const isOptionSelected = (fieldLabel, option) => {
    return (
        filters.value.information_field[fieldLabel]?.includes(option) || false
    );
};

const toggleInformationField = (fieldLabel, option) => {
    if (!filters.value.information_field[fieldLabel]) {
        filters.value.information_field[fieldLabel] = [];
    }

    const index = filters.value.information_field[fieldLabel].indexOf(option);

    if (index === -1) {
        filters.value.information_field[fieldLabel].push(option);
    } else {
        filters.value.information_field[fieldLabel].splice(index, 1);
    }
};

watch(
    filters.value,
    (newFilters) => {
        updateCategories();
    },
    { deep: true }
);

const updateCategories = () => {
    const selectedGroups = respondentGroups.value.filter((group) =>
        filters.value.respondent_groups.includes(group.type)
    );

    const categories = selectedGroups.flatMap((group) =>
        group.category ? group.category.split(",") : []
    );

    availableCategories.value = [...new Set(categories)];
};

onMounted(() => {
    store.dispatch("fetchSurveyDetails", route.params.id).then((data) => {
        surveyTitle.value = data.survey.title;
        respondentGroups.value = data.respondentGroups;
        informationFields.value = data.informationFields.map((field) => {
            field.options = field.options ? field.options.split(",") : [];
            return field;
        });
    });
});
</script>

<style></style>
