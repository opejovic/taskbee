<template>
    <div>
        <div class="col-md-12" v-if="items.length <= 0">No tasks created yet</div>

        <table class="table col-md-16" v-if="items.length > 0">
            <thead>
                <tr>
                    <th scope="col text-left" style="width: 35%">Task</th>
                    <th scope="col text-center">Creator</th>
                    <th scope="col text-center">Person responsible</th>
                    <th scope="col text-center" style="width: 10%">Status</th>
                    <th scope="col text-center">Start date</th>
                    <th scope="col text-center">Finish date</th>
                    <th scope="col text-center" style="width: 1%"></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="task in items">
                    <td v-text="task.name"></td>
                    <td v-text="task.creator.full_name"></td>
                    <td v-text="task.assignee.full_name"></td>
                    <task-status :task="task" @task-updated="refresh"></task-status>
                    <td v-text="formattedDate(task.start_date)"></td>
                    <td v-text="formattedDate(task.finish_date)"></td>
                    <td style="cursor: pointer" @click="deleteTask(task)">
                        <i class="material-icons icon" title="Delete">clear</i>
                    </td>
                </tr>
            </tbody>
        </table>        
    </div>
</template>

<script>
	import moment from 'moment';
    import NewTask from './NewTask.vue';
    import TaskStatus from './TaskStatus.vue';

    export default {
      props: ['workspace', 'tasks'],

      data() {
        return {
            items: [],
        }
    },

    methods: {
        formattedDate(date) {
            return moment(date).format("LL");
        },

        refresh() {
            var currentUrl = window.location.href;
            this.fetchTasks(currentUrl);
        },

        fetchTasks(url) {
            axios
                .get(url)
                .then(response => {
                    // handle success
                    // assign tasks to the items data.
                    this.items = response.data[1];
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                });            
        },

        deleteTask(task) {
            axios.delete(`/workspaces/${this.workspace.id}/tasks/${task.id}`)
                .then(response => {
                    this.refresh();
                    this.$toasted.show('Task deleted!');
                })
                .catch();
        },
    },

    created() {
        this.items = this.tasks;


        window.events.$on('task-added', () => {
            this.refresh();
        });
    },
}
</script>

<style>
    .icon {
        font-size: 15px; 
        vertical-align: middle;
    }

    .middle {
        vertical-align: middle;
    }
</style>