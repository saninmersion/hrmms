<template>
    <admin-layout :authorized="isAuthorized($page, 'daily_report_dashboard')" :page-title="trans('admin-general.modules.daily-report-dashboard')">
        <template #breadcrumb>
            <bread-crumb>
                <bread-crumb-item :label="trans('admin-general.modules.daily-report-dashboard')"/>
            </bread-crumb>
        </template>

        <div class="table-wrapper mt-6 shadow-md rounded-md overflow-x-auto overflow-y-hidden">
            <div class="px-4 py-3">
                <ul>
                    <li>*</li>
                    <li><span class="font-bold">DW</span> - Dwelling (House) Count</li>
                    <li><span class="font-bold">CDW</span> - Census Dwelling (House) Count</li>
                    <li><span class="font-bold">CHH</span> - Census Households Count</li>
                </ul>
            </div>
            <table class="w-full leading-normal">
                <thead class="bg-grey-2 text-left font-bold">
                    <tr class="text-center overflow-x-scroll">
                        <th class="bg-grey-2 sticky left-0"/>
                        <th class="px-3 py-4 text-center text-base-1 uppercase tracking-wider">
                            Total <br>
                            <span class="border-r-2 px-1" v-text="'DW'"/>
                            <span class="border-r-2 px-1" v-text="'CDW'"/>
                            <span class="px-1" v-text="'CHH'"/>
                        </th>
                        <th v-for="date in dates"
                            :key="date"
                            nowrap
                            class="px-3 py-4 text-center text-base-1 uppercase tracking-wider"
                            v-text="date"/>
                    </tr>
                </thead>
                <tbody>
                    <fragment v-for="(censusOfficeStat, censusOfficeId) in stats" :key="'stat_'+censusOfficeId">
                        <tr class="text-center overflow-x-scroll bg-primary-50">
                            <td class="px-4 py-3 border-b text-left sticky left-0" nowrap>
                                {{ getFromObject(censusOffices, `${censusOfficeId}.office_name`) }}
                            </td>
                            <td class="px-4 py-3 border-b text-center" nowrap>
                                <span class="border-r-2 px-1" v-text="getTotalSurveyedByCensusOffice(censusOfficeStat, 'total')"/>
                                <span class="border-r-2 px-1" v-text="getTotalSurveyedByCensusOffice(censusOfficeStat, 'total_houses_in_census')"/>
                                <span class="px-1" v-text="getTotalSurveyedByCensusOffice(censusOfficeStat, 'total_families_in_census')"/>
                            </td>
                            <td v-for="date in dates"
                                :key="'survey_'+date"
                                class="px-4 py-3 border-b text-center"
                                nowrap>
                                <span class="border-r-2 px-1" v-text="getCensusOfficeSurveyedByDate(censusOfficeStat, date, 'total')"/>
                                <span class="border-r-2 px-1" v-text="getCensusOfficeSurveyedByDate(censusOfficeStat, date, 'total_houses_in_census')"/>
                                <span class="px-1" v-text="getCensusOfficeSurveyedByDate(censusOfficeStat, date, 'total_families_in_census')"/>
                            </td>
                        </tr>
                        <fragment v-for="(supervisorStat, supervisorId) in censusOfficeStat" :key="`census_${supervisorId}`">
                            <tr class="text-center overflow-x-scroll">
                                <td class="text-left px-8 py-3 border-b ml-2 bg-white" nowrap>
                                    {{ getFromObject(users, `${supervisorId}.name`) }}
                                </td>
                                <td class="px-4 py-3 border-b text-center bg-white" nowrap>
                                    <span class="border-r-2 px-1" v-text="getTotalSurveyedBySupervisor(supervisorStat, 'total')"/>
                                    <span class="border-r-2 px-1" v-text="getTotalSurveyedBySupervisor(supervisorStat, 'total_houses_in_census')"/>
                                    <span class="px-1" v-text="getTotalSurveyedBySupervisor(supervisorStat, 'total_families_in_census')"/>
                                </td>
                                <td v-for="date in dates"
                                    :key="'survey_'+date"
                                    class="px-4 py-3 border-b text-center bg-white"
                                    :class="{'text-gray-400': !getSupervisorSurveyedByDate(supervisorStat, date)}"
                                    nowrap>
                                    <span class="border-r-2 px-1" v-text="getSupervisorSurveyedByDate(supervisorStat, date, 'total')"/>
                                    <span class="border-r-2 px-1" v-text="getSupervisorSurveyedByDate(supervisorStat, date, 'total_houses_in_census')"/>
                                    <span class="px-1" v-text="getSupervisorSurveyedByDate(supervisorStat, date, 'total_families_in_census')"/>
                                </td>
                            </tr>
                        </fragment>
                    </fragment>
                </tbody>
            </table>
        </div>
    </admin-layout>
</template>

<script>
    import BreadCrumb     from "../../Components/BreadCrumb/BreadCrumb"
    import BreadCrumbItem from "../../Components/BreadCrumb/BreadCrumbItem"
    import AdminLayout    from "../../Layouts/AdminLayout"

    export default {
        name: "DailyReportDistrictDashboard",
        components: { BreadCrumb, BreadCrumbItem, AdminLayout },
        props: {
            stats: { type: Object, required: true },
            districts: { type: Object, required: true },
            censusOffices: { type: Object, required: true },
            users: { type: Object, required: true },
            dates: { type: Array, required: true },
        },
        methods: {
            getSupervisorSurveyedByDate(supervisorData, date, key = "total") {
                return this.getFromObject(supervisorData, date + `.0.[${key}]`, 0)
            },

            getCensusOfficeSurveyedByDate(censusOffice, date, key = "total") {
                return Object.values(censusOffice).reduce((acc, supervisorData) => {
                    return acc + this.getSupervisorSurveyedByDate(supervisorData, date, key)
                }, 0)
            },

            getDistrictTotalByDate(district, date, key = "total") {
                return Object.values(district).reduce((acc, censusOffice) => {
                    return acc + this.getFromObject(censusOffice, date + `.0[${key}]`, 0)
                }, 0)
            },

            getTotalSurveyedBySupervisor(supervisorData, key = "total") {
                return Object.values(supervisorData).reduce((acc, cur) => {
                    return acc + cur["0"][key]
                }, 0)
            },

            getTotalSurveyedByCensusOffice(censusOffice, key = "total") {
                return Object.values(censusOffice).reduce((acc, supervisorData) => {
                    return acc + this.getTotalSurveyedBySupervisor(supervisorData, key)
                }, 0)
            },

            getDistrictTotal(district, key = "total") {
                return Object.values(district).reduce((acc, censusOffice) => {
                    return acc + this.getCensusOfficeTotal(censusOffice, key)
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
