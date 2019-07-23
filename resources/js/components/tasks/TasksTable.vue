<template>
	<div>
		<div v-if="items.length == 0" class="w-full mx-auto h-full items-center block text-center py-16 text-gray-600 text-xl">You have not assigned any tasks yet.</div>

		<div v-for="(tasks, status) in items" :key="tasks.id">
			<h4 class="text-center text-gray-700 text-sm border-b mb-5 mt-5 uppercase">
				<strong>{{ status }}</strong>
			</h4>
			<table class="container w-full items-center mx-auto text-center mb-20">
				<thead class="border-b-2 border-gray-300">
					<tr class="text-gray-600 text-xs uppercase">
						<th class="py-4 font-normal text-left" style="width: 30%">Task</th>
						<th class="py-4 font-normal" style="width: 15%">Creator</th>
						<th class="py-4 font-normal" style="width: 15%">Person responsible</th>
						<th class="py-4 font-normal" style="width: 10%">Status</th>
						<th class="py-4 font-normal">Start date</th>
						<th class="py-4 font-normal">Finish date</th>
						<th class="py-4 font-normal" style="width: 1%"></th>
					</tr>
				</thead>
				<tbody>
					<tr class="text-xs border-b" v-for="task in tasks" :key="task.id">
						<td  class="text-gray-700 py-4 text-left" v-text="task.name"></td>
						<td  class="text-gray-700 py-4 text-center" v-text="task.creator.full_name"></td>
						<td  class="text-gray-700 py-4 text-center" v-text="task.assignee.full_name"></td>
						<task-status class="text-gray-700 text-center"  :task="task" @task-updated="refresh"></task-status>
						<td  class="text-gray-700 py-4 text-center" v-text="formattedDate(task.start_date)"></td>
						<td  class="text-gray-700 py-4 text-center" v-text="formattedDate(task.finish_date)"></td>
						<td class="text-gray-700 py-4 text-center" 
							id="delete"
							style="cursor: pointer"
							@click="deleteTask(task)"
							title="Delete"
						>
							<i class="material-icons icon">delete</i>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</template>

<script>
	import moment from "moment";
	import TaskStatus from "./TaskStatus.vue";
	import Swal from "sweetalert2";

	export default {
		props: ["workspace", "tasks", "filters"],

		components: { TaskStatus },

		data() {
			return {
				items: {},
				msg: "Delete"
			};
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
					.catch(function(error) {
						// handle error
					});
			},

			deleteTask(task) {
				Swal.fire({
					title: "Are you sure?",
					text: "You won't be able to revert this.",
					type: "warning",
					showCancelButton: false,
					confirmButtonColor: "#434190",
					cancelButtonColor: "#a0aec0",
					confirmButtonText: "Yes, delete it."
				}).then(result => {
					if (result.value) {
						axios
							.delete(
								`/workspaces/${this.workspace.id}/tasks/${task.id}`
							)
							.then(response => {
								this.refresh();
								window.events.$emit("task-deleted");
								this.$toasted.show("Task deleted!");
							});
					}
				});
			}
		},

		created() {
			this.items = this.tasks;
			window.events.$on("task-added", () => {
				this.refresh();
			});
		},
	};
</script>

<style>
	.icon {
		font-size: 15px;
		vertical-align: middle;
	}
</style>
