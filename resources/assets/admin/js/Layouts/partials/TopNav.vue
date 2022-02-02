<template>
    <nav class="flex justify-between items-center">
        <div class="flex gap-3 items-center">
            <inertia-link v-if="backLink" :href="backLink" class="btn-back text-white cursor-pointer">
                <Icon name="arrow-left" class="!mr-0 !w-5"/>
            </inertia-link>
            <h1 class="inline-block text-white text-xl uppercase tracking-wider" v-text="title"/>
        </div>
        <div class="flex flex-nowrap items-center">
            <user-notification class="mr-4"/>

            <drop-down-menu align="right">
                <template #trigger>
                    <button class="flex border-2 border-transparent w-12 rounded-full focus:outline-none
                                    focus:border-gray-300 transition duration-150 ease-in-out">
                        <img
                            :alt="$page.props.auth.user.display_name"
                            class="w-full rounded-full align-middle border-none shadow-lg"
                            :src="$page.props.auth.user.profile_picture.url">
                    </button>
                </template>

                <template #content>
                    <inertia-link
                        :href="route('profile.show')"
                        class="text-sm py-2 px-4 font-normal block w-full whitespace-no-wrap bg-transparent
                                text-gray-800">
                        {{ trans("user.Account Settings") }}
                    </inertia-link>

                    <div class="h-0 my-2 border border-b-0 border-grey"/>

                    <form @submit.prevent="handleLogout">
                        <inertia-link
                            :href="route('logout')"
                            method="POST"
                            as="button"
                            class="text-sm text-left py-2 px-4 font-normal block w-full whitespace-no-wrap
                                    bg-transparent text-gray-800">
                            {{ trans("auth.logout") }}
                        </inertia-link>
                    </form>
                </template>
            </drop-down-menu>
        </div>
    </nav>
</template>

<script type="text/ecmascript-6">
    import Icon             from "../../Components/General/Icon"
    import UserNotification from "../../Components/General/UserNotification"
    import DropDownMenu     from "./DropDownMenu"

    export default {
        name: "TopNav",

        components: { UserNotification, DropDownMenu, Icon },

        inject: ["pageTitle", "backUrl"],

        data: () => ({
            showDropDownMenu: false,
        }),

        computed: {
            title: function() {
                return this.pageTitle
            },

            backLink: function() {
                return this.backUrl
            },
        },
    }
</script>

<style lang="scss" scoped>

    .btn-back {

    }
</style>
