<template>
  <div class="h-full flex flex-col">
    <!-- header -->
    <header class="px-5 w-full">
      <div class="flex items-center justify-center text-gray-800 text-sm">
        <div
          class="px-2 hover:text-red-400 cursor-pointer border-b-2 hover:border-red-500"
        >
          Urgent
        </div>
        <div
          class="px-2 hover:text-orange-400 cursor-pointer border-b-2 hover:border-yellow-500"
        >
          Pending
        </div>
        <div
          class="px-2 hover:text-green-400 cursor-pointer border-b-2 hover:border-green-500"
        >
          Completed
        </div>
        <div
          class="px-2 hover:text-indigo-500 cursor-pointer border-b-2 hover:border-indigo-600"
        >
          All
        </div>
      </div>
    </header>

    <!-- body -->
    <div class="px-5 py-5 w-full flex-1 items-center">
      <div class="text-left text-gray-800 text-sm xl:flex">
        <div
          class="text-center flex flex-wrap items-center justify-center items-stretch w-full"
        >
          <div
            v-for="task in items"
            :key="task.id"
            class="cursor-pointer w-1/4 py-8 shadow m-1 items-center hover:bg-gray-200 rounded bg-white"
          >
            <div class="">
                {{ task.shortName }}
            </div>

            <div
              class="mx-2 text-xs rounded-lg px-1 font-medium"
              :class="colorBy(task.status)"
            >
              {{ task.status }}
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- footer -->
    <div class="w-full bottom-0 pb-5">
      <paginator :dataSet="dataSet" @changed="fetch"></paginator>
    </div>
  </div>
</template>

<script>
  export default {
    // props: ["tasks"],

    data() {
      return {
        dataSet: false,
        items: []
      };
    },

    created() {
      this.fetch();
    },

    methods: {
      fetch(page) {
        axios.get(this.url(page)).then(this.refresh);
      },

      url(page = 1) {
        return `/api/workspaces/${auth.workspace_id}/tasks?page=${page}`;
      },

      refresh({ data }) {
        this.dataSet = data;
        this.items = data.data;
      },

      colorBy(status) {
        switch (status) {
          case "Urgent":
            return "bg-red-200";
            break;
          case "Pending":
            return "bg-yellow-200";
            break;
          case "Completed":
            return "bg-green-200";
            break;
          default:
            return;
        }
      }
    },

    mounted () {
      window.events.$on(["task-added"], () => {
        this.fetch();
      });
    },
  };
</script>

<style lang="scss" scoped>
</style>