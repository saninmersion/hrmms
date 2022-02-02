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
        name: "DailyReportCreate",

        components: { DailyReportForm, AdminLayout },

        props: {
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

        computed: {
            enumeratorOptions: function() {
                const options = new Map()
                this.enumerators.forEach(enumerator => {
                    options.set(enumerator.id, enumerator.name)
                })
                return options
            },
            isOperator: function() {
                return this.getFromObject(this.$page, "props.auth.user.role") === OPERATOR
            },
        },

        methods: {
            cancelForm: function() {
                this.$inertia.visit(this.route("admin.daily-reports.index"))
            },
            submitForm: function(form) {
                form.post(this.route("admin.daily-reports.store"))
            },
        },
    }
</script>

<style scoped>

</style>
