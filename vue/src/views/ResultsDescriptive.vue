<template>
    <div class="flex">
        <!-- TEST -->
        <div>
            <div class="w-64 bg-white h-screen overflow-y-auto shadow-lg">
                <div class="p-4 space-y-6">
                    <h2 class="text-lg font-semibold text-gray-800">Filters</h2>

                    <div class="space-y-4">
                        <div
                            v-for="(label, key) in filterLabels"
                            :key="key"
                            class="flex items-center justify-between"
                        >
                            <span class="text-sm text-gray-600">{{
                                label
                            }}</span>
                            <button
                                @click="toggleFilter(key)"
                                :class="[
                                    'px-2 py-1 text-xs font-medium rounded-full',
                                    isFilterActive(key)
                                        ? 'bg-blue-100 text-blue-800'
                                        : 'bg-gray-100 text-gray-800',
                                ]"
                            >
                                {{ isFilterActive(key) ? "On" : "Off" }}
                            </button>
                        </div>
                    </div>

                    <div v-if="showRespondentGroupFilter" class="space-y-2">
                        <h3 class="text-sm font-medium text-gray-700">
                            Respondent Group
                        </h3>
                        <div class="space-y-1">
                            <label
                                v-for="group in respondentGroups"
                                :key="group.id"
                                class="flex items-center"
                            >
                                <input
                                    type="checkbox"
                                    :value="group.type"
                                    v-model="filters.respondent_types"
                                    class="form-checkbox h-4 w-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500"
                                />
                                <span class="ml-2 text-sm text-gray-600">{{
                                    group.type
                                }}</span>
                            </label>
                        </div>
                    </div>

                    <div
                        v-if="
                            showRespondentCategoryFilter &&
                            availableCategories.length
                        "
                        class="space-y-2"
                    >
                        <h3 class="text-sm font-medium text-gray-700">
                            Respondent Category
                        </h3>
                        <div class="space-y-1">
                            <label
                                v-for="category in availableCategories"
                                :key="category"
                                class="flex items-center"
                            >
                                <input
                                    type="checkbox"
                                    :value="category"
                                    v-model="filters.respondent_categories"
                                    class="form-checkbox h-4 w-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500"
                                />
                                <span class="ml-2 text-sm text-gray-600">{{
                                    category
                                }}</span>
                            </label>
                        </div>
                    </div>

                    <div v-if="showInformationFieldFilter" class="space-y-4">
                        <h3 class="text-sm font-medium text-gray-700">
                            Information Fields
                        </h3>
                        <div
                            v-for="field in informationFields"
                            :key="field.id"
                            class="space-y-2"
                        >
                            <div
                                v-if="field.type === 'select'"
                                class="space-y-1"
                            >
                            <h4 class="text-xs font-medium text-gray-600">
                                {{ field.label }}
                            </h4>
                                <label
                                    v-for="option in field.options"
                                    :key="option"
                                    class="flex items-center"
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
                                        class="form-checkbox h-4 w-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500"
                                    />
                                    <span class="ml-2 text-sm text-gray-600">{{
                                        option
                                    }}</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div v-if="showQuestionFieldFilter" class="space-y-2">
                        <h3 class="text-sm font-medium text-gray-700">
                            Questions
                        </h3>
                        <div class="space-y-1">
                            <label
                                v-for="question in questions"
                                :key="question.id"
                                class="flex items-center"
                            >
                                <input
                                    type="checkbox"
                                    :value="question.id"
                                    v-model="filters.question_ids"
                                    class="form-checkbox h-4 w-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500"
                                />
                                <span class="ml-2 text-sm text-gray-600">{{
                                    question.question
                                }}</span>
                            </label>
                        </div>
                    </div>

                    <div
                        v-if="showQuestionSectionFieldFilter"
                        class="space-y-2"
                    >
                        <h3 class="text-sm font-medium text-gray-700">
                            Question Sections
                        </h3>
                        <div class="space-y-1">
                            <label
                                v-for="section in questionSections"
                                :key="section.id"
                                class="flex items-center"
                            >
                                <input
                                    type="checkbox"
                                    :value="section.id"
                                    v-model="filters.question_sections_ids"
                                    class="form-checkbox h-4 w-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500"
                                />
                                <span class="ml-2 text-sm text-gray-600">{{
                                    section.section_label
                                }}</span>
                            </label>
                        </div>
                    </div>

                    <div v-if="showQuestionGroupFieldFilter" class="space-y-2">
                        <h3 class="text-sm font-medium text-gray-700">
                            Question Groups
                        </h3>
                        <div class="space-y-1">
                            <template
                                v-for="section in questionSections"
                                :key="section.id"
                            >
                                <label
                                    v-for="question_group in section.question_groups"
                                    :key="question_group.id"
                                    class="flex items-center"
                                >
                                    <input
                                        type="checkbox"
                                        :value="question_group.id"
                                        v-model="filters.question_groups_ids"
                                        class="form-checkbox h-4 w-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500"
                                    />
                                    <span class="ml-2 text-sm text-gray-600">{{
                                        question_group.label
                                    }}</span>
                                </label>
                            </template>
                        </div>
                    </div>

                    <div
                        v-if="showQuestionCategoryFieldFilter"
                        class="space-y-2"
                    >
                        <h3 class="text-sm font-medium text-gray-700">
                            Question Category
                        </h3>
                        <div class="space-y-1">
                            <template
                                v-for="section in questionSections"
                                :key="section.id"
                            >
                                <template
                                    v-for="question_group in section.question_groups"
                                    :key="question_group.id"
                                >
                                    <label
                                        v-for="question_category in question_group.question_categories"
                                        class="flex items-center"
                                    >
                                        <input
                                            type="checkbox"
                                            :value="question_category"
                                            v-model="
                                                filters.question_categories
                                            "
                                            class="form-checkbox h-4 w-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500"
                                        />
                                        <span
                                            class="ml-2 text-sm text-gray-600"
                                            >{{ question_category }}</span
                                        >
                                    </label>
                                </template>
                            </template>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-gray-200">
                        <button
                            @click="applyFilters"
                            class="w-full px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                        >
                            Apply Filters
                        </button>
                        <button
                            @click="resetFilters"
                            class="w-full mt-2 px-4 py-2 bg-gray-100 text-gray-800 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2"
                        >
                            Reset Filters
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- /TEST -->

        <PCAPageComponent>
            <template v-slot:header>
                <div class="flex items-center justify-between">
                    <h1 class="text-3xl font-bold text-gray-900">
                        Result Analysis for: Survey1
                    </h1>
                </div>
            </template>

            <!-- <pre>{{ testData }}</pre> -->
            <!-- <pre>{{ questionSections }}</pre> -->
            <!-- PREVIEW -->
            <div>
                <div>
                    <h1 class="text-xl font-semibold">Survey Preview</h1>
                </div>
                <div
                    v-for="(section, sectionIndex) of questionSections"
                    :key="sectionIndex"
                    class="mt-4"
                >
                    <h1>SECTION {{ sectionIndex + 1 }}</h1>
                    <div
                        v-for="(
                            question_group, groupIndex
                        ) of section.question_groups"
                        :key="groupIndex"
                        class="mt-4"
                    >
                        <h3 class="text-lg font-semibold mb-4">
                            Preview for Group {{ question_group.number }}
                        </h3>
                        <h1 class="text-md font-semibold mb-4">
                            {{ question_group.number }}.
                            {{ question_group.label }}
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

                                    <th class="border px-4 py-2">Answer</th>
                                </tr>
                            </thead>
                            <tbody>
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
                                                    (q) =>
                                                        q.category === category
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
                                            <!-- Preview answer fields based on question type -->
                                            <!-- Likert Scale (1-5) preview -->
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
                                                            'preview-question-' +
                                                            questionIndex
                                                        "
                                                        disabled
                                                        class="mr-2"
                                                    />
                                                    {{ option }}
                                                </div>
                                            </div>
                                            <!-- Text question preview -->
                                            <div
                                                v-else-if="
                                                    question.question_type ===
                                                    'text'
                                                "
                                            >
                                                <input
                                                    type="text"
                                                    class="block w-full border px-3 py-2 rounded-md shadow-sm"
                                                    placeholder="Answer"
                                                    disabled
                                                />
                                            </div>
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
                                        <td>{{ question.question }}</td>

                                        <td>
                                            <!-- Likert Scale (1-5) preview -->
                                            <div
                                                v-if="
                                                    question.question_type ===
                                                    'radio'
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
                                                            'question-' +
                                                            questionIndex
                                                        "
                                                        :value="option"
                                                        disabled
                                                    />
                                                    {{ option }}
                                                </div>
                                            </div>
                                            <!-- Text question preview -->
                                            <div
                                                v-else-if="
                                                    question.question_type ===
                                                    'text'
                                                "
                                                class="mt-1"
                                            >
                                                <input
                                                    type="text"
                                                    class="block w-full px-3 py-2 border rounded-md shadow-sm"
                                                    disabled
                                                />
                                            </div>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- /PREVIEW -->
            <div class="text-gray-700">
                <!-- FILTERING OLD -->
                <!-- <div class="shadow-md sm:rounded-md sm:overflow-hidden">
                <div class="px-4 py-5 bg-white space-y-1 sm:p-6">
                    <div>
                        <h1>Filter Data Analysis Here</h1>
                    </div>
                    <div>
                        <div class="mt-2">
                            <div class="mt-1">
                                <span
                                    class="mr-2 gap-4 flex flex-row items-center"
                                >
                                    <h3
                                        class="block text-sm font-medium text-gray-700"
                                    >
                                        Filter By:
                                    </h3>
                                    <label>
                                        <input
                                            type="checkbox"
                                            v-model="showRespondentGroupFilter"
                                        />
                                        Respondent group
                                    </label>
                                    <label>
                                        <input
                                            type="checkbox"
                                            v-model="
                                                showRespondentCategoryFilter
                                            "
                                        />
                                        Respondent category
                                    </label>
                                    <label>
                                        <input
                                            type="checkbox"
                                            v-model="showInformationFieldFilter"
                                        />
                                        Information field
                                    </label>
                                    <label>
                                        <input
                                            type="checkbox"
                                            v-model="showQuestionFieldFilter"
                                        />
                                        Questions
                                    </label>
                                    <label>
                                        <input
                                            type="checkbox"
                                            v-model="
                                                showQuestionSectionFieldFilter
                                            "
                                        />
                                        Questions Section
                                    </label>
                                    <label>
                                        <input
                                            type="checkbox"
                                            v-model="
                                                showQuestionGroupFieldFilter
                                            "
                                        />
                                        Questions Group
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
                                            v-model="filters.respondent_types"
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
                                </label>
                            </div>
                            <div v-if="showQuestionFieldFilter" class="mt-2">
                                <label>
                                    Select Question
                                    <div
                                        v-for="question in questions"
                                        :key="question.id"
                                    >
                                        <input
                                            type="checkbox"
                                            :value="question.id"
                                            v-model="filters.question_ids"
                                        />
                                        {{ question.question }}
                                    </div>
                                </label>
                            </div>
                            <div
                                v-if="showQuestionSectionFieldFilter"
                                class="mt-2"
                            >
                                <label>
                                    Select Question Section
                                    <div
                                        v-for="section in questionSections"
                                        :key="section.id"
                                    >
                                        <input
                                            type="checkbox"
                                            :value="section.id"
                                            v-model="
                                                filters.question_sections_ids
                                            "
                                        />
                                        {{ section.section_label }}
                                    </div>
                                </label>
                            </div>
                            <div
                                v-if="showQuestionGroupFieldFilter"
                                class="mt-2"
                            >
                                <label>
                                    Select Question Group
                                    <div
                                        v-for="section in questionSections"
                                        :key="section.id"
                                    >
                                        <div
                                            v-for="question_group in section.question_groups"
                                            :key="question_group.id"
                                        >
                                            <input
                                                type="checkbox"
                                                :value="question_group.id"
                                                v-model="
                                                    filters.question_groups_ids
                                                "
                                            />
                                            {{ question_group.label }}
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div> -->
                <!-- /FILTERING OLD -->
                <!-- FILTERING NEW -->
                <!-- <div class="shadow-md sm:rounded-md sm:overflow-hidden">
                <div
                    class="max-w-4xl mx-auto bg-white rounded-lg shadow-md p-6"
                >
                    <h1 class="text-2xl font-bold mb-6 text-center">
                        Product Search
                    </h1>
                    <div class="mb-6">
                        <input
                            type="text"
                            id="searchInput"
                            placeholder="Search products..."
                            class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-200"
                            aria-label="Search products"
                        />
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div class="relative">
                            <select
                                id="priceRange"
                                class="w-full p-3 border border-gray-300 rounded-md appearance-none focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-200"
                                aria-label="Price Range"
                            >
                                <option value="">Price Range</option>
                                <option value="0-50">$0 - $50</option>
                                <option value="51-100">$51 - $100</option>
                                <option value="101-200">$101 - $200</option>
                                <option value="201+">$201+</option>
                            </select>
                            <div
                                class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700"
                            >
                                <svg
                                    class="fill-current h-4 w-4"
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20"
                                >
                                    <path
                                        d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"
                                    />
                                </svg>
                            </div>
                        </div>
                        <div class="relative">
                            <select
                                id="brand"
                                class="w-full p-3 border border-gray-300 rounded-md appearance-none focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-200"
                                aria-label="Brand"
                            >
                                <option value="">Brand</option>
                                <option value="apple">Apple</option>
                                <option value="samsung">Samsung</option>
                                <option value="sony">Sony</option>
                                <option value="lg">LG</option>
                            </select>
                            <div
                                class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700"
                            >
                                <svg
                                    class="fill-current h-4 w-4"
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20"
                                >
                                    <path
                                        d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"
                                    />
                                </svg>
                            </div>
                        </div>
                        <div class="relative">
                            <select
                                id="color"
                                class="w-full p-3 border border-gray-300 rounded-md appearance-none focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-200"
                                aria-label="Color"
                            >
                                <option value="">Color</option>
                                <option value="black">Black</option>
                                <option value="white">White</option>
                                <option value="red">Red</option>
                                <option value="blue">Blue</option>
                            </select>
                            <div
                                class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700"
                            >
                                <svg
                                    class="fill-current h-4 w-4"
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20"
                                >
                                    <path
                                        d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"
                                    />
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div id="results" class="mt-8">
                        <p class="text-center text-gray-600">
                            Use the filters above to search for products.
                        </p>
                    </div>
                    <div id="noResults" class="mt-8 hidden">
                        <p class="text-center text-red-500">
                            No results found. Please try different search
                            criteria.
                        </p>
                    </div>
                </div>
            </div> -->
                <!-- /FILTERING NEW -->

                <div class="mt-4">
                    <h2 class="text-xl font-bold">Analysis Results</h2>
                    <p>Number of Responses: {{ totalResponses }}</p>
                    <p>Total Answers: {{ totalAnswers }}</p>
                    <p>
                        Filtered Answer Analysis:
                        {{ averageAnswerScale.toFixed(2) }}
                    </p>
                    <!-- Render filtered responses here -->
                    <!-- <button
                        @click="applyFilters"
                        class="w-full px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                    >
                        File Report:
                    </button> -->
                </div>
            </div>
            <!-- Analysis Report PREVIEW -->
            <!-- <AnalysisReportPreview
                :testData="testData"
                :questionSections="questionSections"
            /> -->
            <!-- <pre>{{ surveyData.id }}</pre> -->
            <router-link
                v-if="surveyData && surveyData.id"
                :to="{
                    name: 'SurveyReport',
                    params: { surveyId: surveyData.id },
                }"
            >
                <button class="mt-4 px-2 py-1 text-s font-medium rounded-full bg-blue-100 text-blue-800">View Full Report</button>
            </router-link>
            <!-- /PREVIEW -->
        </PCAPageComponent>
    </div>
