<template>
    <nav class="sidebar md:left-0 md:block md:fixed md:top-0 md:bottom-0  md:flex-row md:flex-no-wrap
                md:overflow-hidden shadow-xl bg-gray-800  flex-wrap items-center justify-between relative
                md:w-64 z-10 p-4 md:px-6 overflow-y-auto md:overflow-y-auto">
        <div class="md:flex-col md:items-stretch md:min-h-full md:flex-no-wrap px-0 flex flex-wrap items-center
                    justify-between w-full mx-auto">
            <!-- Logo -->

            <div class="w-full flex justify-between items-center">
                <inertia-link
                    class="flex font-bold gap-2 items-center text-2xl text-gray-100 uppercase whitespace-no-wrap"
                    :href="route('admin.dashboard')">
                    <img src="/images/logo.png" alt="logo" class="h-10">
                    <span v-text="$page.props.app.name"/>
                </inertia-link>
                <div class="burger bg-gray-800 md:hidden" @click="handleToggle">
                    <span/>
                    <span/>
                    <span/>
                </div>
            </div>
            <div
                class="md:flex md:flex-col md:items-stretch md:opacity-100 md:relative md:shadow-none shadow
                        absolute top-0 left-0 right-0 z-40 overflow-y-auto overflow-x-hidden h-auto items-center
                        flex-1 rounded hidden mobile-nav bg-gray-800 mt-8 md:mt-0 py-8 px-4 md:p-0">
                <menus/>
            </div>
        </div>
    </nav>
</template>

<script type="text/ecmascript-6">
    import Menus from "./Menus"

    export default {
        name: "Sidebar",

        components: { Menus },

        methods: {
            handleToggle() {
                document.body.classList.toggle("show--nav")
            },
        },
    }
</script>
