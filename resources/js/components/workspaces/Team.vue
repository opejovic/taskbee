<template>
  <div class="h-full flex flex-col">
    <!-- header -->
    <header class="px-5 w-full">
      <img class="w-32 mx-auto" src="/img/dashboard.svg" alt="" />
      <div class="text-2xl text-indigo-700 text-light pb-5">
        <a :href="workspaceRoute">{{ workspace.name }}</a>
      </div>
    </header>

    <!-- body -->
    <div class="px-5 py-5 w-full flex-1 h-full overflow-y-hidden">
      <div class="text-left px-10 text-gray-800 text-sm">
        <div class="flex items-center justify-between pb-2">
          <div class="w-full">
            {{ workspace.creator.full_name }}
          </div>
          <span
            class="bg-indigo-500 text-white text-xs font-semibold px-2 rounded-full"
          >
            Admin
          </span>
        </div>

        <div v-for="invitation in invitations" :key="invitation.id">
          <li style="list-style: none;">
            <div class="flex items-center justify-between pb-2">
              {{ invitation.first_name }} {{ invitation.last_name }}
              <span
                class="text-gray-700 text-xs font-semibold px-2 rounded-full"
                :class="
                  hasBeenUsed(invitation) ? 'bg-green-300' : 'bg-orange-300'
                "
              >
                {{ hasBeenUsed(invitation) ? "Active" : "Invited" }}
              </span>
            </div>
          </li>
        </div>

        <p v-if="invitations === null" class="text-gray-600">
          Still no invited members
        </p>
      </div>
    </div>

    <!-- footer -->
    <div v-if="userOwnsWorkspace" class="w-full bottom-0 pb-5">
      <div v-if="invitesRemaining">
        <a
          :href="authorizationRoute"
          class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded inline-flex items-center"
        >
          <svg
            class="fill-current w-4 h-4 mr-2"
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 20 20"
          >
            <path
              d="M2 6H0v2h2v2h2V8h2V6H4V4H2v2zm7 0a3 3 0 0 1 6 0v2a3 3 0 0 1-6 0V6zm11 9.14A15.93 15.93 0 0 0 12 13c-2.91 0-5.65.78-8 2.14V18h16v-2.86z"
            />
          </svg>
          <span>Invite Members</span>
        </a>

        <p class="pt-2 text-xs text-gray-600">
          You can invite {{ authorization.invitesRemaining }} more
          {{ pluralizeMembers }}.
        </p>
        <!-- else -->
      </div>
      <div v-else>
          <member-slot-checkout
            :workspace="workspace"
            style="vertical-align: bottom;"
          ></member-slot-checkout>
          <p class="pt-2 text-xs text-gray-600">
            You have used all your invites. Purchase more slots
            <a class="border-b-2 border-indigo-300" href="">here</a>.
          </p>
        </div>
    </div>
  </div>
</template>

<script>
  export default {
    props: ["workspace", "invitations"],

    data() {
      return {
        authorization: this.workspace.authorization
      };
    },

    computed: {
      workspaceRoute() {
        return `/workspaces/${this.workspace.id}`;
      },

      userOwnsWorkspace() {
        return auth.id == this.workspace.creator.id;
      },

      authorizationRoute() {
        return `/workspace-setup/${this.authorization.code}`;
      },

      invitesRemaining() {
        return this.authorization.invitesRemaining;
      },

      pluralizeMembers() {
        return this.authorization.invitesRemaining === 1 ? "member" : "members";
      }
    },

    methods: {
      hasBeenUsed(invitation) {
        return invitation.user_id !== null;
      }
    }
  };
</script>

<style lang="scss" scoped>
</style>