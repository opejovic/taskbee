<template>
    <div>
        <button class="btn btn-primary btn-block"
           @click="initStripe"
           :class="{ 'btn-loading': processing }"
           :disabled="processing"
           >
            <div :class="processing ? 'd-flex align-items-center' : 'text-center'">
                <span v-text="state">Loading...</span>
                <div 
                    class="spinner-border spinner-border-sm" 
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
                    return 'Processing';
                }

                return 'Buy a member slot';
            },
        },
        methods: {
            initStripe() {
                const stripe = Stripe(process.env.MIX_STRIPE_KEY);
                this.processing = true;

                axios.post(`/workspaces/${this.workspace.id}/add-slot`, {
                    //
                }).then(response => {
                    window.location = response.data.hosted_invoice_url;
                    // window.location = `/workspace-setup/${response.data[0]}`
                }).catch(response => {
                    this.processing = false
                })
            },
        },
    };
</script>
