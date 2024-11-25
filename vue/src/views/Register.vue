<template>
    <div class="flex min-h-screen">
        <!-- Left side with registration form -->
        <div class="flex-1 flex flex-col p-8">
            <div class="flex items-center gap-3 text-[#722F37] ">
                <img class="h-12 w-12" src="/logo.png" alt="Pulse Check" />
                <h1 class="text-xl font-bold">Pulse Check</h1>
            </div>

            <div class="flex-1 flex items-center justify-center">
                <div class="w-full max-w-md">
                    <h2 class="text-2xl font-bold text-[#722F37] mb-8 ">
                        Register
                    </h2>

                    <form @submit="register" class="space-y-3">
                        <div
                            v-if="errorMsg"
                            class="bg-red-100 text-red-800 px-4 py-3 rounded-md cursor-pointer"
                            @click="errorMsg = ''"
                        >
                            {{ errorMsg }}
                        </div>

                        <!-- ID Number -->
                        <div>
                            <label
                                for="idnum"
                                class="block text-sm font-medium text-gray-700"
                                >ID number:</label
                            >
                            <input
                                id="idnum"
                                v-model="user.idnum"
                                type="number"
                                required
                                class="mt-2 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-[#722F37] focus:outline-none focus:ring-[#722F37]"
                            />
                            <small class="text-gray-400"
                                >format: 20241234</small
                            >
                        </div>
                        <div class="flex justify-between">
                            <!-- First Name -->
                            <div>
                                <label
                                    for="fname"
                                    class="block text-sm font-medium text-gray-700"
                                    >First name</label
                                >
                                <input
                                    id="fname"
                                    v-model="user.first_name"
                                    type="text"
                                    required
                                    class="mt-2 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-[#722F37] focus:outline-none focus:ring-[#722F37]"
                                />
                            </div>

                            <!-- Last Name -->
                            <div>
                                <label
                                    for="lname"
                                    class="block text-sm font-medium text-gray-700"
                                    >Last name</label
                                >
                                <input
                                    id="lname"
                                    v-model="user.last_name"
                                    type="text"
                                    required
                                    class="mt-2 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-[#722F37] focus:outline-none focus:ring-[#722F37]"
                                />
                            </div>
                        </div>

                        <!-- Email -->
                        <div>
                            <label
                                for="email"
                                class="block text-sm font-medium text-gray-700"
                                >Email address</label
                            >
                            <input
                                id="email"
                                v-model="user.email"
                                type="email"
                                autocomplete="email"
                                required
                                class="mt-2 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-[#722F37] focus:outline-none focus:ring-[#722F37]"
                            />
                        </div>

                        <div class="flex justify-between">
                            <!-- Password -->
                            <div>
                                <label
                                    for="password"
                                    class="block text-sm font-medium text-gray-700"
                                    >Password</label
                                >
                                <input
                                    id="password"
                                    v-model="user.password"
                                    type="password"
                                    autocomplete="current-password"
                                    required
                                    class="mt-2 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-[#722F37] focus:outline-none focus:ring-[#722F37]"
                                />
                            </div>

                            <!-- Password Confirmation -->
                            <div>
                                <label
                                    for="password_confirm"
                                    class="block text-sm font-medium text-gray-700"
                                    >Password Confirmation</label
                                >
                                <input
                                    id="password_confirm"
                                    v-model="user.password_confirmation"
                                    type="password"
                                    autocomplete="current-password_confirm"
                                    required
                                    class="mt-2 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-[#722F37] focus:outline-none focus:ring-[#722F37]"
                                />
                            </div>
                        </div>

                        <div class="flex justify-between">
                        <!-- Respondent Type -->
                        <div>
                            <label
                                for="respondentType"
                                class="block text-sm font-medium text-gray-700"
                                >Respondent Type</label
                            >
                            <select
                                id="respondentType"
                                v-model="user.respondent_type"
                                @change="handleRespondentTypeChange"
                                required
                                class="mt-2 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-[#722F37] focus:outline-none focus:ring-[#722F37]"
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

                        <!-- Conditional Category Selection -->
                        <div v-if="user.respondent_type === 'student'">
                            <label
                                for="studentCategory"
                                class="block text-sm font-medium text-gray-700"
                                >Student Category</label
                            >
                            <select
                                id="studentCategory"
                                v-model="user.category"
                                required
                                class="mt-2 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-[#722F37] focus:outline-none focus:ring-[#722F37]"
                            >
                                <option value="" disabled>
                                    Select student category
                                </option>
                                <option value="BSCS">BSCS</option>
                                <option value="BSIT">BSIT</option>
                            </select>
                        </div>

                        <div v-if="user.respondent_type === 'faculty'">
                            <label
                                for="facultyCategory"
                                class="block text-sm font-medium text-gray-700"
                                >Faculty Category</label
                            >
                            <select
                                id="facultyCategory"
                                v-model="user.category"
                                required
                                class="mt-2 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-[#722F37] focus:outline-none focus:ring-[#722F37]"
                            >
                                <option value="" disabled>
                                    Select faculty category
                                </option>
                                <option value="HR">HR</option>
                                <option value="OSAS">OSAS</option>
                            </select>
                        </div>

                        <div v-if="user.respondent_type === 'staff'">
                            <label
                                for="staffCategory"
                                class="block text-sm font-medium text-gray-700"
                                >Staff Category</label
                            >
                            <select
                                id="staffCategory"
                                v-model="user.category"
                                required
                                class="mt-2 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-[#722F37] focus:outline-none focus:ring-[#722F37]"
                            >
                                <option value="" disabled>
                                    Select staff category
                                </option>
                                <option value="HR">HR</option>
                                <option value="OSAS">OSAS</option>
                            </select>
                        </div>

                        <div v-if="user.respondent_type === 'stakeholder'">
                            <label
                                for="stakeholderCategory"
                                class="block text-sm font-medium text-gray-700"
                                >Stakeholder Category</label
                            >
                            <select
                                id="stakeholderCategory"
                                v-model="user.category"
                                required
                                class="mt-2 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-[#722F37] focus:outline-none focus:ring-[#722F37]"
                            >
                                <option value="" disabled>
                                    Select stakeholder category
                                </option>
                                <option value="stakeholder">Stakeholder</option>
                                <option value="alumni">Alumni</option>
                            </select>
                        </div>
                        </div>

                        <!-- Register Button -->
                        <div>
                            <button
                                type="submit"
                                class="w-full rounded-md bg-[#722F37] px-4 py-2 text-sm font-semibold text-white hover:bg-[#8B383F] focus:outline-none focus:ring-2 focus:ring-[#722F37] focus:ring-offset-2"
                            >
                                Register
                            </button>
                        </div>
                    </form>

                    <!-- Redirect to Login -->
                    <p class="mt-6 text-sm text-gray-600">
                        Already a member?
                        <router-link
                            :to="{ name: 'Login' }"
                            class="font-medium text-[#722F37] hover:underline"
                        >
                            Log in here
                        </router-link>
                        .
                    </p>
                </div>
            </div>
        </div>

        <!-- Right side with seal (same as login.vue) -->
        <div class="hidden lg:block flex-1 bg-gradient-to-br pr-8">
            <img
                src="/spcbg.png"
                alt="St. Peter's College Seal"
                class="w-full h-full opacity-30"
            />
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
            alert("registered successfully");
            router.push({
                name: "Dashboard",
            });
        })
        .catch((error) => {
            // loading.value = false;
            if (error.response) {
                alert(error.response.data);

                console.error("API error response:", error.response.data);
            } else {
                alert(error.message);
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
