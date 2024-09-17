<template>
    <div>
        <div class="sm:mx-auto sm:w-full sm:max-w-sm">
            <img class="mx-auto h-16 w-16" src="/spc.png" alt="Your Company" />
            <h2
                class="mt-10 text-center text-2xl font-bold leading-9 tracking-tight text-gray-900"
            >
                Register
            </h2>
        </div>

        <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
            <form class="space-y-6" @submit="register">
                <div>
                    <label
                        for="idnum"
                        class="block text-sm font-medium leading-6 text-gray-900"
                        >ID number:</label
                    >
                    <div class="mt-2">
                        <input
                            id="idnum"
                            v-model="user.idnum"
                            type="number"
                            required
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                        />
                    </div>
                    <small class="text-gray-400">format: 20241234</small>
                </div>
                <div>
                    <label
                        for="fname"
                        class="block text-sm font-medium leading-6 text-gray-900"
                        >First name</label
                    >
                    <div class="mt-2">
                        <input
                            id="fname"
                            v-model="user.first_name"
                            type="text"
                            required
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                        />
                    </div>
                </div>
                <div>
                    <label
                        for="lname"
                        class="block text-sm font-medium leading-6 text-gray-900"
                        >Last name</label
                    >
                    <div class="mt-2">
                        <input
                            id="lname"
                            v-model="user.last_name"
                            type="text"
                            required
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                        />
                    </div>
                </div>
                <div>
                    <label
                        for="email"
                        class="block text-sm font-medium leading-6 text-gray-900"
                        >Email address</label
                    >
                    <div class="mt-2">
                        <input
                            id="email"
                            v-model="user.email"
                            type="email"
                            autocomplete="email"
                            required
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                        />
                    </div>
                </div>

                <div>
                    <label
                        for="password"
                        class="block text-sm font-medium leading-6 text-gray-900"
                        >Password</label
                    >
                    <div class="mt-2">
                        <input
                            id="password"
                            v-model="user.password"
                            type="password"
                            autocomplete="current-password"
                            required
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                        />
                    </div>
                </div>
                <div>
                    <label
                        for="password_confirm"
                        class="block text-sm font-medium leading-6 text-gray-900"
                        >Password Confirmation</label
                    >
                    <div class="mt-2">
                        <input
                            id="password_confirm"
                            name="password_confirm"
                            v-model="user.password_confirmation"
                            type="password"
                            autocomplete="current-password_confirm"
                            required
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                        />
                    </div>
                </div>

                <div>
                    <label
                        for="respondentType"
                        class="block text-sm font-medium leading-6 text-gray-900"
                        >Respondent Type</label
                    >
                    <div class="mt-2">
                        <select
                            id="respondentType"
                            v-model="user.respondent_type"
                            @change="handleRespondentTypeChange"
                            required
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                        >
                            <option value="" disabled>
                                Select respondent type
                            </option>
                            <option value="student">Student</option>
                            <option value="faculty">Faculty</option>
                            <option value="staff">Staff</option>
                            <option value="stakeholder">Stakeholder</option>
                        </select>
                    </div>
                </div>

                <!-- Conditional rendering based on respondentType -->
                <div v-if="user.respondent_type === 'student'">
                    <label
                        for="studentCategory"
                        class="block text-sm font-medium leading-6 text-gray-900"
                        >Student Category</label
                    >
                    <div class="mt-2">
                        <select
                            id="studentCategory"
                            v-model="user.category"
                            required
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                        >
                            <option value="" disabled>
                                Select student category
                            </option>
                            <option value="BSCS">BSCS</option>
                            <option value="BSIT">BSIT</option>
                        </select>
                    </div>
                </div>

                <div v-if="user.respondent_type === 'faculty'">
                    <label
                        for="facultyCategory"
                        class="block text-sm font-medium leading-6 text-gray-900"
                        >Faculty Category</label
                    >
                    <div class="mt-2">
                        <select
                            id="facultyCategory"
                            v-model="user.category"
                            required
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                        >
                            <option value="" disabled>
                                Select faculty category
                            </option>
                            <option value="HR">HR</option>
                            <option value="OSAS">OSAS</option>
                        </select>
                    </div>
                </div>

                <div v-if="user.respondent_type === 'staff'">
                    <label
                        for="staffCategory"
                        class="block text-sm font-medium leading-6 text-gray-900"
                        >Staff Category</label
                    >
                    <div class="mt-2">
                        <select
                            id="staffCategory"
                            v-model="user.category"
                            required
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                        >
                            <option value="" disabled>
                                Select staff category
                            </option>
                            <option value="HR">HR</option>
                            <option value="OSAS">OSAS</option>
                        </select>
                    </div>
                </div>
                <div v-if="user.respondent_type === 'stakeholder'">
                    <label
                        for="staffCategory"
                        class="block text-sm font-medium leading-6 text-gray-900"
                        >Staff Category</label
                    >
                    <div class="mt-2">
                        <select
                            id="staffCategory"
                            v-model="user.category"
                            required
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                        >
                            <option value="" disabled>
                                Select staff category
                            </option>
                            <option value="stakeholder">stakeholder</option>
                            <option value="alumni">alumni</option>
                        </select>
                    </div>
                </div>

                <div>
                    <button
                        type="submit"
                        class="flex w-full justify-center rounded-md bg-spccolor-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-spccolor-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                    >
                        Register
                    </button>
                </div>
            </form>

            <p class="mt-10 text-center text-sm text-gray-500">
                Already a member?
                <router-link
                    :to="{ name: 'Login' }"
                    class="font-semibold leading-6 text-spccolor-600 hover:text-spccolor-500 hover:underline"
                    >Log in here</router-link
                >
            </p>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";
