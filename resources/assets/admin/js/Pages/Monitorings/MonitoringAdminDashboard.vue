<template>
    <admin-layout class="relative" page-title="Monitoring Dashboard" :authorized="isAuthorized($page, 'monitoring_dashboard')">
        <template v-if="dateTime">
            <div class="grid gap-8 stats-cards-wrapper my-6">
                <div class="bg-grey-2 rounded  shadow text-center py-6 px-4">
                    <h2 class="font-semibold text-3xl text-black">
                        {{ numberTrans(getFromObject(formCounts, "overallFormCount", 0)) }}
                    </h2>
                    <p>{{ trans("admin-monitoring.form-type.overall_monitoring") }}</p>
                </div>
                <div class="bg-grey-2 rounded  shadow text-center py-6 px-4">
                    <h2 class="font-semibold text-3xl text-black">
                        {{ numberTrans(getFromObject(formCounts, "supervisorFormCount", 0)) }}
                    </h2>
                    <p>{{ trans("admin-monitoring.form-type.supervisor_monitoring") }}</p>
                </div>
                <div class="bg-grey-2 rounded  shadow text-center py-6 px-4">
                    <h2 class="font-semibold text-3xl text-black">
                        {{ numberTrans(getFromObject(formCounts, "enumeratorFormCount", 0)) }}
                    </h2>
                    <p>{{ trans("admin-monitoring.form-type.enumerator_monitoring") }}</p>
                </div>
            </div>
            <div class="flex flex-wrap">
                <div class="w-full mb-12 xl:mb-0 pr-4">
                    <district-wise-monitoring v-if="districts" :districts="districts" :district-stats="formStats" :municipalities-stats="municipalityStats"/>
                </div>
            </div>
        </template>

        <empty-data v-else message="No record found."/>
    </admin-layout>
</template>

<script>
    import EmptyData              from "../../Components/DataTable/EmptyData"
    import AdminLayout            from "../../Layouts/AdminLayout"
    import DistrictWiseMonitoring from "./Partials/DistrictWiseMonitoring"

    export default {
        name: "MonitoringAdminDashboard",
        components: { DistrictWiseMonitoring, EmptyData, AdminLayout },
        props: {
            dateTime: { type: String, required: false, default: "" },
            formStats: { type: Object, required: false, default: () => ({}) },
            municipalityStats: { type: Object, required: false, default: () => ({}) },
            formTypes: { type: Array, required: false, default: () => ([]) },
            districts: { type: Array, required: false, default: () => ([]) },
        },
        computed: {
            formCounts: function() {
                const tmpVals = {
                    overallFormCount: 0,
                    supervisorFormCount: 0,
                    enumeratorFormCount: 0,
                }
                return Object.values(this.formStats).reduce((acc, cur) => {
                    acc.overallFormCount += this.getFromObject(cur, "overall_monitoring", 0)
                    acc.supervisorFormCount += this.getFromObject(cur, "supervisor_monitoring", 0)
                    acc.enumeratorFormCount += this.getFromObject(cur, "enumerator_monitoring", 0)
                    return acc
                }, tmpVals)
            },
        },
    }
</script>

<style scoped>

</style>