</template>

<script setup>
import { ref, onMounted, watch, computed } from "vue";
import { useRoute } from "vue-router";
import axiosClient from "../axios";
import store from "../store";
import PCAPageComponent from "../components/PCAPageComponent.vue";
import AnalysisReportPreview from "../components/AnalysisReportPreview.vue";

const route = useRoute();

const filterLabels = {
    showRespondentGroupFilter: "Respondent group",
    showRespondentCategoryFilter: "Respondent category",
    showInformationFieldFilter: "Information field",
    showQuestionFieldFilter: "Questions",
    showQuestionSectionFieldFilter: "Questions Section",
    showQuestionGroupFieldFilter: "Questions Group",
    showQuestionCategoryFieldFilter: "Questions Category",
};

const filters = ref({
    respondent_types: [],
    respondent_categories: [],
    information_field: {},
    question_ids: [],
    question_sections_ids: [],
    question_groups_ids: [],
    question_categories: [],
    start_date: null,
    end_date: null,
});

const activeFilters = ref(
    Object.keys(filterLabels).reduce((acc, key) => {
        acc[key] = false;
        return acc;
    }, {})
);

const isFilterActive = (key) => activeFilters.value[key];

const toggleFilter = (key) => {
    activeFilters.value[key] = !activeFilters.value[key];
};

