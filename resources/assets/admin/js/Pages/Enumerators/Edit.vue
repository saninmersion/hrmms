<template>
    <admin-layout page-title="Enumerator" :authorized="isAuthorized($page, 'enumerator')">
        <enumerator-form
            :form-data="form"
            :is-operator="isOperator"
            :is-edit="true"
            @cancel="cancelForm"
            @save="submitForm"/>
    </admin-layout>
</template>

<script>
    import AdminLayout from "../../Layouts/AdminLayout";
    import EnumeratorForm from "./Partials/EnumeratorForm";

    const OPERATOR = "operators"
    export default {
        name: "EnumeratorEdit",
        components: { EnumeratorForm, AdminLayout },
        props: {
            enumerator: { type: Object, required: true },
        },
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
        watch: {
            enumerator: {
                handler(enumerator) {
                    this.$set(this.form, "name", this.getFromObject(enumerator, "name"))
                    this.$set(this.form, "target", this.getFromObject(enumerator, "target"))
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
                this.$inertia.visit(this.route("admin.enumerators.index"))
            },
            submitForm: function(form) {
                form.post(this.route("admin.enumerators.update", this.enumerator.id))
            },
        },
    }
</script>

<style scoped>

</style>
