<template>
  <dropdown-menu :isOpen="isOpen">
    <button slot="title" @click="toggle()" class="z-10 relative block focus:outline-none" :class="isOpen ? 'text-indigo-700' : 'text-gray-700'">
      Browse
    </button>

    <div slot="content">
      <ul class="">
        <li>
          <a
            :href="allTasks"
            class="block px-4 py-1 text-purple-900 hover:bg-indigo-800 hover:text-white"
          >
            All tasks
          </a>
        </li>
        <li>
          <a
            :href="myTasks"
            class="block px-4 py-1 text-purple-900 hover:bg-indigo-800 hover:text-white"
          >
            My Tasks
          </a>
        </li>
        <li>
          <a
            :href="tasksByMe"
            class="block px-4 py-1 text-purple-900 hover:bg-indigo-800 hover:text-white"
          >
            Tasks I have created
          </a>
        </li>
      </ul>
    </div>
  </dropdown-menu>
</template>

<script>
  import DropdownMenu from "../DropdownMenu";


  export default {
    components: {
      DropdownMenu,
    },

    data() {
        return {
            isOpen: false
        }
    },

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
      toggle() {
        this.isOpen = !this.isOpen;

        if (this.isOpen) {
          this.$emit("opened");
        }
      },

      close() {
        return (this.isOpen = false);
      }
    },

    created() {
      const handleEscape = e => {
        if (e.key === "Esc" || e.key === "Escape") {
          this.isOpen = false;
        }
      };

      document.addEventListener("keydown", handleEscape);

      this.$once("hook:beforeDestroy", () => {
        document.removeEventListener("keydown", handleEscape);
      });
    }
  };
</script>