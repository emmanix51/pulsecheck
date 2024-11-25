<template>
    <div>
        <div class="flex min-h-screen">
            <!-- left -->
            <div class="flex-1 flex flex-col w-1/2">
                <div class="bg-white p-6 w-full">
                    <h2 class="text-xl font-bold mb-4">Manage Your Account</h2>
                    <form @submit.prevent="updateUser">
                        <div class="mb-4">
                            <label class="block text-gray-700">Idnum</label>
                            <input
                                v-model="userForm.idnum"
                                type="number"
                                class="w-full px-3 py-2 border rounded"
                                required
                            />
                        </div>
                        <div class="flex justify-between">
                            <div class="mb-4 w-1/2">
                                <label class="block text-gray-700"
                                    >First Name</label
                                >
                                <input
                                    v-model="userForm.first_name"
                                    type="text"
                                    class="w-full px-3 py-2 border rounded"
                                    required
                                />
                            </div>
                            <div class="mb-4 w-1/2">
                                <label class="block text-gray-700"
                                    >Last Name</label
                                >
                                <input
                                    v-model="userForm.last_name"
                                    type="text"
                                    class="w-full px-3 py-2 border rounded"
                                    required
                                />
                            </div>
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
                        <div class="flex justify-between">
                            <div class="mb-4">
                                <label class="block text-gray-700">Role</label>
                                <h2 class="w-full py-2">
                                    {{ userForm.role }}
                                </h2>
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700"
                                    >Respondent Type</label
                                >
                                <input
                                    v-model="userForm.respondent_type"
                                    type="text"
                                    class="w-full px-3 py-2 border rounded"
                                    disabled
                                />
                            </div>
                        </div>
                        <!-- Additional fields based on respondent_type if needed -->

                        <div class="flex justify-end">
                            <button
                                type="button"
                                @click="resetForm"
                                class="py-2 px-4 bg-gray-500 text-white rounded mr-2"
                            >
                                Reset
                            </button>
                            <button
                                type="submit"
                                class="py-2 px-4 bg-emerald-500 text-white rounded"
                            >
                                Update Account
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- right -->
            <div class="hidden lg:block flex-1 bg-gradient-to-br pr-8 w-full h-full overflow-hidden">
                <img
                    src="/spcbg.png"
                    alt="St. Peter's College Seal"
                    class="object-cover w-full h-full opacity-30"
                />
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from "vue";
import PageComponent from "../components/PageComponent.vue";
import store from "../store";

const user = computed(() => store.state.user.data); // Assuming you have a way to fetch current user details
const userForm = ref({
    id: user.value.id,
    idnum: user.value.idnum,
    first_name: user.value.first_name,
    last_name: user.value.last_name,
    email: user.value.email,
    role: user.value.role,
    respondent_type: user.value.respondent_type,
});

function updateUser() {
    store.dispatch("updateUser", userForm.value).then(() => {
        // Optionally, update user details after update
        // store.dispatch('getCurrentUser'); // Example method to fetch updated user info
        // resetForm();
    });
}

function resetForm() {
    // Reset the form to initial values
    userForm.value = {
        id: user.value.id,
        idnum: user.value.idnum,
        first_name: user.value.first_name,
        last_name: user.value.last_name,
        email: user.value.email,
        role: user.value.role,
        respondent_type: user.value.respondent_type,
        // Add other fields as needed
    };
}
</script>

<style scoped>
/* Add scoped styles as needed */
</style>
