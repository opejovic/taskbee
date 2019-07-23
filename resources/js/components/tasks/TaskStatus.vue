<template>
    <td class="dropdown cursor">
		<div class="relative">
			<select
				@change="updateStatus($event)"
				class="cursor-pointer appearance-none w-full bg-gray-200 border text-gray-700 py-2 px-2 rounded leading-tight focus:outline-none focus:bg-white focus:border-indigo-600"
			>
				<option
					v-text="currentStatus"
					selected
				>
				</option>
				<option
					v-for="taskStatus in statusez"
					:key="taskStatus.id"
					v-text="taskStatus"
				>
				</option>
			</select>
			<div
				class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700"
			>
				<svg
					class="fill-current h-4 w-4"
					xmlns="http://www.w3.org/2000/svg"
					viewBox="0 0 20 20"
				>
					<path
						d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"
					/>
				</svg>
			</div>
		</div>
    </td>
</template>

<script>
	export default {
		props: ["task"],
		data() {
			return {
				statuses: ["Planned", "In progress", "Waiting", "Testing", "Done"],
				currentStatus: this.task.status,
			};
		},

		computed: {
			statusez() {
			var arr = ["Planned", "In progress", "Waiting", "Testing", "Done"];

				for( var i = 0; i < arr.length; i++){ 
					if ( arr[i] == this.currentStatus) {
     					arr.splice(i, 1); 
   					}
				}

				return arr;
			},

			classes() {
				switch (this.task.status) {
					case "Planned":
						return "planned";
						break;
					case "In progress":
						return "inprogress";
						break;
					case "Waiting":
						return "badge-warning";
						break;
					case "Testing":
						return "testing";
						break;
					case "Done":
						return "done";
						break;
					default:
						return;
				}
			}
		},

		methods: {
			selectedTask(taskStatus) {
				if (this.task.status === taskStatus) {
					return this.task.status;
				}
			},

			updateStatus(event) {
				const taskStatus = event.target.value;

				if (this.task.status === taskStatus) {
					return;
				}
				axios
					.patch(
						`/workspaces/${this.task.workspace_id}/tasks/${
							this.task.id
						}`,
						{
							status: taskStatus
						}
					)
					.then(response => {
						this.$emit("task-updated");
						window.events.$emit("task-updated");
						this.$toasted.show("Task updated!");
					})
					.catch(error => {
						this.checkError(error.response.data.message);
						currentStatus = this.task.status;
					});
			},

			checkError(message) {
				if (message === "Task may be updated only once per minute.") {
					this.$toasted.show(message);
				}
			}
		}
	};
</script>

<style>
	.cursor {
		cursor: pointer;
	}

	.done {
		background-color: #00b365;
		color: white;
	}

	.planned {
		background-color: #6200ee;
		color: white;
	}

	.testing {
		background-color: #dae0e5;
	}

	.inprogress {
		background-color: #f06900;
		color: white;
	}
</style>
