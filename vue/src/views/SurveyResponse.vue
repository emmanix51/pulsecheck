<template>
    <PageComponent>
        <template v-slot:header>
            <div class="flex items-center justify-between">
                <h1 class="text-3xl font-bold text-gray-900">
                    Survey Result for: {{ model.title }}
                </h1>
            </div>
        </template>
        <pre>{{ testdata }}</pre>
        <!-- <pre>{{ totalResponse }}</pre>
        <pre>{{ totalAnswerScale }}</pre>
        <pre>{{ averageAnswerScale }}</pre> -->
        <!-- <pre>{{ Object.keys(surveyData.responses).length }}</pre> -->
        <div
            class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 text-gray-700"
        >
            <div v-for="(answer, i) of answerDetails" :key="i">
                {{ answer.question.question }}-{{ answer.answer_scale }}
            </div>
        </div>
    </PageComponent>
</template>

<script setup>
import { computed, ref } from "vue";
import { useRoute, useRouter } from "vue-router";
import store from "../store";

import PageComponent from "../components/PageComponent.vue";

const route = useRoute();
const router = useRouter();

let model = ref({
    title: "",
    slug: "",
    status: null,
    is_public: null,
    description: null,
    respondent_groups: [],
    expire_date: null,
    information_fields: [],
    questions: [],
    responses: [],
});

let totalResponses = ref(0);
let totalAnswerScale = ref(0);
let averageAnswerScale = ref(0);

const testdata = ref({});
const answerDetails = ref({});

if (route.params.id) {
    store.dispatch("fetchResponse", route.params.id).then((data) => {
        console.log(data);
        testdata.value = data;
        answerDetails.value = data.answerDetails;
        // totalResponses.value = data.totalResponses;
        // totalAnswerScale.value = data.totalAnswerScale;
        // averageAnswerScale.value = data.averageAnswerScale;
        // model.value = data.survey;
    });
}
</script>

<style></style>
