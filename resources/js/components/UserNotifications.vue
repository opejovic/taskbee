<template>
    <li class="nav-item dropdown" v-if="notifications.length">
        <a
            id="navbarDropdown"
            class="nav-link dropdown-toggle"
            href="#"
            role="button"
            data-toggle="dropdown"
            aria-haspopup="true"
            aria-expanded="false"
            v-pre
        >
            Notifications
            <span class="caret"></span>
        </a>

        <div
            class="dropdown-menu dropdown-menu-right"
            aria-labelledby="navbarDropdown"
        >
            <li class="dropdown-item text-right">
                <a href="#" style="text-decoration: none;" @click="clearAll"
                    >Clear all</a
                >
            </li>
            <div class="dropdown-divider"></div>
            <li
                class="dropdown-item"
                v-for="(notification, index) in notifications"
                :key="notification.id"
            >
                <a
                    href="#"
                    style="text-decoration: none;"
                    @click="markAsRead(notification, index)"
                    v-text="notificationText(notification)"
                ></a>
            </li>
        </div>
    </li>
</template>

<script>
import moment from "moment";

export default {
    props: ["user"],

    data() {
        return {
            notifications: false
        };
    },

    created() {
        window.events.$on(
            ["task-updated", "task-added", "task-deleted"],
            () => {
                this.fetch();
            }
        );

        this.fetch();
    },

    methods: {
        fetch() {
            axios
                .get(`/profiles/${this.user.id}/notifications`)
                .then(response => (this.notifications = response.data));
        },

        notificationText(notification) {
            const name = this.notificationOwner(notification);

            return this.formattedMessage(
                name,
                notification.data.message,
                notification.updated_at
            );
        },

        markAsRead(notification, index) {
            axios
                .delete(
                    `/profiles/${this.user.id}/notifications/${notification.id}`
                )
                .then(this.notifications.splice(index, 1));
        },

        clearAll() {
            axios
                .delete(`/profiles/${this.user.id}/notifications/`)
                .then(response => {
                    this.notifications = false;
                    this.$toasted.show("Notifications cleared.");
                });
        },

        formattedDate(date) {
            return moment(date).fromNow();
        },

        formattedMessage(name, message, date) {
            return name + " " + message + " " + this.formattedDate(date);
        },

        notificationOwner(notification) {
            return notification.data.member === this.user.full_name
                ? "You"
                : notification.data.member;
        }
    }
};
</script>
