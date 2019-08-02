<template>
  <div>
    <div class="dropdown">
      <div class="dropdown__header" @click="toggleDropdown($event)">
        <span class="text-indigo-800 hover:text-indigo-500"
          ><i
            v-html="hasNotifications"
            class="material-icons"
            style="font-size: 1.2em;"
          ></i
        ></span>
      </div>

      <div
        class="dropdown__content bg-gray-200 rounded border absolute shadow text-sm"
      >
        <ul>
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
    </div>
  </div>
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
      window.events.$on(["task-updated", "task-added", "task-deleted"], () => {
        this.fetch();
      });

      this.fetch();
    },

    computed: {
      hasNotifications() {
        return this.notifications.length
          ? "notifications_active"
          : "notifications_none";
      }
    },

    methods: {
      toggleDropdown(event) {
        event.currentTarget.classList.toggle("is-active");
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

<style lang="scss" scoped>
  .dropdown {
    &__header {
      cursor: pointer;
      position: relative;
      text-overflow: ellipsis;
      i.fa {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        transition: opacity 0.3s;
        &.fa-angle-up {
          opacity: 0;
        }
      }
      &.is-active {
        i.fa {
          &.fa-angle-up {
            opacity: 1;
          }
          &.fa-angle-down {
            opacity: 0;
          }
        }
        + .dropdown__content {
          height: auto;
          opacity: 1;
          visibility: visible;
        }
      }
    }
    &__content {
      height: 0;
      opacity: 0;
      overflow: hidden;
      padding: 15px 10px;
      transition: opacity 0.5s;
      visibility: hidden;
    }
  }
</style>