import { createStore } from "vuex";
import axiosClient from "../axios";

// const tmpSurveys = [
//     {
//         id: 100,
//         title: "TheCodeholic Youtube Channel Content",
//         slug: "thecodeholic-youtube-channel-content",
//         status: "draft",
//         image: "https://i.imgur.com/E017SNN.png",
//         description: "This is a survey description 1",
//         created_at: "2024-06-16 18:00:00",
//         updated_at: "2024-06-16 18:00:00",
//         expire_at: "2024-06-20 18:00:00",
//         questions: [
//             {
//                 id: 1,
//                 type: "select",
//                 question: "From which country are you?",
//                 description: null,
//                 data: {
//                     options: [
//                         {
//                             uuid: "3c128cfa-ddc4-4b9c-8e45-b940615a99bc",
//                             text: "USA",
//                         },
//                         {
//                             uuid: "6216a0a9-78da-44b3-a9f0-8d955039e57d",
//                             text: "Georgia",
//                         },
//                         {
//                             uuid: "0796f817-515b-473d-a65f-21a357aefbec",
//                             text: "India",
//                         },
//                         {
//                             uuid: "068e1673-9149-47a7-bcd5-499714f9b572",
//                             text: "United Kingdom",
//                         },
//                     ],
//                 },
//             },
//             {
//                 id: 2,
//                 type: "checkbox",
//                 question:
//                     "Which language videos do you want to see on my channel",
//                 description:
//                     "qweqweqweqweqweqweeeeeeeeee qwe q2e 2w asf 3 eawf asfd asdf asdf ",
//                 data: {
//                     options: [
//                         {
//                             uuid: "fc963894-912d-40ad-b717-b56a926fd68f",
//                             text: "Javascript",
//                         },
//                         {
//                             uuid: "c791b830-f051-44c3-8654-cbe499f7b98c",
//                             text: "PHP",
//                         },
//                         {
//                             uuid: "3b78184c-83fe-41ff-acbe-7c1eb349b31f",
//                             text: "HTML + CSS",
//                         },
//                         {
//                             uuid: "35b56cd5-9baf-4a0f-9c15-60ab2efb7f0b",
//                             text: "All of the above",
//                         },
//                     ],
//                 },
//             },
//             {
//                 id: 3,
//                 type: "checkbox",
//                 question:
//                     "Which PHP framework do you want to see in my channel?",
//                 description:
//                     "lorem qweqweseeeeee qwe q2e 2w asf 3 eawf asfd asdf asdf ",
//                 data: {
//                     options: [
//                         {
//                             uuid: "76972682-d265-4636-93c4-6c3d7cf3d2bd",
//                             text: "Laravel",
//                         },
//                         {
//                             uuid: "eec20cb1-c7cd-4ba9-8f2f-28c687a4f447",
//                             text: "Yii2",
//                         },
//                         {
//                             uuid: "95e2ee12-8866-44fb-b2a7-362f332f26dd",
//                             text: "Codeigniter",
//                         },
//                         {
//                             uuid: "8f10d378-c2bd-4c7d-b981-3edc13bd02bf",
//                             text: "Symfony",
//                         },
//                     ],
//                 },
//             },
//             {
//                 id: 4,
//                 type: "radio",
//                 question: "Which laravel framework do you love most?",
//                 description: "pisti animal yawa kakapoy sa kinabuhi",
//                 data: {
//                     options: [
//                         {
//                             uuid: "194c2c0b-f70d-4f15-b720-d3623cd3aab3",
//                             text: "Laravel 5",
//                         },
//                         {
//                             uuid: "857509ed-394f-424f-bd3d-11b97e597e9f",
//                             text: "Laravel 6",
//                         },
//                         {
//                             uuid: "917af4a6-55f7-42f5-bd31-00fcfbab3512",
//                             text: "Laravel 7",
//                         },
//                         {
//                             uuid: "f91b62af-9d02-4691-9009-fecf03de54a3",
//                             text: "Laravel 8",
//                         },
//                     ],
//                 },
//             },
//             {
//                 id: 5,
//                 type: "checkbox",
//                 question:
//                     "What type of projects do you want to see in the channel",
//                 description: "pisti animal yawa kakapoy sa kinabuhi",
//                 data: {
//                     options: [
//                         {
//                             uuid: "6cb22f7e-cf88-4dae-b7a7-bc6a0a3f27b1",
//                             text: "REST API",
//                         },
//                         {
//                             uuid: "8dc52035-707b-4cef-bff5-6063bbbfd5fb",
//                             text: "E-Commerce",
//                         },
//                         {
//                             uuid: "5ac59e53-9c88-40ab-95aa-d010a3b34574",
//                             text: "Real Estate",
//                         },
//                         {
//                             uuid: "33f86913-ded7-452e-ac7f-c7b0fdf4f46a",
//                             text: "All of the above",
//                         },
//                     ],
//                 },
//             },
//             {
//                 id: 6,
//                 type: "text",
//                 question: "Whats your favorite youtube channel",
//                 description: null,
//                 data: {},
//             },
//             {
//                 id: 7,
//                 type: "textarea",
//                 question: "What do you think of thecodeholic channel",
//                 description:
//                     "Write your honest opinion, Everythin is anonymous.",
//                 data: {},
//             },
//         ],
//     },
//     {
//         id: 200,
//         title: "Laravel 8",
//         slug: "laravel-8",
//         status: "active",
//         image: "https://i.imgur.com/E017SNN.png",
//         description: "This is a survey description 2",
//         created_at: "2024-06-16 18:00:00",
//         updated_at: "2024-06-16 18:00:00",
//         expire_at: "2024-06-20 18:00:00",
//         questions: [],
//     },
//     {
//         id: 300,
//         title: "Vue 3",
//         slug: "vue-3",
//         status: "active",
//         image: "https://i.imgur.com/E017SNN.png",
//         description: "This is a survey description 3",
//         created_at: "2024-06-16 18:00:00",
//         updated_at: "2024-06-16 18:00:00",
//         expire_at: "2024-06-20 18:00:00",
//         questions: [],
//     },
//     {
//         id: 400,
//         title: "Tailwind 3",
//         slug: "tailwind-3",
//         status: "active",
//         image: "https://i.imgur.com/E017SNN.png",
//         description: "This is a survey description 4",
//         created_at: "2024-06-16 18:00:00",
//         updated_at: "2024-06-16 18:00:00",
//         expire_at: "2024-06-20 18:00:00",
//         questions: [],
//     },
// ];

