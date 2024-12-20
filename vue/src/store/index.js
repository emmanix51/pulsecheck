import { createStore } from "vuex";
import axiosClient from "../axios";
import notificationsModule from "./modules/notifications";

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
            data: JSON.parse(localStorage.getItem("USER_DATA")) || {},
            token: localStorage.getItem("TOKEN"),
        },
        surveys: [],
        users: [],
    },
    getters: {
        getSurveyById: (state) => (id) => {
            return state.surveys.find((survey) => survey.id === id);
        },
        currentUser(state) {
            return state.user.data;
          },
    },
    actions: {
        fetchDashboard({ commit }, id) {
            return axiosClient
                .get(`/dashboard/${id}`)
                .then(({ data }) => {
                    return data;
                })
                .catch((error) => {
                    return {
                        error: error.response.data,
                        status: error.response.status,
                    };
                });
        },
        submitSurveyAnswers(
            { commit },
            {
                id,
                userId,
                answers,
                infoFields,
                selectedGroupType,
                selectedCategory,
            }
        ) {
            return axiosClient
                .post(`/submit-response`, {
                    survey_id: id, // assuming slug is the survey ID, adjust if it's different
                    respondent_id: userId,
                    respondent_type: selectedGroupType,
                    respondent_category: selectedCategory,
                    information_fields: infoFields,
                    answers: answers,
                })
                .then((response) => {
                    // Handle success, maybe commit some mutation or handle globally
                    console.log(response);
                })
                .catch((error) => {
                    // Handle error
                    console.error("Error submitting survey answers:", error);
                });
        },
        fetchSurveyBySlug({ commit }, slug) {
            return axiosClient
                .get(`/survey/slug/${slug}`)
                .then(({ data, status }) => {
                    return { data, status };
                })
                .catch((error) => {
                    console.log(error.response);
                    return {
                        error: error.response.data,
                        status: error.response.status,
                    };
                });
        },
        fetchPublicSurveyBySlug({ commit }, slug) {
            return axiosClient
                .get(`/public/survey/slug/${slug}`)
                .then(({ data, status }) => {
                    return { data, status };
                })
                .catch((error) => {
                    return {
                        error: error.response.data,
                        status: error.response.status,
                    };
                });
        },
        getAllUsers({ commit }, page = 1) {
            return axiosClient
                .get(`/admin/users?page=${page}`)
                .then(({ data }) => {
                    commit("setUsers", data.users.data);
                    return data;
                });
        },
        getAllResults({ commit }, page = 1) {
            return axiosClient.get(`/results?page=${page}`).then(({ data }) => {
                console.log(data);
                return data;
            });
        },
        addUser({ dispatch }, user) {
            return axiosClient.post("/admin/users", user).then(() => {
                dispatch("getAllUsers");
            });
        },
        updateUser({ dispatch }, user) {
            return axiosClient.put(`/admin/users/${user.id}`, user).then(() => {
                dispatch("getAllUsers");
            });
        },
        deleteUser({ dispatch }, id) {
            return axiosClient.delete(`/admin/users/${id}`).then(() => {
                dispatch("getAllUsers");
            });
        },
        deleteSurvey({ commit }, surveyId) {
            return axiosClient
                .delete(`/survey/${surveyId}`)
                .then((response) => {
                    commit("removeSurvey", surveyId);
                    return response;
                });
        },
        deleteTemplate({ commit }, templateId) {
            return axiosClient
                .delete(`/template/${templateId}`)
                .then((response) => {
                    return response;
                });
        },
        async distributeSurvey({ dispatch }, surveyId) {
            try {
                await axiosClient.post(`/survey/${surveyId}/distribute`);
                dispatch("getSurveys");
                dispatch("notifyRespondents", surveyId);
            } catch (error) {
                console.error("Error distributing survey:", error);
            }
        },
        // async notifyRespondents({ commit }, surveyId) {
        //     try {
        //         const response = await axiosClient.post(
        //             `/survey/${surveyId}/notify-respondents`
        //         );
        //         console.log("Notification response:", response);
        //         // Fetch notifications after notifying respondents
        //         await dispatch("notifications/fetchNotifications", null, {
        //             root: true,
        //         });
        //     } catch (error) {
        //         console.error("Error notifying respondents:", error);
        //     }
        // },
        getPublicSurveys({ commit }, page) {
            // console.log(page);
            return axiosClient
                .get(`/public-surveys?page=${page}`)
                .then(({ data }) => {
                    return data;
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
        getTemplates({ commit }) {
            return axiosClient.get(`/template`).then(({ data }) => {
                return data;
            });
        },
        saveAsTemplate({}, formData) {
            return axiosClient.post("/template", formData);
        },
        saveSurvey({ commit }, survey) {
            let response;
            console.log(survey);

            if (survey.id) {
                response = axiosClient
                    .put(`/survey/${survey.id}`, survey)
                    .then((res) => {
                        commit("updateSurvey", res.data);
                        return res;
                    });
            } else {
                response = axiosClient.post("/survey", survey).then((res) => {
                    console.log("test");
                    commit("saveSurvey", res.data);
                    return res;
                });
            }
            return response;
        },
        fetchTemplate({ commit }, id) {
            return axiosClient.get(`/template/${id}`).then(({ data }) => {
                // commit("setSurvey", data.data);
                console.log(data);
                return data;
            });
        },
        fetchSurvey({ commit }, id) {
            return axiosClient.get(`/survey/${id}`).then(({ data }) => {
                commit("setSurvey", data.data);
                console.log(data.data);
                return data.data;
            });
        },
        fetchSurveyResultData({ commit }, id) {
            return axiosClient.get(`/survey/${id}/results`).then(({ data }) => {
                // console.log(data.survey);
                return data;
            });
        },
        fetchResultDescriptiveData({ commit }, { id, params }) {
            return axiosClient
                .get(`/survey/${id}/results/descriptive`, {
                    params,
                })
                .then(({ data }) => {
                    return data;
                });
        },
        fetchSurveyDetails({ commit }, id) {
            return axiosClient.get(`/survey/${id}/details`).then(({ data }) => {
                return data;
            });
        },
        responseCsv({}, id) {
            console.log("Survey ID:", id); // Log the survey ID to check its value
            axiosClient({
                url: `/survey/${id}/export`,
                method: "GET",
                responseType: "blob", // Important for handling binary data
            })
                .then((response) => {
                    const url = window.URL.createObjectURL(
                        new Blob([response.data])
                    );
                    const link = document.createElement("a");
                    link.href = url;
                    link.setAttribute("download", `survey_responses_${id}.csv`); // Define the file name
                    document.body.appendChild(link);
                    link.click();
                    link.remove();
                })
                .catch((error) => {
                    console.error("Error downloading the file:", error);
                });
        },
        fetchResponse({}, id) {
            return axiosClient
                .get(`/survey/responses/${id}`)
                .then(({ data }) => {
                    return data;
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
        forgotPassword({commit},email){
            console.log(email);
            return axiosClient.post("/forgot-password",{ email: email }).then(({data})=>{
                return data;
            })
        },
        logout({ commit }) {
            return axiosClient.post("/logout").then((response) => {
                commit("logout");
                return response;
            });
        },
    },
    mutations: {
        removeUser: (state, userId) => {
            state.users = state.users.filter((user) => user.id !== userId);
        },
        setUsers: (state, users) => {
            state.users = users;
        },
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
            localStorage.removeItem("USER_DATA");
            localStorage.removeItem("TOKEN");
        },
        setUser(state, user) {
            state.user.data = user;
            sessionStorage.setItem("USER_DATA", JSON.stringify(user));
            localStorage.setItem("USER_DATA", JSON.stringify(user));
        },
        setToken: (state, token) => {
            state.user.token = token;
            sessionStorage.setItem("TOKEN", token);
            localStorage.setItem("TOKEN", token);
        },
    },
    modules: {
        notifications: notificationsModule,
    },
});

export default store;