import { useStore } from "vuex";
import { useRouter } from "vue-router";

const store = useStore();
const router = useRouter();

const user = ref({
    idnum: "",
    first_name: "",
    last_name: "",
    email: "",
    password: "",
    password_confirmation: "",
    role: "respondent", // default to respondent
    respondent_type: "",
    category: "",
});

const handleRespondentTypeChange = () => {
    user.value.category = ""; // Reset category when respondent type changes
};

function register(ev) {
    ev.preventDefault();
    store
        .dispatch("register", user.value)
        .then(() => {
            router.push({
                name: "Dashboard",
            });
        })
        .catch((error) => {
            // loading.value = false;
            if (error.response) {
                console.error("API error response:", error.response.data);
            } else {
                console.error("API error message:", error.message);
            }
        });
}
const register1 = async (ev) => {
    ev.preventDefault();
    try {
        await store.dispatch("register", user.value);
        router.push({ name: "Login" });
    } catch (error) {
        console.error(error);
    }
};

// const router = useRouter();

// // Data properties
// const email = ref("");
// const password = ref("");
// const respondentType = ref("");
// const studentCategory = ref("");
// const facultyCategory = ref("");
// const staffCategory = ref("");

// // Register user function
// const registerUser = () => {
//     // Prepare data based on respondent type
//     let userData = {
//         email: email.value,
//         password: password.value,
//         respondent_type: respondentType.value,
//     };

//     // Add category based on respondent type
//     switch (respondentType.value) {
//         case "student":
//             userData.student_category = studentCategory.value;
//             break;
//         case "faculty":
//             userData.faculty_category = facultyCategory.value;
//             break;
//         case "staff":
//             userData.staff_category = staffCategory.value;
//             break;
//         default:
//             // Stakeholder case
//             break;
//     }

//     // Example Axios POST request to Laravel API
//     axios
//         .post("/api/register", userData)
//         .then((response) => {
//             // Handle successful registration
//             console.log(response.data);
//             router.push({ name: "Login" }); // Redirect to login page after registration
//         })
//         .catch((error) => {
//             // Handle registration error
//             console.error("Registration error:", error);
//         });
// };

// // Handle respondent type change
// const handleRespondentTypeChange = () => {
//     // Reset category values when respondent type changes
//     studentCategory.value = "";
//     facultyCategory.value = "";
//     staffCategory.value = "";
// };
</script>

<style></style>
