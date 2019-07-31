<template>
  <div
    class="flex w-full mx-auto items-center"
  >
    <!-- if user is signed in already, dont show the form for registraton, else, show the subscription form. -->
    <div class="mx-auto w-full">
      <div class="px-3">
        <header class="flex justify-between items-center py-8">
          <a href="#" class="flex items-center">
            <img class="w-12" src="/img/logo.svg" alt="Imaginary" />
            <p class="pl-1 font-bold text-xl tracking-tighter text-indigo-900">
              taskbee.
            </p>
          </a>
          <a
            href="/login"
            class="block mt-4 text-sm lg:inline-block lg:mt-0 text-indigo-800 hover:text-indigo-500 border-b-2 border-transparent hover:border-indigo-500"
          >
            Sign in
          </a>
        </header>
      </div>
      <div class="px-3 pb-10 text-center">
        <div class="w-full">
          <span class="font-light text-xl flex-1 text-indigo-900"
            >Create a workspace for your team, and start assigning tasks
            today.</span
          >
        </div>
      </div>
      <div class="flex flex-wrap mx-auto">
        <div class="xl:w-1/2 lg:w-1/2 md:w-1/2 sm:w-full mx-auto px-3">
          <div class="mb-3 text-center">
            <div class="w-full px-3">
              <span class="text-sm uppercase tracking-tight text-indigo-900"
                >Choose a plan</span
              >
            </div>
          </div>
          <div v-for="plan in plans" :key="plan.id" style="cursor: pointer;">
            <!-- this can be its own component Plan or Plans -->
            <div :class="{ unclickable: processing }" class="h-full">
              <div
                class="text-sm tracking-tight text-indigo-900 mb-2 border-2 border-gray-400 px-6 py-4 rounded"
                @click="choose(plan)"
                :class="{ 'indie-border': clicked(plan.id) }"
              >
                <div class="mb-1">
                  <div class="text-lg font-light flex items-center">
                    {{ plan.name }}
                    <i
                      class="material-icons indie-text ml-auto"
                      v-if="clicked(plan.id)"
                      >check_circle_outline</i
                    >
                  </div>
                </div>
                <div class="flex items-center">
                  <div class="text-gray-700 mr-auto tracking-tighter">
                    <span class="font-semibold text-2xl">
                      €
                      {{ (plan.amount * plan.members_limit) / 100 }}</span
                    >
                    <span class="text-xl font-light">
                      / month
                    </span>
                  </div>
                  <div class="text-gray-700 text-left w-1/3">
                    <span class="text-sm"
                      >{{ plan.members_limit }} member slots. Unlimited storage,
                      and more...</span
                    >
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="xl:w-1/2 lg:w-1/2 md:w-1/2 sm:w-full mx-auto px-3">
          <div class="mb-3 text-center">
            <div class="w-full">
              <span class="text-sm uppercase tracking-tight text-indigo-900"
                >Account details</span
              >
            </div>
          </div>
          <div class="w-full">
            <form @submit.prevent="register">
              <div class="w-full">
                <input
                  type="text"
                  class="shadow appearance-none block w-full bg-gray-200 border text-center text-gray-700 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-gray-400"
                  :class="
                    form.errors.has('first_name') ? ' border-red-300' : 'border-transparent'
                  "
                  name="first_name"
                  id="first_name"
                  placeholder="First name"
                  v-model="form.first_name"
                  :disabled="processing"
                  @keydown="form.errors.clear('first_name')"
                />

                <p
                  class="text-red-500 text-xs -mt-2 text-center"
                  v-if="form.errors.has('first_name')"
                  v-text="form.errors.get('first_name')"
                ></p>
              </div>
              <div class=" mb-1">
                <div class="w-full">
                  <input
                    type="text"
                    class="shadow appearance-none block w-full bg-gray-200 border text-center text-gray-700 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-gray-400"
                    :class="
                      form.errors.has('last_name') ? ' border-red-300' : 'border-transparent'
                    "
                    name="last_name"
                    id="last_name"
                    placeholder="Last name"
                    v-model="form.last_name"
                    :disabled="processing"
                    @keydown="form.errors.clear('last_name')"
                  />

                  <p
                    class="text-red-500 text-xs -mt-2 text-center"
                    v-if="form.errors.has('last_name')"
                    v-text="form.errors.get('last_name')"
                  ></p>
                </div>
              </div>
              <div class=" mb-1">
                <div class="w-full">
                  <input
                    type="email"
                    class="shadow appearance-none block w-full bg-gray-200 border text-center text-gray-700 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-gray-400"
                    :class="form.errors.has('email') ? ' border-red-300' : 'border-transparent'"
                    name="email"
                    id="email"
                    placeholder="Email"
                    v-model="form.email"
                    :disabled="processing"
                    @keydown="form.errors.clear('email')"
                  />

                  <p
                    class="text-red-500 text-xs -mt-2 text-center"
                    v-if="form.errors.has('email')"
                    v-text="form.errors.get('email')"
                  ></p>
                </div>
              </div>
              <div class=" mb-1">
                <div class="w-full">
                  <input
                    type="email"
                    class="shadow appearance-none block w-full bg-gray-200 text-center text-gray-700 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-gray-400"
                    name="email_confirmation"
                    id="email_confirmation"
                    placeholder="Confirm Email"
                    v-model="form.email_confirmation"
                    :disabled="processing"
                    @keydown="form.errors.clear('email')"
                  />
                </div>
              </div>
              <div class=" mb-1">
                <div class="w-full">
                  <input
                    type="password"
                    class="shadow appearance-none block w-full bg-gray-200 border text-center text-gray-700 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-gray-400"
                    :class="
                      form.errors.has('password') ? ' border-red-300' : 'border-transparent'
                    "
                    name="password"
                    id="password"
                    placeholder="Password"
                    v-model="form.password"
                    :disabled="processing"
                    @keydown="form.errors.clear('password')"
                  />

                  <p
                    class="text-red-500 text-xs -mt-2 text-center"
                    v-if="form.errors.has('password')"
                    v-text="form.errors.get('password')"
                  ></p>
                </div>
              </div>
              <div class=" mb-1">
                <div class="w-full">
                  <input
                    type="password"
                    class="shadow appearance-none block w-full bg-gray-200 border text-center text-gray-700 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-gray-400"
                    :class="
                      form.errors.has('password_confirmation')
                        ? 'border-red-300'
                        : 'border-transparent'
                    "
                    name="password_confirmation"
                    id="password_confirmation"
                    placeholder="Confirm password"
                    v-model="form.password_confirmation"
                    :disabled="processing"
                    @keydown="form.errors.clear('password')"
                  />

                  <p
                    class="text-red-500 text-xs"
                    v-if="form.errors.has('password_confirmation')"
                    v-text="form.errors.get('password_confirmation')"
                  ></p>
                </div>
              </div>
              <div class="">
                <div class="w-full">
                  <button
                    class="block w-full uppercase mx-auto shadow border-b-4 border-gray-600 bg-indigo-800 hover:bg-indigo-700 focus:shadow-outline focus:outline-none text-white text-xs py-3 px-10 rounded"
                    type="submit"
                    :class="{ loader: processing }"
                    :disabled="processing"
                  >
                    <span v-text="state"></span>
                  </button>
                </div>
              </div>
            </form>
          </div>
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
          password_confirmation: null
        }),

        processing: false,
        spinning: false,
        selectedPlan: 1 // by default the selected plan is the plan with the id of 1.
      };
    },

    computed: {
      state() {
        return this.processing ? "PROCESSING... " : "PROCEED TO CHECKOUT";
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
        this.form
          .post("/register")
          .then(response => {
            // create a customer and then proceed to checkout with the customer
            this.checkout();
          })
          .catch(error => {
            console.log(error.response.data.errors);
          });
      },

      checkout() {
        const stripe = Stripe(process.env.MIX_STRIPE_KEY);

        this.processing = true;

        axios.post(`/plans/${this.selectedPlan}/checkout`, {}).then(response => {
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
  .indie-border {
    border-color: rgb(82, 0, 177);
    border-width: 2px;
  }

  .unclickable {
    pointer-events: none;
  }

  .indie-text {
    color: rgb(82, 0, 177);
  }

  .indie-btn {
    background-color: rgb(82, 0, 177);
    border-color: rgb(82, 0, 177);
    color: rgb(255, 255, 255);
  }

  .indie-btn.disabled,
  .indie-btn:disabled {
    background-color: rgb(82, 0, 177);
    border-color: rgb(82, 0, 177);
    color: rgb(255, 255, 255);
  }

  .indie-btn:hover {
    background-color: rgb(135, 74, 211);
    border-color: rgb(135, 74, 211);
    color: rgb(255, 255, 255);
  }

  .indie-btn-loading {
    background-color: rgb(135, 74, 211);
    border-color: rgb(135, 74, 211);
  }
</style>
