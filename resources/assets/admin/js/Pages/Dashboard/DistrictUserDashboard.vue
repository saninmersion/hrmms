<template>
    <admin-layout page-title="Dashboard" :authorized="isAuthorized($page, 'dashboard')">
        <template #breadcrumb>
            <div/>
        </template>
        <template v-if="dateTime">
            <div class="flex justify-end my-4" role="alert">
                <span class="block sm:inline">Data as of</span>
                <strong class="font-bold">{{ dateTime }}</strong>
            </div>
            <div class="grid gap-8 stats-cards-wrapper  my-6">
                <div class="bg-grey-2 rounded  shadow text-center py-6 px-4">
                    <h2 class="font-semibold text-3xl text-black">
                        {{ numberTrans(getApplicationTotal(getFromObject(overallStats, "submitted", 0)) + getApplicationTotal(getFromObject(overallStats, "draft", 0))) }}
                    </h2>
                    <p>{{ trans("admin-general.total") }}</p>
                </div>
                <div class="bg-grey-2 rounded  shadow text-center py-6 px-4">
                    <h2 class="font-semibold text-3xl text-black">
                        {{ numberTrans(getApplicationTotal(getFromObject(overallStats, "submitted", 0))) }}
                    </h2>
                    <p>{{ trans(`application.application-status.submitted`) }}</p>
                </div>
                <div class="bg-grey-2 rounded  shadow text-center py-6 px-4">
                    <h2 class="font-semibold text-3xl text-black">
                        {{ numberTrans(getApplicationTotal(getFromObject(overallStats, "draft", 0))) }}
                    </h2>
                    <p>{{ trans(`application.application-status.draft`) }}</p>
                </div>
                <div class="bg-grey-2 rounded  shadow w-full lg:w-auto py-6 px-1 flex text-center justify-center items-center">
                    <div class="border-r w-1/3">
                        <h2 class="text-xl text-black font-semibold">
                            {{ numberTrans(getSum(getFromObject(genderDistribution, "male", {}))) }}
                        </h2>
                        <p>
                            {{ trans("application.gender-male") }}
                        </p>
                    </div>
                    <div class="border-r w-1/3">
                        <h2 class="text-xl text-black font-semibold">
                            {{ numberTrans(getSum(getFromObject(genderDistribution, "female", {}))) }}
                        </h2>
                        <p>
                            {{ trans("application.gender-female") }}
                        </p>
                    </div>
                    <div class="w-1/3">
                        <h2 class="text-xl text-black font-semibold">
                            {{ numberTrans(getSum(getFromObject(genderDistribution, "other", {}))) }}
                        </h2>
                        <p>
                            {{ trans("application.gender-other") }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="flex mt-4 flex-wrap">
                <overall-application-status-chart v-if="overallStats" :overall-stats="overallStats" class="w-full md:w-1/2 mb-12 xl:mb-0 pr-4"/>
                <gender-distribution-chart v-if="genderDistribution" :gender-distribution="genderDistribution" class="w-full md:w-1/2 mb-12 xl:mb-0 pr-4"/>
            </div>
            <div class="flex mt-4 flex-wrap">
                <div class="w-full mb-12 xl:mb-0 pr-4">
                    <municipality-wise-distribution v-if="municipalitiesStats" :municipalities="municipalities" :municipalities-stats="municipalitiesStats"/>
                </div>
            </div>
            <div class="flex mt-4 flex-wrap">
                <div class="w-full mb-12 xl:mb-0 pr-4">
                    <municipality-wise-shortlisted-stats v-if="hiringStats" :municipalities="municipalities" :hiring-stats="hiringStats"/>
                </div>
            </div>
        </template>
        <empty-data v-else message="No record found."/>
    </admin-layout>
</template>

<script>
    import EmptyData                        from "../../../../shared/js/Components/DataTable/EmptyData"
    import AdminLayout                      from "../../Layouts/AdminLayout"
    import GenderDistributionChart          from "./Partials/GenderDistributionChart"
    import MunicipalityWiseDistribution     from "./Partials/MunicipalityWiseDistribution"
    import MunicipalityWiseShortlistedStats from "./Partials/MunicipalityWiseShortlistedStats"
    import OverallApplicationStatusChart    from "./Partials/OverallApplicationStatusChart"

    export default {
        name: "DistrictUserDashboard",

        components: {
            MunicipalityWiseShortlistedStats,
            GenderDistributionChart,
            OverallApplicationStatusChart,
            MunicipalityWiseDistribution,
            EmptyData,
            AdminLayout,
        },

        props: {
            overallStats: { type: Object, required: false, default: () => ({}) },
            genderDistribution: { type: Object, required: false, default: () => ({}) },
            dateTime: { type: String, required: false, default: "" },
            municipalitiesStats: { type: Object, required: false, default: () => ({}) },
            hiringStats: { type: Object, required: false, default: () => ({}) },
            district: { type: Object, required: false, default: () => ({}) },
        },
        data() {
            return {
                districtChoice: null,
            }
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
        methods: {
            getApplicationTotal: function(row) {
                return (row.enumerator || 0) + (row.supervisor || 0) + (row.enumerator_supervisor || 0)
            },
            getSum(obj) {
                return Object.entries(obj).reduce((acc, [key, val]) => {
                    if (key === "na") {
                        return acc
                    }
                    return acc + val
                }, 0)
            },
            selectDistrict(district) {
                this.$inertia.visit(this.route("admin.districts.show", district))
            },
        },
    }
</script>
