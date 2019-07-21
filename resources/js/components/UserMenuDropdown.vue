<template>
    <div class="dropdown">
        <span class="dropdown__header" @click="toggleDropdown($event)">
            <span
                class="block text-sm mt-4 lg:inline-block lg:mt-0 text-indigo-800 hover:text-indigo-500 mr-4"
                v-text="auth.first_name"
            ></span>
            <i class="fa fa-angle-down" aria-hidden="true"></i>
            <i class="fa fa-angle-up" aria-hidden="true"></i>
        </span>

        <div class="dropdown__content bg-gray-200 rounded border fixed text-sm">
            <ul>
                <li>
                    <a
                        href="/dashboard"
                        class="block mt-4 lg:inline-block lg:mt-0 text-indigo-800 hover:text-indigo-500 mr-4"
                    >
                        Dashboard
                    </a>
                </li>
                <li>
                    <a
                        :href="profilePage"
                        class="block mt-4 lg:inline-block lg:mt-0 text-indigo-800 hover:text-indigo-500 mr-4"
                    >
                        My profile
                    </a>
                </li>
                <li>
                    <a
                        class="block mt-4 lg:inline-block lg:mt-0 text-indigo-800 hover:text-indigo-500 mr-4"
                        href="/logout"
                        onclick="event.preventDefault();
								document.getElementById('logout-form').submit();"
                    >
                        Logout
                    </a>
                    <form
                        id="logout-form"
                        action="/logout"
                        method="POST"
                        style="display: none;"
                    ></form>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
    export default {
        computed: {
            profilePage() {
                return '/profiles/' + auth.id;
            }
        },

        methods: {
            toggleDropdown(event) {
                event.currentTarget.classList.toggle('is-active');
            }
        }
    };
</script>

<style lang="scss" scoped>
    .dropdown {
        &__header {
            cursor: pointer;
            padding-left: 10px;
            padding-right: 50px;
            position: relative;
            text-overflow: ellipsis;
            i.fa {
                position: absolute;
                right: 10px;
                top: 50%;
                transform: translateY(-50%);
                transition: opacity 0.3s;
                &.fa-angle-up {
                    opacity: 0;
                }
            }
            &.is-active {
                i.fa {
                    &.fa-angle-up {
                        opacity: 1;
                    }
                    &.fa-angle-down {
                        opacity: 0;
                    }
                }
                + .dropdown__content {
                    height: auto;
                    opacity: 1;
                    visibility: visible;
                }
            }
        }
        &__content {
            height: 0;
            opacity: 0;
            overflow: hidden;
            padding: 15px 10px;
            transition: opacity 0.3s;
            visibility: hidden;
        }
    }
</style>
