<template>
    <admin-layout :authorized="isAuthorized($page, 'user')" :page-title="trans('admin-general.modules.user-mgmt')">
        <template #breadcrumb>
            <bread-crumb>
                <bread-crumb-item :label="trans('admin-general.modules.user-mgmt')"/>
            </bread-crumb>
        </template>

        <template #actions>
            <secondary-button v-if="isAuthorized($page, 'user')"
                              :href="route('admin.users.create')"
                              class="!bg-transparent !py-1 !pl-0 sm:!pl-4 !pr-0">
                <Icon class="!w-5 !h-5 !mr-1" name="plus-1"/>
                {{ trans("admin-users.create-user") }}
            </secondary-button>
        </template>

        <user-filters :query="query" @filter="filters => loadData(filters)"/>

        <data-table
            :pagination="pagination"
            :show-filter="false"
            :queries="query"
            :row-clickable="handleRowClick"
            :table-data="users"
            @loaddata="loadData">
            <template #thead>
                <table-head-col class="user-name-th" v-text="trans('admin-users.full-name')"/>
                <table-head-col v-text="trans('admin-users.account')"/>
                <table-head-col v-text="trans('admin-users.role')"/>
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
            </template>
        </data-table>
    </admin-layout>
</template>

<script type="text/ecmascript-6">
    import {
        BreadCrumb,
        BreadCrumbItem,
    }                          from "../../Components/BreadCrumb"
    import { SecondaryButton } from "../../Components/Buttons"
    import {
        DataTable,
        TableBodyCol,
        TableHeadCol,
    }                          from "../../Components/DataTable"
    import Icon                from "../../Components/General/Icon"
    import AdminLayout         from "../../Layouts/AdminLayout"
    import UserFilters         from "./Partials/UserFilters"

    const DISTRICT_USER = "district_staffs"

    export default {
        name: "UserList",

        components: {
            UserFilters,
            SecondaryButton,
            BreadCrumbItem,
            BreadCrumb,
            AdminLayout,
            DataTable,
            TableHeadCol,
            TableBodyCol,
            Icon,
        },

        props: {
            users: { type: Array, required: true },
            options: { type: Object, required: true },
            pagination: { type: Object, required: true },
            queries: { type: Object, required: false, default: () => ({}) },
        },

        data: () => ({
            query: {},
        }),

        provide() {
            return {
                options: this.options,
            }
        },

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
                this.$inertia.visit(this.route("admin.users.index"), {
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
            handleRowClick(user) {
                this.$inertia.visit(this.route("admin.users.show", user.id))
            },
        },
    }
</script>
