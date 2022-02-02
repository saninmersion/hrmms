<template>
    <admin-layout page-title="Supervisor Monitoring Form" :authorized="isAuthorized($page, 'monitoring_general')">
        <supervisor-monitoring-form
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
    import SupervisorMonitoringForm from "./Partials/SupervisorMonitoringForm"

    const OPERATOR = "operators"

    export default {
        name: "SupervisorMonitoringFormEdit",
        components: { SupervisorMonitoringForm, AdminLayout },
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
                            house_monitorings: [
                                {
                                    house_sn: "",
                                    floor_count: "",
                                    floor_count_check: "",
                                    house_purpose: "",
                                    house_purpose_check: "",
                                    family_count: "",
                                    family_count_check: "",
                                },
                            ],
                            family_monitorings: [
                                {
                                    family_sn: "",
                                    house_head_name: "",
                                    house_head_name_check: "",
                                    male_count: "",
                                    male_count_check: "",
                                    female_count: "",
                                    female_count_check: "",
                                    agriculture_land: "",
                                    agriculture_land_check: "",
                                    professional_training: "",
                                    professional_training_check: "",
                                },
                            ],

                        },
                        monitoring_suggestions: "",
                    },
                }, {
                    bag: "supervisorMonitoringForm",
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
                form.post(this.route("admin.monitorings.supervisor.update", this.monitoring.id))
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
