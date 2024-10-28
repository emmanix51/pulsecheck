<template>
    <div class="min-h-full">
        <Disclosure as="nav" class="bg-spccolor-600" v-slot="{ open }">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 items-center justify-between">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <img
                                class="h-8 w-8 rounded-full"
                                src="/spc.png"
                                alt="Your Company"
                            />
                        </div>
                        <div class="hidden md:block">
                            <div class="ml-10 flex items-baseline space-x-4">
                                <router-link
                                    v-for="item in navigation"
                                    :key="item.name"
                                    :to="item.to"
                                    active-class="bg-white text-spccolor-600"
                                    :class="[
                                        this.$route.name === item.to.name
                                            ? ''
                                            : 'text-gray-300 hover:bg-spccolor-500 border-transparent hover:border-white hover:text-white transition-all ease-in-out',
                                        'px-3 py-2 rounded-md text-sm font-medium',
                                    ]"
                                    >{{ item.name }}
                                </router-link>
                            </div>
                        </div>
                    </div>
                    <div class="hidden md:block">
                        <div class="ml-4 flex items-center md:ml-6">
                            <button
                                type="button"
                                class="relative rounded-full bg-white p-1 text-spccolor-600 hover:bg-spccolor-500 hover:text-white focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800"
                                @click="toggleNotifications"
                            >
                                <span class="sr-only">View notifications</span>
                                <BellIcon class="h-6 w-6" aria-hidden="true" />

                                <span
                                    v-if="unreadCount > 0"
                                    class="absolute top-0 right-0 inline-block h-4 w-4 transform rounded-full bg-red-600 ring-2 text-white ring-white"
                                    >1</span
                                >
                            </button>

                            <!-- Notifications dropdown -->
                            <transition
                                enter-active-class="transition ease-out duration-100"
                                enter-from-class="transform opacity-0 scale-95"
                                enter-to-class="transform opacity-100 scale-100"
                                leave-active-class="transition ease-in duration-75"
                                leave-from-class="transform opacity-100 scale-100"
                                leave-to-class="transform opacity-0 scale-95"
                            >
                                <div
                                    v-if="showNotifications"
                                    class="absolute right-[13%] mt-3 w-80 origin-top-left bg-white py-1 rounded-md shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                                >
                                    <button
                                        @click="toggleNotifications"
                                        class="absolute top-0 right-0 text-gray-400 hover:text-gray-600"
                                    ></button>
                                    <div
                                        v-for="notification in notifications"
                                        :key="notification.id"
                                        class="block w-full px-4 py-2 text-sm text-gray-700 cursor-pointer hover:bg-gray-100"
                                        @click="markAsRead(notification.id)"
                                    >
                                        {{ notification.message }}
                                    </div>
                                </div>
                            </transition>

                            <!-- Profile dropdown -->
                            <Menu as="div" class="relative ml-3">
                                <div>
                                    <MenuButton
                                        class="relative flex max-w-xs items-center rounded-full bg-white text-sm focus:outline-none ring-2 ring-white focus:ring-offset-2 focus:ring-offset-gray-800"
                                    >
                                        <span class="absolute -inset-1.5" />
                                        <span class="sr-only"
                                            >Open user menu</span
                                        >
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke-width="1.5"
                                            stroke="currentColor"
                                            class="size-6"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"
                                            />
                                        </svg>
                                    </MenuButton>
                                </div>
                                <transition
                                    enter-active-class="transition ease-out duration-100"
                                    enter-from-class="transform opacity-0 scale-95"
                                    enter-to-class="transform opacity-100 scale-100"
                                    leave-active-class="transition ease-in duration-75"
                                    leave-from-class="transform opacity-100 scale-100"
                                    leave-to-class="transform opacity-0 scale-95"
                                >
                                    <MenuItems
                                        class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                                    >
                                        <MenuItem v-slot="{}">
                                            <router-link
                                                :to="{ name: 'ManageAccount' }"
                                                :class="[
                                                    'block px-4 py-2 text-sm text-gray-700 cursor-pointer',
                                                ]"
                                                >Manage account</router-link
                                            >
                                        </MenuItem>
                                        <MenuItem v-slot="{}">
                                            <a
                                                @click="logout"
                                                :class="[
                                                    'block px-4 py-2 text-sm text-gray-700 cursor-pointer',
                                                ]"
                                                >Sign out</a
                                            >
                                        </MenuItem>
                                    </MenuItems>
                                </transition>
                            </Menu>
                        </div>
                    </div>
                    <div class="-mr-2 flex md:hidden">
                        <!-- Mobile menu button -->
                        <DisclosureButton
                            class="relative inline-flex items-center justify-center rounded-md bg-gray-800 p-2 text-gray-400 hover:bg-gray-700 hover:text-white focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800"
                        >
                            <span class="absolute -inset-0.5" />
                            <span class="sr-only">Open main menu</span>
                            <Bars3Icon
                                v-if="!open"
                                class="block h-6 w-6"
                                aria-hidden="true"
                            />
                            <XMarkIcon
                                v-else
                                class="block h-6 w-6"
                                aria-hidden="true"
                            />
                        </DisclosureButton>
                    </div>
                </div>
            </div>
            <DisclosurePanel class="md:hidden">
                <div class="space-y-1 px-2 pb-3 pt-2 sm:px-3">
                    <router-link
                        v-for="item in navigation"
                        :key="item.name"
                        :to="item.to"
                        active-class="bg-gray-900 text-white"
                        :class="[
                            this.$route.name === item.to.name
                                ? ''
                                : 'text-gray-300 hover:bg-gray-700 hover:text-white',
                            'block px-3 py-2 rounded-md text-base font-medium',
                        ]"
                        >{{ item.name }}
                    </router-link>
                </div>
                <div class="border-t border-gray-700 pb-3 pt-4">
                    <div class="flex items-center px-5">
                        <div class="flex-shrink-0">
                            <img
                                class="h-10 w-10 rounded-full"
                                :src="user.imageUrl"
                                alt=""
                            />
                        </div>
                        <div class="ml-3">
                            <div
                                class="text-base font-medium leading-none text-white"
                            >
                                {{ user.name }}
                            </div>
                            <div
                                class="text-sm font-medium leading-none text-gray-400"
                            >
                                {{ user.email }}
                            </div>
                        </div>
                        <button
                            type="button"
                            class="relative ml-auto flex-shrink-0 rounded-full bg-gray-800 p-1 text-gray-400 hover:text-white focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800"
                            @click="toggleNotifications"
                        >
                            <span class="absolute -inset-1.5" />
                            <span class="sr-only">View notifications</span>
                            <BellIcon class="h-6 w-6" aria-hidden="true" />
                            <span
                                v-if="unreadCount > 0"
                                class="absolute top-0 right-0 inline-block h-2 w-2 transform rounded-full bg-red-600 ring-2 ring-white"
                            />
                        </button>
                    </div>
                    <div v-if="showNotifications">
                        <div
                            v-for="notification in notifications"
                            :key="notification.id"
                            class="block max-w-sm justify-center mx-auto px-4 py-2 text-sm text-gray-700 cursor-pointer bg-gray-100"
                            @click="markAsRead(notification.id)"
                        >
                            {{ notification.message }}
                        </div>
                    </div>
                    <div class="mt-3 space-y-1 px-2">
                        <router-link
                            :to="{ name: 'ManageAccount' }"
                            class="block rounded-md px-3 py-2 text-base font-medium text-gray-400 hover:bg-gray-700 hover:text-white hover:cursor-pointer"
                            >ManageAccount</router-link
                        >
                        <a
                            @click="logout"
                            class="block rounded-md px-3 py-2 text-base font-medium text-gray-400 hover:bg-gray-700 hover:text-white hover:cursor-pointer"
                            >Sign out</a
                        >
                    </div>
                </div>
            </DisclosurePanel>
        </Disclosure>

        <router-view></router-view>
    </div>
