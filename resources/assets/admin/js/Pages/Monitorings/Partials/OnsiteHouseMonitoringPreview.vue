<template>
    <div>
        <h3 class="heading-primary heading-primary--sm !py-2" v-text="'स्थल  - ' + numberTrans( index + 1)"/>

        <div class="data-block !py-0">
            <applicant-preview-data-block
                :label="'जनगणना घर क्रम संख्या'"
                :value="numberTrans(houseMonitoring.house_sn.toString())"/>

            <applicant-preview-data-block
                :label="'घरको तला संख्या'"
                :value="numberTrans(houseMonitoring.floor_count)"/>

            <applicant-preview-data-block
                :label="'सूचीकरणमा भएको विवरण अनुसार'"
                :value="trans(`admin-monitoring.form-options.${ houseMonitoring.floor_count_check }`)"/>

            <applicant-preview-data-block
                :label="'घर मुख्यरुपमा के को लागि (कोड)'"
                :value="houseMonitoring.house_purpose"/>

            <applicant-preview-data-block
                :label="'सूचीकरणमा भएको विवरण अनुसार'"
                :value="trans(`admin-monitoring.form-options.${ houseMonitoring.house_purpose_check }`)"/>

            <applicant-preview-data-block
                :label="'घरकाे परिवार संख्या'"
                :value="numberTrans(houseMonitoring.family_count)"/>

            <applicant-preview-data-block
                :label="'सूचीकरणमा भएको विवरण अनुसार'"
                :value="trans(`admin-monitoring.form-options.${ houseMonitoring.family_count_check }`)"/>
        </div>
    </div>
</template>

<script>
    import ApplicantPreviewDataBlock from "../../Applicants/Partials/ApplicantPreviewDataBlock"

    export default {
        name: "OnsiteFamilyMonitoringPreview",
        components: { ApplicantPreviewDataBlock },
        props: {
            houseMonitoring: { type: Object, required: true },
            index: { type: Number, required: true },
        },
    }
</script>

<style scoped>

</style>
