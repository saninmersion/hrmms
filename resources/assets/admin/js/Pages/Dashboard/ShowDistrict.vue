<template>
    <admin-layout :page-title="title" :authorized="isAuthorized($page, 'district_dashboard')">
        <template #breadcrumb>
            <div/>
        </template>
        <template #actions>
            <drop-down-input :value="districtChoice" :options="districtOptions" class="!w-56" @input="selectDistrict"/>
        </template>
        <template v-if="dateTime">
            <div class="flex justify-end" role="alert">
                <span class="block sm:inline">Data as of</span>
                <strong class="font-semibold">{{ dateTime }}</strong>
            </div>
            <div class="grid gap-8 stats-cards-wrapper my-6">
                <div class="bg-grey-2 rounded  shadow text-center py-6 px-4">
                    <h2 class="font-bold text-3xl text-black">
                        {{ numberTrans(getApplicationTotal(getFromObject(overallStats, "submitted", 0)) + getApplicationTotal(getFromObject(overallStats, "draft", 0))) }}
                    </h2>
                    <p>{{ trans("admin-general.total") }}</p>
                </div>
                <div class="bg-grey-2 rounded  shadow text-center py-6 px-4">
                    <h2 class="font-bold text-3xl text-black">
                        {{ numberTrans(getApplicationTotal(getFromObject(overallStats, "submitted", 0))) }}
                    </h2>
                    <p>{{ trans(`application.application-status.submitted`) }}</p>
                </div>
                <div class="bg-grey-2 rounded  shadow text-center py-6 px-4">
                    <h2 class="font-bold text-3xl text-black">
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
            <div class="flex flex-wrap pr-2">
                <overall-application-status-chart v-if="overallStats" :overall-stats="overallStats" class="w-full md:w-1/2 pr-5"/>
                <gender-distribution-chart v-if="genderDistribution" :gender-distribution="genderDistribution" class="w-full md:w-1/2 pl-5"/>
            </div>
            <div class="mt-4">
                <municipality-wise-distribution v-if="municipalitiesStats" :municipalities="municipalities" :municipalities-stats="municipalitiesStats" :hiring-stats="hiringStats"/>
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
    import DropDownInput                    from "../../Components/Forms/DropDownInput"
    import AdminLayout                      from "../../Layouts/AdminLayout"
    import GenderDistributionChart          from "./Partials/GenderDistributionChart"
    import MunicipalityWiseDistribution     from "./Partials/MunicipalityWiseDistribution"
    import MunicipalityWiseShortlistedStats from "./Partials/MunicipalityWiseShortlistedStats"
    import OverallApplicationStatusChart    from "./Partials/OverallApplicationStatusChart"

    export default {
        name: "ShowDistrict",

        components: {
            MunicipalityWiseShortlistedStats,
            GenderDistributionChart,
            OverallApplicationStatusChart,
            DropDownInput,
            MunicipalityWiseDistribution,
            EmptyData,
            AdminLayout,
        },

        props: {
            title: { type: String, required: false, default: "" },
            overallStats: { type: Object, required: false, default: () => ({}) },
            genderDistribution: { type: Object, required: false, default: () => ({}) },
            dateTime: { type: String, required: false, default: "" },
            municipalitiesStats: { type: Object, required: false, default: () => ({}) },
            hiringStats: { type: Object, required: false, default: () => ({}) },
            districts: { type: Array, required: true },
            district: { type: Object, required: true },
        },
        data() {
            return {
                districtChoice: null,
            }
        },
        watch: {
            title: {
                handler(title) {
                    const tmpDistrict = this.districts.find(district => district.title_en === title || district.title_ne === title)
                    this.$set(this, "districtChoice", Number(tmpDistrict.code))
                },
                immediate: true,
            },
        },
        computed: {
            districtOptions: function() {
                const options = new Map()
                const key = this.$currentLocale === "en" ? "title_en" : "title_ne"

                this.districts.forEach(district => {
                    options.set(parseInt(district.code), district[key])
                })

                return options
            },
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
