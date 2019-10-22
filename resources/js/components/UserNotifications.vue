<template>
    <dropdown-menu :isOpen="isOpen">
    <button slot="title" @click="toggle()" class="z-10 relative block focus:outline-none">
      <i
        v-html="hasNotifications"
        class=" hover:text-indigo-500 material-icons"
        :class="isOpen ? 'text-indigo-700' : 'text-gray-700'"
        style="font-size: 1.2em;"
      ></i
    >
    </button>

  <div slot="content">
        <ul class="px-4 py-2">
          <div v-if="notifications.length">
            <li class="text-left border-b-2 border-blue-900">
              <a class="hover:text-indigo-600" href="#" @click="clearAll"
                >Clear all</a
              >
            </li>
            <li
              v-for="(notification, index) in notifications"
              :key="notification.id"
              class="border-b border-gray-600 mt-1 mb-1"
            >
              -
              <a
                href="#"
                class="hover:text-indigo-600"
                @click="markAsRead(notification, index)"
                v-text="notificationText(notification)"
              ></a>
            </li>
          </div>
          <div v-else>
            <li>
              There are no new notifcations.
            </li>
          </div>
        </ul>
      </div>
    </dropdown-menu>
</template>

<script>
  import DropdownMenu from "./DropdownMenu";
  import moment from "moment";

  export default {
    components: {
      DropdownMenu,
    },

    props: ['user'],

    data() {
      return {
        isOpen: false,
        notifications: false
      }
    },

    computed: {
      hasNotifications() {
        return this.notifications.length
          ? "notifications_active"
          : "notifications_none";
      }
    },

    created() {
      window.events.$on(["task-updated", "task-added", "task-deleted"], () => {
        this.fetch();
      });

      this.fetch();

      const handleEscape = (e) => {
        if (e.key === 'Esc' || e.key === 'Escape') {
          this.isOpen = false
        }
      }

      document.addEventListener('keydown', handleEscape)

      this.$once('hook:beforeDestroy', () => {
        document.removeEventListener('keydown', handleEscape)
      })
    },

    methods: {
      toggle() {
        this.isOpen = !this.isOpen

        if (this.isOpen) {
          this.$emit('opened')
        }
      },

      close() {
        return this.isOpen = false;
      },

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
          .delete(`/profiles/${this.user.id}/notifications/${notification.id}`)
          .then(this.notifications.splice(index, 1));
      },

      clearAll(event) {
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