const sampleSurvey = [
    {
        id: 1,
        user_id: 1,
        image: "https://i.imgur.com/rXllBYU.jpeg",
        title: "Customer Satisfaction",
        slug: "customer-satisfaction",
        status: "draft",
        description: "A survey to gauge customer satisfaction",
        respondent_group: "students",
        created_at: "2024-06-01T12:00:00Z",
        updated_at: "2024-06-01T12:00:00Z",
        expire_date: "2024-12-31T23:59:59Z",
        questions: [
            {
                id: 1,
                survey_id: 1,
                type: "text",
                question: "What is your overall satisfaction?",
                description: "Please rate your overall experience.",
                data: null,
                created_at: "2024-06-01T12:00:00Z",
                updated_at: "2024-06-01T12:00:00Z",
            },
            {
                id: 2,
                survey_id: 1,
                type: "radio",
                question: "How did you hear about us?",
                description: "Select one option.",
                data: '{"options": ["Internet", "Friend", "Advertisement", "Other"]}',
                created_at: "2024-06-01T12:00:00Z",
                updated_at: "2024-06-01T12:00:00Z",
            },
            {
                id: 3,
                survey_id: 1,
                type: "text",
                question: "Any additional comments?",
                description: "Feel free to share any other feedback.",
                data: null,
                created_at: "2024-06-01T12:00:00Z",
                updated_at: "2024-06-01T12:00:00Z",
            },
        ],
    },
    {
        id: 2,
        user_id: 1,
        image: "https://i.imgur.com/rXllBYU.jpeg",
        title: "Customer Satisfaction 2",
        slug: "customer-satisfaction-2",
        status: "draft",
        description: "A survey to gauge customer satisfaction",
        respondent_group: ["students", "faculty", "staff"],
        created_at: "2024-06-01T12:00:00Z",
        updated_at: "2024-06-01T12:00:00Z",
        expire_date: "2024-12-31T23:59:59Z",
        questions: [
            {
                id: 4,
                survey_id: 2,
                type: "text",
                question: "What is your overall satisfaction?",
                description: "Please rate your overall experience.",
                data: null,
                created_at: "2024-06-01T12:00:00Z",
                updated_at: "2024-06-01T12:00:00Z",
            },
            {
                id: 5,
                survey_id: 2,
                type: "radio",
                question: "How did you hear about us?",
                description: "Select one option.",
                data: '{"options": ["Internet", "Friend", "Advertisement", "Other"]}',
                created_at: "2024-06-01T12:00:00Z",
                updated_at: "2024-06-01T12:00:00Z",
            },
            {
                id: 6,
                survey_id: 2,
                type: "text",
                question: "Any additional comments?",
                description: "Feel free to share any other feedback.",
                data: null,
                created_at: "2024-06-01T12:00:00Z",
                updated_at: "2024-06-01T12:00:00Z",
            },
        ],
    },
];

