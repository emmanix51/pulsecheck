<template>
    <div>
        <div>
            <!-- <pre>{{ testData }}</pre> -->
            <h1 class="text-xl font-semibold">Analysis report Preview</h1>
        </div>
        <div
            v-for="(section, sectionIndex) of questionSections"
            :key="sectionIndex"
            class="mt-4"
        >
            <h1>SECTION {{ sectionIndex + 1 }}</h1>
            <div
                v-for="(question_group, groupIndex) of section.question_groups"
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

                            <th class="border px-4 py-2">Mean</th>
                            <th class="border px-4 py-2">Interpretation</th>
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
                                    <!-- Preview answer fields based on question type -->
                                    <!-- Likert Scale (1-5) preview -->
                                    <div
                                        v-if="
                                            question.question_type === 'radio'
                                        "
                                        class="flex space-x-2"
                                    >
                                        {{ question.average }}
                                    </div>
                                    <!-- Text question preview -->
                                    <!-- <div
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
                                            </div> -->
                                </td>
                            </tr>
                            <tr>
                                <td>overall mean:</td>
                                <td>overall mean:</td>
                                <td>deez</td>
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
                                                    'question-' + questionIndex
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
                                            question.question_type === 'text'
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
</template>

<script setup>
import { computed } from "vue";

const props = defineProps(["testData", "questionSections"]);

const calculateOverallMean = (questions) => {
    const total = questions.reduce(
        (sum, question) => sum + (question.average || 0),
        0
    );
    return (total / questions.length).toFixed(2);
};
</script>

<style></style>
