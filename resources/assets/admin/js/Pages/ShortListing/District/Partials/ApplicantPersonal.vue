<template>
    <div class="py-4 px-6 mb-6 bg-grey-1 rounded-md border">
        <h2 class="heading-primary">
            {{ trans("application.sections.personal") }}
        </h2>

        <!-- Personal -->
        <div class="flex flex-wrap">
            <div class="order-2 lg:order-1 data-block  border-b border-blue-200">
                <applicant-preview-data-block
                    :label="trans('application.preview.name_in_nepali')"
                    :value="applicant.personal.name_in_nepali"/>
                <applicant-preview-data-block
                    :label="trans('application.preview.name_in_english')"
                    :value="applicant.personal.name_in_english"/>
                <applicant-preview-data-block
                    :label="trans('application.fields.gender') ">
                    <template #value>
                        <span v-if="getFromObject(applicant, 'personal.gender')"
                              v-text="trans(`application.gender-${getFromObject(applicant, 'personal.gender')}`)"/>
                    </template>
                </applicant-preview-data-block>
                <applicant-preview-data-block
                    :label="trans('application.fields.dob')">
                    <template #value>
                        <span>
                            {{ trans("application.calendar_bs") }}
                            {{ numberTrans(getFromObject(applicant, "personal.dob_bs")) }}
                        </span>
                        /
                        <span>
                            {{ trans("application.calendar_ad") }}
                            {{ numberTrans(getFromObject(applicant, "personal.dob_ad")) }}
                        </span>
                    </template>
                </applicant-preview-data-block>
            </div>
        </div>

        <!-- Citizenship -->
        <div>
            <div class="heading-primary heading-primary--sm pt-4">
                {{ trans("application.fields.citizenship") }}
            </div>
            <div class="flex flex-wrap">
                <div class="lg:w-3/4 order-2 lg:order-1 data-block  border-b border-blue-200">
                    <applicant-preview-data-block
                        :label="trans('application.fields.citizenship_no')"
                        :value="numberTrans(getFromObject(applicant, 'personal.citizenship.number'))"/>
                    <applicant-preview-data-block
                        :label="trans('application.fields.citizenship_issued_district')"
                        :value="getFromObject(applicant, 'personal.citizenship.issued_district', '-')"/>
                    <applicant-preview-data-block
                        :label="trans('application.fields.citizenship_issued_date')">
                        <template #value>
                            <span
                                v-if="getFromObject(applicant, 'personal.citizenship.issued_date', '-')"
                                v-text="numberTrans(getFromObject(applicant, 'personal.citizenship.issued_date', ''))"/>
                        </template>
                    </applicant-preview-data-block>
                </div>
            </div>
        </div>

        <!-- Others -->
        <div class="data-block lg:w-3/4 ">
            <applicant-preview-data-block
                :label="trans('application.fields.mobile_number')"
                :value="numberTrans(getFromObject(applicant, 'personal.mobile_number'))"/>
            <applicant-preview-data-block
                :label="trans('application.fields.grand_father_name')"
                :value="numberTrans(getFromObject(applicant, 'personal.grand_father_name'))"/>
            <applicant-preview-data-block
                :label="trans('application.fields.father_name')"
                :value="numberTrans(getFromObject(applicant, 'personal.father_name'))"/>
        </div>
    </div>
</template>

<script>
    import ApplicantPreviewDataBlock from "../../../Applicants/Partials/ApplicantPreviewDataBlock"

    export default {
        name: "ApplicantPersonal",
        components: { ApplicantPreviewDataBlock },
        props: {
            applicant: { type: Object, required: true },
        },
        data() {
            return {}
        },
    }
</script>
