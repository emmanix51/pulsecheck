import axiosClient from "../../axios";
const notificationsModule = {
    state: {
        notifications: [],
        unreadCount: 0,
    },
    getters: {
        allNotifications: (state) => state.notifications,
        unreadNotifications: (state) =>
            state.notifications.filter((notification) => !notification.read),
        unreadCount: (state) => state.unreadCount,
    },
    actions: {
        async wew({ commit }) {
            try {
                console.log(wew);
            } catch (error) {
                console.error("Error fetching notifications:", error);
            }
        },
        async fetchNotifications({ commit }) {
            try {
                const response = await axiosClient.get("/notifications");
                console.log(response);
                commit("setNotifications", response.data);
            } catch (error) {
                console.error("Error fetching notifications:", error);
            }
        },
        async markAsRead({ commit }, notificationId) {
            try {
                await axiosClient.post(
                    `/notifications/${notificationId}/mark-as-read`
                );
                commit("updateNotificationReadStatus", notificationId);
            } catch (error) {
                console.error("Error marking notification as read:", error);
            }
        },
    },
    mutations: {
        setNotifications: (state, notifications) => {
            state.notifications = notifications;
            state.unreadCount = notifications.filter(
                (notification) => !notification.read
            ).length;
        },
        updateNotificationReadStatus: (state, notificationId) => {
            const notification = state.notifications.find(
                (notification) => notification.id === notificationId
            );
            if (notification) {
                notification.read = true;
                state.unreadCount -= 1;
            }
        },
    },
};

export default notificationsModule;
