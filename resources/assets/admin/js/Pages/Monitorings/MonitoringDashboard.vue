<template>
    <admin-layout class="relative" page-title="Monitoring Dashboard" :authorized="isAuthorized($page, 'monitoring_dashboard')">
        <template v-if="dateTime">
            <div class="grid gap-8 stats-cards-wrapper my-6">
                <div class="bg-grey-2 rounded  shadow text-center py-6 px-4">
                    <h2 class="font-semibold text-3xl text-black">
                        {{ numberTrans(getFromObject(formStats, "overall_monitoring.count", 0)) }}
                    </h2>
                    <p>{{ trans("admin-monitoring.form-type.overall_monitoring") }}</p>
                </div>
                <div class="bg-grey-2 rounded  shadow text-center py-6 px-4">
                    <h2 class="font-semibold text-3xl text-black">
                        {{ numberTrans(getFromObject(formStats, "supervisor_monitoring.count", 0)) }}
                    </h2>
                    <p>{{ trans("admin-monitoring.form-type.supervisor_monitoring") }}</p>
                </div>
                <div class="bg-grey-2 rounded  shadow text-center py-6 px-4">
                    <h2 class="font-semibold text-3xl text-black">
                        {{ numberTrans(getFromObject(formStats, "enumerator_monitoring.count", 0)) }}
                    </h2>
                    <p>{{ trans("admin-monitoring.form-type.enumerator_monitoring") }}</p>
                </div>
            </div>
            <div class="flex flex-wrap">
                <div class="w-full mb-12 xl:mb-0 pr-4">
                    <municipality-wise-monitoring
                        v-if="municipalityStats"
                        :municipalities="municipalities"
                        :district="district.district_code"
                        :municipalities-stats="municipalityStats"/>
                </div>
            </div>
        </template>

        <empty-data v-else message="No record found."/>
    </admin-layout>
</template>

<script>
    import EmptyData                  from "../../Components/DataTable/EmptyData"
    import AdminLayout                from "../../Layouts/AdminLayout"
    import MunicipalityWiseMonitoring from "./Partials/MunicipalityWiseMonitoring"

    export default {
        name: "MonitoringDashboard",
        components: { MunicipalityWiseMonitoring, EmptyData, AdminLayout },
        props: {
            dateTime: { type: String, required: false, default: "" },
            formStats: { type: Object, required: false, default: () => ({}) },
            municipalityStats: { type: Object, required: false, default: () => ({}) },
            formTypes: { type: Array, required: false, default: () => ([]) },
            district: { type: Object, required: false, default: () => ({}) },
        },
        computed: {
            municipalities: function() {
                const municipalities = this.getFromObject(this.district, "municipalities")
                if (!this.isArray(municipalities)) {
                    return []
                }
                return municipalities
            },
        },
    }
</script>

<style scoped>

</style>
