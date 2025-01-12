<template>
    <PageComponent>
        <template v-slot:header>
            <div class="flex justify-between items-center">
                <h1 class="text-3xl font-bold text-gray-900">
                    User Management
                </h1>
                <button
                    @click="showAddUserForm = true"
                    class="py-2 px-3 text-white bg-emerald-500 rounded-md hover:bg-emerald-600"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-4 w-4 -mt-1 inline-block"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 4v16m8-8H4"
                        />
                    </svg>
                    Add User
                </button>
            </div>
        </template>

        <!-- Add User Form Modal -->
        <div
            v-if="showAddUserForm"
            class="fixed inset-0 z-50 flex items-center justify-center bg-gray-800 bg-opacity-50"
        >
            <div class="bg-white p-6 rounded-lg shadow-lg max-w-md w-full">
                <h2 class="text-xl font-bold mb-4">
                    {{ isEditMode ? "Edit User" : "Add User" }}
                </h2>
                <form
                    @submit.prevent="isEditMode ? updateUser() : addUser()"
                    class="grid grid-cols-3"
                >
                    <div class="mb-4">
                        <label class="block text-gray-700">Idnum</label>
                        <input
                            v-model="userForm.idnum"
                            type="number"
                            class="w-full px-3 py-2 border rounded"
                            required
                        />
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700">First Name</label>
                        <input
                            v-model="userForm.first_name"
                            type="text"
                            class="w-full px-3 py-2 border rounded"
                            required
                        />
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700">Last Name</label>
                        <input
                            v-model="userForm.last_name"
                            type="text"
                            class="w-full px-3 py-2 border rounded"
                            required
                        />
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700">Email</label>
                        <input
                            v-model="userForm.email"
                            type="email"
                            class="w-full px-3 py-2 border rounded"
                            required
                        />
                    </div>
                    <div class="mb-4" v-if="!isEditMode">
                        <label class="block text-gray-700">Password</label>
                        <input
                            v-model="userForm.password"
                            type="password"
                            class="w-full px-3 py-2 border rounded"
                            required
                        />
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700">Role</label>
                        <select
                            v-model="userForm.role"
                            class="w-full px-3 py-2 border rounded"
                            required
                        >
                            <option value="admin">Admin</option>
                            <option value="surveymaker">Survey Maker</option>
                            <option value="respondent">Respondent</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700"
                            >Respondent Type</label
                        >
                        <select
                            v-model="userForm.respondent_type"
                            class="w-full px-3 py-2 border rounded"
                        >
                            <option value="student">Student</option>
                            <option value="faculty">Faculty</option>
                            <option value="staff">Staff</option>
                            <option value="stakeholder">Stakeholder</option>
                        </select>
                    </div>
                    <!-- Conditional rendering based on respondentType -->
                    <div v-if="userForm.respondent_type === 'student'">
                        <div>
                            <label
                                for="year_level"
                                class="block text-sm font-medium text-gray-700"
                                >Year Level</label
                            >
                            <select
                                id="year_level"
                                v-model="userForm.year_level"
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
                                v-model="userForm.college_id"
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
                        <div v-if="userForm.college_id">
                            <label
                                for="program"
                                class="block text-sm font-medium text-gray-700"
                                >Program</label
                            >
                            <select
                                id="program"
                                v-model="userForm.program_id"
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
                    <div v-if="userForm.respondent_type === 'faculty'">
                        
                        <div>
                            <label
                                for="college"
                                class="block text-sm font-medium text-gray-700"
                                >College</label
                            >
                            <select
                                id="college"
                                v-model="userForm.college_id"
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
                        <div v-if="userForm.college_id">
                            <label
                                for="program"
                                class="block text-sm font-medium text-gray-700"
                                >Program</label
                            >
                            <select
                                id="program"
                                v-model="userForm.program_id"
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

                    

                    <div v-if="userForm.respondent_type === 'staff'">
                        <label
                            for="staffCategory"
                            class="block text-sm font-medium leading-6 text-gray-900"
                            >Staff Category</label
                        >
                        <div class="mt-2">
                            <select
                                id="staffCategory"
                                v-model="userForm.category"
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
                    <div class="flex items-end justify-items-end">
                        <button
                            type="button"
                            @click="closeForm"
                            class="h-20 px-4 bg-gray-500 text-white rounded mr-2"
                        >
                            Cancel
                        </button>
                        <button
                            type="submit"
                            class="h-20 px-4 bg-emerald-500 text-white rounded"
                        >
                            {{ isEditMode ? "Update" : "Add" }} User
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- User Table -->
        <section class="container mx-auto p-6 font-mono">
          
            <div class="w-full mb-8 overflow-hidden rounded-lg shadow-lg">
                <div class="w-full overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr
                                class="text-md font-semibold tracking-wide text-left text-gray-900 bg-gray-100 uppercase border-b border-gray-600"
                            >
                                <th class="px-4 py-3">Name</th>
                                <th class="px-4 py-3">IdNum</th>
                                <th class="px-4 py-3">Role</th>
                                <th class="px-4 py-3">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            <tr
                                v-for="user in users"
                                :key="user.id"
                                class="text-gray-700"
                            >
                                <td class="px-4 py-3 border">
                                    <div class="flex items-center text-sm">
                                        <div>
                                            <p class="font-semibold text-black">
                                                {{ user.first_name }}
                                                {{ user.last_name }}
                                            </p>
                                            <p class="text-xs text-gray-600">
                                                {{ user.respondent_type }}
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td
                                    class="px-4 py-3 text-ms font-semibold border"
                                >
                                    {{ user.idnum }}
                                </td>
                                <td class="px-4 py-3 text-xs border">
                                    <span
                                        v-if="user.role === 'admin'"
                                        class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-sm"
                                    >
                                        Admin
                                    </span>
                                    <span
                                        v-else-if="user.role === 'surveymaker'"
                                        class="px-2 py-1 font-semibold leading-tight text-red-700 bg-red-100 rounded-sm"
                                    >
                                        Survey Maker
                                    </span>
                                    <span
                                        v-else
                                        class="px-2 py-1 font-semibold leading-tight text-indigo-700 bg-indigo-100 rounded-sm"
                                    >
                                        Respondent
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm border">
                                    <button
                                        @click="editUser(user)"
                                        class="py-2 px-3 mr-2 bg-indigo-700 text-white rounded"
                                    >
                                        Manage
                                    </button>
                                    <button
                                        @click="deleteUser(user.id)"
                                        class="py-2 px-3 mr-2 bg-red-700 text-white rounded"
                                    >
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
        <!-- Pagination -->
        <div
            class="max-w-full md:max-w-screen-md lg:max-w-screen-lg xl:max-w-screen-xl mx-auto bg-white p-6 rounded-lg shadow-sm"
        >
            <div class="flex justify-center">
                <nav class="flex space-x-2" aria-label="Pagination">
                    <a
                        href="#"
                        @click.prevent="fetchUsers(currentPage - 1)"
                        class="cursor-pointer relative inline-flex items-center px-4 py-2 text-sm bg-spccolor-600 border hover:border-violet-100 text-white font-semibold cursor-pointer leading-5 rounded-md transition duration-150 ease-in-out focus:outline-none focus:shadow-outline-blue focus:border-blue-300 focus:z-10"
                        :class="{
                            ' cursor-not-allowed': currentPage === 1,
                        }"
                        :disabled="currentPage === '1'"
                    >
                        Previous
                    </a>
                    <div v-for="(link, i) of pageLinks" :key="i">
                        <a
                            :href="link.url"
                            class="cursor-pointer relative inline-flex items-center px-4 py-2 text-sm bg-spccolor-600 hover:border-violet-100 text-white font-semibold leading-5 rounded-md transition duration-150 ease-in-out focus:outline-none focus:shadow-outline-blue focus:border-blue-300 focus:z-10"
                            :class="{
                                ' cursor-not-allowed':
                                    !link.url ||
                                    parseInt(link.label) === currentPage,
                                'bg-blue-500': link.active,
                            }"
                            :disabled="
                                !link.url ||
                                parseInt(link.label) === currentPage
                            "
                            @click.prevent="
                                !(
                                    !link.url ||
                                    parseInt(link.label) === currentPage
                                ) && fetchUsers(parseInt(link.label))
                            "
                        >
                            {{ link.label }}
                        </a>
                    </div>
                    <a
                        href="#"
                        @click.prevent="fetchUsers(currentPage + 1)"
                        class="cursor-pointer relative inline-flex items-center px-4 py-2 text-sm bg-spccolor-600 hover:border-violet-100 text-white font-semibold cursor-pointer leading-5 rounded-md transition duration-150 ease-in-out focus:outline-none focus:shadow-outline-blue focus:border-blue-300 focus:z-10"
                        :class="{
                            ' cursor-not-allowed': !hasNextPage,
                        }"
                        :disabled="!hasNextPage"
                    >
                        Next
                    </a>
                </nav>
            </div>
        </div>
    </PageComponent>
