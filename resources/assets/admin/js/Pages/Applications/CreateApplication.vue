<template>
    <admin-layout page-title="Create New Application" :authorized="isAuthorized($page, 'offline_application')">
        <template #breadcrumb>
            <bread-crumb>
                <bread-crumb-item :href="route('admin.dashboard')" label="Dashboard"/>
                <bread-crumb-item :href="route('admin.applications.index')" label="Applicants"/>
                <bread-crumb-item label="Create new Application"/>
            </bread-crumb>
        </template>

        <div class="form">
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

                <application-position-form :form="form"/>

                <application-personal-form v-model="form.personal" :form="form"/>

                <application-qualification-form v-model="form.qualification" :form="form" :application="form.application"/>

                <div class="form-page-footer flex items-center bg-white bottom-0 py-4 md:py-6 gap-4 md:gap-6  sticky w-full">
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

                    <action-message :on="form.recentlySuccessful">
                        <div class="flex items-center">
                            <Icon name="success"/>
                            <span>
                                {{ trans("general.Saved") }}
                            </span>
                        </div>
                    </action-message>

                    <action-message :on="form.failed" class="flex items-center">
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
    </admin-layout>
</template>

<script>
    import ActionMessage                from "../../Components/ActionMessage"
    import {
        BreadCrumb,
        BreadCrumbItem,
    }                                   from "../../Components/BreadCrumb"
    import PrimaryButton                from "../../Components/Buttons/PrimaryButton"
    import Icon                         from "../../Components/General/Icon"
    import AdminLayout                  from "../../Layouts/AdminLayout"
    import ApplicationPersonalForm      from "./Partials/ApplicationPersonalForm"
    import ApplicationPositionForm      from "./Partials/ApplicationPositionForm"
    import ApplicationQualificationForm from "./Partials/ApplicationQualificationForm"

    export default {
        name: "CreateApplication",
        components: {
            PrimaryButton,
            Icon,
            ActionMessage,
            AdminLayout,
            BreadCrumb,
            BreadCrumbItem,
            ApplicationPersonalForm,
            ApplicationPositionForm,
            ApplicationQualificationForm,
        },
        props: {
            applicant: { type: Object, required: false, default: () => ({}) },
            applicationTypes: { type: Array, required: true },
            newDistricts: { type: Array, required: true },
            oldDistricts: { type: Array, required: true },
            gradingSystems: { type: Array, required: true },
            majorSubjects: { type: Array, required: true },
            ethnicities: { type: Array, required: true },
            motherTongues: { type: Array, required: true },
            disabilities: { type: Array, required: true },
            mediaUploadUrl: { type: String, required: true },
            errors: { type: Object, required: false, default: () => ({}) },
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
        data() {
            return {
                form: this.$inertia.form({
                    application: {
                        application_for: "",
                        locations: [
                            {
                                priority: 1,
                                district: null,
                                municipality: null,
                                ward: null,
                            },
                        ],
                    },

                    personal: {},
                    qualification: {
                        experience: {},
                    },
                }, {
                    bag: "offlineApplicationForm",
                    resetOnSuccess: true,
                }),

                isSaving: false,
                saved: false,
                failed: false,
            }
        },
        watch: {
            applicant: {
                handler(applicant) {
                    this.$set(this.form, "application", { ...this.form.application, ...applicant.application })
                    this.$set(this.form, "personal", { ...this.form.personal, ...applicant.personal })
                    this.$set(this.form, "qualification", { ...this.form.qualification, ...applicant.qualification })

                    const locations = this.getFromObject(this.form, "application.locations", [])

                    if (locations.length === 0) {
                        this.form.application.locations = [
                            {
                                priority: 1,
                                district: null,
                                municipality: null,
                                ward: null,
                            },
                        ]
                    }
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

        methods: {
            handleOnSubmit() {
                this.form.post(this.route("admin.applications.offline-form.save", this.applicant.id), {
                    preserveState: true,
                    preserveScroll: true,
                })
            },
        },
    }
</script>

<style scoped>

</style>
