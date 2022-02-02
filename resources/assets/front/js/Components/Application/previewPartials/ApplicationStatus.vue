<template>
    <div class="card  mb-6">
        <div class="heading-primary">
            {{ trans("general.application_details") }}
        </div>

        <div class="data-block">
            <div v-if="applicant.is_submitted" class="data-block-item">
                <div class="data-block-label">
                    {{ trans("general.submission-number") }}
                </div>
                <div class="data-block-value">
                    <strong>{{ applicant.submission_number }}</strong>
                </div>
            </div>
            <div class="data-block-item">
                <div class="data-block-label">
                    {{ trans("general.submission-status") }}
                </div>
                <div class="data-block-value">
                    {{ submissionStatus }}
                </div>
            </div>
            <div v-if="applicant.is_submitted" class="data-block-item">
                <div class="data-block-label">
                    {{ trans("application.application-status.submission-date") }}
                </div>
                <div class="data-block-value">
                    <span>{{ numberTrans(getFromObject(applicant, "application.submission_date")) }}</span>
                </div>
            </div>
            <template v-else>
                <div class="data-block-item">
                    <div class="data-block-label">
                        {{ trans("application.application-status.created-date") }}
                    </div>
                    <div class="data-block-value">
                        <span>{{ numberTrans(getFromObject(applicant, "application.created_date")) }}</span>
                    </div>
                </div>
                <div class="data-block-item">
                    <div class="data-block-label">
                        {{ trans("application.application-status.last-date") }}
                    </div>
                    <div class="data-block-value">
                        <span>{{ numberTrans(getFromObject(applicant, "application.last_date")) }}</span>
                    </div>
                </div>
                <div class="data-block-item">
                    <div class="data-block-label">
                        {{ trans("application.application-status.days-remaining") }}
                    </div>
                    <div class="data-block-value">
                        <span>{{ numberTrans(getFromObject(applicant, "application.remaining_days")) }}</span>
                    </div>
                </div>
            </template>
        </div>
    </div>
</template>

<script>
    export default {
        name: "ApplicationStatus",
        props: {
            applicant: { type: Object, required: true },
        },
        computed: {
            submissionStatus: function() {
                return this.trans(`application.application-status.${this.applicant.status}`)
            },
        },
    }
</script>

<style lang="scss">
    .submitted {
        color: #53a752;
    }

    .draft {
        color: #a75252;
    }
</style>
