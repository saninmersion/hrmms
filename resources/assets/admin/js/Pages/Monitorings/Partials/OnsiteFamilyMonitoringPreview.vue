<template>
    <div>
        <h3 class="heading-primary heading-primary--sm !py-2" v-text="'स्थल - ' + numberTrans( index + 1)"/>

        <div class="data-block !py-0">
            <applicant-preview-data-block
                :label="'परिवार क्र.सं.'"
                :value="numberTrans(familyMonitoring.family_sn.toString())"/>

            <applicant-preview-data-block
                :label="'परिवारमूलीको नाम'"
                :value="familyMonitoring.house_head_name"/>

            <applicant-preview-data-block
                :label="'सूचीकरणमा भएको विवरण अनुसार'"
                :value="trans(`admin-monitoring.form-options.${ familyMonitoring.house_head_name_check }`)"/>

            <applicant-preview-data-block
                :label="'जम्मा पुरुष संख्या'"
                :value="numberTrans(familyMonitoring.male_count)"/>

            <applicant-preview-data-block
                :label="'सूचीकरणमा भएको विवरण अनुसार'"
                :value="trans(`admin-monitoring.form-options.${ familyMonitoring.male_count_check }`)"/>

            <applicant-preview-data-block
                :label="'जम्मा महिला संख्या'"
                :value="numberTrans(familyMonitoring.female_count)"/>

            <applicant-preview-data-block
                :label="'सूचीकरणमा भएको विवरण अनुसार'"
                :value="trans(`admin-monitoring.form-options.${ familyMonitoring.female_count_check }`)"/>

            <applicant-preview-data-block
                :label="'परिवारको यो गा.पा./न.पा भित्र आफैंले चलन गरेको कृषि प्रयोजनको जग्गा छ ?'"
                :value="familyMonitoring.agriculture_land"/>

            <applicant-preview-data-block
                :label="'सूचीकरणमा भएको विवरण अनुसार'"
                :value="trans(`admin-monitoring.form-options.${ familyMonitoring.agriculture_land_check }`)"/>

            <applicant-preview-data-block
                :label="'परिवारमा प्राविधक तथा व्यावसायिक शिक्षा/तलिम लिएका संख्या'"
                :value="numberTrans(familyMonitoring.professional_training)"/>

            <applicant-preview-data-block
                :label="'सूचीकरणमा भएको विवरण अनुसार'"
                :value="trans(`admin-monitoring.form-options.${ familyMonitoring.professional_training_check }`)"/>
        </div>
    </div>
</template>

<script>
    import ApplicantPreviewDataBlock from "../../Applicants/Partials/ApplicantPreviewDataBlock"

    export default {
        name: "OnsiteFamilyMonitoringPreview",
        components: { ApplicantPreviewDataBlock },
        props: {
            familyMonitoring: { type: Object, required: true },
            index: { type: Number, required: true },
        },
    }
</script>

<style scoped>

</style>
