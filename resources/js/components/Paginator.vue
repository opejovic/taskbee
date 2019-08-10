<template>
  <ul class="pagination flex items-center justify-center" v-if="shouldPaginate">
    <li
      v-show="prevUrl" @click.prevent="page--"
      class="shadow rounded-lg mx-1 bg-indigo-800 text-white rounded py-1 px-4 text-xs hover:bg-indigo-700 cursor-pointer"
    >
      <a class="page-link">Previous</a>
    </li>
    <li
      v-show="nextUrl" @click.prevent="page++"
      class="shadow rounded-lg mx-1 bg-indigo-800 text-white rounded py-1 px-4 text-xs hover:bg-indigo-700 cursor-pointer"
    >
      <a class="page-link">Next</a>
    </li>
  </ul>
</template>

<script>
  export default {
    props: ['dataSet'],

    data() {
      return {
        page: 1,
        prevUrl: false,
        nextUrl: false
      };
    },

    watch: {
      dataSet() {
        this.page = this.dataSet.current_page;
        this.prevUrl = this.dataSet.prev_page_url;
        this.nextUrl = this.dataSet.next_page_url;
      },

      page() {
        this.broadcast();
      }
    },

    computed: {
      shouldPaginate() {
        return !!this.prevUrl || !!this.nextUrl;
      }
    },

    methods: {
      broadcast() {
        this.$emit('changed', this.page);
      }
    },
  };
</script>

<style lang="scss" scoped>
</style>