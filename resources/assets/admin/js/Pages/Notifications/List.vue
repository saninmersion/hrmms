<template>
    <admin-layout :authorized="true" :page-title="trans('admin-general.modules.notification')">
        <template #breadcrumb>
            <bread-crumb>
                <bread-crumb-item :label="trans('admin-general.modules.notification')"/>
            </bread-crumb>
        </template>
        <template #actions class="">
            <inertia-link
                :href="route('admin.notifications.markAsRead', {})"
                as="button"
                class="inline-flex items-center justify-center px-4 py-2 rounded-md font-semibold text-xs text-white uppercase tracking-widest"
                method="POST">
                Mark all as read
            </inertia-link>
        </template>
        <table v-if="notifications.length > 0">
            <thead>
                <tr>
                    <table-head-col class="notification-name-th" v-text="trans('admin-general.modules.notification')"/>
                    <table-head-col v-text="trans('Created At')"/>
                    <table-head-col v-text="trans('Action')"/>
                </tr>
            </thead>

            <tbody class="text-base-3">
                <tr v-for="(notification) in notifications"
                    :key="`tbody-${notification.id}`">
                    <!-- eslint-disable-next-line vue/no-v-html -->
                    <table-body-col v-html="notification.data.message"/>
                    <table-body-col>
                        {{ new Date(notification.created_at).toLocaleString() }}
                    </table-body-col>
                    <table-body-col>
                        <inertia-link
                            :href="route('admin.notifications.markAsRead', {id: notification.id })"
                            as="button"
                            class="inline-flex items-center justify-center px-4 py-2 bg-success border border-transparent
                    rounded-md font-semibold text-xs text-white uppercase tracking-widest"
                            method="POST">
                            Mark as read
                        </inertia-link>
                    </table-body-col>
                </tr>
            </tbody>
        </table>
        <empty-data v-else class="bg-white  ">
            <slot name="empty-message">
                <p class="text-xl text-gray-700">
                    No Notifications found.
                </p>
            </slot>
        </empty-data>
    </admin-layout>
</template>

<script type="text/ecmascript-6">
    import {
        BreadCrumb,
        BreadCrumbItem,
    }                  from "../../Components/BreadCrumb"
    import {
        TableBodyCol,
        TableHeadCol,
    }                  from "../../Components/DataTable"
    import EmptyData   from "../../Components/DataTable/EmptyData"
    import AdminLayout from "../../Layouts/AdminLayout"

    export default {
        name: "NotificationList",

        components: {
            EmptyData,
            BreadCrumbItem,
            BreadCrumb,
            AdminLayout,
            TableHeadCol,
            TableBodyCol,
        },

        props: {
            notifications: { type: Array, required: true },
        },

        data: () => ({
            query: {},
        }),

        watch: {
            queries: {
                handler(queries) {
                    this.query = { ...queries }
                },
                immediate: true,
                deep: true,
            },
        },

        methods: {
            loadData(query) {
                this.$inertia.visit(this.route("admin.notifications.index"), {
                    method: "get",
                    data: query,
                    preserveState: true,
                    preserveScroll: true,
                })
            },
        },
    }
</script>
