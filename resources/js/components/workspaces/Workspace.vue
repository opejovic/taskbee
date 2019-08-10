<template>
  <div class="py-5 xl:flex lg:flex md:flex sm:flex w-full">
    <div
      class="xl:w-1/4 lg:w-1/2 md:w-1/2 sm:w-1/2 xl:mb-0 lg:mb-0 md:mb-0 sm:mb-0 mb-5 w-full mx-auto pt-5 border shadow text-center bg-white rounded"
    >
      <team :workspace="workspace" :invitations="invitations"></team>
    </div>

    <div
      class="md:ml-5 xl:ml-5 sm:ml-5 xl:w-3/4 lg:w-1/2 md:w-1/2 sm:w-1/2 w-full mx-auto pt-5 border shadow text-center bg-white rounded"
    >
      <tasks-overview :tasks="tasks"></tasks-overview>
    </div>
  </div>
</template>

<script>
  import Team from "./Team.vue";
  import TasksOverview from "./TasksOverview.vue";

  export default {
    components: {
      Team,
      TasksOverview
    },
    props: ["workspace", "invitations", "tasks"]
  };
</script>

<style lang="scss" scoped>
</style>