<template>
  <div>
    <button
      class="mx-auto bg-indigo-800 hover:bg-indigo-600 text-white font-normal text-sm py-3 px-10 rounded flex items-center"
      @click="confirm"
      :disabled="processing"
    >
      <div class="flex items-center px-5">
        <svg
          class="fill-current w-4 h-4 mr-2"
          xmlns="http://www.w3.org/2000/svg"
          viewBox="0 0 20 20"
        >
          <path
            d="M2 6H0v2h2v2h2V8h2V6H4V4H2v2zm7 0a3 3 0 0 1 6 0v2a3 3 0 0 1-6 0V6zm11 9.14A15.93 15.93 0 0 0 12 13c-2.91 0-5.65.78-8 2.14V18h16v-2.86z"
          />
        </svg>
        <span v-text="state"></span>
      </div>
      <span v-if="processing" class="loader"></span>
    </button>
  </div>
</template>

<script>
  import Swal from "sweetalert2";

  export default {
    props: ["plan", "workspace"],
    data() {
      return {
        processing: false
      };
    },

    computed: {
      state() {
        return this.processing ? "Processing..." : "Purchase Slot";
      }
    },

    methods: {
      confirm() {
        Swal.fire({
          title: "Please confirm your purchase",
          text: "You will be billed on the monthly basis and one time payment for the added member.",
          type: "info",
          showCancelButton: true,
          confirmButtonColor: "#434190",
          cancelButtonColor: "#a0aec0",
          confirmButtonText: "Yes, proceed."
        }).then(result => {
          // If the user clicks proceed, he will be invoiced.
          if (result.value) {
            this.initStripe();
          }
        });
      },

      initStripe() {
        const stripe = Stripe(process.env.MIX_STRIPE_KEY);
        this.processing = true;

        axios
          .post(`/workspaces/${this.workspace.id}/add-slot`)
          .then(response => {
            // return a link for the hosted invoice instead of redirecting the user.
            window.location = response.data.hosted_invoice_url;
          })
          .catch(error => {
            console.log(error);
          });
      }
    }
  };
</script>
