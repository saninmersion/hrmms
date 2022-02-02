<template>
    <div class="card !py-0">
        <div class="data-block">
            <applicant-preview-data-block :label="`Status`"
                                          class="!py-0">
                <template #value>
                    <strong v-if="isHired">
                        <span class="text-hired">
                            Hired
                        </span>
                    </strong>
                    <strong v-else-if="isRejected" class="text-danger-500 cursor-pointer">Rejected</strong>
                    <strong v-else>{{ shortlistingStatus }}</strong>
                </template>
            </applicant-preview-data-block>
        </div>
    </div>
</template>
<script>
    import ApplicantPreviewDataBlock from "../../Applicants/Partials/ApplicantPreviewDataBlock"

    const HIRED = "hired"
    const REJECTED = "rejected"

    export default {
        name: "ShortlistingStatus",
        components: { ApplicantPreviewDataBlock },
        props: {
            applicant: { type: Object, required: true },
        },
        inject: ["role"],
        computed: {
            isHired: function() {
                return this.getFromObject(this.applicant, `hiring.${this.role}.status`) === HIRED
            },
            isRejected: function() {
                return this.getFromObject(this.applicant, `hiring.${this.role}.status`) === REJECTED
            },
            shortlistingStatus: function() {
                if (this.applicant && this.getFromObject(this.applicant, `is_shortlisted.${this.role}`, false)) {
                    return "Shortlisted"
                }
                return "Not Shortlisted"
            },
        },
        methods: {
            downloadContract(applicant) {
                this.$inertia.get(this.route("admin.shortlisting.applicant.generate-contract", [applicant.id, this.role]));
            },
        },
    }
</script>
<style lang="scss">
    .text-hired {
        color: #32B67A;
    }

    .text-draft {
        color: #2E3133;
    }
</style>