const showRespondentGroupFilter = computed(
    () => activeFilters.value.showRespondentGroupFilter
);
const showRespondentCategoryFilter = computed(
    () => activeFilters.value.showRespondentCategoryFilter
);
const showInformationFieldFilter = computed(
    () => activeFilters.value.showInformationFieldFilter
);
const showQuestionFieldFilter = computed(
    () => activeFilters.value.showQuestionFieldFilter
);
const showQuestionSectionFieldFilter = computed(
    () => activeFilters.value.showQuestionSectionFieldFilter
);
const showQuestionGroupFieldFilter = computed(
    () => activeFilters.value.showQuestionGroupFieldFilter
);
const showQuestionCategoryFieldFilter = computed(
    () => activeFilters.value.showQuestionCategoryFieldFilter
);

const totalResponses = ref(0);
const totalAnswers = ref(0);
const averageAnswerScale = ref(0);
const filteredResponses = ref([]);

// const showRespondentGroupFilter = ref(false);
// const showRespondentCategoryFilter = ref(false);
// const showInformationFieldFilter = ref(false);
// const showQuestionSectionFieldFilter = ref(false);
// const showQuestionGroupFieldFilter = ref(false);
// const showQuestionFieldFilter = ref(false);

const respondentGroups = ref([]);
const informationFields = ref([]);
const availableCategories = ref([]);
const questions = ref([]);
const questionSections = ref([]);
const questionGroups = ref([]);
const questionCategories = ref([]);

