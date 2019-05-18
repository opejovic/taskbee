<template>
    <div>
        <button class="btn btn-primary btn-block"
           @click="openStripe"
           :class="{ 'btn-loading': processing }"
           :disabled="processing"
           >
           Purchase bundle
        </button>
    </div>
</template>

<script>
    export default {
        props: ['bundle'],
        data() {
            return {
                stripeHandler: null,
                processing: false,
            }
        },

        computed: {
            description() {
                return `Purchase ${this.bundle.name} bundle.`
            },
            totalPrice() {
                return this.bundle.price
            },
            priceInDollars() {
                return (this.bundle.price / 100).toFixed(2)
            },
            totalPriceInDollars() {
                return (this.bundle.price / 100).toFixed(2)
            },
        },
        methods: {
            initStripe() {
                const handler = StripeCheckout.configure({
                    key: process.env.MIX_STRIPE_KEY
                })
                window.addEventListener('popstate', () => {
                    handler.close()
                })
                return handler
            },
            openStripe(callback) {
                this.stripeHandler.open({
                    name: 'TaskMonkey',
                    description: this.description,
                    currency: "eur",
                    allowRememberMe: false,
                    panelLabel: 'Pay {{amount}}',
                    amount: this.totalPrice,
                    image: '/img/checkout-image.png',
                    token: this.purchaseBundle,
                })
            },
            purchaseBundle(token) {
                this.processing = true
                axios.post(`/bundles/${this.bundle.name}/pay`, {
                    email: token.email,
                    payment_token: token.id,
                }).then(response => {
                    console.log(response);
                    // window.location = `/orders/${response.data.confirmation_number}`
                }).catch(response => {
                    this.processing = false
                })
            }
        },
        created() {
            this.stripeHandler = this.initStripe()
        }
    };
</script>
