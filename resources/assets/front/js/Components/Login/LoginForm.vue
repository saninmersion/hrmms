<template>
    <div>
        <template v-if="!failed">
            <login-new v-if="mode === 'create' && !hasDeadlinePassed"
                       :district-options="districtOptions"
                       :icon-link="iconLink"
                       :is-saving="isSaving"
                       :value="loginData"
                       @check="backAndCheck"
                       @edit="backAndEdit"
                       @submit="loginSubmit"/>

            <login-old v-if="mode === 'edit' && !hasDeadlinePassed"
                       :icon-link="iconLink"
                       :is-saving="isSaving"
                       :value="loginData"
                       @check="backAndCheck"
                       @create="backAndCreate"
                       @submit="loginSubmit"/>

            <login-check v-if="mode === 'check' || hasDeadlinePassed"
                         :has-deadline-passed="hasDeadlinePassed"
                         :icon-link="iconLink"
                         :is-saving="isSaving"
                         :value="loginData"
                         @create="backAndCreate"
                         @submit="loginSubmit"/>
        </template>

        <template v-else>
            <login-failed :icon-link="iconLink"
                          :login-data="loginData"
                          :has-deadline-passed="hasDeadlinePassed"
                          :mode="mode"
                          :reason="failedReason"
                          @back="failed = false"
                          @create="backAndCreate"/>
        </template>
    </div>
</template>

<script type="text/ecmascript-6">
    import Api         from "../../../../shared/js/Api"
    import LoginCheck  from "./LoginCheck"
    import LoginFailed from "./LoginFailed"
    import LoginNew    from "./LoginNew"
    import LoginOld    from "./LoginOld"

    export default {
        name: "NewApplicationLoginForm",

        components: { LoginFailed, LoginCheck, LoginOld, LoginNew },

        props: {
            districts: { type: Array, required: true },
            newLoginUrl: { type: String, required: true },
            oldLoginUrl: { type: String, required: true },
            checkLoginUrl: { type: String, required: true },
            iconLink: { type: String, required: true },
            deadline: { type: String, required: true },
        },

        data: () => ({
            loginData: {
                dob: null,
                citizenship_number: null,
                citizenship_district: null,
                mobile_number: null,
                gender: null,
            },

            mode: "create",
            failed: false,
            failedReason: "",
            isSaving: false,
        }),
        computed: {
            districtOptions: function() {
                const options = new Map()
                const key = this.$currentLocale === "en" ? "title_en" : "title_ne"

                this.districts.forEach(district => {
                    options.set(district.code, district[key])
                })

                return options
            },

            loginUrl: function() {
                if (this.mode === "create") {
                    return this.newLoginUrl
                }

                if (this.mode === "edit") {
                    return this.oldLoginUrl
                }

                if (this.mode === "check") {
                    return this.checkLoginUrl
                }

                return this.newLoginUrl
            },

            hasDeadlinePassed() {
                const deadline = new Date(this.deadline)
                const today = new Date()

                return deadline.setHours(24) <= today
            },
        },
        mounted() {
            if (this.hasDeadlinePassed) {
                this.mode = "check"
            }
        },

        methods: {
            async loginSubmit(value) {
                if (this.isSaving) {
                    return false
                }

                this.loginData = { ...this.loginData, ...value }
                this.validation().clearAll()
                this.isSaving = true

                try {
                    const { body: { data } } = await Api.post(this.loginUrl, value)
                    this.isSaving = false

                    window.location.href = data.redirection_url
                } catch (e) {
                    this.isSaving = false

                    if (e.response && e.response.status === 422) {
                        this.validation().set(e.response.data.errors)
                    }

                    if (e.response && e.response.status === 401) {
                        this.loginFailed(e.response.data.message)
                    }

                    console.log(e)
                }
            },

            loginFailed(reason) {
                this.failed = true
                this.failedReason = reason
            },

            backAndCreate() {
                this.mode = "create"
                this.failed = false
            },

            backAndCheck() {
                this.mode = "check"
                this.failed = false
            },

            backAndEdit() {
                this.mode = "edit"
                this.failed = false
            },
        },
    }
</script>
