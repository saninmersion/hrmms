<template>
    <admin-layout page-title="Enumerator Monitoring Form" :authorized="isAuthorized($page, 'monitoring_general')">
        <enumerator-monitoring-form
            :form-data="form"
            :is-operator="isOperator"
            :districts="districts"
            :monitors="monitors"
            :options="options"
            @cancel="cancelForm"
            @save="submitForm"/>
    </admin-layout>
</template>

<script>
    import AdminLayout              from "../../Layouts/AdminLayout"
    import EnumeratorMonitoringForm from "./Partials/EnumeratorMonitoringForm"

    const OPERATOR = "operators"

    export default {
        name: "EnumeratorMonitoringFormEdit",
        components: { EnumeratorMonitoringForm, AdminLayout },
        props: {
            districts: { type: Array, required: true },
            monitors: { type: Array, required: false, default: () => ([]) },
            options: { type: Object, required: true },
            monitoring: { type: Object, required: true },
        },
        data() {
            return {
                form: this.$inertia.form({
                    monitored_by: null,
                    district: "",
                    municipality: "",
                    ward: "",
                    census_area: "",
                    monitoring_data: {
                        monitored_person_name: "",
                        monitored_person_mobile_no: "",
                        politeness_behaviour: {
                            greeting: "",
                            introduction: "",
                            objective: "",
                            demeanor: "",
                            gratitude: "",
                        },
                        interview_process: {
                            question_similarity: "",
                            clarification: "",
                            every_section: "",
                        },
                        time_management: {
                            long_argument: "",
                            interruption: "",
                            impatience: "",
                        },
                        impartiality: {
                            neutrality: "",
                            reaction: "",
                            personal_opinion: "",
                            answer_indication: "",
                        },
                        onsite_monitoring: {
                            house_sn: "",
                            family_sn: "",
                            head_of_household_name: "",
                            head_of_household_age: "",
                            head_of_household_gender: "",
                            resident_family_total: "",
                            resident_family_male: "",
                            resident_family_female: "",
                            resident_family_check: "",
                            absent_family_total: "",
                            absent_family_male: "",
                            absent_family_female: "",
                            absent_family_check: "",
                            current_house_owner: "",
                            current_house_check: "",
                            current_house_roof: "",
                            current_house_roof_check: "",
                            facility_television_code: "",
                            facility_television_check: "",
                            facility_computer_code: "",
                            facility_computer_check: "",
                            facility_refrigerator_code: "",
                            facility_refrigerator_check: "",
                            family_death: "",
                            family_death_check: "",
                            child_less_than_one_count: "",
                            child_less_than_one_check: "",
                            elderly_more_than_60_count: "",
                            elderly_more_than_60_check: "",
                            literate_read_write_count: "",
                            literate_read_write_check: "",
                            disabled_count: "",
                            disabled_check: "",
                        },
                        monitoring_suggestions: "",
                    },
                }, {
                    bag: "enumeratorMonitoringForm",
                    resetOnSuccess: true,
                }),
            }
        },
        watch: {
            monitoring: {
                handler(monitoring) {
                    this.$set(this.form, "monitored_by", this.getFromObject(monitoring, "monitored_by"))
                    this.$set(this.form, "district", this.getFromObject(monitoring, "district"))
                    this.$set(this.form, "municipality", this.getFromObject(monitoring, "municipality"))
                    this.$set(this.form, "ward", this.getFromObject(monitoring, "ward"))
                    this.$set(this.form, "census_area", this.getFromObject(monitoring, "census_area"))
                    this.$set(this.form, "monitoring_data", { ...this.form.monitoring_data, ...monitoring.monitoring_data })
                },
                immediate: true,
                deep: true,
            },
        },
        computed: {
            isOperator: function() {
                return this.getFromObject(this.$page, "props.auth.user.role") === OPERATOR
            },
        },
        methods: {
            submitForm(form) {
                form.post(this.route("admin.monitorings.enumerator.update", this.monitoring.id))
                console.log(form)
            },
            cancelForm() {
                this.$inertia.visit(this.route("admin.monitorings.show", this.monitoring.id))
            },
        },
    }
</script>

<style scoped>

</style>
