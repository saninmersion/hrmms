<template>
    <admin-layout class="relative" page-title="Dashboard" :authorized="isAuthorized($page, 'district_dashboard')">
        <template v-if="dateTime">
            <div class="flex justify-between mb-6">
                <div class="text-sm" role="alert">
                    <span class="block sm:inline">Data as of</span>
                    <strong class="font-bold">{{ dateTime }}</strong>
                </div>
                <div>
                    <a class="text-primary-500 hover:opacity-90 p-2" target="_blank" :href="route('admin.console.applications.export')">
                        Refresh application stats
                    </a>
                </div>
            </div>
            <div class="flex flex-wrap">
                <district-distribution :district-wise="districtWise" :districts="districts" :hired-stats="hiredStats"/>
            </div>
        </template>

        <empty-data v-else message="No record found."/>
    </admin-layout>
</template>

<script>
    import EmptyData            from "../../../../shared/js/Components/DataTable/EmptyData"
    import AdminLayout          from "../../Layouts/AdminLayout"
    import DistrictDistribution from "./Partials/DistrictDistribution"

    export default {
        name: "Dashboard",

        components: {
            DistrictDistribution,
            EmptyData,
            AdminLayout,
        },

        props: {
            districtWise: { type: Object, required: false, default: () => ({}) },
            dateTime: { type: String, required: false, default: "" },
            districts: { type: Array, required: false, default: () => ([]) },
            hiredStats: { type: Object, required: false, default: () => ({}) },
        },
    }
</script>
