<template>
    <div>
        <span v-if="textMessage !== ''"
              class="block py-3 px-4 mb-8 text-sm text-black text-center rounded"
              :class="[isShortListed ? 'bg-success-100' : 'bg-danger-100']">
            {{ textMessage }}
        </span>
        <span v-else class="bg-blue-100 block py-3 px-4 mb-8 text-sm text-black text-center rounded">
            {{ trans("application.help-text.check_shortlist_status") }}
        </span>

        <div class="flex justify-between items-center flex-wrap gap-4">
            <h3 class="heading-primary">
                {{ trans("application.check_shortlist_status") }}
            </h3>
        </div>

        <div class="form-group mt-8">
            <label-component :value="`${trans('general.submission-number')} (${trans('application.help-text.for_example')} NSCA-0120130)`"/>
            <input-component v-model="submission_number"
                             :disabled="disableSubmissionNumberInput"
                             :placeholder="trans('general.submission-number')"
                             type="text"
                             @change="clear('submission_number')"/>
            <input-error :message="validation().get('submission_number')"/>
        </div>

        <div class="form-group mt-8">
            <label-component :value="trans('application.mobile_number')"/>
            <input-component v-model="mobile_number"
                             :disabled="disableMobileNumberInput"
                             :placeholder="trans('application.mobile_number')"
                             type="text"
                             @change="clear('mobile_number')"/>
            <input-error :message="validation().get('mobile_number')"/>
        </div>

        <div class="form-group">
            <primary-button :class="{'pointer-events-none' : isChecking}" class="py-3 w-full justify-center mt-2 mb-4 sm:mb-0 cursor-pointer" @click.prevent="handleOnSubmit">
                {{ trans("application.check_shortlist_status") }}
            </primary-button>
        </div>
    </div>
</template>

<script>
    import { isEmpty }       from "lodash"
    import Api               from "../../../../shared/js/Api"
    import { PrimaryButton } from "../../../../shared/js/Components/Buttons"
    import {
        InputComponent,
        InputError,
        LabelComponent,
    }                        from "../../../../shared/js/Components/Forms"

    export default {
        name: "CheckShortlistStatusForm",

        components: {
            PrimaryButton,
            InputError,
            LabelComponent,
            InputComponent,
        },

        props: {
            checkRoute: { type: String, required: true },
        },

        data: function() {
            return {
                isChecking: false,
                isShortListed: false,
                mobile_number: null,
                submission_number: null,
                textMessage: "",
            }
        },

        computed: {
            disableSubmissionNumberInput() {
                return !isEmpty(this.mobile_number)
            },

            disableMobileNumberInput() {
                return !isEmpty(this.submission_number)
            },
        },

        methods: {
            async handleOnSubmit() {
                if (this.isChecking) {
                    return false
                }

                this.validation().clearAll()
                this.isChecking = true

                try {
                    const data = {}

                    if (!isEmpty(this.submission_number)) {
                        data.submission_number = this.submission_number
                    }

                    if (!isEmpty(this.mobile_number)) {
                        data.mobile_number = this.mobile_number
                    }

                    const res = await Api.post(this.checkRoute, data)

                    if (res.body) {
                        this.isShortListed = true
                        const applicant = res.body.data.applicant
                        console.log(applicant)

                        const identification = isEmpty(this.submission_number) ? this.mobile_number : this.submission_number
                        const name = this.getFullName(applicant)
                        const ward = this.getWard(applicant)
                        const role = this.getRole(applicant.role)

                        this.textMessage = `${name} (${identification}) ${ward}, ${applicant.municipality.district.title_ne}, ${applicant.municipality.title_ne}मा ${role}को लागि सर्टलिस्ट गरिएको छ।`
                    }

                    this.isChecking = false
                } catch (e) {
                    this.isChecking = false
                    this.isShortListed = false

                    if (e.response && e.response.status === 422) {
                        this.validation().set(e.response.data.errors)
                    }

                    if (e.response && e.response.status === 404) {
                        this.textMessage = e.response.data.message
                    }
                }
            },

            getFullName(applicant) {
                return `${this.getFromObject(applicant, "applicant.details.name_in_english.first_name", "")}
                         ${this.getFromObject(applicant, "applicant.details.name_in_english.middle_name", "")}
                          ${this.getFromObject(applicant, "applicant.details.name_in_english.last_name", "")}`
            },

            getWard(applicant) {
                return this.trans("application.preview.ward_no_", {
                    ":attr": this.numberTrans(this.getFromObject(applicant, "metadata.ward", "") ?? ""),
                })
            },
            getRole(role) {
                return this.trans(`application.preview.position-${role}`)
            },

            clear(key) {
                this.validation().clear(key)
                this.textMessage = ""
            },
        },
    }
</script>

<style scoped>

</style>
