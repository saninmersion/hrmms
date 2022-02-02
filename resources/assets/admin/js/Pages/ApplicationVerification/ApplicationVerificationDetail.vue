<template>
    <admin-layout :authorized="isAuthorized($page, 'user')" :back-url="route('admin.verifications.user', verifiedData.verifier_id)" :is-sidebar-open="false" page-title="Application Verification">
        <template #breadcrumb>
            <bread-crumb>
                <bread-crumb-item :href="route('admin.verifications.user', verifiedData.verifier_id)" label="Application Verification"/>
                <bread-crumb-item :label="getFromObject(applicant, 'name_in_nepali', '')"/>
            </bread-crumb>
        </template>

        <div id="verification-show" class="w-full flex justify-between flex-wrap">
            <div class="w-1/2">
                <!-- Personal Details -->
                <div class="mb-6 py-2 px-8">
                    <h2 class="font-semibold text-xl mb-6">
                        {{ trans("application.sections.personal") }}
                    </h2>
                    <application-verification-detail-row :applicant="applicant"
                                                         :check-list="verifiedData.checklist"
                                                         :modified="verifiedData.modified"
                                                         label="नाम थर (देवनागरिमा)"
                                                         name="name_in_nepali"/>
                    <application-verification-detail-row :applicant="applicant"
                                                         :check-list="verifiedData.checklist"
                                                         :modified="verifiedData.modified"
                                                         label="नाम थर (अंग्रेजी)"
                                                         name="name_in_english"/>
                    <application-verification-detail-row :applicant="applicant"
                                                         :check-list="verifiedData.checklist"
                                                         :modified="verifiedData.modified"
                                                         label="लिङ्ग"
                                                         name="gender">
                        <template #show="{value}">
                            <p class="col-span-4 px-2 py-1 rounded" v-text="trans(`application.gender-${value}`)"/>
                        </template>
                        <template v-if="value" #input="{value}">
                            <p class="col-span-4 px-2 py-1 rounded" v-text="trans(`application.gender-${value}`)"/>
                        </template>
                    </application-verification-detail-row>
                    <application-verification-detail-row :applicant="applicant"
                                                         :check-list="verifiedData.checklist"
                                                         :modified="verifiedData.modified"
                                                         label="जन्म मिति"
                                                         name="dob_bs"/>
                    <application-verification-detail-row :applicant="applicant"
                                                         :check-list="verifiedData.checklist"
                                                         :modified="verifiedData.modified"
                                                         label="नागरिकता नं"
                                                         name="citizenship_number"/>
                    <application-verification-detail-row :applicant="applicant"
                                                         :check-list="verifiedData.checklist"
                                                         :modified="verifiedData.modified"
                                                         label="जारी गर्ने जिल्ला"
                                                         name="citizenship_issued_district">
                        <template #show="{value}">
                            <p class="col-span-4 px-2 py-1 rounded" v-text="getDistrict(value)"/>
                        </template>
                        <template #input="{value}">
                            <p class="col-span-4 px-2 py-1 rounded" v-text="getDistrict(value)"/>
                        </template>
                    </application-verification-detail-row>
                    <application-verification-detail-row :applicant="applicant"
                                                         :check-list="verifiedData.checklist"
                                                         :modified="verifiedData.modified"
                                                         label="जारी मिति"
                                                         name="citizenship_issued_date"/>
                </div>
            </div>
            <div class="w-1/2">
                <div v-if="getFromObject(applicant.documents, 'citizenship_files', false)">
                    <image-preview :images="getFromObject(applicant.documents, 'citizenship_files')"/>
                </div>
            </div>
            <div class="w-1/2">
                <!-- Supervisor -->
                <div
                    v-if="getFromObject(applicant, 'application_for') === 'supervisor' || getFromObject(applicant, 'application_for') === 'enumerator_supervisor'"
                    class="mb-6 py-2 px-8">
                    <h2 class="font-semibold text-xl mb-6">
                        {{ trans("application.sections.qualification") }} ({{ trans("application.education-for-supervisor") }})
                    </h2>
                    <application-verification-detail-row :applicant="applicant"
                                                         :check-list="verifiedData.checklist"
                                                         :modified="verifiedData.modified"
                                                         label="मूल विषय लिएकाे"
                                                         name="education_supervisor_major_subject">
                        <template #show="{value}">
                            <p class="col-span-4 px-2 py-1 rounded">
                                {{ trans(`application.major_subjects.${value}`) }}
                                <span v-if="applicant.education_supervisor_major_subject_other">( {{ applicant.education_supervisor_major_subject_other }} )</span>
                            </p>
                        </template>
                        <template v-if="value" #input="{value}">
                            <p class="col-span-4 px-2 py-1 rounded">
                                {{ trans(`application.major_subjects.${value}`) }}
                                <span v-if="applicant.education_supervisor_major_subject_other">( {{ applicant.education_supervisor_major_subject_other }} )</span>
                            </p>
                        </template>
                    </application-verification-detail-row>
                    <application-verification-detail-row :applicant="applicant"
                                                         :check-list="verifiedData.checklist"
                                                         :modified="verifiedData.modified"
                                                         label="Grading System"
                                                         name="education_supervisor_grading_system">
                        <template v-if="value" #input="{value}">
                            <p class="col-span-4 px-2 py-1 rounded" v-text="value"/>
                        </template>
                    </application-verification-detail-row>
                    <application-verification-detail-row :applicant="applicant"
                                                         :check-list="verifiedData.checklist"
                                                         :label="supervisorGradingLabel"
                                                         :modified="verifiedData.modified"
                                                         name="education_supervisor_grade_percentage"/>
                </div>
            </div>

            <div class="w-1/2">
                <div v-if="getFromObject(applicant.documents, 'files_for_supervisor_education', false)">
                    <image-preview :images="getFromObject(applicant.documents, 'files_for_supervisor_education')"/>
                </div>
            </div>
            <div class="w-1/2">
                <!-- Enumerator -->
                <div
                    v-if="getFromObject(applicant, 'application_for') === 'enumerator' || getFromObject(applicant, 'application_for') === 'enumerator_supervisor'"
                    class="mb-6 py-2 px-8">
                    <h2 class="font-semibold text-xl mb-6">
                        {{ trans("application.sections.qualification") }} ({{ trans("application.education-for-enumerator") }})
                    </h2>
                    <application-verification-detail-row :applicant="applicant"
                                                         :check-list="verifiedData.checklist"
                                                         :modified="verifiedData.modified"
                                                         label="मूल विषय लिएकाे"
                                                         name="education_enumerator_major_subject">
                        <template #show="{value}">
                            <p class="col-span-4 px-2 py-1 rounded">
                                {{ trans(`application.major_subjects.${value}`) }}
                                <span v-if="applicant.education_enumerator_major_subject_other">( {{ applicant.education_enumerator_major_subject_other }} )</span>
                            </p>
                        </template>
                        <template v-if="value" #input="{value}">
                            <p class="col-span-4 px-2 py-1 rounded">
                                {{ trans(`application.major_subjects.${value}`) }}
                                <span v-if="applicant.education_enumerator_major_subject_other">( {{ applicant.education_enumerator_major_subject_other }} )</span>
                            </p>
                        </template>
                    </application-verification-detail-row>
                    <application-verification-detail-row :applicant="applicant"
                                                         :check-list="verifiedData.checklist"
                                                         :modified="verifiedData.modified"
                                                         label="Grading System"
                                                         name="education_enumerator_grading_system"/>
                    <application-verification-detail-row :applicant="applicant"
                                                         :check-list="verifiedData.checklist"
                                                         :label="enumeratorGradingLabel"
                                                         :modified="verifiedData.modified"
                                                         name="education_enumerator_grade_percentage"/>
                </div>
            </div>
            <div class="w-1/2">
                <div v-if="getFromObject(applicant.documents, 'files_for_enumerator_education', false)">
                    <image-preview :images="getFromObject(applicant.documents, 'files_for_enumerator_education')"/>
                </div>
            </div>
            <div class="w-1/2">
                <!-- Training -->

                <div v-if="getFromObject(applicant, 'has_training', false)" class="mb-6 py-2 px-8">
                    <h2 class="font-semibold text-xl mb-6">
                        {{ trans("application.preview.training") }}
                    </h2>
                    <application-verification-detail-row :applicant="applicant"
                                                         :check-list="verifiedData.checklist"
                                                         :modified="verifiedData.modified"
                                                         label="तालिम प्रदान गर्ने निकाय"
                                                         name="training_institute"/>
                    <application-verification-detail-row :applicant="applicant"
                                                         :check-list="verifiedData.checklist"
                                                         :modified="verifiedData.modified"
                                                         label="तालिम अवधी (दिनमा)"
                                                         name="training_period"/>
                </div>
            </div>
            <div class="w-1/2">
                <div v-if="getFromObject(applicant.documents, 'training_documents', false)">
                    <image-preview :images="getFromObject(applicant.documents, 'training_documents')"/>
                </div>
            </div>
            <div class="w-1/2">
                <!-- Work Experience -->

                <div v-if="getFromObject(applicant, 'has_experience', false)" class="mb-6 py-2 px-8">
                    <h2 class="font-semibold text-xl mb-6">
                        {{ trans("application.preview.experience") }}
                    </h2>
                    <application-verification-detail-row :applicant="applicant"
                                                         :check-list="verifiedData.checklist"
                                                         :modified="verifiedData.modified"
                                                         label="काम गरेकाे संस्थाकाे नाम"
                                                         name="experience_organization"/>
                    <application-verification-detail-row :applicant="applicant"
                                                         :check-list="verifiedData.checklist"
                                                         :modified="verifiedData.modified"
                                                         label="काम गरेकाे अवधी (month)"
                                                         name="experience_period_month"/>
                    <application-verification-detail-row :applicant="applicant"
                                                         :check-list="verifiedData.checklist"
                                                         :modified="verifiedData.modified"
                                                         label="काम गरेकाे अवधी (day)"
                                                         name="experience_period_day"/>
                </div>
            </div>
            <div class="w-1/2">
                <div v-if="getFromObject(applicant.documents, 'experience_documents', false)">
                    <image-preview :images="getFromObject(applicant.documents, 'experience_documents')"/>
                </div>
            </div>
            <div class="w-1/2">
                <!-- Actions -->
                <div class="actions mb-6 py-2 px-8">
                    <div class="grid grid-cols-3">
                        <label class="col-span-1" for="status">Status</label>
                        <div class="col-span-2">
                            <drop-down-input id="status"
                                             :options="verificationStatusOptions"
                                             :value="verifiedData.verification_status"
                                             class="flex-col gap-1"
                                             name=""/>
                        </div>
                    </div>
                    <div v-if="verifiedData.verification_status === 'recommended_for_rejection'" class="grid grid-cols-3">
                        <label class="col-span-1" for="rejection_reason">Reason for Rejection </label>
                        <div class="col-span-2">
                            <radio-group-input id="rejection_reason"
                                               :options="rejectionReasonOptions"
                                               :value="verifiedData.rejection_reason"
                                               class="pt-2 pb-4"
                                               name=""/>
                        </div>
                    </div>
                    <div class="grid grid-cols-3">
                        <label class="col-span-1" for="remarks">Remarks</label>
                        <textarea id="remarks"
                                  :value="verifiedData.remarks"
                                  class="col-span-2 border form-input rounded-md shadow-sm block mt-1 w-full form-textarea"/>
                    </div>
                </div>
            </div>
        </div>
    </admin-layout>
