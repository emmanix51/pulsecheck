<template>
    <PageComponent>
        <template v-slot:header>
            <div class="flex items-center justify-between">
                <h1 class="text-3xl font-bold text-gray-900">
                    {{ route.params.id ? model.title : "Create a Survey" }}
                </h1>
            </div>
            <div class="flex">
                <!-- <pre>{{ model }}</pre> -->
                <div v-if="model.is_public">
                    <a
                        :href="`/public/survey/${model.slug}`"
                        target="_blank"
                        v-if="model.slug"
                        class="flex py-2 px-4 border border-transparent text-sm rounded-full text-indigo-500 hover:bg-indigo-700 hover:text-white transition-colors focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-2"
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
                </div>
                <button
                    v-if="route.params.id"
                    type="button"
                    @click="deleteSurvey()"
                    class="py-2 px-3 text-red-500 rounded-md hover:underline"
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
                    <router-link
                        class="flex py-2 px-4 border border-transparent text-sm rounded-md text-spccolor-500 hover:bg-spccolor-600 hover:text-white transition-colors focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-2"
                        :to="{ name: 'SurveyResults' }"
                    >
                        Survey Result
                    </router-link>
                </div>
            </div>
        </template>
        <!-- <pre>{{ model }}</pre> -->
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
                    <!-- Instruction -->
                    <div>
                        <label
                            for="about"
                            class="block text-sm font-medium text-gray-700"
                        >
                            Instruction
                        </label>
                        <div class="mt-1">
                            <textarea
                                id="instruction"
                                name="instruction"
                                rows="3"
                                v-model="model.instruction"
                                autocomplete="survey_description"
                                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md"
                                placeholder="Instruction for your survey"
                            />
                        </div>
                    </div>
                    <!-- Instruction -->

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
                                v-if="group.type === student"
                                class="block text-sm font-medium text-gray-700"
                            >
                                Course/Grade Level (comma-separated)
                            </label>
                            <label
                                v-else-if="
                                    group.type === faculty ||
                                    group.type === staff
                                "
                                class="block text-sm font-medium text-gray-700"
                            >
                                Position (comma-separated)
                            </label>
                            <label
                                v-else:
                                class="block text-sm font-medium text-gray-700"
                            >
                                Classification (comma-separated)
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
                            <option value="number">Number</option>
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

                <!-- Question Category -->
                <!-- <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
                    <h3
                        class="text-2xl font-semibold flex items-center justify-between"
                    >
                        Question Category
                        <button
                            type="button"
                            @click="addQuestionCategory"
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
                            Add Question Category
                        </button>
                    </h3>
                    <div
                        v-if="!model.question_categories.length"
                        class="text-center text-gray-600"
                    >
                        You don't have any question category created
                    </div>
                    <div
                        v-for="(category, index) in model.question_categories"
                        :key="index"
                    >
                        <label class="block text-sm font-medium text-gray-700">
                            Question Classification {{ index + 1 }}
                        </label>
                        <input
                            type="text"
                            v-model="model.question_categories[index]"
                            class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                        />
                        <button
                            type="button"
                            @click="removeQuestionCategory(index)"
                            class="mt-2 text-red-500 hover:text-red-700"
                        >
                            Remove
                        </button>
                    </div>
                </div> -->
                <!-- /Question Category -->
                <div>
                    <h1 class="text-center text-xl font-bold">QUESTIONNAIRE</h1>
                </div>
                <!-- Questions field -->
                <div
                    v-for="(section, sectionIndex) in model.question_sections"
                    :key="sectionIndex"
                    class="px-4 py-5 bg-white space-y-6 mt-5 sm:p-6 border-t-2 border-gray-400"
                >
                    <h1 class="text-xl font-bold">
                        SECTION {{ section.section_number }}
                    </h1>
                    <div class="flex gap-2">
                        <input
                            v-model="section.section_label"
                            class="text-lg font-semibold flex items-center justify-between w-full"
                            placeholder="Section Label..."
                        />
                    </div>
                    <!--  Section Instruction -->
                    <label class="block text-sm font-medium text-gray-700">
                        Section Instruction
                    </label>
                    <textarea
                        v-model="section.section_instruction"
                        class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                        placeholder="Type instruction here"
                    />
                    <div
                        v-for="(question_group, groupIndex) of model
                            .question_sections[sectionIndex].question_groups"
                        class="py-5 bg-white space-y-6 mt-5 sm:p-6"
                        :key="groupIndex"
                    >
                        <div>
                            <h3
                                class="text-2xl font-semibold flex items-center justify-between mb-2"
                            >
                                Section{{ section.section_number }} Question
                                Group {{ question_group.number }}
                            </h3>

                            <div class="flex gap-2">
                                <input
                                    v-model="question_group.label"
                                    class="text-lg font-semibold flex items-center justify-between w-full"
                                    placeholder="Label..."
                                />
                            </div>

                            <!--  Instruction -->
                            <label
                                class="block text-sm font-medium my-2 text-gray-700"
                            >
                                Instruction
                            </label>
                            <textarea
                                v-model="question_group.question_instruction"
                                class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                            />
                        </div>
                        <!-- /Instruction -->

                        <!--  Category Label-->
                        <label class="block text-sm font-medium text-gray-700">
                            Category Label
                        </label>
                        <input
                            type="text"
                            v-model="question_group.category_label"
                            class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                        />
                        <!-- /Category Label-->

                        <!-- Group Question Categories -->
                        <label class="block text-sm font-medium text-gray-700"
                            >Question Categories</label
                        >
                        <div
                            v-if="!question_group.question_categories.length"
                            class="text-center text-gray-600"
                        >
                            No categories added for this group.
                        </div>
                        <div
                            v-for="(
                                category, categoryIndex
                            ) in question_group.question_categories"
                            :key="categoryIndex"
                        >
                            <input
                                type="text"
                                v-model="
                                    question_group.question_categories[
                                        categoryIndex
                                    ]
                                "
                                class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                            />
                            <button
                                type="button"
                                @click="
                                    removeQuestionCategory(
                                        sectionIndex,
                                        groupIndex,
                                        categoryIndex
                                    )
                                "
                                class="text-red-500 hover:text-red-700"
                            >
                                Remove Category
                            </button>
                        </div>
                        <button
                            type="button"
                            @click="
                                addQuestionCategory(sectionIndex, groupIndex)
                            "
                            class="mt-2 text-blue-500 hover:text-blue-700"
                        >
                            Add Question Category
                        </button>

                        <!-- Questions -->
                        <div
                            v-if="!question_group.questions.length"
                            class="text-center text-gray-600"
                        >
                            No questions added for this group.
                        </div>
                        <button
                            type="button"
                            @click="addQuestion(sectionIndex, groupIndex)"
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

                        <div
                            v-for="(
                                question, questionIndex
                            ) in question_group.questions"
                            :key="questionIndex"
                        >
                            <label for="question-category"
                                >Question Category</label
                            >
                            <select
                                id="question-category"
                                v-model="question.category"
                            >
                                <option value="">none</option>
                                <option
                                    v-for="category in question_group.question_categories"
                                    :value="category"
                                    :key="category"
                                >
                                    {{ category }}
                                </option>
                            </select>
                            <label
                                class="block text-sm font-medium text-gray-700"
                                >Question {{ questionIndex + 1 }}</label
                            >
                            <select
                                v-model="question.question_type"
                                class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                            >
                                <option value="text">Text</option>
                                <option value="radio">
                                    Likert Scale (1-5)
                                </option>
                            </select>
                            <input
                                type="text"
                                v-model="question.question"
                                placeholder="Question..."
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
                                @click="
                                    removeQuestion(
                                        sectionIndex,
                                        groupIndex,
                                        questionIndex
                                    )
                                "
                                class="text-red-500 hover:text-red-700"
                            >
                                Remove Question
                            </button>
                        </div>

                        <button
                            type="button"
                            @click="addQuestion(sectionIndex, groupIndex)"
                            class="mt-2 text-blue-500 hover:text-blue-700"
                        >
                            Add Question
                        </button>
                        <!-- Remove Question Group -->
                        <button
                            type="button"
                            class="mt-4 py-2 px-3 text-white bg-red-600 rounded-md hover:bg-red-500"
                            @click="
                                removeQuestionGroup(sectionIndex, groupIndex)
                            "
                        >
                            Remove Question Group
                        </button>
                    </div>
                    <!-- Add New Question Group -->
                    <div
                        class="px-4 py-5 bg-white space-y-6 sm:p-6 flex justify-center"
                    >
                        <button
                            type="button"
                            class="py-3 px-4 text-blue-600 bg-white border border-blue-500 rounded-md hover:bg-blue-500 hover:text-white"
                            @click="addQuestionGroup(sectionIndex)"
                        >
                            Add Question Group
                        </button>
                    </div>
                </div>
                <!-- Add New Question Section -->
                <div
                    class="px-4 py-5 bg-white space-y-6 sm:p-6 flex justify-center"
                >
                    <button
                        type="button"
                        class="py-3 px-4 text-blue-600 bg-white border border-blue-500 rounded-md hover:bg-blue-500 hover:text-white"
                        @click="addQuestionSection"
                    >
                        Add Question Section
                    </button>
                </div>
            </div>
            <!-- Questions field -->

            <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                <button @click="saveAsTemplate" type="button">
                    Save as template
                </button>
                <button
                    @click="showPreviewBtn"
                    type="button"
                    class="inline-flex justify-center mx-2 py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                    Show Preview
                </button>
                <button
                    type="submit"
                    class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                    Save
                </button>
            </div>
        </form>
        <!-- PREVIEW -->
        <div v-if="showPreview">
            <div>
                <h1 class="text-xl font-semibold">Survey Preview</h1>
            </div>
            <div
                v-for="(section, sectionIndex) of model.question_sections"
                :key="sectionIndex"
                class="mt-4"
            >
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
                                <th class="border px-4 py-2">
                                    Question Description
                                </th>
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
                                        {{ question.description }}
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
                                    <td class="border px-4 py-2">
                                        {{ question.description }}
                                    </td>
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
    </PageComponent>
