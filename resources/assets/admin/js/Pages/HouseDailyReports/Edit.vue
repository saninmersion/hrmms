<template>
    <admin-layout page-title="Daily Report" :authorized="isAuthorized($page, 'daily_report')">
        <daily-report-form
            :form-data="form"
            :is-operator="isOperator"
            @cancel="cancelForm"
            @save="submitForm"/>
    </admin-layout>
</template>

<script>
    import AdminLayout     from "../../Layouts/AdminLayout"
    import DailyReportForm from "./Partials/DailyReportForm"

    const OPERATOR = "operators"
    export default {
        name: "HouseDailyReportEdit",
        components: { DailyReportForm, AdminLayout },
        props: {
            report: { type: Object, required: true },
        },
        data() {
            return {
                form: this.$inertia.form(
                    {
                        report_date: "",
                        total_surveyed: 0,
                        number_of_houses_in_census: 0,
                        number_of_families_in_census: 0,
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
                    this.$set(this.form, "number_of_houses_in_census", this.getFromObject(report, "number_of_houses_in_census"))
                    this.$set(this.form, "number_of_families_in_census", this.getFromObject(report, "number_of_families_in_census"))
                },
                deep: true,
                immediate: true,
            },
        },
        computed: {
            isOperator: function() {
                return this.getFromObject(this.$page, "props.auth.user.role") === OPERATOR
            },
        },
        methods: {
            cancelForm: function() {
                this.$inertia.visit(this.route("admin.house-daily-reports.index"))
            },
            submitForm: function(form) {
                form.post(this.route("admin.house-daily-reports.update", this.report.id))
            },
        },
    }
</script>

<style scoped>

</style>
