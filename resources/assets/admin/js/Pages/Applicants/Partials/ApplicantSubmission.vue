<template>
    <div class="pt-4 px-6 mb-6 bg-grey-1 rounded-md border">
        <div class="heading-primary">
            {{ trans("general.application_details") }}
        </div>
        <div class="data-block">
            <applicant-preview-data-block v-if="applicant.is_submitted" :label="trans('general.submission-number')">
                <template #value>
                    <strong>{{ applicant.submission_number }}</strong>
                </template>
            </applicant-preview-data-block>
            <applicant-preview-data-block :label="trans('general.submission-status')">
                <template #value>
                    <span :class="'font-medium text-' + applicant.status">{{ submissionStatus }}</span>
                </template>
            </applicant-preview-data-block>
            <applicant-preview-data-block
                v-if="applicant.is_submitted"
                :label="trans('application.application-status.submission-date')"
                :value="numberTrans(getFromObject(applicant, 'dates.submitted_at.date'))"/>
            <template v-else>
                <applicant-preview-data-block
                    :label="trans('application.application-status.created-date')"
                    :value="numberTrans(getFromObject(applicant, 'dates.created_at.date'))"/>
                <applicant-preview-data-block
                    :label="trans('application.application-status.last-date')"
                    :value="numberTrans(getFromObject(applicant, 'dates.last_date.date'))"/>
                <applicant-preview-data-block
                    :label="trans('application.application-status.days-remaining')"
                    :value="numberTrans(getFromObject(applicant, 'dates.remaining_days'))"/>
            </template>
        </div>
    </div>
</template>

<script>
    import ApplicantPreviewDataBlock from "./ApplicantPreviewDataBlock"

    export default {
        name: "ApplicantSubmission",
        components: { ApplicantPreviewDataBlock },
        props: {
            applicant: { type: Object, required: true },
        },
        computed: {
            submissionStatus: function() {
                if (this.applicant && this.applicant.status) {
                    return this.trans(`application.application-status.${this.applicant.status}`)
                }
                return this.trans("application.application-status.form_not_filled")
            },
        },
    }
</script>

<style lang="scss">
    .text-submitted {
        color: #32B67A;
    }

    .text-draft {
        color: #2E3133;
    }
</style>
