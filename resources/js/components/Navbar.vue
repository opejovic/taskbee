<template>
  <div class="bg-gray-100 shadow p-5">
    <nav class="container flex items-center justify-between flex-wrap mx-auto">
      <div class="flex items-center flex-shrink-0 text-indigo-900 mr-6">
        <a href="/" class="flex items-center">
          <img class="w-12" src="/img/logo.svg" alt="Imaginary" />
          <p class="pl-1 font-bold text-xl tracking-tighter text-indigo-900">
            taskbee.
          </p>
        </a>
      </div>
      <div class="block lg:hidden">
        <button
          @click="toggleHamburger"
          class="flex items-center px-3 py-2 border rounded text-indigo-800 border-indigo-700 hover:text-indigo-400 hover:border-indigo-400"
        >
          <svg
            class="fill-current h-3 w-3"
            viewBox="0 0 20 20"
            xmlns="http://www.w3.org/2000/svg"
          >
            <title>Menu</title>
            <path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z" />
          </svg>
        </button>
      </div>

      <div
        v-if="auth"
        :class="isOpen ? 'block' : 'hidden'"
        class="w-full flex-grow lg:flex lg:items-center lg:w-auto"
      >
        <div class="mx-auto">
          <new-task-modal
            v-if="auth.workspace_id !== null"
            class="lg:mt-0 mt-4 lg:-mr-8"
            :workspace="workspace"
          ></new-task-modal>
        </div>
        <div class="lg:flex items-center">
          <div class="text-sm lg:flex-grow">
            <task-filter-dropdown
              class="block mt-4 lg:inline-block lg:mt-0 text-indigo-900 hover:text-indigo-600 mr-4"
              v-if="auth.workspace_id !== null"
            ></task-filter-dropdown>
          </div>
          <user-notifications
            class="block lg:inline-block mr-4 lg:mt-1 mt-4"
            :user="auth"
          ></user-notifications>
          <div class="block flex items-center">
            <img
              class="block lg:mt-0 mt-4 mr-2"
              :src="auth.avatar_path"
              width="40px"
              height="40px"
              alt="avatar"
              style="border-radius: 50%;"
            />
            <user-menu-dropdown
              class="text-sm block lg:mt-0 mt-4 lg:mt-0 text-indigo-900 hover:text-indigo-600"
            ></user-menu-dropdown>
          </div>
        </div>
      </div>
    </nav>
  </div>
</template>

<script>
  export default {
    props: ['workspace'],
    
    data() {
      return {
        isOpen: false,
      }
    },

    methods: {
      toggleHamburger() {
        this.isOpen = ! this.isOpen;
      }
    },
  };
</script>

<style lang="scss" scoped>
</style>