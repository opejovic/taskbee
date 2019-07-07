<template>
	<div>
		<button
			class="btn btn-primary btn-block"
			@click="checkout"
			:class="{ 'btn-loading': processing }"
			:disabled="processing"
		>

			<div :class="processing ? 'd-flex align-items-center' : 'text-center'">
				<span v-text="state">Loading...</span>
				<div
					class="spinner-border ml-auto spinner-border-sm"
					role="status"
					v-show="processing"
				>
				</div>
			</div>
		</button>
	</div>
</template>

<script>
	export default {
		props: ['plan'],
		data() {
			return {
				processing: false,
				spinning: false,
			}
		},

		computed: {
			state() {
				return this.processing ? 'Processing' : 'Purchase Bundle';
			},
		},

		methods: {
			checkout() {
				const stripe = Stripe(process.env.MIX_STRIPE_KEY);

				this.processing = true;

				axios.post(`/plans/${this.plan.id}/checkout`, {
				}).then(response => {
					stripe.redirectToCheckout({
						// Make the id field from the Checkout Session creation API response
						// available to this file, so you can provide it as parameter here
						// instead of the {{CHECKOUT_SESSION_ID}} placeholder.
						sessionId: response.data.id
					}).then((result) => {
						// If `redirectToCheckout` fails due to a browser or network
						// error, display the localized error message to your customer
						// using `result.error.message`.
					});
				});
			},

		},
	};
</script>
