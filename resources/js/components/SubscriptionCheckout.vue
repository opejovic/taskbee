<template>
    <div>
        <button class="btn btn-primary btn-block"
           @click="initStripe"
           :class="{ 'btn-loading': processing }"
           :disabled="processing"
           >
            <span class="spinner-border spinner-border-sm" 
                role="status" 
                aria-hidden="true" 
                v-show="processing"
                >
            </span>
            <span v-text="state"></span>
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
                if (this.processing) {
                    return 'Proceeding to checkout...';
                }

                return 'Purchase bundle';
            },

            description() {
                return `Purchase ${this.plan.name} bundle.`
            },
            totalPrice() {
                return this.plan.amount
            },
            priceInDollars() {
                return (this.plan.amount / 100).toFixed(2)
            },
            totalPriceInDollars() {
                return (this.plan.amount / 100).toFixed(2)
            },
        },
        methods: {
            initStripe() {
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
