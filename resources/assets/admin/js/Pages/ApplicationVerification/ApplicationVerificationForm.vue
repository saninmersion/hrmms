<template>
    <admin-layout :authorized="isAuthorized($page, 'verification')" :back-url="route('admin.assigned-applications.index')" :is-sidebar-open="false" page-title="Application Verification">
        <template #breadcrumb>
            <bread-crumb>
                <bread-crumb-item :href="route('admin.assigned-applications.index')" label="Application Verification"/>
                <bread-crumb-item :label="getFromObject(applicant, 'name_in_nepali', '')"/>
            </bread-crumb>
        </template>

        <div id="verification-form" class="flex  flex-wrap gap-8 w-full p-2">
            <!-- Personal Details -->
            <div class="w-full">
                <h2 class="font-semibold text-base-2 text-2xl">
                    {{ trans("application.sections.personal") }}
                </h2>
                <div class="flex gap-4">
                    <div class="w-3/5">
                        <application-verification-row :applicant="applicant"
                                                      :check-list="form.checklist"
                                                      :modified="form.modified"
                                                      label="नाम थर (देवनागरिमा)"
                                                      name="name_in_nepali"
                                                      @modified="handleModified"
                                                      @update_checklist="updateChecklist"/>
                        <application-verification-row :applicant="applicant"
                                                      :check-list="form.checklist"
                                                      :modified="form.modified"
                                                      label="नाम थर (अंग्रेजी)"
                                                      name="name_in_english"
                                                      @modified="handleModified"
                                                      @update_checklist="updateChecklist"/>
                        <application-verification-row :applicant="applicant"
                                                      :check-list="form.checklist"
                                                      :modified="form.modified"
                                                      label="लिङ्ग"
                                                      name="gender"
                                                      @modified="handleModified"
                                                      @update_checklist="updateChecklist">
                            <template #show="{value}">
                                <p class="col-span-4 px-2 py-1 rounded" v-text="trans(`application.gender-${value}`)"/>
                            </template>
                            <template #input="{value, handler}">
                                <drop-down-input class="!w-2/3"
                                                 :options="genderOptions"
                                                 :value="value"
                                                 @input="handler"/>
                            </template>
                        </application-verification-row>
                        <application-verification-row :applicant="applicant"
                                                      :check-list="form.checklist"
                                                      :modified="form.modified"
                                                      label="जन्म मिति"
                                                      name="dob_bs"
                                                      @modified="handleModified"
                                                      @update_checklist="updateChecklist"/>
                        <application-verification-row :applicant="applicant"
                                                      :check-list="form.checklist"
                                                      :modified="form.modified"
                                                      label="नागरिकता नं"
                                                      name="citizenship_number"
                                                      @modified="handleModified"
                                                      @update_checklist="updateChecklist"/>
                        <application-verification-row :applicant="applicant"
                                                      :check-list="form.checklist"
                                                      :modified="form.modified"
                                                      label="जारी गर्ने जिल्ला"
                                                      name="citizenship_issued_district"
                                                      @modified="handleModified"
                                                      @update_checklist="updateChecklist">
                            <template #show="{value}">
                                <p class="col-span-4 px-2 py-1 rounded" v-text="getDistrict(value)"/>
                            </template>
                            <template #input="{value, handler}">
                                <drop-down-input class="!w-2/3"

                                                 :options="districtOptions"
                                                 :value="value"
                                                 @input="handler"/>
                            </template>
                        </application-verification-row>
                        <application-verification-row :applicant="applicant"
                                                      :check-list="form.checklist"
                                                      :modified="form.modified"
                                                      label="जारी मिति"
                                                      name="citizenship_issued_date"
                                                      @modified="handleModified"
                                                      @update_checklist="updateChecklist"/>
                    </div>
                    <div class="w-2/5">
                        <div v-if="applicant.documents.citizenship_files.length > 0">
                            <image-preview :images="getFromObject(applicant.documents, 'citizenship_files')"/>
                        </div>
                        <div v-else>
                            <p class="shadow px-4 py-10 text-center text-xl">
                                No Documents Found.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Supervisor -->
            <div v-if="getFromObject(applicant, 'application_for') === 'supervisor' || getFromObject(applicant, 'application_for') === 'enumerator_supervisor'" class="w-full">
                <h2 class="font-semibold text-base-2 text-2xl">
                    {{ trans("application.sections.qualification") }} ({{ trans("application.education-for-supervisor") }})
                </h2>
                <div class="flex">
                    <div class="w-3/5">
                        <div>
                            <application-verification-row :applicant="applicant"
                                                          :check-list="form.checklist"
                                                          :modified="form.modified"
                                                          label="मूल विषय लिएकाे"
                                                          name="education_supervisor_major_subject"
                                                          @modified="handleModified"
                                                          @update_checklist="updateChecklist">
                                <template #show="{value}">
                                    <p class="col-span-4 px-2 py-1 rounded">
                                        {{ trans(`application.major_subjects.${value}`) }}
                                        <span v-if="applicant.education_supervisor_major_subject_other">( {{ applicant.education_supervisor_major_subject_other }} )</span>
                                    </p>
                                </template>
                            </application-verification-row>
                            <application-verification-row :applicant="applicant"
                                                          :check-list="form.checklist"
                                                          :modified="form.modified"
                                                          label="Grading System"
                                                          name="education_supervisor_grading_system"
                                                          @modified="handleModified"
                                                          @update_checklist="updateChecklist">
                                <template #input="{value, handler}">
                                    <drop-down-input class="!w-2/3"
                                                     :options="gradingSystemOptions"
                                                     :value="value"
                                                     @input="handler"/>
                                </template>
                            </application-verification-row>
                            <application-verification-row :applicant="applicant"
                                                          :check-list="form.checklist"
                                                          :modified="form.modified"
                                                          :label="supervisorGradingLabel"
                                                          name="education_supervisor_grade_percentage"
                                                          @modified="handleModified"
                                                          @update_checklist="updateChecklist"/>
                        </div>
                    </div>
                    <div class="w-2/5">
                        <div v-if="applicant.documents.files_for_supervisor_education.length > 0">
                            <image-preview :images="getFromObject(applicant.documents, 'files_for_supervisor_education')"/>
                        </div>
                        <div v-else>
                            <p class="shadow px-4 py-10 text-center text-xl">
                                No Documents Found.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <!--            Enumerator-->
            <div v-if="getFromObject(applicant, 'application_for') === 'enumerator' || getFromObject(applicant, 'application_for') === 'enumerator_supervisor'" class="w-full">
                <h2 class="font-semibold text-base-2 text-2xl">
                    {{ trans("application.sections.qualification") }} ({{ trans("application.education-for-enumerator") }})
                </h2>
                <div class="flex gap-4">
                    <div class="w-3/5">
                        <application-verification-row :applicant="applicant"
                                                      :check-list="form.checklist"
                                                      :modified="form.modified"
                                                      label="मूल विषय लिएकाे"
                                                      name="education_enumerator_major_subject"
                                                      @modified="handleModified"
                                                      @update_checklist="updateChecklist">
                            <template #show="{value}">
                                <p class="col-span-4 px-2 py-1 rounded">
                                    {{ trans(`application.major_subjects.${value}`) }}
                                    <span v-if="applicant.education_enumerator_major_subject_other">( {{ applicant.education_enumerator_major_subject_other }} )</span>
                                </p>
                            </template>
                        </application-verification-row>
                        <application-verification-row :applicant="applicant"
                                                      :check-list="form.checklist"
                                                      :modified="form.modified"
                                                      label="Grading System"
                                                      name="education_enumerator_grading_system"
                                                      @modified="handleModified"
                                                      @update_checklist="updateChecklist">
                            <template #input="{value, handler}">
                                <drop-down-input class="!w-2/3"
                                                 :options="gradingSystemOptions"
                                                 :value="value"
                                                 @input="handler"/>
                            </template>
                        </application-verification-row>
                        <application-verification-row :applicant="applicant"
                                                      :check-list="form.checklist"
                                                      :modified="form.modified"
                                                      :label="enumeratorGradingLabel"
                                                      name="education_enumerator_grade_percentage"
                                                      @modified="handleModified"
                                                      @update_checklist="updateChecklist"/>
                    </div>
                    <div class="w-2/5">
                        <div v-if="applicant.documents.files_for_enumerator_education.length > 0">
                            <image-preview :images="getFromObject(applicant.documents, 'files_for_enumerator_education')"/>
                        </div>
                        <div v-else>
                            <p class="shadow px-4 py-10 text-center text-xl">
                                No Documents Found.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Training -->
            <div v-if="getFromObject(applicant, 'has_training', false)" class="w-full">
                <h2 class="font-semibold text-base-2 text-2xl">
                    {{ trans("application.preview.training") }}
                </h2>
                <div class="flex">
                    <div v-if="getFromObject(applicant, 'has_training', false)" class="w-3/5">
                        <application-verification-row :applicant="applicant"
                                                      :check-list="form.checklist"
                                                      :modified="form.modified"
                                                      label="तालिम प्रदान गर्ने निकाय"
                                                      name="training_institute"
                                                      @modified="handleModified"
                                                      @update_checklist="updateChecklist"/>
                        <application-verification-row :applicant="applicant"
                                                      :check-list="form.checklist"
                                                      :modified="form.modified"
                                                      label="तालिम अवधी (दिनमा)"
                                                      name="training_period"
                                                      @modified="handleModified"
                                                      @update_checklist="updateChecklist"/>
                    </div>
                    <div class="w-2/5">
                        <div v-if="applicant.documents.training_documents.length > 0">
                            <image-preview :images="getFromObject(applicant.documents, 'training_documents')"/>
                        </div>
                        <div v-else>
                            <p class="shadow px-4 py-10 text-center text-xl">
                                No Documents Found.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Work Experience -->
            <div v-if="getFromObject(applicant, 'has_experience', false)" class="w-full">
                <h2 class="font-semibold text-base-2 text-2xl">
                    {{ trans("application.preview.experience") }}
                </h2>
                <div class="flex">
                    <div v-if="getFromObject(applicant, 'has_experience', false)" class="w-3/5">
                        <application-verification-row :applicant="applicant"
                                                      :check-list="form.checklist"
                                                      :modified="form.modified"
                                                      label="काम गरेकाे संस्थाकाे नाम"
                                                      name="experience_organization"
                                                      @modified="handleModified"
                                                      @update_checklist="updateChecklist"/>
                        <application-verification-row :applicant="applicant"
                                                      :check-list="form.checklist"
                                                      :modified="form.modified"
                                                      label="काम गरेकाे अवधी (month)"
                                                      name="experience_period_month"
                                                      @modified="handleModified"
                                                      @update_checklist="updateChecklist"/>
                        <application-verification-row :applicant="applicant"
                                                      :check-list="form.checklist"
                                                      :modified="form.modified"
                                                      label="काम गरेकाे अवधी (day)"
                                                      name="experience_period_day"
                                                      @modified="handleModified"
                                                      @update_checklist="updateChecklist"/>
                    </div>

                    <div class="w-2/5">
                        <div v-if="applicant.documents.experience_documents.length > 0">
                            <image-preview :images="getFromObject(applicant.documents, 'experience_documents')"/>
                        </div>
                        <div v-else>
                            <p class="shadow px-4 py-10 text-center text-xl">
                                No Documents Found.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Actions -->
            <div class="actions flex flex-col gap-6 w-3/5 py-8">
                <div class="flex justify-between w-full">
                    <label class="w-2/5" for="status">Status</label>
                    <drop-down-input id="status"
                                     v-model="form.verification_status"
                                     :options="verificationStatusOptions"
                                     name=""
                                     class="!w-1/2"/>
                </div>
                <div v-if="form.verification_status === 'recommended_for_rejection'" class="flex justify-between w-full">
                    <label class="w-2/5" for="rejection_reason">Reason for Rejection </label>
                    <div class="w-1/2">
                        <radio-group-input id="rejection_reason"
                                           v-model="form.rejection_reason"
                                           :options="rejectionReasonOptions"
                                           name=""/>
                    </div>
                </div>
                <div class="flex justify-between w-full">
                    <label class="w-2/5" for="remarks">Remarks</label>
                    <div class="w-1/2">
                        <textarea id="remarks"
                                  v-model="form.remarks"
                                  class="border form-input rounded-md shadow-sm block mt-1 w-full form-textarea"/>
                    </div>
                </div>
            </div>
            <div class="bg-white border-t bottom-0 py-4 sticky w-full">
                <div class="mt-4 flex justify-between">
                    <cancel-button :href="route('admin.assigned-applications.index')">
                        Cancel
                    </cancel-button>
                    <div class="flex gap-6">
                        <primary-button type="submit" @click.prevent="handleSubmit">
                            Save
                        </primary-button>
                        <primary-button type="submit" @click.prevent="handleSubmitAndNext">
                            Save and Next
                        </primary-button>
                    </div>
                </div>
            </div>
        </div>
    </admin-layout>
