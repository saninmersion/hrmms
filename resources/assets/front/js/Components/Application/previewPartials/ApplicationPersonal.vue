<template>
    <div class="card card-js-scroll mb-6">
        <div class="heading-primary">
            {{ trans("application.sections.personal") }}
        </div>

        <!-- Personal -->
        <div class="flex flex-wrap">
            <div class="lg:w-3/4 data-block  border-b border-blue-200">
                <div class="data-block-item">
                    <div class="data-block-label">
                        {{ trans("application.preview.name_in_nepali") }}
                    </div>
                    <div class="data-block-value">
                        <span>{{ formattedName(getFromObject(applicant, "personal.name_in_nepali")) || "-" }}</span>
                    </div>
                </div>

                <div class="data-block-item">
                    <div class="data-block-label">
                        {{ trans("application.preview.name_in_english") }}
                    </div>
                    <div class="data-block-value">
                        <span>{{ formattedName(getFromObject(applicant, "personal.name_in_english")) || "-" }}</span>
                    </div>
                </div>

                <div class="data-block-item">
                    <div class="data-block-label">
                        {{ trans("application.fields.gender") }}
                    </div>
                    <div class="data-block-value">
                        <span v-if="getFromObject(applicant, 'personal.gender')"
                              v-text="trans(`application.gender-${getFromObject(applicant, 'personal.gender')}`)"/>
                    </div>
                </div>

                <div class="data-block-item">
                    <div class="data-block-label">
                        {{ trans("application.fields.ethnicity") }}
                    </div>
                    <div class="data-block-value">
                        <span v-if="getFromObject(applicant, 'personal.ethnicity')">
                            {{
                                (getFromObject(applicant, "personal.ethnicity") !== "other")
                                    ? trans(`caste.${getFromObject(applicant, "personal.ethnicity")}`)
                                    : getFromObject(applicant, "personal.ethnicity_other")
                            }}
                        </span>
                        <span v-if="!getFromObject(applicant, 'personal.ethnicity')">-</span>
                    </div>
                </div>

                <div class="data-block-item">
                    <div class="data-block-label">
                        {{ trans("application.fields.mother_tongue") }}
                    </div>
                    <div class="data-block-value">
                        <span v-if="getFromObject(applicant, 'personal.mother_tongue')">
                            {{
                                (getFromObject(applicant, "personal.mother_tongue") === "other")
                                    ? getFromObject(applicant, "personal.mother_tongue_other")
                                    : trans(`mother_tongue.${getFromObject(applicant, "personal.mother_tongue")}`)
                            }}
                        </span>
                        <span v-if="!getFromObject(applicant, 'personal.mother_tongue')">-</span>
                    </div>
                </div>

                <div class="data-block-item">
                    <div class="data-block-label">
                        {{ trans("application.fields.disability") }}
                    </div>
                    <div class="data-block-value">
                        <span v-if="getFromObject(applicant, 'personal.disability')">
                            {{
                                (getFromObject(applicant, "personal.disability") === "other")
                                    ? getFromObject(applicant, "personal.disability_other")
                                    : trans(`application.disabilities.${getFromObject(applicant, "personal.disability")}`)
                            }}
                        </span>
                        <span v-if="!getFromObject(applicant, 'personal.disability')">-</span>
                    </div>
                </div>

                <div class="data-block-item">
                    <div class="data-block-label">
                        {{ trans("application.fields.dob") }}
                    </div>
                    <div class="data-block-value">
                        <span>
                            {{ trans("application.calendar_bs") }}
                            {{ numberTrans(getFromObject(applicant, "personal.dob_bs")) }}
                        </span>
                        /
                        <span>
                            {{ trans("application.calendar_ad") }}
                            {{ numberTrans(getFromObject(applicant, "personal.dob_ad")) }}
                        </span>
                    </div>
                </div>
            </div>
            <div v-if="getFromObject(applicant, 'personal.applicant_photo') && applicant.personal.applicant_photo[0]"
                 class="data-file mx-auto lg:w-1/4 flex items-center justify-center mt-4 lg:-mt-10">
                <img :alt="applicant.personal.applicant_photo[0].filename"
                     :src="`${applicant.personal.applicant_photo[0].url}?w=200`"
                     class="h-36 w-36 object-cover">
            </div>
        </div>

        <!-- Citizenship -->
        <div>
            <div class="heading-primary heading-primary--sm">
                {{ trans("application.fields.citizenship") }}
            </div>
            <div class="flex flex-wrap">
                <div class="data-block lg:w-3/4 border-b border-blue-200">
                    <div class="data-block-item">
                        <div class="data-block-label">
                            {{ trans("application.fields.citizenship_no") }}
                        </div>
                        <div class="data-block-value">
                            <span>{{ numberTrans(getFromObject(applicant, "personal.citizenship_number")) }}</span>
                        </div>
                    </div>

                    <div class="data-block-item">
                        <div class="data-block-label">
                            {{ trans("application.fields.citizenship_issued_district") }}
                        </div>
                        <div class="data-block-value">
                            <span
                                v-text="formatOldDistrict(getFromObject(applicant, 'personal.citizenship_issued_district'))"/>
                        </div>
                    </div>

                    <div class="data-block-item">
                        <div class="data-block-label">
                            {{ trans("application.fields.citizenship_issued_date") }}
                        </div>
                        <div class="data-block-value">
                            <span
                                v-if="getFromObject(applicant, 'personal.citizenship_issued_date')"
                                v-text="numberTrans(getFromObject(applicant, 'personal.citizenship_issued_date', ''))"/>
                            <span v-if="!getFromObject(applicant, 'personal.citizenship_issued_date')">-</span>
                        </div>
                    </div>
                </div>
                <div
                    v-if="getFromObject(applicant, 'personal.citizenship_files') && isArray(applicant.personal.citizenship_files)"
                    class="data-file slider-citizen -mt-12">
                    <Slider :images="applicant.personal.citizenship_files"/>
                </div>
            </div>
        </div>

        <!-- permanent address -->
        <div>
            <div class="heading-primary heading-primary--sm">
                {{ trans("application.fields.permanent_address") }}
            </div>

            <div class="flex flex-wrap">
                <div class="data-block lg:w-3/4  border-b border-blue-200">
                    <div class="data-block-item">
                        <div class="data-block-label">
                            {{ trans("application.fields.district") }}
                        </div>
                        <div class="data-block-value">
                            <span
                                v-text="formatNewDistrict(getFromObject(applicant, 'personal.permanent_address.district')) || '-'"/>
                        </div>
                    </div>

                    <div class="data-block-item">
                        <div class="data-block-label">
                            {{ trans("application.fields.municipality") }}
                        </div>
                        <div class="data-block-value">
                            <span
                                v-text="formatMunicipality(getFromObject(applicant, 'personal.permanent_address')) || '-'"/>
                        </div>
                    </div>

                    <div class="data-block-item">
                        <div class="data-block-label">
                            {{ trans("application.fields.ward") }}
                        </div>
                        <div class="data-block-value">
                            <span
                                v-text="numberTrans(getFromObject(applicant, 'personal.permanent_address.ward', '') || '-')"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- current address -->
        <div v-if="getFromObject(applicant, 'personal.has_current_address', false)">
            <div class="heading-primary heading-primary--sm">
                {{ trans("application.preview.current_address") }}
            </div>

            <div class="flex flex-wrap">
                <div class="data-block lg:w-3/4  border-b border-blue-200">
                    <div class="data-block-item">
                        <div class="data-block-label">
                            {{ trans("application.fields.district") }}
                        </div>
                        <div class="data-block-value">
                            <span
                                v-text="formatNewDistrict(getFromObject(applicant, 'personal.current_address.district')) || '-'"/>
                        </div>
                    </div>

                    <div class="data-block-item">
                        <div class="data-block-label">
                            {{ trans("application.fields.municipality") }}
                        </div>
                        <div class="data-block-value">
                            <span
                                v-text="formatMunicipality(getFromObject(applicant, 'personal.current_address')) || '-'"/>
                        </div>
                    </div>

                    <div class="data-block-item">
                        <div class="data-block-label">
                            {{ trans("application.fields.ward") }}
                        </div>
                        <div class="data-block-value">
                            <span
                                v-text="numberTrans(getFromObject(applicant, 'personal.current_address.ward', '') || '-')"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Others -->
        <div class="data-block lg:w-3/4 ">
            <div class="data-block-item">
                <div class="data-block-label">
                    {{ trans("application.fields.mobile_number") }}
                </div>

                <div class="data-block-value">
                    <span>{{ numberTrans(getFromObject(applicant, "personal.mobile_number")) }}</span>
                </div>
            </div>

            <div class="data-block-item">
                <div class="data-block-label">
                    {{ trans("application.fields.grand_father_name") }}
                </div>
                <div class="data-block-value">
                    <span>{{ formattedName(getFromObject(applicant, "personal.grand_father_name")) || "-" }}</span>
                </div>
            </div>

            <div class="data-block-item">
                <div class="data-block-label">
                    {{ trans("application.fields.father_name") }}
                </div>
                <div class="data-block-value">
                    <span>{{ formattedName(getFromObject(applicant, "personal.father_name")) || "-" }}</span>
                </div>
            </div>

            <div class="data-block-item">
                <div class="data-block-label">
                    {{ trans("application.fields.mother_name") }}
                </div>
                <div class="data-block-value">
                    <span>{{ formattedName(getFromObject(applicant, "personal.mother_name")) || "-" }}</span>
                </div>
            </div>

            <div class="data-block-item">
                <div class="data-block-label">
                    {{ trans("application.fields.spouse_name") }}
                </div>
                <div class="data-block-value">
                    <span>{{ formattedName(getFromObject(applicant, "personal.spouse_name")) || "-" }}</span>
                </div>
            </div>
        </div>
    </div>
