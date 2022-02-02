<template>
    <admin-layout page-title="Applicants" :authorized="isAuthorized($page, 'export')">
        <template #breadcrumb>
            <bread-crumb>
                <bread-crumb-item label="Application Exports"/>
            </bread-crumb>
        </template>

        <div class="relative flex flex-col sm:flex-row sm:items-center bg-white shadow rounded-md py-5 pl-6 pr-8 sm:pr-6">
            <template v-if="exportedFile">
                <div class="flex flex-row items-center border-b sm:border-b-0 w-full sm:w-auto pb-4 sm:pb-0">
                    <div class="text-success-500">
                        <i class="fas fa-file-csv"/>
                    </div>
                    <div class="text-sm font-medium ml-3">
                        {{ exportedFile.filename }}
                    </div>
                </div>
                <div class="text-sm tracking-wide text-gray-500 mt-4 sm:mt-0 sm:ml-4">
                    Data as of: {{ getFromObject(exportedFile, "date_as_of.raw") }} ({{ getFromObject(exportedFile, "date_as_of.diff") }})
                </div>
                <a class="text-primary-500 hover:opacity-90 p-2" target="_blank" :href="exportedFile.download_url">
                    <Icon name="download"/>
                </a>
            </template>

            <div v-else class="flex flex-row items-center border-b sm:border-b-0 w-full sm:w-auto pb-4 sm:pb-0">
                <div class="text-sm font-medium ml-3">
                    No file generated yet.
                </div>
            </div>
        </div>

        <template v-if="downloadLogs.length">
            <div class="mt-5">
                <h3 class="text-xl font-semibold">
                    Download Log
                </h3>
            </div>
        </template>

        <data-table
            :pagination="pagination"
            :queries="query"
            :show-filter="false"
            :table-data="downloadLogs"
            @loaddata="loadData">
            <template #thead>
                <table-head-col>File name</table-head-col>
                <table-head-col>Downloaded By</table-head-col>
                <table-head-col>Downloaded At</table-head-col>
            </template>

            <template #tbody="{row: log}">
                <table-body-col v-text="getFromObject(log, 'exported_file.filename')"/>
                <table-body-col v-text="getFromObject(log, 'downloaded_by.display_name')"/>
                <table-body-col>
                    <p v-text="getFromObject(log, 'downloaded_at.raw')"/>

                    <p class="text-gray-500 mt-1" v-text="getFromObject(log, 'downloaded_at.diff')"/>
                </table-body-col>
            </template>

            <template #empty-message>
                <p class="text-xl text-gray-700">
                    File not downloaded yet.
                </p>
            </template>
        </data-table>
    </admin-layout>
</template>

<script type="text/ecmascript-6">
    import Icon        from "../../../js/Components/General/Icon"
    import {
        BreadCrumb,
        BreadCrumbItem,
    }                  from "../../Components/BreadCrumb"
    import {
        DataTable,
        TableBodyCol,
        TableHeadCol,
    }                  from "../../Components/DataTable"
    import AdminLayout from "../../Layouts/AdminLayout"

    export default {
        name: "ApplicantExportsList",

        components: {
            Icon,
            BreadCrumbItem,
            BreadCrumb,
            AdminLayout,
            DataTable,
            TableHeadCol,
            TableBodyCol,
        },

        props: {
            exportedFile: { type: Object, required: false, default: null },
            downloadLogs: { type: Array, required: true },
            pagination: { type: Object, required: true },
            queries: { type: Object, required: false, default: () => ({}) },
        },

        data() {
            return {
                query: {},
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
                this.$inertia.visit(this.route("admin.applications.exports.index"), {
                    method: "get",
                    data: query,
                    preserveState: true,
                    preserveScroll: true,
                })
            },
        },
    }
</script>
