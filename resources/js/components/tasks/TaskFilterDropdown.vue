<template>
  <span class="dropdown">
    <span class="dropdown__header" @click="toggleDropdown($event)">
      <span class="text-indigo-800 hover:text-indigo-500">Browse</span>
      <i class="fa fa-angle-down" aria-hidden="true"></i>
      <i class="fa fa-angle-up" aria-hidden="true"></i>
    </span>

    <div
      class="dropdown__content bg-gray-200 rounded border absolute shadow text-sm"
    >
      <ul>
        <li>
          <a
            :href="allTasks"
            class="block mt-4 lg:inline-block lg:mt-0 text-indigo-800 hover:text-indigo-500 mr-4"
          >
            All tasks
          </a>
        </li>
        <li>
          <a
            :href="myTasks"
            class="block mt-4 lg:inline-block lg:mt-0 text-indigo-800 hover:text-indigo-500 mr-4"
          >
            My Tasks
          </a>
        </li>
        <li>
          <a
            :href="tasksByMe"
            class="block mt-4 lg:inline-block lg:mt-0 text-indigo-800 hover:text-indigo-500 mr-4"
          >
            Tasks I have created
          </a>
        </li>
      </ul>
    </div>
  </span>
</template>

<script>
  export default {
    computed: {
      allTasks() {
        return "/workspaces/" + auth.workspace_id + "/tasks";
      },

      myTasks() {
        return "/workspaces/" + auth.workspace_id + "/tasks?my";
      },

      tasksByMe() {
        return "/workspaces/" + auth.workspace_id + "/tasks?creator=" + auth.id;
      }
    },

    methods: {
      toggleDropdown(event) {
        event.currentTarget.classList.toggle("is-active");
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
