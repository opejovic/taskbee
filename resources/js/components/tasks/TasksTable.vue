<template>
	<div>
		<h3 v-if="items.length == 0">No tasks created yet.</h3>

		<div v-for="(tasks, status) in items" :key="tasks.id">
			<h4 class="text-center">
				<strong>{{ status }}</strong>
			</h4>
			<table class="table col-md-16 mb-5 table-responsive-md">
				<thead>
					<tr>
						<th scope="col text-left" style="width: 30%">Task</th>
						<th scope="col text-center" style="width: 15%">
							Creator
						</th>
						<th scope="col text-center" style="width: 15%">
							Person responsible
						</th>
						<th scope="col text-center" style="width: 10%">
							Status
						</th>
						<th scope="col text-center">Start date</th>
						<th scope="col text-center">Finish date</th>
						<th scope="col text-center" style="width: 1%"></th>
					</tr>
				</thead>
				<tbody>
					<tr v-for="task in tasks" :key="task.id">
						<td v-text="task.name"></td>
						<td v-text="task.creator.full_name"></td>
						<td v-text="task.assignee.full_name"></td>
						<task-status
							:task="task"
							@task-updated="refresh"
						></task-status>
						<td v-text="formattedDate(task.start_date)"></td>
						<td v-text="formattedDate(task.finish_date)"></td>
						<td style="cursor: pointer" @click="deleteTask(task)">
							<i class="material-icons icon" title="Delete"
								>delete</i
							>
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
				items: {}
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
					text: "You won't be able to revert this!",
					type: "warning",
					showCancelButton: true,
					confirmButtonColor: "#3085d6",
					cancelButtonColor: "#d33",
					confirmButtonText: "Yes, delete it!"
				}).then(result => {
					if (result.value) {
						axios
							.delete(
								`/workspaces/${this.workspace.id}/tasks/${task.id}`
							)
							.then(response => {
								this.refresh();
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
		}
	};
</script>

<style>
	.icon {
		font-size: 15px;
		vertical-align: middle;
	}
</style>