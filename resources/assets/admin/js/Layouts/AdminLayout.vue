<template>
    <fragment>
        <sidebar v-if="isSidebarOpen"/>
        <div class="relative bg-gray-100" :class="{'md:ml-64' : isSidebarOpen}">
            <div class="bg-primary-600  px-4 py-6 md:p-6">
                <top-nav/>
                <div class="flex flex-wrap gap-4 items-center justify-between my-16 pb-8 ">
                    <div class="breadcrumb">
                        <slot name="breadcrumb"/>
                    </div>
                    <div>
                        <slot name="actions"/>
                    </div>
                </div>
            </div>
            <div class="body-container flex flex-col justify-between px-4 md:px-6  mx-auto w-full -mt-24">
                <div class="hidden lds-ring-wrap">
                    <div class="lds-ring m-auto">
                        <div/>
                        <div/>
                        <div/>
                    </div>
                </div>
                <div class="content-wrapper bg-white break-words flex flex-col relative rounded-lg shadow-lg
                            w-full px-4 py-6 lg:px-6">
                    <flash-message/>
                    <slot v-if="authorized"/>
                    <unauthorized v-else/>
                </div>
                <app-footer/>
            </div>
        </div>
    </fragment>
</template>

<script type="text/ecmascript-6">
    import FlashMessage from "../Components/General/FlashMessage"
    import Unauthorized from "../Pages/Unauthorized"
    import AppFooter    from "./partials/AppFooter"
    import Sidebar      from "./partials/Sidebar"
    import TopNav       from "./partials/TopNav"

    export default {
        name: "AdminLayout",

        components: { FlashMessage, Unauthorized, AppFooter, TopNav, Sidebar },

        props: {
            isSidebarOpen: { type: Boolean, default: true },
            pageTitle: { type: String, required: true },
            authorized: { type: Boolean, required: false, default: false },
            backUrl: { type: String, required: false, default: "" },
        },

        provide() {
            return {
                pageTitle: this.pageTitle,
                backUrl: this.backUrl,
            }
        },

        created() {
            document.title = `${this.pageTitle} | ${this.$page.props.app.name}`
        },

        mounted() {
            this.$inertia.on("finish", () => {
                document.querySelector("body").classList.remove("show--nav")
            })
        },
    }
</script>

<style lang="scss" scoped>
    .body-container {
        min-height: calc(100vh - 6rem);

        .content-wrapper {
            min-height: 300px;
        }
    }
</style>
