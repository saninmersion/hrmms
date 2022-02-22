<template>
    <div class="card card-js-scroll">
        <div class="heading-primary">
            {{ trans("application.sections.qualification") }}
        </div>

        <div class="data-block lg:w-3/4 !py-0">
            <div class="data-block-item">
                <div class="data-block-label">
                    {{ trans("application.preview.has_education_qualification") }}
                </div>
                <div class="data-block-value">
                    <span
                        v-text="formatBoolean(getFromObject(applicant, 'qualification.has_education_qualification'))"/>
                </div>
            </div>
        </div>

        <template v-if="getFromObject(applicant, 'qualification.has_education_qualification')">
            <div v-if="supervisorSelected">
                <div class="heading-primary heading-primary--sm">
                    {{ trans("application.education-for-supervisor") }}
                </div>

                <div class="flex flex-wrap">
                    <div class="data-block lg:w-3/4 ">
                        <div class="data-block-item">
                            <div class="data-block-label">
                                {{ trans("application.preview.academic_degree") }}
                            </div>
                            <div class="data-block-value">
                                <span>{{ trans("application.preview.level-graduate") }}</span>
                            </div>
                        </div>

                        <div class="data-block-item">
                            <div class="data-block-label">
                                {{ trans("application.preview.major_subject") }}
                            </div>
                            <div class="data-block-value">
                                <span
                                    v-text="formattedMajorSubject(getFromObject(applicant, 'qualification.education.supervisor')) || '-'"/>
                            </div>
                        </div>

                        <div class="data-block-item">
                            <div class="data-block-label"
                                 v-text="formatGradeOrPercentage(getFromObject(applicant, 'qualification.education.supervisor.grading_system'))"/>
                            <div class="data-block-value">
                                <span
                                    v-text="formatScore(getFromObject(applicant, 'qualification.education.supervisor')) || '-'"/>
                            </div>
                        </div>
                    </div>
                    <div
                        v-if="getFromObject(applicant, 'qualification.files_for_supervisor_education') && isArray(applicant.qualification.files_for_supervisor_education)"
                        class="data-file slider-doc">
                        <Slider :images="applicant.qualification.files_for_supervisor_education"/>
                    </div>
                </div>
            </div>

            <div v-if="enumeratorSelected">
                <div class="heading-primary heading-primary--sm">
                    {{ trans("application.education-for-enumerator") }}
                </div>

                <div class="flex flex-wrap">
                    <div class="data-block lg:w-3/4 ">
                        <div class="data-block-item">
                            <div class="data-block-label">
                                {{ trans("application.preview.academic_degree") }}
                            </div>
                            <div class="data-block-value">
                                <span>{{ trans("application.preview.level-plus2") }}</span>
                            </div>
                        </div>

                        <div class="data-block-item">
                            <div class="data-block-label"
                                 v-text="formatGradeOrPercentage(getFromObject(applicant, 'qualification.education.enumerator.grading_system'))"/>
                            <div class="data-block-value">
                                <span
                                    v-text="formatScore(getFromObject(applicant, 'qualification.education.enumerator')) || '-'"/>
                            </div>
                        </div>
                    </div>
                    <div
                        v-if="getFromObject(applicant, 'qualification.files_for_enumerator_education') && isArray(applicant.qualification.files_for_enumerator_education)"
                        class="data-file slider-doc">
                        <Slider :images=" applicant.qualification.files_for_enumerator_education"/>
                    </div>
                </div>
            </div>

            <div class="flex flex-wrap ">
                <div class="data-block lg:w-3/4 border-t border-blue-200">
                    <div class="data-block-item">
                        <div class="data-block-label">
                            {{ trans("application.preview.extra_education") }}
                        </div>
                    </div>

                    <div class="data-block-item">
                        <div class="data-block-label">
                            {{ trans("application.preview.major_subject") }}
                        </div>
                        <div class="data-block-value">
                            <span
                                v-text="formattedMajorSubject(getFromObject(applicant, 'qualification.education.extra')) || '-'"/>
                        </div>
                    </div>
                </div>
                <div
                    v-if="getFromObject(applicant, 'qualification.files_for_extra_education') && isArray(applicant.qualification.files_for_extra_education)"
                    class="data-file slider-doc mt-10">
                    <Slider :images="applicant.qualification.files_for_extra_education"/>
                </div>
            </div>
        </template>

        <div class="data-block">
            <div class="lg:w-3/4 pt-6 border-t border-blue-200">
                <div class="data-block-item">
                    <div class="data-block-label">
                        {{ trans("application.preview.has_experience") }}
                    </div>
                    <div class="data-block-value">
                        <span
                            v-text="formatBoolean(getFromObject(applicant, 'qualification.has_experience'))"/>
                    </div>
                </div>
            </div>
            <div v-if="getFromObject(applicant, 'qualification.has_experience', false)" class="flex flex-wrap">
                <div class="lg:w-3/4">
                    <div class="heading-primary heading-primary--sm">
                        {{ trans("application.preview.experience") }}
                    </div>

                    <div class="data-block-item">
                        <div class="data-block-label">
                            {{ trans("application.fields.experience_organization") }}
                        </div>
                        <div class="data-block-value">
                            <span v-text="getFromObject(applicant, 'qualification.experience.organization') || '-'"/>
                        </div>
                    </div>

                    <div class="data-block-item">
                        <div class="data-block-label">
                            {{ trans("application.fields.experience_period") }}
                        </div>
                        <div class="data-block-value">
                            <span v-text="formatExperiencePeriod(getFromObject(applicant, 'qualification.experience')) || '-'"/>
                        </div>
                    </div>
                </div>
                <div
                    v-if="getFromObject(applicant, 'qualification.experience_documents') && isArray(applicant.qualification.experience_documents)"
                    class="data-file slider-doc">
                    <Slider :images="applicant.qualification.experience_documents"/>
                </div>
            </div>
        </div>
    </div>
