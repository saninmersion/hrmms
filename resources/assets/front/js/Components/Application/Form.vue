<template>
    <div class="form-page scroll-enabled">
        <div class="container">
            <div class="form-page__heading gap-4">
                <h3 class="heading-primary heading-primary--lg">
                    {{ trans("application.application_form_title") }}
                </h3>
                <button
                    class="font-normal !px-2  text-base text-primary inline-flex items-center ml-auto -mr-2"
                    @click.prevent="handleLogout">
                    {{ trans("auth.logout") }}
                    <Icon name="logout" class="!ml-3 !w-5 !h-5 !mr-0" fill="#2b6846"/>
                </button>
            </div>

            <div class="form-page__content">
                <div class="sidebar">
                    <div class="sidebar__list">
                        <div class="sidebar__item">
                            <a class="sidebar__link" href="javascript:void(0)" v-text="trans('application.sections.position')"/>
                        </div>
                        <div class="sidebar__item">
                            <a class="sidebar__link" href="javascript:void(0)" v-text="trans('application.sections.personal')"/>
                        </div>
                        <div class="sidebar__item">
                            <a class="sidebar__link" href="javascript:void(0)" v-text="trans('application.sections.qualification')"/>
                        </div>
                    </div>
                    <a :href="howToPageUrl" class="btn btn--outline lg:mt-4 inline-block xl:block">
                        {{ trans("general.how-to-button") }}
                    </a>
                </div>

                <form enctype="multipart/form-data" @submit.prevent="handleOnSubmit">
                    <div v-if="validation().hasError()" class="bg-danger-100 border-0 mb-6 px-6 py-4 relative rounded">
                        <div class="inline-block">
                            <p class="align-middle mr-8">
                                {{ trans("application.validation-errors-help-text") }}
                            </p>

                            <p>
                                <strong>{{ trans("general.note") }}:</strong>
                                {{ trans("application.validation-errors-note") }}
                            </p>
                        </div>
                    </div>

                    <application-help-text :text="trans('application.help-text.form')"/>

                    <application-position-form v-model="formData.application"/>

                    <application-personal-form v-model="formData.personal"/>

                    <application-qualification-form v-model="formData.qualification" :application="formData.application"/>

                    <div class="form-page-footer bg-white py-4 bg-gray-50 flex items-center gap-8">
                        <primary-button class="px-8  !font-base" type="submit">
                            {{ trans("general.Save") }}
                        </primary-button>

                        <div v-if="isSaving" class="flex items-center gap-3">
                            <div class="lds-ring">
                                <div/>
                                <div/>
                                <div/>
                            </div>
                            <span v-text="trans('general.saving')"/>
                        </div>

                        <action-message :on="saved">
                            <div class="flex items-center">
                                <Icon name="success"/>
                                <span>
                                    {{ trans("general.Saved") }}
                                </span>
                            </div>
                        </action-message>

                        <action-message :on="failed" class="flex items-center">
                            <div class="flex items-center">
                                <span class="btn-remove mr-2 !w-4 !h-4">
                                    <Icon name="close" class="!w-2.5 !h-2.5 !m-auto"/>
                                </span>
                                <span>{{ trans("general.Not Saved") }}</span>
                            </div>
                        </action-message>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script type="application/ecmascript">
    import Api                          from "../../../../shared/js/Api"
    import ActionMessage                from "../../../../shared/js/Components/ActionMessage"
    import { PrimaryButton }            from "../../../../shared/js/Components/Buttons"
    import Icon                         from "../Common/Icon"
    import applicationFormData          from "./applicationFormData"
    import ApplicationHelpText          from "./partials/ApplicationHelpText"
    import ApplicationPersonalForm      from "./partials/ApplicationPersonalForm"
    import ApplicationPositionForm      from "./partials/ApplicationPositionForm"
    import ApplicationQualificationForm from "./partials/ApplicationQualificationForm"

    export default {
        name: "ApplicationForm",

        components: {
            ApplicationHelpText,
            ActionMessage,
            PrimaryButton,
            ApplicationQualificationForm,
            ApplicationPersonalForm,
            ApplicationPositionForm,
            Icon,
        },

        props: {
            applicant: { type: Object, required: true },
            applicationTypes: { type: Array, required: true },
            newDistricts: { type: Array, required: true },
            oldDistricts: { type: Array, required: true },
            gradingSystems: { type: Array, required: true },
            majorSubjects: { type: Array, required: true },
            ethnicities: { type: Array, required: true },
            motherTongues: { type: Array, required: true },
            disabilities: { type: Array, required: true },
            mediaUploadUrl: { type: String, required: true },
            submitUrl: { type: String, required: true },
            errors: { type: Object, required: false, default: () => ({}) },
            logoutUrl: { type: String, required: true },
            howToPageUrl: { type: String, required: true },
        },

        data: () => ({
            formData: applicationFormData,

            isSaving: false,
            saved: false,
            failed: false,
        }),

        watch: {
            applicant: {
                handler(applicant) {
                    console.log(applicant)
                    this.formData = { ...this.formData, ...applicant }
                },
                immediate: true,
            },
            errors: {
                handler(errors) {
                    this.validation().set(errors)
                },
                immediate: true,
            },
        },

        provide() {
            return {
                applicationTypes: this.applicationTypes,
                newDistricts: this.newDistricts,
                oldDistricts: this.oldDistricts,
                gradingSystems: this.gradingSystems,
                majorSubjects: this.majorSubjects,
                ethnicities: this.ethnicities,
                motherTongues: this.motherTongues,
                disabilities: this.disabilities,
                mediaUploadUrl: this.mediaUploadUrl,
            }
        },

        mounted() {
            setInterval(() => {
                this.handleOnSubmit(false)
            }, 1000 * 60 * 5)
        },

        methods: {
            async handleLogout() {
                try {
                    const { body: { data } } = await Api.post(this.logoutUrl)

                    window.location.href = data.redirection_url
                } catch (e) {
                    console.log(e)
                }
            },
            async handleOnSubmit(redirection = true) {
                this.validation().clearAll()
                this.isSaving = true
                this.saved = false
                this.failed = false

                try {
                    const { body: { data } } = await Api.post(this.submitUrl, this.formData)

                    this.isSaving = false
                    this.saved = true

                    if (redirection) {
                        window.location.href = data.redirection_url
                    }
                } catch (e) {
                    this.isSaving = false
                    this.failed = true
                    if (e.response && e.response.status === 422) {
                        this.validation().set(e.response.data.errors)
                    }

                    console.log(e)
                }
            },
        },
    }
</script>