</template>

<script>
    import { isEmpty }                      from "lodash"
    import DropDownInput                    from "../../../../shared/js/Components/Forms/DropDownInput"
    import {
        BreadCrumb,
        BreadCrumbItem,
    }                                       from "../../Components/BreadCrumb"
    import RadioGroupInput                  from "../../Components/Forms/RadioGroupInput"
    import AdminLayout                      from "../../Layouts/AdminLayout"
    import ApplicationVerificationDetailRow from "./Partials/ApplicationVerificationDetailRow"
    import ImagePreview                     from "./Partials/ImagePreview"

    export default {
        name: "ApplicationVerificationForm",
        components: {
            RadioGroupInput,
            ApplicationVerificationDetailRow,
            DropDownInput,
            ImagePreview,
            AdminLayout,
            BreadCrumb,
            BreadCrumbItem,
        },

        provide() {
            return {
                options: this.options,
            }
        },

        props: {
            applicant: { type: Object, required: true },
            verifiedData: { type: Object, default: () => {} },
            options: { type: Object, required: true },
        },

        computed: {
            verificationStatusOptions() {
                const options = new Map()

                this.options.verificationTypes.forEach(type => {
                    if (type !== "not_verified") {
                        options.set(type, this.trans(`admin-application.verification_status-${type}`))
                    }
                })

                return options
            },
            genderOptions() {
                const options = new Map()

                this.options.genders.forEach(type => {
                    options.set(type, this.trans(`application.gender-${type}`))
                })

                return options
            },
            gradingSystemOptions() {
                const options = new Map()

                this.options.gradingSystems.forEach(type => {
                    options.set(type, this.trans(`application.grading_system.${type}`))
                })

                return options
            },
            districtOptions: function() {
                const options = new Map()
                const key = this.$currentLocale === "en" ? "title_en" : "title_ne"

                this.options.districts.forEach(district => {
                    options.set(parseInt(district.code), district[key])
                })

                return options
            },
            rejectionReasonOptions() {
                const options = new Map()

                options.set("invalid_citizenship", "Invalid Citizenship")
                options.set("invalid_transcript", "Invalid Transcript")
                options.set("other", "Other")

                return options
            },
            isOkay() {
                return this.verifiedData.checklist.valid_citizenship && this.verifiedData.checklist.valid_transcript && this.verifiedData.checklist.valid_plus2_certificate
            },
            correctionNeeded() {
                return !isEmpty(this.verifiedData.modified)
            },
            enumeratorGradingLabel() {
                const gradeVal = this.getFromObject(this.form, "modified.education_enumerator_grading_system")
                if (!this.verifiedData.checklist.education_enumerator_grading_system && gradeVal) {
                    return this.trans(`application.grading_system.${gradeVal}`)
                }
                return this.trans(`application.grading_system.${this.getFromObject(this.applicant, "education_enumerator_grading_system", "")}`)
            },
            supervisorGradingLabel() {
                const gradeVal = this.getFromObject(this.form, "modified.education_supervisor_grading_system")
                if (!this.verifiedData.checklist.education_supervisor_grading_system && gradeVal) {
                    return this.trans(`application.grading_system.${gradeVal}`)
                }
                return this.trans(`application.grading_system.${this.getFromObject(this.applicant, "education_supervisor_grading_system", "")}`)
            },
        },

        methods: {
            getDistrict(code) {
                const key = this.$currentLocale === "en" ? "title_en" : "title_ne"
                const district = this.options.districts.filter(d => parseInt(d.code) === parseInt(code))

                return district[0][key]
            },
        },
    }
</script>

<style scoped>

</style>
