<template>
    <div class="pt-4 px-6 mb-6 bg-grey-1 rounded-md border">
        <h2 class="heading-primary">
            {{ trans("application.sections.position") }}
        </h2>
        <div class="data-block !pb-0">
            <applicant-preview-data-block
                :label="trans('application.preview.application_position')"
                :value="applicant.application_for ? trans(`application.preview.position-${applicant.application_for}`) : ''"/>
            <applicant-preview-data-block
                :label="trans('application.preview.application_locations')">
                <template #value>
                    <ul>
                        <li v-if="applicant.location.district || applicant.location.municipality"
                            class="data-block-value block">
                            {{ formatLocation(applicant.location) }}
                        </li>
                    </ul>
                </template>
            </applicant-preview-data-block>
        </div>
    </div>
</template>

<script>

    import ApplicantPreviewDataBlock from "../../../Applicants/Partials/ApplicantPreviewDataBlock"

    export default {
        name: "ApplicantPosition",
        components: { ApplicantPreviewDataBlock },
        props: {
            applicant: { type: Object, required: true },
        },
        methods: {
            formatLocation(location) {
                if (!location.district) {
                    return ""
                }

                const formatted = []

                formatted.unshift(location.district)

                if (!location.municipality) {
                    return formatted.join(", ")
                }

                formatted.unshift(location.municipality)

                if (location.ward) {
                    formatted.unshift(this.trans("application.preview.ward_no_", {
                        ":attr": this.numberTrans(location.ward),
                    }))
                }

                return formatted.join(", ")
            },
        },
    }
</script>

<style scoped>

</style>