</template>
<script setup>
import { ref, computed, onMounted } from "vue";
import PageComponent from "../components/PageComponent.vue";
import store from "../store";
import axiosClient from "../axios"; // You'll need axios for making API calls

const showAddUserForm = ref(false);
const isEditMode = ref(false);
const userForm = ref({
    id: null,
    idnum: "",
    first_name: "",
    last_name: "",
    email: "",
    password: "",
    role: "respondent",
    respondent_type: "student",
    category: "",
    year_level: "",
    college_id: null,
    program_id: null,
});

const colleges = ref([]);
const programs = ref([]);

const handleRespondentTypeChange = () => {
    userForm.value.category = ""; // Reset category when respondent type changes
    userForm.value.college_id = ""; // Reset college when respondent type changes
    userForm.value.program_id = "";
     // Reset program when respondent type changes
};

const currentPage = ref(1);
const pageLinks = ref([]);

const users = computed(() => store.state.users);
onMounted(async () => {
    fetchColleges();
    fetchPrograms();
    try {
        const response = await store.dispatch("getAllUsers");
        const links = response.users.links;

        if (links.length > 0) {
            pageLinks.value = links.slice(1, -1);
        }
        // pageLinks.value = response.users.links;
        currentPage.value = 1;
        console.log(pageLinks);
    } catch (error) {}
});

