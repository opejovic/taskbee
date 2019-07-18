<template>
	<div class="d-flex justify-content-center col-md-12">
		<!-- if user is signed in already, dont show the form for registraton, else, show the subscription form. -->
		<div class="col-md-4">
			<h4>Choose your plan:</h4>
			<div v-for="plan in plans" :key="plan.id" type="button">
				<!-- this can be its own component Plan or Plans -->
				<div
					class="card mb-2"
					@click="choose(plan)"
					:class="clicked(plan.id) ? 'blue' : ''"
				>
					<div class="card-header" v-text="plan.name"></div>
					<div class="card-body">
						{{ (plan.amount * plan.members_limit) / 100 }} EUR /
						monthly
					</div>
				</div>
			</div>
		</div>

		<div>
			<h4>Your credentials here:</h4>
			<div class="card col-md-12">
				<div class="card-body">
					<form @submit.prevent="register">
						<div class="form-row">
							<div class="form-group">
								<label for="first_name">First name</label>
								<input
									type="text"
									class="form-control"
									:class="
                                        form.errors.has('first_name')
                                            ? ' is-invalid'
                                            : ''
                                    "
									name="first_name"
									id="first_name"
									placeholder="Enter your first name"
									v-model="form.first_name"
									required
								/>

								<span class="invalid-feedback" role="alert" v-if="form.errors.has('first_name')">
									<strong v-text="form.errors.get('first_name')"></strong>
								</span>
							</div>
							<div class="form-group col-md-6">
								<label for="last_name">Last name</label>
								<input
									type="text"
									class="form-control"
									:class="
                                        form.errors.has('last_name')
                                            ? ' is-invalid'
                                            : ''
                                    "
									name="last_name"
									id="last_name"
									placeholder="Enter your last name"
									v-model="form.last_name"
									required
								/>
								<span class="invalid-feedback" role="alert" v-if="form.errors.has('last_name')">
									<strong v-text="form.errors.get('last_name')"></strong>
								</span>
							</div>
						</div>
						<div class="form-group">
							<label for="email">Email address</label>
							<input
								type="email"
								class="form-control"
								:class="
									form.errors.has('email')
										? ' is-invalid'
										: ''
								"
								name="email"
								id="email"
								aria-describedby="emailHelp"
								placeholder="Enter email"
								v-model="form.email"
								required
							/>

							<span class="invalid-feedback" role="alert" v-if="form.errors.has('email')">
								<strong v-text="form.errors.get('email')"></strong>
							</span>
						</div>
						<div class="form-group">
							<input
								type="email"
								class="form-control"
								:class="
									form.errors.has('email_confirmation')
										? ' is-invalid'
										: ''
								"
								name="email_confirmation"
								id="email-confirm"
								aria-describedby="emailHelp"
								placeholder="Confirm email"
								v-model="form.email_confirmation"
								required
							/>
							<span class="invalid-feedback" role="alert" v-if="form.errors.has('email_confirmation')">
								<strong v-text="form.errors.get('email_confirmation')"></strong>
							</span>
							<small id="emailHelp" class="form-text text-muted">
								We'll never share your email with anyone else.
							</small>
						</div>
						<div class="form-group">
							<label for="password">Password</label>
							<input
								type="password"
								class="form-control"
								:class="
									form.errors.has('password')
										? ' is-invalid'
										: ''
								"
								name="password"
								id="password"
								placeholder="Password"
								v-model="form.password"
								required
							/>
							<span class="invalid-feedback" role="alert" v-if="form.errors.has('password')">
								<strong v-text="form.errors.get('password')"></strong>
							</span>
						</div>
						<div class="form-group">
							<input
								type="password"
								class="form-control"
								:class="
									form.errors.has('password_confirmation')
										? ' is-invalid'
										: ''
								"
								name="password_confirmation"
								id="password-confirm"
								placeholder="Confirm password"
								v-model="form.password_confirmation"
								required
							/>
							<span class="invalid-feedback" role="alert" v-if="form.errors.has('password_confirmation')">
								<strong v-text="form.errors.get('password_confirmation')"></strong>
							</span>
						</div>

						<button
							class="btn btn-primary btn-block"
							type="submit"
							:class="{ 'btn-loading': processing }"
							:disabled="processing"
						>
							<div
								:class="
									processing
										? 'd-flex align-items-center'
										: 'text-center'
								"
							>
								<span v-text="state">Loading...</span>
								<div
									class="spinner-border ml-auto spinner-border-sm"
									role="status"
									v-show="processing"
								></div>
							</div>
						</button>
					</form>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
	export default {
		props: ["plans"],
		data() {
			return {
				form: new Form({
					first_name: null,
					last_name: null,
					email: null,
					email_confirmation: null,
					password: null,
					password_confirmation: null,
				}),

				processing: false,
				spinning: false,
				selectedPlan: 1, // by default the selected plan is the plan with the id of 1.
			};
		},

		computed: {
			state() {
				return this.processing
					? "Proceeding to checkout"
					: "Create my account";
			}
		},

		methods: {
			choose(plan) {
				this.selectedPlan = plan.id;
			},

			clicked(id) {
				return this.selectedPlan === id ? true : false;
			},

			register() {
				this.form.post("/register")
					.then(response => {
						// create a customer than proceede to checkout with the customer

						this.checkout();
					})
					.catch(error => {
						console.log(error.response.data.errors);
					});
			},

			checkout() {
				const stripe = Stripe(process.env.MIX_STRIPE_KEY);

				this.processing = true;

				axios
					.post(`/plans/${this.selectedPlan}/checkout`, {})
					.then(response => {
						stripe
							.redirectToCheckout({
								// Make the id field from the Checkout Session creation API response
								// available to this file, so you can provide it as parameter here
								// instead of the {{CHECKOUT_SESSION_ID}} placeholder.
								sessionId: response.data.id
							})
							.then(result => {
								// If `redirectToCheckout` fails due to a browser or network
								// error, display the localized error message to your customer
								// using `result.error.message`.
							});
					});
			}
		}
	};
</script>

<style>
	.blue {
		background-color: rgb(184, 184, 252);
	}
</style>
