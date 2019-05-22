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
        props: ['plan'],
        data() {
            return {
                stripeHandler: null,
                processing: false,
            }
        },

        computed: {
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
                    token: this.purchasePlan,
                })
            },
            purchasePlan(token) {
                this.processing = true
                axios.post(`/bundles/${this.plan.id}/purchase`, {
                    email: token.email,
                    payment_token: token.id,
                }).then(response => {
                    console.log(response);
                    // window.location = `/workspace-setup/${response.data[1]}`
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