</template>

<script type="text/ecmascript-6">
    import Slider from "../../Common/Slider"

    export default {
        name: "ApplicationQualification",

        components: { Slider },

        props: {
            applicant: { type: Object, required: true },
        },

        data: () => ({}),

        computed: {
            supervisorSelected: function() {
                return ["supervisor", "enumerator_supervisor"].includes(
                    this.getFromObject(this.applicant, "application.application_for"),
                )
            },

            enumeratorSelected: function() {
                return ["enumerator", "enumerator_supervisor"].includes(
                    this.getFromObject(this.applicant, "application.application_for"),
                )
            },
        },

        methods: {
            formatBoolean(value) {
                if (value) {
                    return this.trans("general.yes_i_have")
                }

                return this.trans("general.no_i_have_not")
            },

            formattedMajorSubject(data) {
                if (!data || !data.major_subject) {
                    return ""
                }

                if (data.major_subject === "others") {
                    return data.major_subject_other
                }

                return this.trans(`application.major_subjects.${data.major_subject}`)
            },

            formatGradeOrPercentage(gradingSystem) {
                if (!gradingSystem) {
                    return this.trans("application.grading_system.percentage")
                }

                return this.trans(`application.grading_system.${gradingSystem}`)
            },

            formatScore(education) {
                const gradingSystem = education.grading_system || "percentage"

                if (gradingSystem === "percentage") {
                    return this.numberTrans(`${education.percentage || "-"} %`)
                }

                return this.numberTrans(education.grade || "")
            },

            formatTrainingPeriod(period) {
                if (!period) {
                    return ""
                }

                return `${this.numberTrans(period)} ${this.trans("general.day")}`
            },

            formatExperiencePeriod(experience) {
                const formatted = []

                if (experience.period_month) {
                    formatted.push(`${experience.period_month} ${this.trans("general.month")}`)
                }

                if (experience.period_day) {
                    formatted.push(`${experience.period_day} ${this.trans("general.day")}`)
                }

                return formatted.join(" ")
            },
        },
    }
</script>
