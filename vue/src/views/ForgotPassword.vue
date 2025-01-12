<template>
    <div>
        <div class="sm:mx-auto sm:w-full sm:max-w-sm">
            <img class="mx-auto h-16 w-16" src="/spc.png" alt="spclogo" />
            <h2
                class="mt-10 text-center text-2xl font-bold leading-9 tracking-tight text-gray-900"
            >
                Forgot your password?
            </h2>
        </div>

        <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
            <form class="space-y-6" @submit="forgotPassword">
                <div
                    v-if="errorMsg"
                    class="bg-red-600 text-white px-3 py-2 rounded"
                    @click="errorMsg = ''"
                >
                    {{ errorMsg }}
                </div>
                <div>
                    <label
                        for="email"
                        class="block text-sm font-medium leading-6 text-gray-900"
                        >Email address</label
                    >
                    <div class="mt-2">
                        <input
                            v-model="user.email"
                            id="email"
                            name="email"
                            type="email"
                            autocomplete="email"
                            required=""
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-spccolor-600 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-sppcolor-600 sm:text-sm sm:leading-6"
                        />
                    </div>
                </div>

                <div>
                    <button
                        type="submit"
                        class="flex w-full justify-center rounded-md bg-spccolor-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-spccolor-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                    >
                        Recover Password
                    </button>
                </div>
            </form>
            <!-- <pre>{{user}}</pre> -->

            <p class="mt-10 text-center text-sm text-gray-500">
                Want to login?
                {{ " " }}
                <router-link
                    :to="{ name: 'Login' }"
                    class="font-semibold leading-6 text-spccolor-600 hover:text-spccolor-500 hover:underline"
                    >Login page here</router-link
                >
            </p>
            <p class="mt-10 text-center text-sm text-gray-500">
                Not a member?
                {{ " " }}
                <router-link
                    :to="{ name: 'Register' }"
                    class="font-semibold leading-6 text-spccolor-600 hover:text-spccolor-500 hover:underline"
                    >Register here</router-link
                >
            </p>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";
import { useRouter } from "vue-router";
import store from "../store";

const router = useRouter();

let user = ref({
    email: "",
});

let errorMsg = ref("");

function forgotPassword(ev) {
    ev.preventDefault();
    console.log("User email:", user.value.email);
    store
        .dispatch("forgotPassword", user.value.email)
        .then(() => {
            router.push({
                name: "Dashboard",
            });
        })
        .catch((err) => {
            if (err.response && err.response.data) {
                errorMsg.value =
                    err.response.data.message || "An unexpected error occurred";
            } else {
                errorMsg.value = "An unexpected error occurred";
            }
        });
}
</script>

<style></style>
