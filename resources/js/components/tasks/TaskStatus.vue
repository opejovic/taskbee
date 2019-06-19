<template>
	<td class="dropdown cursor">
		<a id="navbarDropdown" class="dropdown-toggle" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-text="task.status">
			<span class="caret"></span>
		</a>

		<div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
			<a class="dropdown-item" v-for="taskStatus in statuses" v-text="taskStatus" @click="updateStatus(taskStatus)"></a>
		</div>                        
	</td>
</template>

<script>
	export default {
		props: ['task'],
		data() {
			return {
				statuses: ['Planned', 'In progress', 'Waiting', 'Testing', 'Done'],
			}
		},
		methods: {
			updateStatus(taskStatus) {
				if (this.task.status == taskStatus) {
					return;
				}

				axios.patch(`/workspaces/${this.task.workspace_id}/tasks/${this.task.id}`, {
					status: taskStatus
				})
				.then(response =>  {
					this.$emit('task-updated');
					this.$toasted.show('Task updated!');
				})
				.catch();
			}
		},
	}
</script>

<style>
	.cursor {
		cursor: pointer;
	}
</style>