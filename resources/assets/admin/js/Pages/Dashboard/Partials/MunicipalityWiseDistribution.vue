<template>
    <dashboard-stats-table title="Municipality-wise distribution of Submitted applications">
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
                <dashboard-stats-table-head-col v-text="trans('application.success-message-post.enumerator_supervisor')"/>
                <dashboard-stats-table-head-col/>
            </tr>
            <tr>
                <dashboard-stats-table-head-col/>
                <dashboard-stats-table-head-col>Need</dashboard-stats-table-head-col>
                <dashboard-stats-table-head-col>P1</dashboard-stats-table-head-col>
                <dashboard-stats-table-head-col>P2</dashboard-stats-table-head-col>
                <dashboard-stats-table-head-col>Need</dashboard-stats-table-head-col>
                <dashboard-stats-table-head-col>P1</dashboard-stats-table-head-col>
                <dashboard-stats-table-head-col>P2</dashboard-stats-table-head-col>
                <dashboard-stats-table-head-col>P1</dashboard-stats-table-head-col>
                <dashboard-stats-table-head-col>P2</dashboard-stats-table-head-col>
            </tr>
        </template>

        <template
            v-if="municipalities && isArray(municipalities)"
            #table-body>
            <tr v-for="(municipality, index) in municipalities" :key="index">
                <dashboard-stats-table-col class="text-left text-sm" v-text="getTitle(municipality)"/>
                <dashboard-stats-table-col class="text-left">
                    -
                </dashboard-stats-table-col>
                <dashboard-stats-table-col class="text-left" v-text="numberTrans(getValue(municipality, 'first', 'enumerator'))"/>
                <dashboard-stats-table-col class="text-left" v-text="numberTrans(getValue(municipality, 'second', 'enumerator'))"/>
                <dashboard-stats-table-col class="text-left">
                    -
                </dashboard-stats-table-col>
                <dashboard-stats-table-col class="text-left" v-text="numberTrans(getValue(municipality, 'first', 'supervisor'))"/>
                <dashboard-stats-table-col class="text-left" v-text="numberTrans(getValue(municipality, 'second', 'supervisor'))"/>
                <dashboard-stats-table-col class="text-left" v-text="numberTrans(getValue(municipality, 'first', 'enumerator_supervisor'))"/>
                <dashboard-stats-table-col class="text-left" v-text="numberTrans(getValue(municipality, 'second', 'enumerator_supervisor'))"/>
            </tr>
        </template>
    </dashboard-stats-table>
</template>

<script>
    import DashboardStatsTableCol     from "../../../Components/DataTable/TableBodyCol"
    import DashboardStatsTableHeadCol from "../../../Components/DataTable/TableHeadCol"
    import DashboardStatsTable        from "./DashboardStatsTable"

    export default {
        name: "MunicipalityWiseDistribution",
        components: { DashboardStatsTable, DashboardStatsTableHeadCol, DashboardStatsTableCol },
        props: {
            municipalitiesStats: { type: Object, required: false, default: () => ({}) },
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
            getValue(municipality, priority, job) {
                const code = this.getFromObject(municipality, "code")
                return this.getFromObject(this.municipalitiesStats, `${priority}.${code}.${job}`, 0)
            },
        },
    }
</script>

<style scoped>

</style>
