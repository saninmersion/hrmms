<template>
    <admin-layout :authorized="isAuthorized($page, 'enumerator')" :page-title="trans('admin-general.modules.enumerator-list')">
        <template #breadcrumb>
            <bread-crumb>
                <bread-crumb-item :label="trans('admin-general.modules.enumerator-list')"/>
            </bread-crumb>
        </template>
        <template #actions>
            <secondary-button v-if="isAuthorized($page, 'daily_report')"
                              :href="route('admin.enumerators.create')"
                              class="!bg-transparent !py-1 !pl-0 sm:!pl-4 !pr-0">
                <Icon class="!w-5 !h-5 !mr-1" name="plus-1"/>
                {{ trans("admin-general.modules.enumerator-create") }}
            </secondary-button>
        </template>
        <data-table
            :pagination="pagination"
            :show-filter="false"
            :queries="query"
            :table-data="enumerators"
            @loaddata="loadData">
            <template #thead>
                <table-head-col v-text="`Name`"/>
                <table-head-col v-text="`Target`"/>
                <table-head-col v-text="`Total Progress`"/>
                <table-head-col v-text="`Actions`"/>
            </template>
            <template #tbody="{row: enumerator}" class="text-base-3">
                <table-body-col v-text="getFromObject(enumerator, 'name')"/>
                <table-body-col v-text="getFromObject(enumerator, 'target')"/>
                <table-body-col class="text-center" v-text="getFromObject(enumerator, 'total_progress')"/>
                <table-body-col>
                    <primary-button @click="editEnumerator(enumerator)" v-text="`Edit`"/>
                </table-body-col>
            </template>
        </data-table>
    </admin-layout>
</template>

<script>
    import AdminLayout from "../../Layouts/AdminLayout";
    import BreadCrumb from "../../Components/BreadCrumb/BreadCrumb";
    import BreadCrumbItem from "../../Components/BreadCrumb/BreadCrumbItem";
    import SecondaryButton from "../../Components/Buttons/SecondaryButton";
    import Icon from "../../Components/General/Icon";
    import DataTable from "../../Components/DataTable/DataTable";
    import TableHeadCol from "../../Components/DataTable/TableHeadCol";
    import TableBodyCol from "../../Components/DataTable/TableBodyCol";
    import PrimaryButton from "../../Components/Buttons/PrimaryButton";

    export default {
        name: "EnumeratorList",
        components: { PrimaryButton, TableBodyCol, TableHeadCol, DataTable, Icon, SecondaryButton, BreadCrumbItem, BreadCrumb, AdminLayout },
        props: {
            enumerators: { type: Array, required: true },
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
                this.$inertia.visit(this.route("admin.enumerators.index"), {
                    method: "get",
                    data: query,
                    preserveState: true,
                    preserveScroll: true,
                })
            },

            editEnumerator(enumerator) {
                this.$inertia.visit(this.route("admin.enumerators.edit", enumerator.id))
            },
        },
    }
</script>

<style scoped>

</style>
