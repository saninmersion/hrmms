<template>
    <dashboard-stats-table title="Municipality-wise shortlisted stats">
        <template #table-head>
            <tr/>
            <tr>
                <dashboard-stats-table-head-col/>
                <dashboard-stats-table-head-col/>
                <dashboard-stats-table-head-col v-text="trans('application.success-message-post.enumerator')"/>
                <dashboard-stats-table-head-col/>
                <dashboard-stats-table-head-col/>
                <dashboard-stats-table-head-col v-text="trans('application.success-message-post.supervisor')"/>
                <dashboard-stats-table-head-col/>
            </tr>
            <tr>
                <dashboard-stats-table-head-col/>
                <dashboard-stats-table-head-col>Shortlisted</dashboard-stats-table-head-col>
                <dashboard-stats-table-head-col>Hired</dashboard-stats-table-head-col>
                <dashboard-stats-table-head-col>Rejected</dashboard-stats-table-head-col>
                <dashboard-stats-table-head-col>Shortlisted</dashboard-stats-table-head-col>
                <dashboard-stats-table-head-col>Hired</dashboard-stats-table-head-col>
                <dashboard-stats-table-head-col>Rejected</dashboard-stats-table-head-col>
            </tr>
        </template>

        <template
            v-if="municipalities && isArray(municipalities)"
            #table-body>
            <tr v-for="(municipality, index) in municipalities" :key="index">
                <dashboard-stats-table-col class="text-left text-sm" v-text="getTitle(municipality)"/>
                <dashboard-stats-table-col class="text-left" v-text="numberTrans(getShortlisted(municipality, 'enumerator'))"/>
                <dashboard-stats-table-col class="text-left" v-text="numberTrans(getHired(municipality, 'enumerator'))"/>
                <dashboard-stats-table-col class="text-left" v-text="numberTrans(getRejected(municipality, 'enumerator'))"/>
                <dashboard-stats-table-col class="text-left" v-text="numberTrans(getShortlisted(municipality, 'supervisor'))"/>
                <dashboard-stats-table-col class="text-left" v-text="numberTrans(getHired(municipality, 'supervisor'))"/>
                <dashboard-stats-table-col class="text-left" v-text="numberTrans(getRejected(municipality, 'supervisor'))"/>
            </tr>
        </template>
    </dashboard-stats-table>
</template>

<script>
    import DashboardStatsTable from "./DashboardStatsTable"
    import DashboardStatsTableCol     from "../../../Components/DataTable/TableBodyCol"
    import DashboardStatsTableHeadCol from "../../../Components/DataTable/TableHeadCol"
    export default {
        name: "MunicipalityWiseShortlistedStats",
        components: { DashboardStatsTable, DashboardStatsTableHeadCol, DashboardStatsTableCol },
        props: {
            hiringStats: { type: Object, required: false, default: () => ({}) },
            municipalities: { type: Array, required: false, default: () => ([]) },
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
            getShortlisted(municipality, role) {
                return this.getFromObject(this.hiringStats, `${municipality.code}.${role}.total_count`, 0);
            },
            getHired(municipality, role) {
                return this.getFromObject(this.hiringStats, `${municipality.code}.${role}.hired`, 0);
            },
            getRejected(municipality, role) {
                return this.getFromObject(this.hiringStats, `${municipality.code}.${role}.rejected`, 0);
            },
        },
    }
</script>

<style scoped>

</style>
