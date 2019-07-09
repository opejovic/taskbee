<template>
    <div>
        <button
            class="btn btn-primary btn-block"
            @click="initStripe"
            :class="{ 'btn-loading': processing }"
            :disabled="processing"
        >
            <span
                class="spinner-border spinner-border-sm"
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
    props: ["workspace"],

    data() {
        return {
            processing: false,
            spinning: false
        };
    },

    computed: {
        state() {
            return this.processing
                ? "Proceeding to checkout..."
                : "Renew subscription";
        }
    },

    methods: {
        initStripe() {
            const stripe = Stripe(process.env.MIX_STRIPE_KEY);

            this.processing = true;

            axios
                .post(`/workspaces/${this.workspace.id}/renew`)
                .then(response => {
                    this.processing = false;
                    console.log(response);
                    window.location = response.data;
                })
                .catch((this.processing = false));
        }
    }
};
</script>
