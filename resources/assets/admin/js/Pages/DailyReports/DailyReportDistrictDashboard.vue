<template>
    <admin-layout :authorized="isAuthorized($page, 'daily_report_dashboard')" :page-title="trans('admin-general.modules.daily-report-dashboard')">
        <template #breadcrumb>
            <bread-crumb>
                <bread-crumb-item :label="trans('admin-general.modules.daily-report-dashboard')"/>
            </bread-crumb>
        </template>

        <div class="table-wrapper mt-6 shadow-md rounded-md overflow-x-auto overflow-y-hidden">
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
                    <fragment v-for="(censusOfficeStat, censusOfficeId) in stats" :key="'stat_'+censusOfficeId">
                        <tr class="text-center overflow-x-scroll bg-primary-50">
                            <td class="px-4 py-3 border-b text-left sticky left-0" nowrap>
                                {{ getFromObject(censusOffices, `${censusOfficeId}.office_name`) }}
                            </td>
                            <td class="px-4 py-3 border-b text-center" nowrap v-text="getTotalSurveyedByCensusOffice(censusOfficeStat)"/>
                            <td v-for="date in dates"
                                :key="'survey_'+date"
                                class="px-4 py-3 border-b text-center"
                                nowrap
                                v-text="getCensusOfficeSurveyedByDate(censusOfficeStat, date)"/>
                        </tr>
                        <fragment v-for="(supervisorStat, supervisorId) in censusOfficeStat" :key="`census_${supervisorId}`">
                            <tr class="text-center overflow-x-scroll">
                                <td class="text-left px-8 py-3 border-b ml-2 bg-white" nowrap>
                                    {{ getFromObject(users, `${supervisorId}.name`) }}
                                </td>
                                <td class="px-4 py-3 border-b text-center bg-white" nowrap v-text="getTotalSurveyedBySupervisor(supervisorStat)"/>
                                <td v-for="date in dates"
                                    :key="'survey_'+date"
                                    class="px-4 py-3 border-b text-center bg-white"
                                    :class="{'text-gray-400': !getSupervisorSurveyedByDate(supervisorStat, date)}"
                                    nowrap
                                    v-text="getSupervisorSurveyedByDate(supervisorStat, date)"/>
                            </tr>
                            <tr v-for="(enumeratorStat, enumeratorId) in supervisorStat" :key="`census_${enumeratorId}`" class="text-center overflow-x-scroll">
                                <td class="text-left px-12 py-3 border-b ml-2 bg-white" nowrap>
                                    {{ getFromObject(getEnumerator(supervisorId, enumeratorId), "name") }}
                                    <span class="text-success">
                                        ( {{ getFromObject(getEnumerator(supervisorId, enumeratorId), "target", 0) }} )
                                    </span>
                                </td>
                                <td class="px-4 py-3 border-b text-center bg-white" nowrap v-text="getTotalSurveyedByEnumerator(enumeratorStat)"/>
                                <td v-for="date in dates"
                                    :key="'survey_'+date"
                                    class="px-4 py-3 border-b text-center bg-white"
                                    :class="{'text-gray-400': !getEnumeratorSurveyedByDate(enumeratorStat, date)}"
                                    nowrap
                                    v-text="getEnumeratorSurveyedByDate(enumeratorStat, date)"/>
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
            getEnumeratorSurveyedByDate(enumeratorData, date) {
                return this.getFromObject(enumeratorData, date + ".0.total", 0)
            },

            getTotalSurveyedByEnumerator(enumeratorData) {
                return Object.values(enumeratorData).reduce((acc, cur) => {
                    return acc + cur["0"].total
                }, 0)
            },

            getSupervisorSurveyedByDate(supervisorData, date) {
                return Object.values(supervisorData).reduce((acc, enumeratorData) => {
                    return acc + this.getEnumeratorSurveyedByDate(enumeratorData, date)
                }, 0)
            },

            getTotalSurveyedBySupervisor(supervisorData) {
                return Object.values(supervisorData).reduce((acc, enumeratorData) => {
                    return acc + this.getTotalSurveyedByEnumerator(enumeratorData)
                }, 0)
            },

            getCensusOfficeSurveyedByDate(censusOffice, date) {
                return Object.values(censusOffice).reduce((acc, supervisorData) => {
                    return acc + this.getSupervisorSurveyedByDate(supervisorData, date)
                }, 0)
            },

            getTotalSurveyedByCensusOffice(censusOffice) {
                return Object.values(censusOffice).reduce((acc, supervisorData) => {
                    return acc + this.getTotalSurveyedBySupervisor(supervisorData)
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

            getEnumerator(surveyorId, enumeratorId) {
                const supervisor = this.users[surveyorId]
                if (!(supervisor.enumerators.length > 0)) {
                    return "Unidentified"
                }

                return supervisor.enumerators.find(el => parseInt(el.id) === parseInt(enumeratorId))
            },
        },
    }
</script>

<style scoped>

</style>
