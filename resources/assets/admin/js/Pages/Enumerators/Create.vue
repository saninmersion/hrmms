<template>
    <admin-layout page-title="Enumerator" :authorized="isAuthorized($page, 'enumerator')">
        <enumerator-form
            :form-data="form"
            :is-operator="isOperator"
            @cancel="cancelForm"
            @save="submitForm"/>
    </admin-layout>
</template>

<script>
    import AdminLayout from "../../Layouts/AdminLayout";
    import EnumeratorForm from "./Partials/EnumeratorForm";
    const OPERATOR = "operators"
    export default {
        name: "EnumeratorCreate",
        components: { EnumeratorForm, AdminLayout },
        data() {
            return {
                form: this.$inertia.form(
                    {
                        name: "",
                        target: "",
                    },
                    {
                        bag: "enumeratorForm",
                        resetOnSuccess: true,
                    },
                ),
            }
        },
        computed: {
            isOperator: function() {
                return this.getFromObject(this.$page, "props.auth.user.role") === OPERATOR
            },
        },
        methods: {
            cancelForm: function() {
                this.$inertia.visit(this.route("admin.enumerators.index"))
            },
            submitForm: function(form) {
                form.post(this.route("admin.enumerators.store"))
            },
        },
    }
</script>

<style scoped>

</style>
