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
        </div>
    </div>
</template>

<script>

    import ApplicantPreviewDataBlock from "../../../Applicants/Partials/ApplicantPreviewDataBlock"

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
