<template>
    <admin-layout :authorized="isAuthorized($page, 'daily_report_dashboard')"
                  :page-title="trans('admin-general.modules.daily-report-dashboard')">
        <template #breadcrumb>
            <bread-crumb>
                <bread-crumb-item :label="trans('admin-general.modules.daily-report-dashboard')"/>
            </bread-crumb>
        </template>
        <div v-if="isAuthorized($page, 'house_report_export')"
             class="downloads flex flex-wrap md:justify-end  gap-4 md:gap-8 mt-1 ">
            <a :href="route('admin.house-daily-reports.exports.daily-listing')"
               class="flex  text-primary-500 hover:opacity-90"
               target="_blank">
                <Icon name="download"/>
                <span class="block -mt-1">
                    Download Daily House Listing Summary
                </span>
            </a>
        </div>
        <div class="grid gap-6">
            <div class="py-3">
                <ul>
                    <li> Total Dwelling (House) Count - DW = {{ formatWithCommas(totalCount) }}</li>
                    <li> Total Census Dwelling (House) Count - CDW = {{ formatWithCommas(totalDwellingsCount) }}</li>
                    <li> Total Census Households Count - CHH = {{ formatWithCommas(totalHouseholdsCount) }}</li>
                </ul>
            </div>
            <LargeTable v-if="showTable" v :dates="dates">
                <accordion>
                    <AccordionItem v-for="(province, index) in stats" :key="index">
                        <template #accordion-trigger>
                            <table>
                                <thead>
                                    <tr class="bg-primary-50 text-base-1 tracking-wider font-bold">
                                        <th class="px-4 py-3 bg-grey-2 border-b" v-text="getFromObject(province, 'title_ne', '')"/>
                                        <Td :total="getProvinceTotal(province, 'total')"
                                            :total-house="getProvinceTotal(province, 'total_houses_in_census')"
                                            :total-families="getProvinceTotal(province, 'total_families_in_census')"/>
                                        <Td v-for="date in dates"
                                            :key="date"
                                            :total="getProvinceTotalByDate(province, date, 'total')"
                                            :total-house="getProvinceTotalByDate(province, date, 'total_houses_in_census')"
                                            :total-families="getProvinceTotalByDate(province, date, 'total_families_in_census')"/>
                                    </tr>
                                </thead>
                            </table>
                        </template>
                        <template #accordion-content>
                            <table>
                                <tbody>
                                    <fragment v-for="(districtStat, district_code) in province.districts"
                                              :key="'stat_'+district_code">
                                        <tr class="bg-grey-5">
                                            <td v-text="getFromObject(districts, `${district_code}.title_en`)"/>
                                            <Td :class="{'text-primary-500': getDistrictTotal(districtStat)}"
                                                :href="goToDailyReportList(district_code, 'all')"
                                                :total="getDistrictTotal(districtStat, 'total')"
                                                :total-house="getDistrictTotal(districtStat, 'total_houses_in_census')"
                                                :total-families="getDistrictTotal(districtStat, 'total_families_in_census')"/>
                                            <Td v-for="date in dates"
                                                :key="'survey_'+date"
                                                :total="getDistrictTotalByDate(districtStat, date, 'total')"
                                                :total-house="getDistrictTotalByDate(districtStat, date, 'total_houses_in_census')"
                                                :total-families="getDistrictTotalByDate(districtStat, date, 'total_families_in_census')"/>
                                        </tr>
                                        <tr v-for="(censusOffice, censusOfficeId) in districtStat"
                                            :key="`census_${censusOfficeId}`"
                                            class="text-xs ">
                                            <td class="py-1.5"
                                                v-text="'District Census Office -' + getFromObject(censusOffices, `${censusOfficeId}.office_name`)"/>
                                            <Td class="py-1.5"
                                                :href="goToDailyReportList(district_code, censusOfficeId)"
                                                :total="getCensusOfficeTotal(censusOffice, 'total')"
                                                :total-house="getCensusOfficeTotal(censusOffice, 'total_houses_in_census')"
                                                :total-families="getCensusOfficeTotal(censusOffice, 'total_families_in_census')"/>
                                            <Td v-for="date in dates"
                                                :key="'survey_'+date"
                                                class="py-1.5"
                                                :total="getCensusOfficeSurveyed(censusOffice, date, 'total')"
                                                :total-house="getCensusOfficeSurveyed(censusOffice, date, 'total_houses_in_census')"
                                                :total-families="getCensusOfficeSurveyed(censusOffice, date, 'total_families_in_census')"/>
                                        </tr>
                                    </fragment>
                                </tbody>
                            </table>
                        </template>
                    </AccordionItem>
                </accordion>
            </LargeTable>
            <div v-else>
                <div class="flex justify-center items-center flex-col" style="height: 400px;">
                    <i class="fas fa-home px-4 py-3 fa-4x font-medium"/>
                    <span class="text-xl px-4 py-3">No Dwellings Data Found</span>
                </div>
            </div>
        </div>
    </admin-layout>