</template>

<script setup>
import {
    Disclosure,
    DisclosureButton,
    DisclosurePanel,
    Menu,
    MenuButton,
    MenuItem,
    MenuItems,
} from "@headlessui/vue";
import { Bars3Icon, BellIcon, XMarkIcon } from "@heroicons/vue/24/outline";
import { useStore } from "vuex";
import { computed, onMounted, ref } from "vue";
import { useRouter } from "vue-router";

const store = useStore();
const router = useRouter();

function logout() {
    store.commit("logout");
    router.push({
        name: "Login",
    });
}

let navigation = ref("");

const user = computed(() => store.state.user.data);
const notifications = computed(() => store.state.notifications.notifications);
const unreadCount = computed(() => store.state.notifications.unreadCount);
console.log(user.value.role);
console.log(store);

const showNotifications = ref(false);

const toggleNotifications = () => {
    showNotifications.value = !showNotifications.value;
};

const markAsRead = async (notificationId) => {
    await store.dispatch("markAsRead", notificationId);
};
onMounted(async () => {
    await store.dispatch("fetchNotifications");
});

if (user.value.role === "admin") {
    navigation.value = [
        { name: "Dashboard", to: { name: "Dashboard" }, current: true },
        { name: "Users Management", to: { name: "UserManagement" } },
        { name: "Survey Results", to: { name: "Surveys" } },
    ];
} else if (user.value.role === "surveymaker") {
    navigation.value = [
        { name: "Dashboard", to: { name: "Dashboard" }, current: true },
        { name: "Surveys", to: { name: "Surveys" } },
        { name: "Survey Templates", to: { name: "ManageAccount" } },
        { name: "Users Management", to: { name: "UserManagement" } },
    ];
} else if (user.value.role === "respondent") {
    navigation.value = [
        { name: "Dashboard", to: { name: "Dashboard" }, current: true },
        {
            name: "Surveys List",
            to: { name: "RespondentSurveys" },
            current: true,
        },
        { name: "Surveys", to: { name: "Surveys" }, current: true },
        { name: "My Responses", to: { name: "RespondentResponses" } },
        { name: "Templates", to: { name: "Templates" } },
    ];
} else {
    console.log(error);
}
</script>

<style scoped>
.notifications-dropdown {
    position: absolute;
    background: white;
    border: 1px solid #ccc;
    right: 0;
    top: 100%;
    width: 300px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}
.notifications-dropdown ul {
    list-style: none;
    padding: 0;
    margin: 0;
}
.notifications-dropdown li {
    padding: 10px;
    border-bottom: 1px solid #ccc;
}
.notifications-dropdown li:last-child {
    border-bottom: none;
}
</style>
