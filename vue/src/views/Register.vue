<template>
    <div class="flex min-h-screen">
        <!-- Left side with registration form -->
        <div class="flex-1 flex flex-col p-8">
            <div class="flex items-center gap-3 text-[#722F37]">
                <img class="h-12 w-12" src="/logo.png" alt="Pulse Check" />
                <h1 class="text-xl font-bold">Pulse Check</h1>
            </div>

            <div class="flex-1 flex items-center justify-center">
                <div class="w-full max-w-md">
                    <h2 class="text-2xl font-bold text-[#722F37] mb-8">
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
                                    <option value="stakeholder">
                                        Stakeholder
                                    </option>
                                </select>
                            </div>

                            <!-- Conditional Fields for Student Respondent Type -->
                            <div v-if="user.respondent_type === 'student'">
                                <div>
                                    <label
                                        for="year_level"
                                        class="block text-sm font-medium text-gray-700"
                                        >Year Level</label
                                    >
                                    <select
                                        id="year_level"
                                        v-model="user.year_level"
                                        required
                                        class="mt-2 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-[#722F37] focus:outline-none focus:ring-[#722F37]"
                                    >
                                        <option value="" disabled>
                                            Select Year Level
                                        </option>
                                        <option value="1">1st Year</option>
                                        <option value="2">2nd Year</option>
                                        <option value="3">3rd Year</option>
                                        <option value="4">4th Year</option>
                                    </select>
                                </div>

                                <div>
                                    <label
                                        for="college"
                                        class="block text-sm font-medium text-gray-700"
                                        >College</label
                                    >
                                    <select
                                        id="college"
                                        v-model="user.college_id"
                                        @change="fetchPrograms"
                                        required
                                        class="mt-2 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-[#722F37] focus:outline-none focus:ring-[#722F37]"
                                    >
                                        <option value="" disabled>
                                            Select College
                                        </option>
                                        <option
                                            v-for="college in colleges"
                                            :key="college.id"
                                            :value="college.id"
                                        >
                                            {{ college.college_name }}
                                        </option>
                                    </select>
                                </div>

                                <!-- Dynamic Programs Based on Selected College -->
                                <div v-if="user.college_id">
                                    <label
                                        for="program"
                                        class="block text-sm font-medium text-gray-700"
                                        >Program</label
                                    >
                                    <select
                                        id="program"
                                        v-model="user.program_id"
                                        required
                                        class="mt-2 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-[#722F37] focus:outline-none focus:ring-[#722F37]"
                                    >
                                        <option value="" disabled>
                                            Select Program
                                        </option>
                                        <option
                                            v-for="program in programs"
                                            :key="program.id"
                                            :value="program.id"
                                        >
                                            {{ program.program_name }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div v-else-if="user.respondent_type === 'faculty'">
                                <div>
                                    <label
                                        for="college"
                                        class="block text-sm font-medium text-gray-700"
                                        >College</label
                                    >
                                    <select
                                        id="college"
                                        v-model="user.college_id"
                                        @change="fetchPrograms"
                                        required
                                        class="mt-2 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-[#722F37] focus:outline-none focus:ring-[#722F37]"
                                    >
                                        <option value="" disabled>
                                            Select College
                                        </option>
                                        <option
                                            v-for="college in colleges"
                                            :key="college.id"
                                            :value="college.id"
                                        >
                                            {{ college.college_name }}
                                        </option>
                                    </select>
                                </div>
                                <!-- Dynamic Programs Based on Selected College -->
                                <div v-if="user.college_id">
                                    <label
                                        for="program"
                                        class="block text-sm font-medium text-gray-700"
                                        >Program</label
                                    >
                                    <select
                                        id="program"
                                        v-model="user.program_id"
                                        required
                                        class="mt-2 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-[#722F37] focus:outline-none focus:ring-[#722F37]"
                                    >
                                        <option value="" disabled>
                                            Select Program
                                        </option>
                                        <option
                                            v-for="program in programs"
                                            :key="program.id"
                                            :value="program.id"
                                        >
                                            {{ program.program_name }}
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div v-else-if="user.respondent_type === 'staff'">
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

                            <div
                                v-else-if="
                                    user.respondent_type === 'stakeholder'
                                "
                            >
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
                                    <option value="Parents of SPC student">
                                        Parents of SPC student
                                    </option>
                                    <option value="Alumni">Alumni</option>
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
                            >Log in here</router-link
                        >.
                    </p>
                </div>
            </div>
        </div>

        <!-- Right side with seal (same as login.vue) -->
        <div class="hidden lg:block flex-1 bg-gradient-to-br pr-8 self-center">
            <img
                src="/spcbg.png"
                alt="St. Peter's College Seal"
                class="w-2/3 h-2/3 opacity-30"
            />
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useStore } from "vuex";
import { useRouter } from "vue-router";
import axiosClient from "../axios"; // You'll need axios for making API calls

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
    year_level: "",
    college_id: "",
    program_id: "",
});

const colleges = ref([]);
const programs = ref([]);

const handleRespondentTypeChange = () => {
    user.value.category = ""; // Reset category when respondent type changes
    user.value.college_id = ""; // Reset college when respondent type changes
    user.value.program_id = ""; // Reset program when respondent type changes
};

const fetchColleges = async () => {
    try {
        const response = await axiosClient.get("/college"); // Fetch colleges from your backend
        colleges.value = response.data;
    } catch (error) {
        console.error("Error fetching colleges:", error);
    }
};

const fetchPrograms = async () => {
    if (!user.value.college_id) return;

    try {
        const response = await axiosClient.get(
            `program?college_id=${user.value.college_id}`
        ); // Fetch programs based on selected college
        programs.value = response.data;
    } catch (error) {
        console.error("Error fetching programs:", error);
    }
};

onMounted(() => {
    fetchColleges(); // Fetch colleges when component is mounted
});

function register(ev) {
    ev.preventDefault();
    store
        .dispatch("register", user.value)
        .then(() => {
            alert("Registered successfully");
            // router.push({ name: "Dashboard" });
        })
        .catch((error) => {
            alert(error.message);
        });
}
</script>

<style scoped>
/* Your styling can go here */
</style>
