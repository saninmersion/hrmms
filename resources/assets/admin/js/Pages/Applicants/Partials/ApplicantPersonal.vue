<template>
    <div class="py-4 px-6 mb-6 bg-grey-1 rounded-md border">
        <h2 class="heading-primary">
            {{ trans("application.sections.personal") }}
        </h2>

        <!-- Personal -->
        <div class="flex flex-wrap">
            <div class="lg:w-3/4 order-2 lg:order-1 data-block  border-b border-blue-200">
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
                <applicant-preview-data-block :label="trans('application.fields.ethnicity')">
                    <template #value>
                        <span v-if="getFromObject(applicant, 'personal.ethnicity')">
                            {{
                                (getFromObject(applicant, "personal.ethnicity") !== "other")
                                    ? trans(`caste.${getFromObject(applicant, "personal.ethnicity")}`) : getFromObject(applicant, "personal.ethnicity_other")
                            }}
                        </span>
                        <span v-if="!getFromObject(applicant, 'personal.ethnicity')">-</span>
                    </template>
                </applicant-preview-data-block>
                <applicant-preview-data-block
                    :label="trans('application.fields.mother_tongue')">
                    <template #value>
                        <span v-if="getFromObject(applicant, 'personal.mother_tongue')">
                            {{
                                (getFromObject(applicant, "personal.mother_tongue") !== "other")
                                    ? trans(`mother_tongue.${getFromObject(applicant, "personal.mother_tongue")}`) : getFromObject(applicant, "personal.mother_tongue_other")
                            }}
                        </span>
                        <span v-if="!getFromObject(applicant, 'personal.mother_tongue')">-</span>
                    </template>
                </applicant-preview-data-block>
                <applicant-preview-data-block
                    :label="trans('application.fields.disability')"
                    :value="applicant.personal.disability"/>
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
            <div v-if="getFromObject(applicant, 'personal.applicant_photo') && applicant.personal.applicant_photo[0]"
                 class="order-1 lg:order-2 md:my-3 mx-auto lg:w-1/4 flex items-center justify-center mt-4 lg:-mt-10">
                <img :alt="applicant.personal.applicant_photo[0].filename"
                     :src="`${applicant.personal.applicant_photo[0].url}?w=200`"
                     class="h-36 w-36 object-cover cursor-pointer"
                     @click="showApplicantPhotoLightBox">
                <vue-light-box :imgs="applicantPhotoBox.urls" :visible="applicantPhotoBox.visible" :index="applicantPhotoBox.index" @hide="hideApplicantPhotoLightBox"/>
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
                <slider-light-box v-if="getFromObject(applicant, 'personal.citizenship.files') && isArray(applicant.personal.citizenship.files)"
                                  class="order-1 lg:order-2 my-4 mx-auto md:ml-auto md:mr-4"
                                  :images="applicant.personal.citizenship.files"
                                  slider-class="slider-citizen"/>
            </div>
        </div>

        <!-- permanent address -->
        <div>
            <div class="heading-primary heading-primary--sm pt-4">
                {{ trans("application.fields.permanent_address") }}
            </div>

            <div class="flex flex-wrap">
                <div class="data-block lg:w-3/4  border-b border-blue-200">
                    <applicant-preview-data-block
                        :label="trans('application.fields.district')"
                        :value="getFromObject(applicant, 'personal.addresses.permanent_address.district', '')"/>
                    <applicant-preview-data-block
                        :label="trans('application.fields.municipality')"
                        :value="getFromObject(applicant, 'personal.addresses.permanent_address.municipality', '')"/>
                    <applicant-preview-data-block
                        :label="trans('application.fields.ward')"
                        :value="numberTrans(getFromObject(applicant, 'personal.addresses.permanent_address.ward', '') || '')"/>
                </div>
            </div>
        </div>

        <!-- current address -->
        <div v-if="getFromObject(applicant, 'personal.addresses.has_current_address', false)">
            <div class="heading-primary heading-primary--sm pt-4">
                {{ trans("application.preview.current_address") }}
            </div>

            <div class="flex flex-wrap">
                <div class="data-block lg:w-3/4  border-b border-blue-200">
                    <applicant-preview-data-block
                        :label="trans('application.fields.district')"
                        :value="getFromObject(applicant, 'personal.addresses.current_address.district', '')"/>

                    <applicant-preview-data-block
                        :label="trans('application.fields.municipality')"
                        :value="getFromObject(applicant, 'personal.addresses.current_address.municipality', '')"/>
                    <applicant-preview-data-block
                        :label="trans('application.fields.ward')"
                        :value="numberTrans(getFromObject(applicant, 'personal.addresses.current_address.ward', '') || '')"/>
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
            <applicant-preview-data-block
                :label="trans('application.fields.mother_name')"
                :value="numberTrans(getFromObject(applicant, 'personal.mother_name'))"/>
            <applicant-preview-data-block
                :label="trans('application.fields.spouse_name')"
                :value="numberTrans(getFromObject(applicant, 'personal.spouse_name'))"/>
        </div>
    </div>
</template>

<script>
    import SliderLightBox            from "../../../Components/SliderLightBox"
    import VueLightBox               from "../../../Components/VueLightbox"
    import ApplicantPreviewDataBlock from "./ApplicantPreviewDataBlock"

    export default {
        name: "ApplicantPersonal",
        components: { SliderLightBox, ApplicantPreviewDataBlock, VueLightBox },
        props: {
            applicant: { type: Object, required: true },
        },
        data() {
            return {
                applicantPhotoBox: {
                    visible: false,
                    index: 0,
                    urls: [],
                },
            }
        },
        methods: {
            showApplicantPhotoLightBox() {
                this.$set(this.applicantPhotoBox, "urls", [this.applicant.personal.applicant_photo[0].url])
                this.$set(this.applicantPhotoBox, "visible", true)
            },
            hideApplicantPhotoLightBox() {
                this.$set(this.applicantPhotoBox, "visible", false)
                this.$set(this.applicantPhotoBox, "index", 0)
            },
        },
    }
</script>
