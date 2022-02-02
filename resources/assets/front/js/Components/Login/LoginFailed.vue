<template>
    <div>
        <button class="link mb-6" @click="$emit('back')">
            <svg class="mr-2">
                <use :xlink:href="`${iconLink}ic-arrow-left`"/>
            </svg>
            {{ trans("application.go_back_edit") }}
        </button>

        <h3 class="heading-primary" v-text="trans('general.Verify')"/>

        <div class="mt-6">
            <div class="flex mb-4">
                <b class="w-2/5" v-text="trans('application.date_of_birth')"/>
                <span v-text="numberTrans(loginData.dob)"/>
            </div>

            <div v-if="mode === 'create'" class="flex">
                <b class="w-2/5" v-text="trans('application.Nepali Citizenship No')"/>
                <span v-text="numberTrans(loginData.citizenship_number)"/>
            </div>

            <div class="flex">
                <b class="w-2/5" v-text="trans('application.mobile_number')"/>
                <span v-text="numberTrans(loginData.mobile_number)"/>
            </div>

            <div v-if="mode==='create'" class="flex">
                <b class="w-2/5" v-text="trans('application.gender')"/>
                <span v-text="trans(`application.gender-${loginData.gender}`)"/>
            </div>
        </div>

        <div class="p-4 card card--gray my-8" v-text="reasonText"/>

        <button v-if="mode !== 'create' && !hasDeadlinePassed" class="btn w-full my-4" @click="$emit('create')">
            {{ trans("application.submit_new_application") }}
        </button>
    </div>
</template>

<script type="text/ecmascript-6">

    export default {
        name: "LoginFailed",

        props: {
            reason: { type: String, required: true },
            mode: { type: String, required: true },
            loginData: { type: Object, required: true },
            iconLink: { type: String, required: true },
            hasDeadlinePassed: { type: Boolean, required: true },
        },

        computed: {
            reasonText: function() {
                if (this.reason === "age") {
                    return this.trans("application.login-failed.age")
                }

                if (this.reason === "not-found") {
                    return this.trans("application.login_failed")
                }

                return this.reason
            },
        },
    }
</script>