const applyFilters = () => {
    const params = { ...filters.value };

    const informationFieldEntries = Object.entries(params.information_field)
        .map(([key, value]) => {
            return value
                .map(
                    (option) =>
                        `information_field[${key}][]=${encodeURIComponent(
                            option
                        )}`
                )
                .join("&");
        })
        .join("&");

    const respondentGroupsParams = params.respondent_types
        .map((group) => `respondent_types[]=${encodeURIComponent(group)}`)
        .join("&");
    const respondentCategoriesParams = params.respondent_categories
        .map(
            (category) =>
                `respondent_categories[]=${encodeURIComponent(category)}`
        )
        .join("&");
    const questionSectionsIdsParams = params.question_sections_ids
        .map((id) => `question_sections_ids[]=${encodeURIComponent(id)}`)
        .join("&");
    const questionGroupsIdsParams = params.question_groups_ids
        .map((id) => `question_groups_ids[]=${encodeURIComponent(id)}`)
        .join("&");
    const questionCategoriesParams = params.question_categories
        .map((id) => `question_categories[]=${encodeURIComponent(id)}`)
        .join("&");
    const questionIdsParams = params.question_ids
        .map((id) => `question_ids[]=${encodeURIComponent(id)}`)
        .join("&");

    const queryString = `${respondentGroupsParams}&${respondentCategoriesParams}&${informationFieldEntries}&${questionSectionsIdsParams}&${questionGroupsIdsParams}&${questionCategoriesParams}&${questionIdsParams}`;

    console.log(
        "Request URL:",
        `/survey/${route.params.id}/results/descriptive?${queryString}`
    );

    axiosClient
        .get(`/survey/${route.params.id}/results/descriptive?${queryString}`)
        .then((response) => {
            console.log(response);
            const data = response.data;
            totalResponses.value = data.totalResponses;
            totalAnswers.value = data.totalAnswers;
            averageAnswerScale.value = data.averageAnswerScale;
            filteredResponses.value = data.filteredResponses;
        })
        .catch((error) => {
            console.error("Error fetching descriptive analysis:", error);
        });
};