const fetchColleges = async () => {
    try {
        const response = await axiosClient.get("/college"); // Fetch colleges from your backend
        colleges.value = response.data;
    } catch (error) {
        console.error("Error fetching colleges:", error);
    }
};

const fetchPrograms = async () => {
    if (!userForm.value.college_id) return;

    try {
        const response = await axiosClient.get(
            `program?college_id=${userForm.value.college_id}`
        ); // Fetch programs based on selected college
        programs.value = response.data;
    } catch (error) {
        console.error("Error fetching programs:", error);
    }
};

async function fetchUsers(page) {
    try {
        const response = await store.dispatch("getAllUsers", page);
        const links = response.users.links;

        if (links.length > 0) {
            pageLinks.value = links.slice(1, -1);
        }
        currentPage.value = page;
    } catch (error) {
        console.error("Error fetching users:", error);
        // Handle error if needed
    }
}

function closeForm() {
    showAddUserForm.value = false;
    isEditMode.value = false;
    resetForm();
}

function resetForm() {
    userForm.value = {
        id: null,
        first_name: "",
        last_name: "",
        email: "",
        password: "",
        role: "respondent",
        respondent_type: "student",
        category: "",
    };
}

function addUser() {
    store.dispatch("addUser", userForm.value).then(() => {
        store.dispatch("getAllUsers");
        closeForm();
    });
}

function editUser(user) {
    isEditMode.value = true;
    showAddUserForm.value = true;
    userForm.value = { ...user, password: "" };
}

function updateUser() {
    store.dispatch("updateUser", userForm.value).then(() => {
        store.dispatch("getAllUsers");
        closeForm();
    });
}

function deleteUser(id) {
    if (
        confirm(
            "Are you sure you want to delete this user? This action cannot be undone."
        )
    ) {
        store.dispatch("deleteUser", id).then(() => {
            store.dispatch("getAllUsers");
        });
    }
}
</script>
