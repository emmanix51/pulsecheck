<template>
  <div class="flex min-h-screen">
    <!-- Left side with form -->
    <div class="flex-1 flex flex-col p-8">
      <div class="flex items-center gap-3 text-[#722F37]">
        <img class="h-12 w-12" src="/logo.png" alt="Pulse Check" />
        <h1 class="text-xl font-bold">Pulse Check</h1>
      </div>

      <div class="flex-1 flex items-center justify-center">
        <div class="w-full max-w-md">
          <h2 class="text-2xl font-bold text-[#722F37] mb-8">Log in</h2>

          <form @submit="login" class="space-y-6">
            <div v-if="errorMsg" 
                 class="bg-red-100 text-red-800 px-4 py-3 rounded-md cursor-pointer"
                 @click="errorMsg = ''">
              {{ errorMsg }}
            </div>

            <div>
              <label for="email" class="block text-sm font-medium text-gray-700">Username:</label>
              <input
                v-model="user.email"
                id="email"
                type="email"
                required
                class="mt-2 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-[#722F37] focus:outline-none focus:ring-[#722F37]"
              />
            </div>

            <div>
              <label for="password" class="block text-sm font-medium text-gray-700">Password:</label>
              <input
                v-model="user.password"
                id="password"
                type="password"
                required
                class="mt-2 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-[#722F37] focus:outline-none focus:ring-[#722F37]"
              />
            </div>

            <div>
              <button
                type="submit"
                class="w-full rounded-md bg-[#722F37] px-4 py-2 text-sm font-semibold text-white hover:bg-[#8B383F] focus:outline-none focus:ring-2 focus:ring-[#722F37] focus:ring-offset-2"
              >
                Login
              </button>
            </div>
          </form>

          <p class="mt-6 text-sm text-gray-600">
            Don't have an account? 
            <router-link
              :to="{ name: 'Register' }"
              class="font-medium text-[#722F37] hover:underline"
            >
              Register here
            </router-link>
            .
          </p>
          <p class="mt-6 text-sm text-gray-600">
            Forgot password?
            <router-link
              :to="{ name: 'ForgotPassword' }"
              class="font-medium text-[#722F37] hover:underline"
            >
              Click here
            </router-link>
            .
          </p>
        </div>
      </div>
    </div>

    <!-- Right side with seal -->
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
import { ref } from "vue";
import { useRouter } from "vue-router";
import store from "../store";

const router = useRouter();

const user = {
    email: "",
    password: "",
    remember: false,
};

let errorMsg = ref("");

function login(ev) {
    ev.preventDefault();
    store
        .dispatch("login", user)
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
