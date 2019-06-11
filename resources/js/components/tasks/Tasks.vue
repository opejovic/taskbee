<template>
	<div class="flex row justify-content-between">
			<new-task @task-added="refresh" :workspace="workspace"></new-task>
			<tasks-table :workspace="workspace"></tasks-table>
	</div>
</template>

<script>
	import NewTask from './NewTask.vue';
	import TasksTable from './TasksTable';

	export default {
		props: ['workspace'],
		data() {
			return {
				tasks: [],
				isRefreshed: false,
			}
		},
		methods: {
			refresh() {
				isRefreshed = true;
			},
		},

		mounted() {
            //	
		}
	}
</script>