const store = createStore({
    state: {
        user: {
            data: JSON.parse(sessionStorage.getItem("USER_DATA")) || {},
            token: sessionStorage.getItem("TOKEN"),
        },
        surveys: [],
    },
    getters: {
        getSurveyById: (state) => (id) => {
            return state.surveys.find((survey) => survey.id === id);
        },
    },
    actions: {
        deleteSurvey({ commit }, surveyId) {
            return axiosClient
                .delete(`/survey/${surveyId}`)
                .then((response) => {
                    commit("removeSurvey", surveyId);
                    return response;
                });
        },
        getSurveys({ commit }, { url = null } = {}) {
            // commit('setSurveysLoading', true)
            url = url || "/survey";
            return axiosClient.get(url).then((res) => {
                //   commit('setSurveysLoading', false)
                commit("setSurveys", res.data);
                return res;
            });
        },
        saveSurvey({ commit }, survey) {
            let response;
            if (survey.id) {
                response = axiosClient
                    .put(`/survey/${survey.id}`, survey)
                    .then((res) => {
                        commit("updateSurvey", res.data);
                        return res;
                    });
            } else {
                response = axiosClient.post("/survey", survey).then((res) => {
                    commit("saveSurvey", res.data);
                    return res;
                });
            }
            return response;
        },
        fetchSurvey({ commit }, id) {
            return axiosClient.get(`/survey/${id}`).then(({ data }) => {
                commit("setSurvey", data.data);
                console.log(data.data);
                return data.data;
            });
        },
        register({ commit }, user) {
            return axiosClient
                .post("/register", user)
                .then(({ data }) => {
                    commit("setUser", data.user);
                    commit("setToken", data.token);
                    return data;
                })
                .catch((error) => {
                    if (error.response.status === 422) {
                        console.error(error.response.data);
                    }
                });
        },
        login({ commit }, user) {
            return axiosClient.post("/login", user).then(({ data }) => {
                commit("setUser", data.user);
                commit("setToken", data.token);
                return data;
            });
        },
        logout({ commit }) {
            return axiosClient.post("/logout").then((response) => {
                commit("logout");
                return response;
            });
        },
    },
    mutations: {
        removeSurvey: (state, surveyId) => {
            state.surveys = state.surveys.filter(
                (survey) => survey.id !== surveyId
            );
        },
        setSurveys: (state, surveys) => {
            state.surveys.links = surveys.meta.links;
            state.surveys.data = surveys.data;
        },
        setSurvey: (state, survey) => {
            const index = state.surveys.findIndex((s) => s.id === survey.id);
            if (index !== -1) {
                state.surveys.splice(index, 1, survey);
            } else {
                state.surveys.push(survey);
            }
        },
        saveSurvey: (state, survey) => {
            state.surveys.push(survey);
        },
        updateSurvey: (state, updatedSurvey) => {
            state.surveys = state.surveys.map((survey) =>
                survey.id === updatedSurvey.id ? updatedSurvey : survey
            );
        },
        logout: (state) => {
            state.user.data = {};
            state.user.token = null;
            sessionStorage.removeItem("USER_DATA");
            sessionStorage.removeItem("TOKEN");
        },
        setUser(state, user) {
            state.user.data = user;
            sessionStorage.setItem("USER_DATA", JSON.stringify(user));
        },
        setToken: (state, token) => {
            state.user.token = token;
            sessionStorage.setItem("TOKEN", token);
        },
    },
    modules: {},
});

export default store;
