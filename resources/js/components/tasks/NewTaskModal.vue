<template>
	<div>
		<button
			class="block uppercase mx-auto shadow bg-indigo-800 hover:bg-indigo-700 focus:shadow-outline focus:outline-none text-white text-xs py-3 -mt-2 -mb-2 px-10 rounded"
			@click="showModal"
		>
			<i class="material-icons add align-self-center">add</i>
			Add task
		</button>

		<modal
			name="new-task-modal"
			class="modal"
			height="auto"
			id="addTaskModal"
		>
			<div class="px-8 py-8 h-full w-full" role="document">
					
				<div class="w-full mx-auto px-3">
				<div class="text-xs text-indigo-900 text-right align-middle">
						<button type="button" @click="hide" class="close">
							<span aria-hidden="true" class="text-lg">&times;</span>
						</button>
					</div>
					<div class="text-center mb-5 -mt-5">
						<div
							class="text-lg uppercase tracking-tight text-indigo-900"
							id="addTaskModalLabel"
						>
							Add new task
						</div>
					</div>
					<div class="modal-body">
						<form @submit.prevent="addTask">
							<div class=" -mx-3 mb-3">
								<div class="w-full md:w-3/3 px-3">
									<p
										class="text-xs tracking-tight text-indigo-900"
									>
										What is the task?
									</p>
									<input
										type="text"
										class="appearance-none block w-full bg-gray-200 text-gray-700 border rounded py-3 px-4 leading-tight focus:outline-none focus:border-indigo-600 focus:bg-white"
										:class="
											form.errors.has('name')
												? ' border-red-700 '
												: ''
										"
										name="name"
										id="name"
										v-model="form.name"
										@keydown="form.errors.clear('name')"
									/>

									<p
										class="text-red-600 text-xs"
										v-if="form.errors.has('name')"
										v-text="form.errors.get('name')"
									></p>
								</div>
							</div>

							<div class=" -mx-3 mb-3">
								<div class="w-full md:w-3/3 px-3">
									<p
										class="text-xs tracking-tight text-indigo-900"
										for="start_date"
									>
										Who has to complete this task?
									</p>
									<div class="relative">
										<select
											v-model="form.user_responsible"
											@click="
												form.errors.clear(
													'user_responsible'
												)
											"
											class="block appearance-none w-full bg-gray-200 border text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-indigo-600"
											:class="
												form.errors.has(
													'user_responsible'
												)
													? 'border-red-700'
													: ''
											"
										>
											<option
												v-for="member in members"
												:value="member.user_id"
												:key="member.user_id"
											>
												{{ member.first_name }}
												{{ member.last_name }}
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
									<p
										class="text-red-600 text-xs"
										v-if="
											form.errors.has('user_responsible')
										"
										v-text="
											form.errors.get('user_responsible')
										"
									></p>
								</div>
							</div>

							<div class="flex w-full mb-3">
								<div class="flex-1 mr-1">
									<p
										class="text-xs tracking-tight text-indigo-900"
									>
										When is the start date of the task?
									</p>
									<input
										class="block appearance-none w-full bg-gray-200 border text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-indigo-600"
										type="date"
										name="start_date"
										:class="
											form.errors.has('start_date')
												? ' is-invalid'
												: ''
										"
										id="start_date"
										placeholder="Start date"
										v-model="form.start_date"
										@click="form.errors.clear('start_date')"
										required
									/>

									<span
										class="invalid-feedback"
										role="alert"
										v-if="form.errors.has('start_date')"
									>
										<strong
											v-text="
												form.errors.get('start_date')
											"
										></strong>
									</span>
								</div>

								<div class="flex-1 ml-1">
									<p
										class="text-xs tracking-tight text-indigo-900"
									>
										When should it be done?
									</p>
									<input
										class="block appearance-none w-full bg-gray-200 border text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-indigo-600"
										type="date"
										name="finish_date"
										:class="
											form.errors.has('finish_date')
												? ' is-invalid'
												: ''
										"
										id="finish_date"
										placeholder="Finish date"
										v-model="form.finish_date"
										@click="
											form.errors.clear('finish_date')
										"
										required
									/>

									<span
										class="invalid-feedback"
										role="alert"
										v-if="form.errors.has('finish_date')"
									>
										<strong
											v-text="
												form.errors.get('finish_date')
											"
										></strong>
									</span>
								</div>
							</div>

							<div class=" -mx-3 mb-3">
								<div class="w-full md:w-3/3 px-3">
									<p
										class="text-xs tracking-tight text-indigo-900"
									>
										What is the status of the task?
									</p>
									<div class="relative">
										<select
											id="status"
											name="status"
											v-model="form.status"
											@click="form.errors.clear('status')"
											class="block appearance-none w-full bg-gray-200 border text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-indigo-600"
											:class="
												form.errors.has('status')
													? 'border-red-700'
													: ''
											"
										>
											<option value="Planned"
												>Planned</option
											>
											<option value="In progress"
												>In Progress</option
											>
											<option value="Waiting"
												>Waiting</option
											>
											<option value="Testing"
												>Testing</option
											>
											<option value="Done">Done</option>
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
									<p
										class="text-red-600 text-xs"
										v-if="form.errors.has('status')"
										v-text="form.errors.get('status')"
									></p>
								</div>
							</div>

							<div class="py-4">
								<p
									class="text-xs tracking-tight text-indigo-900"
								>
									Member responsible will be notified via
									email.
								</p>
								<div class="flex w-full text-right">
									
									<button
										type="submit"
										class="flex-1 block uppercase mx-auto shadow  bg-indigo-800 hover:bg-indigo-700 focus:shadow-outline focus:outline-none text-white text-xs py-3 px-10 rounded"
										:disabled="form.errors.any()"
									>
										Submit
									</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</modal>
	</div>
</template>

<script>
	export default {
		props: ["workspace"],
		data() {
			return {
				form: new Form({
					name: null,
					user_responsible: null,
					start_date: null,
					finish_date: null,
					status: null
				}),

				members: null
			};
		},

		methods: {
			showModal() {
				this.$modal.show("new-task-modal");
			},

			addTask() {
				this.form
					.post(`/workspaces/${this.workspace.id}/tasks`)
					.then(response => {
						// Passing a task to the emited event.
						this.form.reset();
						window.events.$emit("task-added", response);
						$("#addTaskModal").modal("hide");

						// flash a message to the user
						this.$toasted.show("Task created!");
					});
			},

			hide() {
				this.form.reset();
				this.$modal.hide("new-task-modal");
			}
		},

		created() {
			this.members = this.workspace.members;
		}
	};
</script>

<style>
	.add {
		vertical-align: middle;
		font-size: 20px;
	}

	.big-task {
		background-color: #6200ee;
		color: white;
		vertical-align: middle;
	}

	.big-task:hover {
		background-color: #2e006e;
		color: white;
	}
</style>
