<template>
    <admin-layout page-title="Daily Report" :authorized="isAuthorized($page, 'daily_report')">
        <daily-report-form
            :form-data="form"
            :is-operator="isOperator"
            :enumerator-options="enumeratorOptions"
            @cancel="cancelForm"
            @save="submitForm"/>
    </admin-layout>
</template>

<script>
    import AdminLayout     from "../../Layouts/AdminLayout"
    import DailyReportForm from "./Partials/DailyReportForm"

    const OPERATOR = "operators"
    export default {
        name: "DailyReportEdit",
        components: { DailyReportForm, AdminLayout },
        props: {
            report: { type: Object, required: true },
            enumerators: { type: Array, required: true },
        },
        data() {
            return {
                form: this.$inertia.form(
                    {
                        enumerator_id: "",
                        report_date: "",
                        total_surveyed: "",
                        report_type: "",
                    },
                    {
                        bag: "dailyReportForm",
                        resetOnSuccess: true,
                    }),
            }
        },
        watch: {
            report: {
                handler(report) {
                    this.$set(this.form, "report_date", this.getFromObject(report, "report_date.date"))
                    this.$set(this.form, "total_surveyed", this.getFromObject(report, "total_surveyed"))
                    this.$set(this.form, "enumerator_id", this.getFromObject(report, "enumerator_id"))
                    this.$set(this.form, "report_type", this.getFromObject(report, "report_type"))
                },
                deep: true,
                immediate: true,
            },
        },
        computed: {
            isOperator: function() {
                return this.getFromObject(this.$page, "props.auth.user.role") === OPERATOR
            },
            enumeratorOptions: function() {
                const options = new Map()
                this.enumerators.forEach(enumerator => {
                    options.set(enumerator.id, enumerator.name)
                })
                return options
            },
        },
        methods: {
            cancelForm: function() {
                this.$inertia.visit(this.route("admin.daily-reports.index"))
            },
            submitForm: function(form) {
                form.post(this.route("admin.daily-reports.update", this.report.id))
            },
        },
    }
</script>

<style scoped>

</style>
