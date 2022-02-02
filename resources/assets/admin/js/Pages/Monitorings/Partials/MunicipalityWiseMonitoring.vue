<template>
    <dashboard-stats-table title="Municipality-wise Monitoring Forms">
        <template #table-head>
            <tr/>
            <tr>
                <dashboard-stats-table-head-col/>
                <dashboard-stats-table-head-col v-text="trans('admin-monitoring.form-type.overall_monitoring')"/>
                <dashboard-stats-table-head-col v-text="trans('admin-monitoring.form-type.supervisor_monitoring')"/>
                <dashboard-stats-table-head-col v-text="trans('admin-monitoring.form-type.enumerator_monitoring')"/>
            </tr>
        </template>

        <template
            v-if="municipalities && isArray(municipalities)"
            #table-body>
            <tr v-for="(municipality, index) in municipalities" :key="index">
                <dashboard-stats-table-col class="text-left text-sm" v-text="getTitle(municipality)"/>
                <dashboard-stats-table-col class="text-left">
                    <inertia-link
                        :class="{'text-primary-500': getFromObject(municipalitiesStats, `${getMunicipalityCode(municipality)}.overall_monitoring`, null)}"
                        :href="goToMonitoringList('overall_monitoring', district, getMunicipalityCode(municipality))"
                        v-text="numberTrans(getFromObject(municipalitiesStats, `${getMunicipalityCode(municipality)}.overall_monitoring`, '-'))"/>
                </dashboard-stats-table-col>
                <dashboard-stats-table-col class="text-left">
                    <inertia-link
                        :class="{'text-primary-500': getFromObject(municipalitiesStats, `${getMunicipalityCode(municipality)}.supervisor_monitoring`, null)}"
                        :href="goToMonitoringList('supervisor_monitoring', district, getMunicipalityCode(municipality))"
                        v-text="numberTrans(getFromObject(municipalitiesStats, `${getMunicipalityCode(municipality)}.supervisor_monitoring`, '-'))"/>
                </dashboard-stats-table-col>
                <dashboard-stats-table-col class="text-left">
                    <inertia-link
                        :class="{'text-primary-500': getFromObject(municipalitiesStats, `${getMunicipalityCode(municipality)}.enumerator_monitoring`, null)}"
                        :href="goToMonitoringList('enumerator_monitoring', district, getMunicipalityCode(municipality))"
                        v-text="numberTrans(getFromObject(municipalitiesStats, `${getMunicipalityCode(municipality)}.enumerator_monitoring`, '-'))"/>
                </dashboard-stats-table-col>
            </tr>
        </template>
    </dashboard-stats-table>
</template>

<script>
    import DashboardStatsTableCol     from "../../../Components/DataTable/TableBodyCol"
    import DashboardStatsTableHeadCol from "../../../Components/DataTable/TableHeadCol"
    import DashboardStatsTable        from "../../Dashboard/Partials/DashboardStatsTable"

    export default {
        name: "MunicipalityWiseMonitoring",
        components: { DashboardStatsTable, DashboardStatsTableCol, DashboardStatsTableHeadCol },
        props: {
            municipalitiesStats: { type: Object, required: false, default: () => ({}) },
            municipalities: { type: Array, required: false, default: () => ([]) },
            district: { type: Number, required: true },
        },
        methods: {
            getTitle(municipality) {
                if (!municipality) {
                    return ""
                }

                return this.$currentLocale === "en"
                    ? this.getFromObject(municipality, "title_en", "")
                    : this.getFromObject(municipality, "title_ne", "")
            },
            getMunicipalityCode(municipality) {
                if (!municipality) {
                    return ""
                }
                return this.getFromObject(municipality, "code", "")
            },
            goToMonitoringList(monitoringType, districtCode, municipalityCode = null) {
                return this.route("admin.monitorings.index", {
                    _query: {
                        district_code: districtCode,
                        municipality_code: municipalityCode,
                        form_type: monitoringType,
                    },
                })
            },
        },
    }
</script>

<style scoped>

</style>
