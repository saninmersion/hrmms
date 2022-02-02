<template>
    <div class="py-4 px-6 bg-grey-1 rounded-md border">
        <h2 class="heading-primary">
            {{ trans("application.sections.qualification") }}
        </h2>
        <div v-if="supervisorSelected">
            <div class="heading-primary heading-primary--sm">
                {{ trans("application.education-for-supervisor") }}
            </div>
            <div class="order-2 lg:order-1 data-block  border-b border-blue-200 pt-4">
                <applicant-preview-data-block
                    :label="trans('application.preview.academic_degree')"
                    :value="trans('application.preview.level-graduate')"/>
                <applicant-preview-data-block
                    :label="trans('application.preview.major_subject')"
                    :value="formattedMajorSubject(getFromObject(applicant, 'qualification.education.supervisor'))"/>
                <applicant-preview-data-block
                    :label="formatGradeOrPercentage(getFromObject(applicant, 'qualification.education.supervisor.grading_system'))"
                    :value="formatScore(getFromObject(applicant, 'qualification.education.supervisor'))"/>
            </div>
        </div>
        <div v-if="enumeratorSelected">
            <div class="heading-primary heading-primary--sm">
                {{ trans("application.education-for-enumerator") }}
            </div>
            <div class="order-2 lg:order-1 data-block  border-b border-blue-200 pt-4">
                <applicant-preview-data-block
                    :label="trans('application.preview.academic_degree')"
                    :value="trans('application.preview.level-plus2')"/>
                <applicant-preview-data-block
                    :label="formatGradeOrPercentage(getFromObject(applicant, 'qualification.education.enumerator.grading_system'))"
                    :value="formatScore(getFromObject(applicant, 'qualification.education.enumerator'))"/>
            </div>
        </div>
        <div class="data-block">
            <label
                v-text="trans('application.preview.extra_education')"/>
            <div class=" order-2 lg:order-1 data-block  border-b border-blue-200 pt-4">
                <applicant-preview-data-block
                    :label="trans('application.preview.major_subject')"
                    :value="formattedMajorSubject(getFromObject(applicant, 'qualification.education.extra'))"/>
            </div>
        </div>
        <div class="pb-4 data-block">
            <applicant-preview-data-block
                :label="trans('application.preview.has_training')"
                :value="formatBoolean(getFromObject(applicant, 'qualification.has_training'))"/>
        </div>
        <div v-if="getFromObject(applicant, 'qualification.has_training', false)">
            <h2 class="heading-primary heading-primary--sm">
                {{ trans("application.preview.training") }}
            </h2>

            <div class=" order-2 lg:order-1 data-block  border-b border-blue-200 pt-4">
                <applicant-preview-data-block
                    :label="trans('application.fields.training_organization')"
                    :value="getFromObject(applicant, 'qualification.training.institute')"/>
                <applicant-preview-data-block
                    :label="trans('application.fields.training_period')"
                    :value="formatTrainingPeriod(getFromObject(applicant, 'qualification.training.period'))"/>
            </div>
        </div>
        <div class=" order-2 lg:order-1 data-block pt-4">
            <applicant-preview-data-block
                :label="trans('application.preview.has_experience')"
                :value="formatBoolean(getFromObject(applicant, 'qualification.has_experience'))"/>
        </div>
        <div v-if="getFromObject(applicant, 'qualification.has_experience', false)">
            <div class="heading-primary heading-primary--sm">
                {{ trans("application.preview.experience") }}
            </div>

            <div class=" order-2 lg:order-1 data-block">
                <applicant-preview-data-block
                    :label="trans('application.fields.experience_organization')"
                    :value="getFromObject(applicant, 'qualification.experience.organization')"/>
                <applicant-preview-data-block
                    :label="trans('application.fields.experience_period')"
                    :value="formatExperiencePeriod(getFromObject(applicant, 'qualification.experience'))"/>
            </div>
        </div>
    </div>
</template>

<script>
    import ApplicantPreviewDataBlock from "../../Applicants/Partials/ApplicantPreviewDataBlock"

    const SUPERVISOR = "supervisor"
    const ENUMERATOR = "enumerator"
    export default {
        name: "ShortListDetailQualification",

        components: { ApplicantPreviewDataBlock },

        props: {
            applicant: { type: Object, required: true },
        },

        inject: ["role"],

        data: () => ({}),

        computed: {
            supervisorSelected: function() {
                return this.role === SUPERVISOR
            },

            enumeratorSelected: function() {
                return this.role === ENUMERATOR
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
                    formatted.push(`${this.numberTrans(experience.period_month)} ${this.trans("general.month")}`)
                }

                if (experience.period_day) {
                    formatted.push(`${this.numberTrans(experience.period_day)} ${this.trans("general.day")}`)
                }

                return formatted.join(" ")
            },
        },

    }
</script>
