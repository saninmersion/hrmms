<template>
    <admin-layout page-title="Overall Monitoring List" :authorized="isAuthorized($page, 'monitoring')">
        <template #breadcrumb>
            <bread-crumb>
                <bread-crumb-item :href="route('admin.monitorings.index')" label="Monitoring"/>
                <bread-crumb-item label="List"/>
            </bread-crumb>
        </template>

        <div v-if="isAuthorized($page, 'monitoring_export')" class="downloads flex flex-wrap md:justify-end  gap-4 md:gap-8 mt-1 ">
            <a :href="route('admin.monitorings.exports.overall')" class="flex  text-primary-500 hover:opacity-90" target="_blank">
                <Icon name="download"/>
                <span class="block -mt-1">
                    Download Overall Monitoring
                </span>
            </a>
            <a :href="route('admin.monitorings.exports.supervisor')" class="flex  text-primary-500 hover:opacity-90" target="_blank">
                <Icon name="download"/>
                <span class="block -mt-1">
                    Download Supervisor Monitoring
                </span>
            </a>
            <a :href="route('admin.monitorings.exports.enumerator')" class="flex  text-primary-500 hover:opacity-90" target="_blank">
                <Icon name="download"/>
                <span class="block -mt-1">
                    Download Enumerator Monitoring
                </span>
            </a>
        </div>

        <template #actions class="">
            <div class="flex flex-nowrap justify-between items-center">
                <drop-down-menu class="form-dropdown" align="right">
                    <template #trigger>
                        <button class="text-white flex items-center">
                            <Icon name="plus-1"/>
                            सुपरिवेक्षण फाराम
                        </button>
                    </template>
                    <template #content>
                        <div>
                            <inertia-link v-if="isAuthorized($page, 'monitoring_general')"
                                          :href="route('admin.monitorings.form.overall')"
                                          class="text-sm py-2 px-4 font-normal block w-full whitespace-no-wrap
                                    bg-transparent text-gray-800">
                                {{ trans("admin-monitoring.form-type.overall_monitoring") }}
                            </inertia-link>

                            <inertia-link v-if="isAuthorized($page, 'monitoring_supervisor')"
                                          :href="route('admin.monitorings.form.supervisor')"
                                          class="text-sm py-2 px-4 font-normal block w-full whitespace-no-wrap
                                    bg-transparent text-gray-800">
                                {{ trans("admin-monitoring.form-type.supervisor_monitoring") }}
                            </inertia-link>

                            <inertia-link v-if="isAuthorized($page, 'monitoring_enumerator')"
                                          :href="route('admin.monitorings.form.enumerator')"
                                          class="text-sm py-2 px-4 font-normal block w-full whitespace-no-wrap
                                    bg-transparent text-gray-800">
                                {{ trans("admin-monitoring.form-type.enumerator_monitoring") }}
                            </inertia-link>
                        </div>
                    </template>
                </drop-down-menu>
            </div>
        </template>

        <monitoring-filters :query="query" @filter="filters => loadData(filters)"/>
        <data-table :pagination="pagination"
                    :queries="query"
                    :show-filter="false"
                    :table-data="monitorings"
                    class="monitoring-table"
                    :row-clickable="handleRowClick"
                    @loaddata="loadData">
            <template #thead>
                <table-head-col v-if="isOperator" nowrap>
                    Monitored By
                </table-head-col>
                <table-head-col nowrap>
                    Monitoring Type
                </table-head-col>
                <table-head-col>District</table-head-col>
                <table-head-col>Municipality</table-head-col>
                <table-head-col>Monitoring Date</table-head-col>
                <table-head-col>Monitored Person Name</table-head-col>
                <table-head-col>Monitored Person Mobile No</table-head-col>
            </template>
            <template #settings>
                <span class="cursor-pointer" title="Reload" @click.prevent="loadData">
                    <icon name="reload" class="!mr-0 fill-current"/>
                </span>
            </template>
            <template #tbody="{row: monitoring}">
                <table-body-col v-if="isOperator" v-text="monitoring.monitored_by"/>
                <table-body-col no-wrap>
                    <p class="whitespace-nowrap" v-text="trans(`admin-monitoring.form-type.${monitoring.form}`)"/>
                </table-body-col>
                <table-body-col v-text="monitoring.district"/>
                <table-body-col v-text="monitoring.municipality"/>
                <table-body-col v-text="getFromObject(monitoring, 'monitoring_date.formatted')"/>
                <table-body-col v-text="getFromObject(monitoring, 'monitored_person_name', '-')"/>
                <table-body-col v-text="getFromObject(monitoring, 'monitored_person_mobile_no', '-')"/>
            </template>
        </data-table>
    </admin-layout>
</template>

<script>
    import {
        BreadCrumb,
        BreadCrumbItem,
    }                        from "../../Components/BreadCrumb"
    import {
        DataTable,
        TableBodyCol,
        TableHeadCol,
    }                        from "../../Components/DataTable"
    import Icon              from "../../Components/General/Icon"
    import AdminLayout       from "../../Layouts/AdminLayout"
    import DropDownMenu      from "../../Layouts/partials/DropDownMenu"
    import MonitoringFilters from "./Partials/MonitoringFilters"

    const OPERATOR = "operators"

    export default {
        name: "List",
        components: {
            MonitoringFilters,
            Icon,
            BreadCrumb,
            BreadCrumbItem,
            AdminLayout,
            DataTable,
            TableHeadCol,
            TableBodyCol,
            DropDownMenu,
        },
        props: {
            monitorings: { type: Array, required: true },
            pagination: { type: Object, required: true },
            districts: { type: Array, required: true },
            queries: { type: Object, required: false, default: () => ({}) },
        },

        provide() {
            return {
                districts: this.districts,
            }
        },

        data() {
            return {
                query: {
                    form_type: null,
                    district_code: null,
                    municipality_code: null,
                },
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
        computed: {
            isOperator: function() {
                return this.getFromObject(this.$page, "props.auth.user.role") === OPERATOR
            },
        },

        methods: {
            filterReset() {
                this.$set(this.query, "form_type", "all")
                this.loadData(this.query)
            },
            loadData(query) {
                this.$inertia.visit(this.route("admin.monitorings.index"), {
                    method: "get",
                    data: query,
                    preserveState: true,
                    preserveScroll: true,
                    only: ["monitorings", "pagination", "queries", "districts"],
                })
            },
            handleRowClick(monitorings) {
                this.$inertia.visit(this.route("admin.monitorings.show", monitorings.id))
            },
        },
    }
</script>

<style lang="scss">
    .form-dropdown .dropdown-menu {
        width: 180px;
    }
</style>
