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
        props: ['plan', 'workspace'],
        data() {
            return {
                processing: false,
                spinning: false,
            }
        },

        computed: {
            state() {
                if (this.processing) {
                    return 'Processing your request...';
                }

                return 'Buy a member slot';
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
                
                axios.post(`/workspaces/${this.workspace.id}/add-slot`, {

                }).then(response => {
                    this.processing = false;
                    window.location = `/workspace-setup/${response.data}`
                }).catch(response => {
                    this.processing = false
                })
            },

    },
};
</script>
