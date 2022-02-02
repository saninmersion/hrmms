<template>
    <admin-layout page-title="Overall Monitoring Form" :authorized="isAuthorized($page, 'monitoring_general')">
        <overall-monitoring-form
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
    import AdminLayout           from "../../Layouts/AdminLayout"
    import OverallMonitoringForm from "./Partials/OverallMonitoringForm"

    const OPERATOR = "operators"

    export default {
        name: "OverallMonitoringFormEdit",
        components: { AdminLayout, OverallMonitoringForm },
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
                        family_count: "",
                        onsite_monitoring: {
                            marking: "",
                            prior_information: "",
                            local_advertisement: "",
                            missed_count: "",
                            complaints: "",
                            obstacles: "",
                        },
                        monitoring_problems: "",
                        monitoring_suggestions: "",
                    },
                }, {
                    bag: "overallMonitoringForm",
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
                form.post(this.route("admin.monitorings.overall.update", this.monitoring.id))
                console.log(form);
            },
            cancelForm() {
                this.$inertia.visit(this.route("admin.monitorings.show", this.monitoring.id))
            },
        },
    }
</script>

<style scoped>

</style>
