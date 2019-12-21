<template>
  <div class="relative">
    <slot name="title"></slot>
    <button
      v-if="isOpen"
      @click="close"
      tabindex="-1"
      class="fixed inset-0 h-full w-full cursor-default focus:outline-none"
    ></button>

    <transition
      enter-active-class="transition-all transition-fastest ease-out-quad"
      leave-active-class="transition-all transition-faster ease-in-quad"
      enter-class="opacity-0 scale-70"
      enter-to-class="opacity-100 scale-100"
      leave-class="opacity-100 scale-100"
      leave-to-class="opacity-0 scale-70"
    >
      <div
        v-if="isOpen"
        class="w-48 absolute origin-top-right right-0 py-2 mt-1 bg-gray-200 rounded-lg text-sm shadow-md"
      >
        <slot name="content"></slot>
      </div>
    </transition>
  </div>
</template>

<script>
export default {
  props: ["isOpen"],
  methods: {
    close() {
      this.$emit("close");
    }
  }
};
</script>