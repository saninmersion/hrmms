<template>
    <div class="grid gap-8">
        <div>
            <!-- परिचयात्मक खण्ड -->
            <applicant-preview-data-block
                :label="'जनगणना घर क्र.सं.'"
                :value="numberTrans(onsiteMonitoring.house_sn)"/>

            <applicant-preview-data-block
                :label="'जनगणना परिवार क्र.सं.'"
                :value="numberTrans(onsiteMonitoring.family_sn)"/>

            <applicant-preview-data-block
                :label="'परिवारमूलीको नाम'"
                :value="onsiteMonitoring.head_of_household_name"/>

            <applicant-preview-data-block
                :label="'परिवारमूलीको उमेर'"
                :value="numberTrans(onsiteMonitoring.head_of_household_age)"/>

            <applicant-preview-data-block
                :label="'परिवारमूलीको लिङ्ग'"
                :value="trans(`application.gender-${onsiteMonitoring.head_of_household_gender}`)"/>
        </div>

        <div>
            <h3 class="heading-primary heading-primary--sm" v-text="'अक्सर वसोबास गर्ने परिवारका संख्या'"/>
            <div>
                <applicant-preview-data-block
                    :label="'जम्मा'"
                    :value="numberTrans(onsiteMonitoring.resident_family_total)"/>

                <applicant-preview-data-block
                    :label="'पुरुष'"
                    :value="numberTrans(onsiteMonitoring.resident_family_male)"/>

                <applicant-preview-data-block
                    :label="'महिला'"
                    :value="numberTrans(onsiteMonitoring.resident_family_female)"/>

                <applicant-preview-data-block
                    :label="'सूचीकरणमा भएको विवरण अनुसार'"
                    :value="trans(`admin-monitoring.form-options.${onsiteMonitoring.resident_family_check}`)"/>
            </div>
        </div>

        <div>
            <h3 class="heading-primary heading-primary--sm">
                अनुपस्थित (विदेशमा रहेका) परिवारका संख्या
            </h3>

            <div>
                <applicant-preview-data-block
                    :label="'जम्मा'"
                    :value="numberTrans(onsiteMonitoring.absent_family_total)"/>

                <applicant-preview-data-block
                    :label="'पुरुष'"
                    :value="numberTrans(onsiteMonitoring.absent_family_male)"/>

                <applicant-preview-data-block
                    :label="'महिला'"
                    :value="numberTrans(onsiteMonitoring.absent_family_female)"/>

                <applicant-preview-data-block
                    :label="'सूचीकरणमा भएको विवरण अनुसार'"
                    :value="trans(`admin-monitoring.form-options.${onsiteMonitoring.absent_family_check}`)"/>
            </div>
        </div>

        <div>
            <!-- family section पारिवारिक खण्ड-->
            <h3 class="heading-primary heading-primary--sm" v-text="'परिवारले प्रयोग गरेको घरको स्वामित्व'"/>

            <div>
                <applicant-preview-data-block
                    :label="'घरको स्वामित्व'"
                    :value="trans(`admin-monitoring.form-options.home_ownership.${onsiteMonitoring.current_house_owner}`)"/>

                <applicant-preview-data-block :label="'सूचीकरणमा भएको विवरण अनुसार'"
                                              :value="trans(`admin-monitoring.form-options.${onsiteMonitoring.current_house_check}`)"/>
            </div>
        </div>

        <div>
            <!-- family section पारिवारिक खण्ड-->
            <h3 class="heading-primary heading-primary--sm" v-text="'परिवारले प्रयोग गरेको घरको छाना'"/>

            <div>
                <applicant-preview-data-block
                    :label="'घरको छानाको प्रकार'"
                    :value="trans(`admin-monitoring.form-options.roof_material.${onsiteMonitoring.current_house_roof}`)"/>

                <applicant-preview-data-block :label="'सूचीकरणमा भएको विवरण अनुसार'"
                                              :value="trans(`admin-monitoring.form-options.${onsiteMonitoring.current_house_roof_check}`)"/>
            </div>
        </div>

        <div>
            <h3 class="heading-primary heading-primary--sm" v-text="'घरायसी सुविधा तथा साधनहरू'"/>

            <div class="grid gap-4">
                <div>
                    <span class="text-base-2 font-semibold">टेलिभिजन</span>
                    <div>
                        <applicant-preview-data-block
                            :label="'काेड'"
                            :value="trans(`admin-monitoring.form-options.${onsiteMonitoring.facility_television_code}`)"/>
                        <applicant-preview-data-block :label="'सूचीकरणमा भएको विवरण अनुसार'"
                                                      :value="trans(`admin-monitoring.form-options.${onsiteMonitoring.facility_television_check}`)"/>
                    </div>
                </div>
                <div>
                    <span class="text-base-2 font-semibold">कम्प्युटर/ल्यापटप</span>
                    <div>
                        <applicant-preview-data-block
                            :label="'काेड'"
                            :value="trans(`admin-monitoring.form-options.${onsiteMonitoring.facility_computer_code}`)"/>
                        <applicant-preview-data-block :label="'सूचीकरणमा भएको विवरण अनुसार'"
                                                      :value="trans(`admin-monitoring.form-options.${onsiteMonitoring.facility_computer_check}`)"/>
                    </div>
                </div>
                <div>
                    <span class="text-base-2 font-semibold">रेफ्रिजरेटर</span>
                    <div>
                        <applicant-preview-data-block
                            :label="'काेड'"
                            :value="trans(`admin-monitoring.form-options.${onsiteMonitoring.facility_refrigerator_code}`)"/>
                        <applicant-preview-data-block :label="'सूचीकरणमा भएको विवरण अनुसार'"
                                                      :value="trans(`admin-monitoring.form-options.${onsiteMonitoring.facility_refrigerator_check}`)"/>
                    </div>
                </div>
            </div>
        </div>

        <!-- मृत्यु सम्बन्धी विवरण-->

        <div>
            <h3 class="heading-primary heading-primary--sm" v-text="'गत १२ महिनामा परिवारमा कसैको मृत्यु भएको'"/>

            <div>
                <applicant-preview-data-block
                    :label="'मृत्यु भएको'"
                    :value="trans(`admin-monitoring.form-options.${onsiteMonitoring.family_death}`)"/>

                <applicant-preview-data-block :label="'सूचीकरणमा भएको विवरण अनुसार'"
                                              :value="trans(`admin-monitoring.form-options.${onsiteMonitoring.family_death_check}`)"/>
            </div>
        </div>

        <!-- व्यक्तिगत खण्ड-->

        <div>
            <h3 class="heading-primary heading-primary--sm" v-text="'परिवारमा १ बर्ष उमेर नपुगेका बालबालिकाको संख्या'"/>
            <div>
                <applicant-preview-data-block
                    :label="'बालबालिकाको संख्या'"
                    :value="onsiteMonitoring.child_less_than_one_count"/>

                <applicant-preview-data-block :label="'सूचीकरणमा भएको विवरण अनुसार'"
                                              :value="trans(`admin-monitoring.form-options.${onsiteMonitoring.child_less_than_one_check}`)"/>
            </div>
        </div>

        <div>
            <h3 class="heading-primary heading-primary--sm" v-text="'परिवारमा ६० बर्ष वा सो भन्दा बढी उमेर माथिका व्यक्तिको संख्या'"/>

            <div>
                <applicant-preview-data-block
                    :label="'६० बर्ष वा सो भन्दा बढी उमेर माथिका व्यक्तिको संख्या '"
                    :value="onsiteMonitoring.elderly_more_than_60_count"/>

                <applicant-preview-data-block :label="'सूचीकरणमा भएको विवरण अनुसार'"
                                              :value="trans(`admin-monitoring.form-options.${onsiteMonitoring.elderly_more_than_60_check}`)"/>
            </div>
        </div>

        <div>
            <h3 class="heading-primary heading-primary--sm" v-text="'यस परिवारमा पढ्न र लेख्न दुवै जान्नेको संख्या कति छ ?'"/>
            <div>
                <applicant-preview-data-block
                    :label="'पढ्न र लेख्न दुवै जान्नेको संख्या'"
                    :value="onsiteMonitoring.literate_read_write_count"/>

                <applicant-preview-data-block :label="'सूचीकरणमा भएको विवरण अनुसार'"
                                              :value="trans(`admin-monitoring.form-options.${onsiteMonitoring.literate_read_write_check}`)"/>
            </div>
        </div>

        <div>
            <h3 class="heading-primary heading-primary--sm" v-text="'परिवारमा अपाङ्गता भएका व्यक्तिको संख्या'"/>

            <div>
                <applicant-preview-data-block
                    :label="'अपाङ्गता भएका व्यक्तिको संख्या'"
                    :value="onsiteMonitoring.disabled_count"/>

                <applicant-preview-data-block :label="'सूचीकरणमा भएको विवरण अनुसार'"
                                              :value="trans(`admin-monitoring.form-options.${onsiteMonitoring.disabled_check}`)"/>
            </div>
        </div>
    </div>
</template>

<script>
    import ApplicantPreviewDataBlock from "../../Applicants/Partials/ApplicantPreviewDataBlock"

    export default {
        name: "EnumeratorOnsiteMonitoring",
        components: { ApplicantPreviewDataBlock },
        props: {
            onsiteMonitoring: { type: Object, required: true },
        },
    }
</script>

<style scoped>
    .data-block-label {
    }
</style>