</template>

<script type="text/ecmascript-6">
    import Slider from "../../Common/Slider"

    export default {
        name: "ApplicationPersonal",

        components: { Slider },

        inject: ["oldDistricts", "newDistricts"],

        props: {
            applicant: { type: Object, required: true },
        },

        methods: {
            formattedName(name) {
                if (!name) {
                    return ""
                }

                const fullName = []

                if (name.first_name) {
                    fullName.push(name.first_name)
                }

                if (name.middle_name) {
                    fullName.push(name.middle_name)
                }

                if (name.last_name) {
                    fullName.push(name.last_name)
                }

                return fullName.join(" ")
            },

            formatOldDistrict(district) {
                if (!district) {
                    return ""
                }

                const selectedDistrict = this.oldDistricts.find(dist => parseInt(dist.code) === parseInt(district))

                if (!selectedDistrict) {
                    return ""
                }

                return selectedDistrict[this.$currentLocale === "en" ? "title_en" : "title_ne"]
            },

            formatNewDistrict(district) {
                if (!district) {
                    return ""
                }

                const selectedDistrict = this.newDistricts.find(dist => parseInt(dist.code) === parseInt(district))

                if (!selectedDistrict) {
                    return ""
                }

                return selectedDistrict[this.$currentLocale === "en" ? "title_en" : "title_ne"]
            },

            formatMunicipality(location) {
                if (!location || !location.district || !location.municipality) {
                    return ""
                }

                const selectedDistrict = this.newDistricts.find(
                    dist => parseInt(dist.code) === parseInt(location.district))

                if (!selectedDistrict) {
                    return ""
                }

                const selectedMunicipality = selectedDistrict.municipalities.find(
                    mun => parseInt(mun.code) === parseInt(location.municipality))

                return selectedMunicipality[this.$currentLocale === "en" ? "title_en" : "title_ne"]
            },
        },
    }
</script>