</template>

<script>
    import { isEmpty }    from "lodash"
    import Icon           from "../../../../admin/js/Components/General/Icon"
    import BreadCrumb     from "../../Components/BreadCrumb/BreadCrumb"
    import BreadCrumbItem from "../../Components/BreadCrumb/BreadCrumbItem"
    import Accordion      from "../../Components/General/Accordion"
    import AccordionItem  from "../../Components/General/AccordionItem"
    import LargeTable     from "../../Components/General/LargeTable"
    import Td             from "../../Components/General/Td"
    import AdminLayout    from "../../Layouts/AdminLayout"

    export default {
        name: "DailyReportAdminDashboard",
        components: { Td, LargeTable, AccordionItem, Accordion, Icon, BreadCrumb, BreadCrumbItem, AdminLayout },
        props: {
            stats: {
                type: Object,
                required: true,
                default: () => {
                },
            },
            districts: { type: Object, required: true },
            censusOffices: { type: Object, required: true },
            dates: { type: Array, required: true },
        },

        computed: {
            showTable() {
                return !isEmpty(this.stats)
            },
            totalCount() {
                return Object.values(this.stats).reduce((acc, province) => {
                    return acc + this.getProvinceTotal(province, "total")
                }, 0)
            },
            totalDwellingsCount() {
                return Object.values(this.stats).reduce((acc, province) => {
                    return acc + this.getProvinceTotal(province, "total_houses_in_census")
                }, 0)
            },
            totalHouseholdsCount() {
                return Object.values(this.stats).reduce((acc, province) => {
                    return acc + this.getProvinceTotal(province, "total_families_in_census")
                }, 0)
            },
        },

        methods: {
            formatWithCommas(number) {
                try {
                    return Intl.NumberFormat("en-IN").format(number)
                } catch (e) {
                    return number
                }
            },
            getCensusOfficeSurveyed(censusOffice, date, key = "total") {
                return this.getFromObject(censusOffice, date + `.0[${key}]`, 0)
            },

            getCensusOfficeTotal(censusOffice, key = "total") {
                if (isEmpty(censusOffice)) {
                    return 0
                }
                return Object.values(censusOffice).reduce((acc, cur) => {
                    return acc + cur["0"][key]
                }, 0)
            },

            getDistrictTotal(district, key = "total") {
                if (isEmpty(district)) {
                    return 0
                }

                return Object.values(district).reduce((acc, censusOffice) => {
                    return acc + this.getCensusOfficeTotal(censusOffice, key)
                }, 0)
            },

            getDistrictTotalByDate(district, date, key = "total") {
                if (isEmpty(district)) {
                    return 0
                }
                return Object.values(district).reduce((acc, censusOffice) => {
                    return acc + this.getFromObject(censusOffice, date + `.0[${key}]`, 0)
                }, 0)
            },

            getProvinceTotal(province, key = "total") {
                if (isEmpty(province)) {
                    return 0
                }

                return Object.values(province.districts).reduce((acc, district) => {
                    return acc + this.getDistrictTotal(district, key)
                }, 0)
            },

            getProvinceTotalByDate(province, date, key = "total") {
                if (isEmpty(province)) {
                    return 0
                }
                return Object.values(province.districts).reduce((acc, district) => {
                    return acc + this.getDistrictTotalByDate(district, date, key)
                }, 0)
            },

            goToDailyReportList(districtCode, censusOffice) {
                return this.route("admin.house-daily-reports.index", {
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
