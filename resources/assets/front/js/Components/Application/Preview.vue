<template>
    <div class="form-page scroll-enabled">
        <div class="container">
            <div class="form-page__heading gap-4">
                <h3 class="heading-primary  heading-primary--lg py-2 xlg:py-0">
                    {{ trans("application.application_preview_title") }}

                    <a v-if="applicant.is_editable"
                       class="font-normal !px-2 text-base  text-primary inline-flex items-center"
                       :href="formUrl">
                        <Icon name="edit"/>
                        {{ trans("general.edit") }}
                    </a>
                </h3>
                <div class="flex gap-8  items-center flex-wrap order-10 xlg:order-0 xlg:ml-auto">
                    <span :class="'status status-' + applicant.status"
                          v-text="trans(`application.application-status.${applicant.status}`)">status</span>
                    <primary-button v-if="applicant.is_editable"
                                    class="font-normal !px-2  text-base !lg:text-sm"
                                    @click.prevent="showSubmitConfirmation">
                        <Icon name="upload" class="!h-3"/>
                        {{ trans("application.application_submit") }}
                    </primary-button>
                </div>
                <button
                    class="xlg:order-12 font-normal !px-2 text-base text-primary inline-flex items-center ml-auto sm:ml-1 -mr-2"
                    @click.prevent="handleLogout">
                    {{ trans("auth.logout") }}
                    <Icon name="logout" class="!ml-3 !mr-0 !w-5 !h-5"/>
                </button>
            </div>

            <div class="form-page__content">
                <div class="sidebar">
                    <div class="sidebar__list">
                        <div class="sidebar__item">
                            <a href="javascript:void(0)" class="sidebar__link" v-text="trans('application.sections.position')"/>
                        </div>
                        <div class="sidebar__item">
                            <a href="javascript:void(0)" class="sidebar__link" v-text="trans('application.sections.personal')"/>
                        </div>
                        <div class="sidebar__item">
                            <a href="javascript:void(0)" class="sidebar__link" v-text="trans('application.sections.qualification')"/>
                        </div>
                    </div>
                    <a :href="howToPageUrl" class="btn btn--outline lg:mt-4 inline-block xl:block">
                        {{ trans("general.how-to-button") }}
                    </a>
                </div>
                <div>
                    <div v-if="successMessage" class="status status-submitted gap-4 sm:gap-5 !py-4 !px-4 sm:px-6 flex mb-8 items-start sm:items-center">
                        <Icon name="success" class="sm:!w-11 sm:!h-11 !mr-0 mt-1 sm:mt-0"/>
                        <p class="text-black text-left -mt-1 sm:mt-0" v-html="successMessage"/><!-- eslint-disable-line vue/no-v-html -->
                    </div>
                    <application-help-text v-if="!applicant.is_submitted" class="bg-danger-100 px-4 py-2" :text="trans('application.help-text.needs_submission')"/>
                    <application-help-text v-if="!applicant.is_submitted" class="bg-blue-100 px-4 py-2" :text="trans('application.help-text.preview')"/>
                    <application-status :applicant="applicant"/>
                    <application-position :applicant="applicant"/>
                    <application-personal :applicant="applicant"/>
                    <application-qualification :applicant="applicant"/>
                </div>
            </div>
        </div>

        <card-modal :show="showConfirmation" @close="hideSubmitConfirmation">
            <h3 class="text-3xl text-black-100 text-center ">
                {{ trans("application.submit-confirmation") }}
            </h3>
            <p class="text-center mt-2">
                {{ trans("application.submit-confirmation-note") }}
            </p>
            <div class="flex flex-col items-center">
                <primary-button class="font-normal !px-2 mt-6" @click.prevent="handleOnSubmitConfirm">
                    {{ trans("application.submit-i-confirm") }}
                </primary-button>
                <button class="font-normal !px-2 mt-6 text-base text-primary inline-flex items-center"
                        @click.prevent="handleOnSubmitCancel">
                    {{ trans("application.submit-cancel") }}
                </button>
            </div>
        </card-modal>
    </div>
</template>

<script type="application/ecmascript">
    import Api                      from "../../../../shared/js/Api"
    import { PrimaryButton }        from "../../../../shared/js/Components/Buttons"
    import Icon                     from "../Common/Icon"
    import CardModal                from "./CardModal"
    import ApplicationHelpText      from "./partials/ApplicationHelpText"
    import ApplicationPersonal      from "./previewPartials/ApplicationPersonal"
    import ApplicationPosition      from "./previewPartials/ApplicationPosition"
    import ApplicationQualification from "./previewPartials/ApplicationQualification"
    import ApplicationStatus        from "./previewPartials/ApplicationStatus"

    export default {
        name: "ApplicationPreview",

        components: {
            Icon,
            ApplicationHelpText,
            ApplicationStatus,
            CardModal,
            ApplicationQualification,
            ApplicationPersonal,
            ApplicationPosition,
            PrimaryButton,
        },

        props: {
            applicant: { type: Object, required: true },
            oldDistricts: { type: Array, required: true },
            newDistricts: { type: Array, required: true },
            formUrl: { type: String, required: true },
            successMessage: { type: String, required: false, default: "" },
            submitUrl: { type: String, required: true },
            logoutUrl: { type: String, required: true },
            howToPageUrl: { type: String, required: true },
        },

        data: () => ({
            showConfirmation: false,
        }),

        provide() {
            return {
                oldDistricts: this.oldDistricts,
                newDistricts: this.newDistricts,
            }
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

            showSubmitConfirmation() {
                this.showConfirmation = true
            },

            hideSubmitConfirmation() {
                this.showConfirmation = false
            },

            async handleOnSubmitConfirm() {
                try {
                    const { body: { data } } = await Api.post(this.submitUrl)

                    window.location.href = data.redirection_url
                } catch (error) {
                    if (error.response && error.response.status === 422) {
                        window.location.href = error.response.data.message
                    }
                }
            },

            handleOnSubmitCancel() {
                this.hideSubmitConfirmation()
            },
        },
    }
</script>
