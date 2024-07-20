<!-- components/Notifications.vue -->
<template>
    <div>
        <h3>Notifications</h3>
        <ul>
            <li
                v-for="notification in unreadNotifications"
                :key="notification.id"
            >
                {{ notification.data.survey_title }}
                <button @click="markAsRead(notification.id)">
                    Mark as Read
                </button>
            </li>
        </ul>
    </div>
</template>

<script>
import { mapGetters, mapActions } from "vuex";

export default {
    computed: {
        ...mapGetters("notifications", ["unreadNotifications"]),
    },
    methods: {
        ...mapActions("notifications", ["fetchNotifications", "markAsRead"]),
    },
    created() {
        this.fetchNotifications();
    },
};
</script>
