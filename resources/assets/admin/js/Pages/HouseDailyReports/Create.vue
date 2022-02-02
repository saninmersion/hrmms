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
        name: "HouseDailyReportCreate",

        components: { DailyReportForm, AdminLayout },

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
                        bag: "houseDailyReportForm",
                        resetOnSuccess: true,
                    }),
            }
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
                form.post(this.route("admin.house-daily-reports.store"))
            },
        },
    }
</script>

<style scoped>

</style>
