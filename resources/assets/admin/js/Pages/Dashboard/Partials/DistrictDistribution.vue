<template>
    <dashboard-stats-table title="Districts based Distribution of Submitted Applicants">
        <template #table-head>
            <tr>
                <dashboard-stats-table-head-col rowspan="2"/>
                <dashboard-stats-table-head-col colspan="2" v-text="trans('application.success-message-post.enumerator')"/>
                <dashboard-stats-table-head-col colspan="2" v-text="trans('application.success-message-post.supervisor')"/>
                <dashboard-stats-table-head-col colspan="4" v-text="trans('application.success-message-post.enumerator_supervisor')"/>
            </tr>
            <tr>
                <dashboard-stats-table-head-col v-text="'P1'"/>
                <dashboard-stats-table-head-col v-text="'P2'"/>
                <dashboard-stats-table-head-col v-text="'P1'"/>
                <dashboard-stats-table-head-col v-text="'P2'"/>
                <dashboard-stats-table-head-col v-text="'P1'"/>
                <dashboard-stats-table-head-col v-text="'P2'"/>
                <dashboard-stats-table-head-col v-text="trans('admin-general.hired-enumerators')"/>
                <dashboard-stats-table-head-col v-text="trans('admin-general.hired-supervisors')"/>
            </tr>
        </template>

        <template #table-body>
            <tr v-for="(district, index) in districts"
                :key="index"
                class="cursor-pointer"
                @click.prevent="handleRowClick(district)">
                <dashboard-stats-table-col class="text-left text-base font-medium">
                    {{ district["title_en"] }}
                </dashboard-stats-table-col>
                <dashboard-stats-table-col v-text="getStats(district.code, 'first', 'enumerator')"/>
                <dashboard-stats-table-col v-text="getStats(district.code, 'second', 'enumerator')"/>
                <dashboard-stats-table-col v-text="getStats(district.code, 'first', 'supervisor')"/>
                <dashboard-stats-table-col v-text="getStats(district.code, 'second', 'supervisor')"/>
                <dashboard-stats-table-col v-text="getStats(district.code, 'first', 'enumerator_supervisor')"/>
                <dashboard-stats-table-col v-text="getStats(district.code, 'second', 'enumerator_supervisor')"/>
                <dashboard-stats-table-col v-text="getHired(district.code, 'enumerator')"/>
                <dashboard-stats-table-col v-text="getHired(district.code, 'supervisor')"/>
            </tr>
        </template>
    </dashboard-stats-table>
</template>

<script>
    import DashboardStatsTableCol     from "../../../Components/DataTable/TableBodyCol"
    import DashboardStatsTableHeadCol from "../../../Components/DataTable/TableHeadCol"
    import DashboardStatsTable        from "./DashboardStatsTable"

    export default {
        name: "DistrictDistribution",

        components: { DashboardStatsTableHeadCol, DashboardStatsTableCol, DashboardStatsTable },

        props: {
            districtWise: { type: Object, required: false, default: () => ({}) },
            districts: { type: Array, required: false, default: () => ([]) },
            hiredStats: { type: Object, required: false, default: () => ({}) },
        },

        methods: {
            getStats(district, priority, type) {
                return this.getFromObject(this.districtWise, `${priority}.${district}.${type}`, 0)
            },
            getHired(district, role) {
                return this.getFromObject(this.hiredStats, `${district}.${role}.hired`, 0)
            },
            handleRowClick(district) {
                this.$inertia.visit(this.route("admin.dashboard.districts.show", district.code))
            },
        },
    }
</script>
