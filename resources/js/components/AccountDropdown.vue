<template>
  <dropdown-menu :isOpen="isOpen" @close="close()">
    <button slot="title" @click="toggle()" class="z-10 relative block h-10 w-10 rounded-full overflow-hidden border-2 focus:outline-none" :class="isOpen ? 'border-indigo-600' : 'border-gray-600'">
      <img class="object-cover" :src="auth.avatar_path" alt="">
    </button>

    <div slot="content">
        <a :href="profilePage" class="block px-4 py-1 text-purple-900 hover:bg-indigo-800 hover:text-white">Account settings</a>
        <a href="/dashboard" class="block px-4 py-1 text-purple-900 hover:bg-indigo-800 hover:text-white">Dashboard</a>
        <a href="/logout" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
            class="block px-4 py-1 text-purple-900 hover:bg-indigo-800 hover:text-white">Sign Out</a>
        <form
          id="logout-form"
          action="/logout"
          method="POST"
          style="display: none;"
        ></form>
    </div>
  </dropdown-menu>
</template>

<script>
  import DropdownMenu from "./DropdownMenu.vue";

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
      profilePage() {
        return "/profiles/" + auth.id;
      }
    },

    methods: {
      toggle() {
        this.isOpen = !this.isOpen
        if (this.isOpen) {
          this.$emit('opened')
        }
      },

      close() {
        this.isOpen = false
      }
    },

    created () {
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
  };
</script>

<style lang="scss" scoped>
</style>
