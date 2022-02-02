<template>
    <admin-layout :authorized="isAuthorized($page, 'daily_report')" :page-title="trans('admin-general.modules.house-daily-report-list')">
        <template #breadcrumb>
            <bread-crumb>
                <bread-crumb-item :label="trans('admin-general.modules.house-daily-report-list')"/>
            </bread-crumb>
        </template>
        <template #actions>
            <secondary-button v-if="isAuthorized($page, 'daily_report_create')"
                              :href="route('admin.house-daily-reports.create')"
                              class="!bg-transparent !py-1 !pl-0 sm:!pl-4 !pr-0">
                <Icon class="!w-5 !h-5 !mr-1" name="plus-1"/>
                {{ trans("admin-general.modules.house-daily-report-create") }}
            </secondary-button>
        </template>

        <daily-report-filters :query="query" @filter="filters => loadData(filters)"/>

        <data-table
            :pagination="pagination"
            :show-filter="false"
            :queries="query"
            :table-data="reports"
            @loaddata="loadData">
            <template #thead>
                <table-head-col v-text="`Report Date`"/>
                <table-head-col v-text="`Survey`"/>
                <table-head-col v-text="`Supervisor`"/>
                <table-head-col v-text="`District`"/>
                <table-head-col v-text="`Census Office`"/>
                <table-head-col v-text="`Sent At`"/>
                <table-head-col v-text="`Actions`"/>
            </template>
            <template #tbody="{row: report}" class="text-base-3">
                <table-body-col v-text="getFromObject(report, 'report_date.date')"/>
                <table-body-col class="text-left">
                    <span>{{ trans(`daily_reports.labels.Number of houses`) }}: {{ getFromObject(report, "total_surveyed", 0) }} </span> <br>
                    <span>{{ trans(`daily_reports.labels.Number of houses in census`) }}: {{ getFromObject(report, "number_of_houses_in_census", 0) }} </span> <br>
                    <span>{{ trans(`daily_reports.labels.Number of families in census`) }}: {{ getFromObject(report, "number_of_families_in_census", 0) }} </span> <br>
                </table-body-col>
                <table-body-col v-text="getFromObject(report, 'supervisor')"/>
                <table-body-col v-text="getFromObject(report, 'district')"/>
                <table-body-col v-text="getFromObject(report, 'census_office')"/>
                <table-body-col v-text="getFromObject(report, 'created_at.raw')"/>
                <table-body-col nowrap>
                    <primary-button @click="editReport(report)" v-text="`Edit`"/>
                    <cancel-button @click="deleteReport(report)" v-text="`Delete`"/>
                </table-body-col>
            </template>
        </data-table>
    </admin-layout>
</template>

<script>
    import BreadCrumb         from "../../Components/BreadCrumb/BreadCrumb"
    import BreadCrumbItem     from "../../Components/BreadCrumb/BreadCrumbItem"
    import CancelButton       from "../../Components/Buttons/DangerButton"
    import PrimaryButton      from "../../Components/Buttons/PrimaryButton"
    import SecondaryButton    from "../../Components/Buttons/SecondaryButton"
    import {
        DataTable,
        TableBodyCol,
        TableHeadCol,
    }                         from "../../Components/DataTable"
    import Icon               from "../../Components/General/Icon"
    import AdminLayout        from "../../Layouts/AdminLayout"
    import DailyReportFilters from "./Partials/DailyReportFilters"

    export default {
        name: "HouseDailyReportList",

        components: {
            CancelButton,
            DailyReportFilters,
            PrimaryButton,
            SecondaryButton,
            BreadCrumb,
            BreadCrumbItem,
            AdminLayout,
            DataTable,
            Icon,
            TableBodyCol,
            TableHeadCol,
        },

        props: {
            reports: { type: Array, required: true },
            pagination: { type: Object, required: true },
            queries: { type: Object, required: false, default: () => ({}) },
            options: { type: Object, required: false, default: () => ({}) },
        },

        data: () => ({
            query: {
                district_code: "all",
                census_office_id: "all",
                supervisor: "",
            },
        }),

        provide() {
            return {
                districts: this.options.districts,
                censusOffices: this.options.censusOffices,
            }
        },

        watch: {
            queries: {
                handler(queries) {
                    this.query = { ...this.query, ...queries }
                },
                immediate: true,
                deep: true,
            },
        },

        methods: {
            loadData(query) {
                this.$inertia.visit(this.route("admin.house-daily-reports.index"), {
                    method: "get",
                    data: query,
                    preserveState: true,
                    preserveScroll: true,
                })
            },

            editReport(report) {
                this.$inertia.visit(this.route("admin.house-daily-reports.edit", report.id))
            },

            deleteReport(report) {
                this.$inertia.delete(this.route("admin.house-daily-reports.destroy", report.id))
            },
        },
    }
</script>

<style scoped>

</style>
