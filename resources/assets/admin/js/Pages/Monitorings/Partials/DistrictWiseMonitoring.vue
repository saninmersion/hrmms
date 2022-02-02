<template>
    <div>
        <h1 class="heading-primary !pb-6 pt-2" v-text="'District-wise Monitoring Forms'"/>
        <div v-if="districts && isArray(districts)" class="c-table shadow-md rounded-lg">
            <div class="flex bg-gray-100">
                <div class="p-4 w-1/4"/>
                <div class="p-4 w-1/4 font-semibold !text-center" v-text="trans('admin-monitoring.form-type.overall_monitoring')"/>
                <div class="p-4 w-1/4 font-semibold !text-center" v-text="trans('admin-monitoring.form-type.supervisor_monitoring')"/>
                <div class="p-4 w-1/4 font-semibold !text-center" v-text="trans('admin-monitoring.form-type.enumerator_monitoring')"/>
            </div>
            <Accordion v-for="(district) in districts"
                       :key="district.code">
                <AccordionItem>
                    <template slot="accordion-trigger">
                        <div class="font-semibold p-1 text-black text-left w-1/4">
                            <span v-text="getTitle(district)"/>
                        </div>
                        <div class="p-2 w-1/4 !text-center text-black font-semibold"
                             v-text="numberTrans(getFromObject(districtStats, `${getCode(district)}.overall_monitoring`, 0))"/>
                        <div class="p-2 w-1/4 !text-center text-black font-semibold"
                             v-text="numberTrans(getFromObject(districtStats, `${getCode(district)}.supervisor_monitoring`, 0))"/>
                        <div class="p-2 w-1/4 !text-center text-black font-semibold"
                             v-text="numberTrans(getFromObject(districtStats, `${getCode(district)}.enumerator_monitoring`, 0))"/>
                        <span class="absolute right-14">
                            <Icon name="caret-down" class="accordion__arrow !w-5 !h-6 !mr-0"/>
                        </span>
                    </template>
                    <template slot="accordion-content">
                        <div>
                            <div v-if="district.municipalities && isArray(district.municipalities)">
                                <div
                                    v-for="municipality in district.municipalities"
                                    :key="municipality.code"
                                    class="c-tr flex text-center !bg-white !hover:bg-white">
                                    <div class="p-4 w-1/4  text-left" v-text="getTitle(municipality)"/>
                                    <inertia-link
                                        class="p-4 w-1/4 !text-center"
                                        :class="{'text-primary-500': getFromObject(municipalitiesStats, `${getCode(municipality)}.overall_monitoring`, null)}"
                                        :href="goToMonitoringList(overallMonitoring, getCode(district), getCode(municipality))"
                                        v-text="numberTrans(getFromObject(municipalitiesStats, `${getCode(municipality)}.overall_monitoring`, '-'))"/>

                                    <inertia-link
                                        class="p-4 w-1/4 !text-center"
                                        :class="{'text-primary-500': getFromObject(municipalitiesStats, `${getCode(municipality)}.supervisor_monitoring`, null)}"
                                        :href="goToMonitoringList(supervisorMonitoring, getCode(district), getCode(municipality))"
                                        v-text="numberTrans(getFromObject(municipalitiesStats, `${getCode(municipality)}.supervisor_monitoring`, '-'))"/>

                                    <inertia-link
                                        class="p-4 w-1/4 !text-center"
                                        :class="{'text-primary-500': getFromObject(municipalitiesStats, `${getCode(municipality)}.enumerator_monitoring`, null)}"
                                        :href="goToMonitoringList(enumeratorMonitoring, getCode(district), getCode(municipality))"
                                        v-text="numberTrans(getFromObject(municipalitiesStats, `${getCode(municipality)}.enumerator_monitoring`, '-'))"/>
                                </div>
                            </div>
                        </div>
                    </template>
                </AccordionItem>
            </Accordion>
        </div>
    </div>
</template>

<script>
    import Accordion     from "../../../Components/General/Accordion"
    import AccordionItem from "../../../Components/General/AccordionItem"
    import Icon          from "../../../Components/General/Icon"

    const OVERALL_MONITORING = "overall_monitoring"
    const SUPERVISOR_MONITORING = "supervisor_monitoring"
    const ENUMERATOR_MONITORING = "enumerator_monitoring"

    export default {
        name: "DistrictWiseMonitoring",
        components: { Accordion, AccordionItem, Icon },
        props: {
            municipalitiesStats: { type: Object, required: false, default: () => ({}) },
            districtStats: { type: Object, required: false, default: () => ({}) },
            districts: { type: Array, required: false, default: () => ([]) },
        },
        created() {
            this.overallMonitoring = OVERALL_MONITORING
            this.enumeratorMonitoring = ENUMERATOR_MONITORING
            this.supervisorMonitoring = SUPERVISOR_MONITORING
        },
        methods: {
            getTitle(district) {
                if (!district) {
                    return ""
                }

                return this.$currentLocale === "en"
                    ? this.getFromObject(district, "title_en", "")
                    : this.getFromObject(district, "title_ne", "")
            },
            getCode(district) {
                if (!district) {
                    return ""
                }
                return this.getFromObject(district, "code", "")
            },
            goToMonitoringList(monitoringType, districtCode, municipalityCode = null) {
                return this.route("admin.monitorings.index", {
                    _query: {
                        district_code: districtCode,
                        municipality_code: municipalityCode,
                        form_type: monitoringType,
                    },
                })
            },
        },
    }
</script>

<style scoped>

</style>