const resetFilters = () => {
    filters.value = {
        respondent_types: [],
        respondent_categories: [],
        information_fields: {},
        question_ids: [],
        question_sections_ids: [],
        question_groups_ids: [],
    };
    Object.keys(activeFilters.value).forEach((key) => {
        activeFilters.value[key] = false;
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
        filters.value.respondent_types.includes(group.type)
    );

    const categories = selectedGroups.flatMap((group) =>
        group.category ? group.category.split(",") : []
    );

    availableCategories.value = [...new Set(categories)];
};
const surveyData = ref({});
const surveyId = computed(() =>
    surveyData.value ? surveyData.value.id : null
);
const testData = ref({});
onMounted(() => {
    store.dispatch("fetchSurveyDetails", route.params.id).then((data) => {
        testData.value = data;
        surveyData.value = data.survey;
        respondentGroups.value = data.respondentGroups;
        informationFields.value = data.informationFields.map((field) => {
            field.options = field.options ? field.options.split(",") : [];
            return field;
        });
        questions.value = data.questions;
        questionSections.value = data.questionSections;
        // Parse question data if it's a radio question
        questionSections.value.forEach((section) => {
            section.question_groups.forEach((group) => {
                console.log(group.question_categories);
                group.question_categories = JSON.parse(
                    group.question_categories
                );
                console.log(group.question_categories);
                group.questions.forEach((question) => {
                    console.log(question.data);
                    if (question.question_type === "radio") {
                        question.data = JSON.parse(question.data);
                    }
                    // Check if the question ID exists in averageScores
                    if (question.id in data.averageScores) {
                        question.average = data.averageScores[question.id]; // Assign the average score
                    } else {
                        question.average = null; // Assign null if not found
                        console.log(
                            "Question ID not found in averageScores:",
                            question.id
                        );
                    }
                });
            });
        });
    });
});
</script>

<style></style>