</template>

<script setup>
import { ref, onMounted, watch, computed } from "vue";
import { useRoute, useRouter } from "vue-router";
import store from "../store";
import PageComponent from "../components/PageComponent.vue";

const route = useRoute();
const router = useRouter();
const user = computed(() => store.state.user.data);

let model = ref({
    title: "",
    slug: "",
    status: null,
    is_public: null,
    description: null,
    respondent_groups: [],
    expire_date: null,
    information_fields: [],
    question_sections: [
        {
            section_number: 1,
            section_label: "",
            section_instruction: "",
            question_groups: [
                {
                    section_number: 1,
                    number: 1,
                    label: "",
                    question_instruction: "",
                    category_label: "",
                    question_categories: [],
                    questions: [],
                },
            ],
        },
    ],
});
let showPreview = ref(false);
const showPreviewBtn = () => {
    showPreview.value = !showPreview.value;
};

// let model = ref({
//     image: "",
//     title: "",
//     slug: "",
//     status: null,
//     is_public: null,
//     description: null,
//     respondent_groups: [],
//     expire_date: null,
//     information_fields: [],
//     question_categories: [],
//     question_instruction: "",
//     question_groups: [{ number: 1, label: "" }],
//     questions: [],
// });

if (route.params.id) {
    store.dispatch("fetchSurvey", route.params.id).then((surveyData) => {
        console.log(surveyData);

        // JSON.parse(surveyData.question_categories);
        try {
            // surveyData.question_categories = JSON.parse(
            //     surveyData.question_categories
            // );
            surveyData.question_sections.forEach((question_section) => {
                question_section.forEach((question_group) => {
                    question_group.question_categories.forEach(
                        (question_category) => {
                            question_category = JSON.parse(question_category);
                        }
                    );
                });
            });
        } catch (e) {
            console.error("Error parsing question_categories:", e);
            // Handle parsing error (e.g., fall back to empty array or other logic)
            // surveyData.question_categories = [];
        }
        surveyData.question_sections.forEach((question_section) => {
            question_section.question_groups.forEach((question_group) => {
                question_group.questions.forEach((question) => {
                    question.data = JSON.parse(question.data);
                });
            });
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

const addQuestionCategory = (sectionIndex, groupIndex) => {
    if (
        model.value.question_sections[sectionIndex].question_groups[groupIndex]
            .category_label
    ) {
        model.value.question_sections[sectionIndex].question_groups[
            groupIndex
        ].question_categories.push(""); // Initialize with an empty string
    } else {
        alert("add category label first");
    }
};

const removeQuestionCategory = (groupIndex, categoryIndex) => {
    model.value.question_groups[groupIndex].question_categories.splice(
        categoryIndex,
        1
    );
};

const addQuestionSection = () => {
    const sectionNum = model.value.question_sections.length + 1;
    model.value.question_sections.push({
        section_number: sectionNum,
        section_label: "",
        section_instruction: "",
        question_groups: [],
    });
};

const removeQuestionSection = (index) => {
    if (confirm(`Are you sure you want to proceed?`)) {
        model.value.question_sections.splice(index, 1);
    }
};

const addQuestionGroup = (sectionIndex) => {
    const groupsNum =
        model.value.question_sections[sectionIndex].question_groups.length + 1;
    model.value.question_sections[sectionIndex].question_groups.push({
        section_number: sectionIndex + 1,
        number: groupsNum,
        label: "",
        question_instruction: "",
        question_categories: [],
        questions: [],
    });
};

const removeQuestionGroup = (sectionIndex, groupIndex) => {
    if (confirm(`Are you sure you want to proceed?`)) {
        model.value.question_sections[sectionIndex].question_groups.splice(
            groupIndex,
            1
        );
    }
};

const addQuestion = (sectionIndex, groupIndex) => {
    model.value.question_sections[sectionIndex].question_groups[
        groupIndex
    ].questions.push({
        group: groupIndex + 1,
        category: "", // can be nullable
        question_type: "",
        question: "",
        description: "",
        data: [1, 2, 3, 4, 5], // Default options for Likert scale questions
    });
};

const removeQuestion = (sectionIndex, groupIndex, index) => {
    model.value.question_sections[sectionIndex].question_groups[
        groupIndex
    ].questions.splice(index, 1);
};

const saveAsTemplate = () => {
    const formData = JSON.parse(JSON.stringify(model.value));
    const templateName = formData.title + " template";
    const userId = user.value.id;
    console.log(userId);

    const templateData = {
        user_id: userId,
        name: templateName,
        template: formData,
    };

    template = store
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
    if (confirm(`Are you sure you want to proceed?`)) {
        const surveyData = JSON.parse(JSON.stringify(model.value)); // Deep copy to avoid mutating the original model

        surveyData.question_sections.forEach((section) => {
            section.question_groups.forEach((group) => {
                group.questions.forEach((question) => {
                    // If question data is a stringified array, parse it into an array

                    // if (
                    //     typeof question.data === "string" &&
                    //     question.data.startsWith("[")
                    // ) {
                    //     question.data = JSON.parse(question.data);
                    // }

                    // If question data is an array, stringify it before sending to backend
                    // if (Array.isArray(question.data)) {
                    //     question.data = JSON.stringify(question.data);
                    // }
                    if (question.question_type === "text") {
                        question.data = null;
                    }
                    console.log(question.data);
                });
            });
        });
        console.log(surveyData);

        store.dispatch("saveSurvey", surveyData).then(({ data }) => {
            // console.log(data);

            router.push({
                name: "SurveyView",
                params: { id: data.id },
            });
        });
    } else {
        alert("cancelled");
    }
};

const deleteSurvey = () => {
    if (confirm("are you sure you wish to proceed?")) {
        store.dispatch("deleteSurvey", model.value.id).then(() => {
            router.push({ name: "Surveys" });
        });
    }
};
</script>
