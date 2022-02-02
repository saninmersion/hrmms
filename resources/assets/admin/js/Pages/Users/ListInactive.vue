<template>
    <admin-layout :page-title="trans('admin-general.modules.user-mgmt')" :authorized="isAuthorized($page, 'activate_monitor')">
        <template #breadcrumb>
            <bread-crumb>
                <bread-crumb-item :label="trans('admin-general.modules.user-mgmt')"/>
            </bread-crumb>
        </template>

        <template #actions/>

        <data-table
            :pagination="pagination"
            :queries="query"
            :table-data="users"
            @loaddata="loadData">
            <template #thead>
                <table-head-col v-text="trans('admin-users.full-name')"/>
                <table-head-col v-text="trans('admin-users.account')"/>
                <table-head-col v-text="trans('admin-users.role')"/>
                <table-head-col v-text="trans('admin-users.actions')"/>
            </template>

            <template #tbody="{row: user}" class="text-base-3">
                <table-body-col>
                    <div class="flex items-center gap-2">
                        <img :alt="user.display_name"
                             :src="user.profile_picture.url"
                             class="w-10 h-10 rounded-full">
                        <span class="text-base-1" v-text="user.name"/>
                    </div>
                </table-body-col>

                <table-body-col>
                    <p class="text-base-3">
                        <strong v-text="trans('admin-users.email')"/>: {{ user.email }}
                    </p>
                    <p class="text-base-3">
                        <strong v-text="trans('admin-users.username')"/>: {{ user.username }}
                    </p>
                </table-body-col>

                <table-body-col>
                    {{ trans(`admin-users.roles.${user.role}`) }}
                    <span v-if="isDistrictUser(user.role)" class="text-base-1"> ({{ getFormattedDistrictName(user.district) }})</span>
                </table-body-col>
                <table-body-col>
                    <primary-button class="" @click="activateUser(user)">
                        Activate
                    </primary-button>
                </table-body-col>
            </template>
        </data-table>
    </admin-layout>
</template>

<script type="text/ecmascript-6">
    import {
        BreadCrumb,
        BreadCrumbItem,
    }                        from "../../Components/BreadCrumb"
    import { PrimaryButton } from "../../Components/Buttons"
    import {
        DataTable,
        TableBodyCol,
        TableHeadCol,
    }                        from "../../Components/DataTable"
    import AdminLayout       from "../../Layouts/AdminLayout"

    const DISTRICT_USER = "district_staffs"

    export default {
        name: "ListInactive",

        components: {
            PrimaryButton,
            BreadCrumbItem,
            BreadCrumb,
            AdminLayout,
            DataTable,
            TableHeadCol,
            TableBodyCol,
        },

        props: {
            users: { type: Array, required: true },
            pagination: { type: Object, required: true },
            queries: { type: Object, required: false, default: () => ({}) },
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
                this.$inertia.visit(this.route("admin.users.index.inactive-monitors"), {
                    method: "get",
                    data: query,
                    preserveState: true,
                    preserveScroll: true,
                })
            },

            isDistrictUser(role) {
                return role === DISTRICT_USER
            },

            getFormattedDistrictName(district) {
                if (district) {
                    return district[this.$currentLocale === "en" ? "title_en" : "title_ne"] || ""
                }
                return ""
            },
            activateUser(user) {
                this.$inertia.post(this.route("admin.users.activate", user.id))
            },
        },
    }
</script>
