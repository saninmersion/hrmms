<template>
    <admin-layout :authorized="isAuthorized($page, 'daily_report_dashboard')" :page-title="trans('admin-general.modules.daily-report-dashboard')">
        <template #breadcrumb>
            <bread-crumb>
                <bread-crumb-item :label="trans('admin-general.modules.daily-report-dashboard')"/>
            </bread-crumb>
        </template>

        <div v-if="showTable" class="table-wrapper mt-6 shadow-md rounded-md overflow-x-auto overflow-y-hidden">
            <table class="w-full leading-normal">
                <thead class="bg-grey-2 text-left font-bold">
                    <tr class="text-center overflow-x-scroll">
                        <th class="bg-grey-2 sticky left-0"/>
                        <th class="px-3 py-4 text-center text-base-1 uppercase tracking-wider">
                            Total
                        </th>
                        <th v-for="date in dates"
                            :key="date"
                            nowrap
                            class="px-3 py-4 text-center text-base-1 uppercase tracking-wider"
                            v-text="date"/>
                    </tr>
                </thead>
                <tbody>
                    <fragment v-for="(districtStat, district_code) in stats" :key="'stat_'+district_code">
                        <tr class="text-center overflow-x-scroll bg-primary-50">
                            <td class="px-4 py-3 border-b text-left sticky left-0 bg-primary-50" nowrap>
                                {{ getFromObject(districts, `${district_code}.title_en`) }}
                            </td>
                            <td class="px-4 py-3 border-b text-center" nowrap>
                                <inertia-link
                                    class="p-4 w-1/4 !text-center"
                                    :class="{'text-primary-500': getDistrictTotal(districtStat)}"
                                    :href="goToDailyReportList(district_code, 'all')"
                                    v-text="getDistrictTotal(districtStat)"/>
                            </td>
                            <td v-for="date in dates"
                                :key="'survey_'+date"
                                class="px-4 py-3 border-b text-center"
                                nowrap
                                v-text="getDistrictTotalByDate(districtStat, date)"/>
                        </tr>
                        <tr v-for="(censusOffice, censusOfficeId) in districtStat" :key="`census_${censusOfficeId}`" class="text-center overflow-x-scroll">
                            <td class="text-left px-8 py-3 border-b ml-2 bg-white sticky left-0" nowrap>
                                District Census Office - {{ getFromObject(censusOffices, `${censusOfficeId}.office_name`) }}
                            </td>
                            <td class="px-4 py-3 border-b text-center bg-white" nowrap>
                                <inertia-link
                                    class="p-4 w-1/4 !text-center"
                                    :class="{'text-primary-500': getCensusOfficeTotal(censusOffice)}"
                                    :href="goToDailyReportList(district_code, censusOfficeId)"
                                    v-text="getCensusOfficeTotal(censusOffice)"/>
                            </td>
                            <td v-for="date in dates"
                                :key="'survey_'+date"
                                class="px-4 py-3 border-b text-center bg-white"
                                :class="{'text-gray-400': !getCensusOfficeSurveyed(censusOffice, date)}"
                                nowrap
                                v-text="getCensusOfficeSurveyed(censusOffice, date)"/>
                        </tr>
                    </fragment>
                </tbody>
            </table>
        </div>

        <div v-else>
            <div class="flex justify-center items-center flex-col" style="height: 400px;">
                <i class="fas fa-calendar-day px-4 py-3 fa-4x font-medium"/>
                <span class="text-xl px-4 py-3">No Daily Reports Data Found</span>
            </div>
        </div>
    </admin-layout>
</template>

<script>
    import { isEmpty }    from "lodash"
    import BreadCrumb     from "../../Components/BreadCrumb/BreadCrumb"
    import BreadCrumbItem from "../../Components/BreadCrumb/BreadCrumbItem"
    import AdminLayout    from "../../Layouts/AdminLayout"

    export default {
        name: "DailyReportAdminDashboard",

        components: { BreadCrumb, BreadCrumbItem, AdminLayout },

        props: {
            stats: { type: Object, required: true, default: () => {} },
            districts: { type: Object, required: true },
            censusOffices: { type: Object, required: true },
            dates: { type: Array, required: true },
        },

        computed: {
            showTable() {
                return !isEmpty(this.stats)
            },
        },

        methods: {
            getCensusOfficeSurveyed(censusOffice, date) {
                return this.getFromObject(censusOffice, date + ".0.total", 0)
            },

            getCensusOfficeTotal(censusOffice) {
                return Object.values(censusOffice).reduce((acc, cur) => {
                    return acc + cur["0"].total
                }, 0)
            },

            getDistrictTotal(district) {
                return Object.values(district).reduce((acc, censusOffice) => {
                    return acc + this.getCensusOfficeTotal(censusOffice)
                }, 0)
            },

            getDistrictTotalByDate(district, date) {
                return Object.values(district).reduce((acc, censusOffice) => {
                    return acc + this.getFromObject(censusOffice, date + ".0.total", 0)
                }, 0)
            },

            goToDailyReportList(districtCode, censusOffice) {
                return this.route("admin.daily-reports.index", {
                    _query: {
                        district_code: districtCode,
                        census_office_id: censusOffice,
                    },
                })
            },
        },
    }
</script>

<style scoped>

</style>
