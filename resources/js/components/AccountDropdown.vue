<template>
  <div class="relative">
    <button @click="toggle()" class="z-10 relative block h-10 w-10 rounded-full overflow-hidden border-2 focus:outline-none" :class="isOpen ? 'border-indigo-600' : 'border-gray-600'">
      <img class="object-cover" :src="auth.avatar_path" alt="">
    </button>

    <button v-if="isOpen" @click="isOpen = false" tabindex="-1" class="fixed inset-0 h-full w-full cursor-default focus:outline-none"></button>

    <transition
      enter-active-class="transition-all transition-fastest ease-out-quad"
      leave-active-class="transition-all transition-faster ease-in-quad"
      enter-class="opacity-0 scale-70"
      enter-to-class="opacity-100 scale-100"
      leave-class="opacity-100 scale-100"
      leave-to-class="opacity-0 scale-70">
      <div v-if="isOpen" class="w-48 absolute origin-top-right right-0 py-2 mt-1 bg-gray-200 rounded-lg text-sm shadow-md">
        <a :href="profilePage" class="block px-4 py-1 text-purple-900 hover:bg-indigo-800 hover:text-white">Account settings</a>
        <a href="/dashboard" class="block px-4 py-1 text-purple-900 hover:bg-indigo-800 hover:text-white">Dashboard</a>
        <a href="/logout" onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();"
        class="block px-4 py-1 text-purple-900 hover:bg-indigo-800 hover:text-white">Sign Out</a>
        <form
          id="logout-form"
          action="/logout"
          method="POST"
          style="display: none;"
        ></form>
      </div>
    </transition>
  </div>
</template>

<script>
  export default {
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
        this.isOpen = !this.isOpen;
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
