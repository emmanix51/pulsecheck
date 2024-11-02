<template>
    <div
        v-if="showPreview"
        class="z-21 fixed inset-0 flex items-center justify-center bg-black bg-opacity-50"
    >
        <div class="bg-white p-6 rounded-md w-[80%] h-[80%] overflow-y-scroll ">
            <div class="flex justify-between">
                <h1 class="text-xl font-semibold">Survey Preview</h1>
                <button @click="close" class="mt-4 text-red-500">Close Preview</button>
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
                    <div v-if="question_group.format == 'withoutCategory'">
                        <h3 class="text-lg font-semibold mb-4">
                            Preview for Group {{ question_group.number }}
                        </h3>
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
                                    <th class="border px-4 py-2">
                                        {{ question_group.label }}
                                    </th>
                                    <th class="border px-4 py-2">Answer</th>
                                </tr>
                            </thead>
                            <tbody>
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
                    <div v-if="question_group.format == 'withCategory'">
                        <h3 class="text-lg font-semibold mb-4">
                            Preview for Group {{ question_group.number }}
                        </h3>
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
                                    <th class="border px-4 py-2">
                                        {{ question_group.label }}
                                    </th>
                                    <th class="border px-4 py-2">
                                        Answer Scale
                                    </th>
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
                            </tbody>
                        </table>
                    </div>
                    <div v-if="question_group.format == 'commentSection'">
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
                                <th class="border px-4 py-2">
                                    {{ question_group.label }}
                                </th>
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
        </div>
    </div>
</template>
<script setup>
import { defineProps, defineEmits } from "vue";

const props = defineProps(["showPreview", "model"]);
const emit = defineEmits(["onClose"]);

const close = () => {
    emit("onClose");
};
</script>
