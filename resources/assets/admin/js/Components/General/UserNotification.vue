<template>
    <div class="flex justify-center">
        <div class="relative">
            <span v-if="hasNotification" class="absolute bg-warning h-2 right-1 rounded-full w-2 z-20"/>
            <button class="bg-white block focus:outline-none p-2 p-3 relative rounded-full z-10" @click="dropdownOpen = !dropdownOpen">
                <svg class="h-5 w-5 text-gray-800" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"/>
                </svg>
            </button>

            <div v-show="dropdownOpen" class="fixed inset-0 h-full w-full z-10" @click="dropdownOpen = false"/>

            <div v-if="$page.props.notifications.length > 0" v-show="dropdownOpen" class="absolute right-0 mt-2 bg-white rounded-md shadow-lg overflow-hidden z-20" style="width:20rem;">
                <div v-for="notification in $page.props.notifications.slice(0,4)" :key="notification.id" class="py-2">
                    <div class="flex flex-col px-4 py-3 border-b hover:bg-gray-100 -mx-2">
                        <!-- eslint-disable-next-line vue/no-v-html -->
                        <p class="text-gray-600 text-sm mx-2" v-html="notification.data.message"/>
                        <p class="text-sm text-gray-500 mx-2 ">
                            [{{ new Date(notification.created_at).toLocaleString() }}]
                        </p>
                    </div>
                </div>
                <inertia-link
                    :href="route('admin.notifications.index')"
                    as="link"
                    class="block bg-gray-800 text-white text-center font-bold py-2 cursor-pointer"
                    method="GET">
                    See all notifications
                </inertia-link>
            </div>
            <div v-else v-show="dropdownOpen" class="absolute right-0 mt-2 bg-white rounded-md shadow-lg overflow-hidden z-20" style="width:20rem;">
                <div class="flex flex-col px-4 py-3 border-b hover:bg-gray-100 -mx-2">
                    <p class="text-gray-600 text-sm mx-2">
                        No notifications to show
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "UserNotification",
        data() {
            return {
                dropdownOpen: false,
            }
        },
        computed: {
            hasNotification() {
                return this.$page.props.notifications.length > 0
            },
        },
    }
</script>

<style>
    .fade-enter-active, .fade-leave-active {
        transition: opacity 0.5s ease-out;
    }

    .fade-enter, .fade-leave-to {
        opacity: 0;
    }

    .flash-message .btn-remove:hover {
        opacity: 1;
    }
</style>
