<template>
    <admin-layout class="relative" page-title="Dashboard" :authorized="isAuthorized($page, 'dashboard')">
        <template v-if="dateTime">
            <div class="flex justify-between">
                <div class="text-sm" role="alert">
                    <span class="block sm:inline">Data as of</span>
                    <strong class="font-semibold">{{ dateTime }}</strong>
                </div>
                <div>
                    <a class="text-primary-500 hover:opacity-90 p-2" target="_blank" :href="route('admin.console.applications.export')">
                        Refresh application stats
                    </a>
                </div>
            </div>
            <div class="grid gap-8 stats-cards-wrapper my-6">
                <div class="bg-grey-2 rounded  shadow text-center py-6 px-4">
                    <h2 class="font-semibold text-3xl text-black">
                        {{ numberTrans(getApplicationTotal(overallStats.submitted) + getApplicationTotal(overallStats.draft)) }}
                    </h2>
                    <p>{{ trans("admin-general.total") }}</p>
                </div>
                <div class="bg-grey-2 rounded  shadow text-center py-6 px-4">
                    <h2 class="font-semibold text-3xl text-black">
                        {{ numberTrans(getApplicationTotal(overallStats.submitted)) }}
                    </h2>
                    <p>{{ trans(`application.application-status.submitted`) }}</p>
                </div>
                <div class="bg-grey-2 rounded  shadow text-center py-6 px-4">
                    <h2 class="font-semibold text-3xl text-black">
                        {{ numberTrans(getApplicationTotal(overallStats.draft)) }}
                    </h2>
                    <p>{{ trans(`application.application-status.draft`) }}</p>
                </div>
                <div class="bg-grey-2 rounded  shadow w-full lg:w-auto py-6 px-1 flex text-center justify-center items-center">
                    <div class="border-r w-1/3">
                        <h2 class="text-xl text-black font-semibold">
                            {{ numberTrans(getSum(genderDistribution.male)) }}
                        </h2>
                        <p>
                            {{ trans("application.gender-male") }}
                        </p>
                    </div>
                    <div class="border-r w-1/3">
                        <h2 class="text-xl text-black font-semibold">
                            {{ numberTrans(getSum(genderDistribution.female)) }}
                        </h2>
                        <p>
                            {{ trans("application.gender-female") }}
                        </p>
                    </div>
                    <div class="w-1/3">
                        <h2 class="text-xl text-black font-semibold">
                            {{ numberTrans(getSum(genderDistribution.other)) }}
                        </h2>
                        <p>
                            {{ trans("application.gender-other") }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="flex flex-wrap pr-2">
                <overall-application-status-chart v-if="overallStats" :overall-stats="overallStats" class="w-full md:w-1/2 pr-5"/>
                <gender-distribution-chart v-if="genderDistribution" :gender-distribution="genderDistribution" class="w-full md:w-1/2 pl-5"/>
                <applications-submitted-chart v-if="dailyTrend" :daily-trend="dailyTrend" class="w-full"/>
            </div>
        </template>

        <empty-data v-else message="No record found."/>
    </admin-layout>
</template>

<script>
    import EmptyData                     from "../../../../shared/js/Components/DataTable/EmptyData"
    import AdminLayout                   from "../../Layouts/AdminLayout"
    import ApplicationsSubmittedChart    from "./Partials/ApplicationsSubmittedChart"
    import GenderDistributionChart       from "./Partials/GenderDistributionChart"
    import OverallApplicationStatusChart from "./Partials/OverallApplicationStatusChart"

    export default {
        name: "Dashboard",

        components: {
            GenderDistributionChart,
            OverallApplicationStatusChart,
            EmptyData,
            ApplicationsSubmittedChart,
            AdminLayout,
        },

        props: {
            overallStats: { type: Object, required: false, default: () => ({}) },
            genderDistribution: { type: Object, required: false, default: () => ({}) },
            dailyTrend: { type: Object, required: false, default: () => ({}) },
            districtWise: { type: Object, required: false, default: () => ({}) },
            dateTime: { type: String, required: false, default: "" },
            districts: { type: Array, required: false, default: () => ([]) },
        },

        methods: {
            getApplicationTotal: function(row) {
                return (row.enumerator || 0) + (row.supervisor || 0) + (row.enumerator_supervisor || 0)
            },
            getSum(obj) {
                if (obj) {
                    return Object.values(obj).reduce((acc, cur) => acc + cur, 0)
                }
                return 0
            },
        },
    }
</script>