</template>

<script>
    import { isEmpty }                from "lodash"
    import PrimaryButton              from "../../../../shared/js/Components/Buttons/PrimaryButton"
    import DropDownInput              from "../../../../shared/js/Components/Forms/DropDownInput"
    import {
        BreadCrumb,
        BreadCrumbItem,
    }                                 from "../../Components/BreadCrumb"
    import { CancelButton }           from "../../Components/Buttons"
    import RadioGroupInput            from "../../Components/Forms/RadioGroupInput"
    import AdminLayout                from "../../Layouts/AdminLayout"
    import ApplicationVerificationRow from "./Partials/ApplicationVerificationRow"
    import ImagePreview               from "./Partials/ImagePreview"

    export default {
        name: "ApplicationVerificationForm",
        components: {
            RadioGroupInput,
            ApplicationVerificationRow,
            DropDownInput,
            PrimaryButton,
            ImagePreview,
            AdminLayout,
            BreadCrumb,
            BreadCrumbItem,
            CancelButton,
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

        data() {
            return {
                form: this.$inertia.form({
                    modified: {},
                    verification_status: "okay",
                    rejection_reason: null,
                    remarks: "",
                    checklist: {
                        valid_citizenship: true,
                        valid_transcript: true,
                        valid_plus2_certificate: true,
                        valid_training_certificate: true,
                        valid_experience_certificate: true,
                        citizenship_number: true,
                        citizenship_issued_district: true,
                        citizenship_issued_date: true,
                        dob_bs: true,
                        name_in_nepali: true,
                        name_in_english: true,
                        gender: true,
                        education_supervisor_grading_system: true,
                        education_supervisor_grade_percentage: true,
                        education_supervisor_major_subject: true,
                        education_enumerator_grading_system: true,
                        education_enumerator_grade_percentage: true,
                        education_enumerator_major_subject: true,
                        training_institute: true,
                        training_period: true,
                        experience_organization: true,
                        experience_period_day: true,
                        experience_period_month: true,
                    },
                    next: false,
                }, {
                    bag: "applicationVerificationForm",
                    resetOnSuccess: true,
                }),
            }
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
                return this.form.checklist.valid_citizenship && this.form.checklist.valid_transcript && this.form.checklist.valid_plus2_certificate
            },
            correctionNeeded() {
                return !isEmpty(this.form.modified)
            },
            enumeratorGradingLabel() {
                const gradeVal = this.getFromObject(this.form, "modified.education_enumerator_grading_system")
                if (!this.form.checklist.education_enumerator_grading_system && gradeVal) {
                    return this.trans(`application.grading_system.${gradeVal}`)
                }
                return this.trans(`application.grading_system.${this.getFromObject(this.applicant, "education_enumerator_grading_system", "")}`)
            },
            supervisorGradingLabel() {
                const gradeVal = this.getFromObject(this.form, "modified.education_supervisor_grading_system")
                if (!this.form.checklist.education_supervisor_grading_system && gradeVal) {
                    return this.trans(`application.grading_system.${gradeVal}`)
                }
                return this.trans(`application.grading_system.${this.getFromObject(this.applicant, "education_supervisor_grading_system", "")}`)
            },
        },

        mounted() {
            this.$nextTick(() => {
                this.resetChecklist()

                if (this.verifiedData) {
                    this.$set(this.form, "modified", { ...this.form.modified, ...this.verifiedData.modified })
                    this.$set(this.form, "checklist", { ...this.form.checklist, ...this.verifiedData.checklist })
                    this.$set(this.form, "verification_status", this.verifiedData.verification_status)
                    this.$set(this.form, "remarks", this.verifiedData.remarks)
                    this.$set(this.form, "rejection_reason", this.verifiedData.rejection_reason)
                }
            })
        },

        methods: {
            async handleSubmit() {
                this.form.post(this.route("admin.assigned-applications.verify", this.applicant.id), {
                    preserveState: false,
                })
            },
            async handleSubmitAndNext() {
                this.$set(this.form, "next", true)
                this.form.post(this.route("admin.assigned-applications.verify", this.applicant.id), {
                    preserveState: false,
                })
            },

            updateData(v) {
                if (v) {
                    this.$set(this.form, "modified", { ...this.form.modified, ...v })
                    if (this.correctionNeeded) {
                        this.$set(this.form, "verification_status", "correction_needed")
                    }
                }
            },
            updateChecklist(v) {
                this.$set(this.form, "checklist", { ...this.form.checklist, ...v })
                if (this.isOkay) {
                    this.$set(this.form, "verification_status", "okay")
                }
            },
            handleModified({ name, value }) {
                if (this.getFromObject(this.applicant, name) === value) {
                    this.$set(this.form.modified, name, null)
                    return
                }
                this.$set(this.form, "verification_status", "correction_needed")

                this.$set(this.form.modified, name, value)
            },
            getDistrict(code) {
                const key = this.$currentLocale === "en" ? "title_en" : "title_ne"
                const district = this.options.districts.filter(d => parseInt(d.code) === parseInt(code))

                return district[0][key]
            },
            resetChecklist() {
                const checkList = {
                    valid_citizenship: true,
                    valid_transcript: true,
                    valid_plus2_certificate: true,
                    valid_training_certificate: true,
                    valid_experience_certificate: true,
                    citizenship_number: true,
                    citizenship_issued_district: true,
                    citizenship_issued_date: true,
                    dob_bs: true,
                    name_in_nepali: true,
                    name_in_english: true,
                    gender: true,
                    education_supervisor_grade_percentage: true,
                    education_supervisor_grading_system: true,
                    education_supervisor_major_subject: true,
                    education_enumerator_grading_system: true,
                    education_enumerator_grade_percentage: true,
                    education_enumerator_major_subject: true,
                    training_institute: true,
                    training_period: true,
                    experience_organization: true,
                    experience_period_day: true,
                    experience_period_month: true,
                }
                this.$set(this.form, "checklist", checkList)
            },
        },
    }
</script>

<style scoped>

</style>
