<template>
    <admin-layout page-title="Overall Monitoring Form" :authorized="isAuthorized($page, 'monitoring_general')">
        <template #actions>
            <secondary-button v-if="isAuthorized($page, 'monitoring_general')"
                              :href="route('admin.monitorings.edit', monitoring.id)"
                              class="!text-primary-500 uppercase py-2 px-4 bg-white flex items-center rounded !font-normal cursor-pointer hover:bg-white">
                <Icon name="edit"/>
                Edit
            </secondary-button>
        </template>
        <h1 class="heading-primary heading-primary--lg">
            अनुगमन बिबरण
        </h1>
        <!-- location-->
        <div class="card">
            <h2 class="heading-primary">
                अनुगमन गरेको:
            </h2>
            <div class="data-block !py-0">
                <applicant-preview-data-block
                    :label="trans('application.fields.district')"
                    :value="monitoring.monitored_district.title_ne"/>

                <applicant-preview-data-block
                    :label="trans('application.fields.municipality')"
                    :value="monitoring.monitored_municipality.title_ne"/>

                <applicant-preview-data-block
                    :label="trans('application.fields.ward')"
                    :value="numberTrans(monitoring.ward.toString())"/>

                <applicant-preview-data-block
                    :label="'गणना छेत्र'"
                    :value="monitoring.census_area"/>

                <applicant-preview-data-block
                    :label="'भेट गरिएको सुपरिवेक्षक/गणक को नाम'"
                    :value="monitoring.monitoring_data.monitored_person_name"/>

                <applicant-preview-data-block
                    :label="'सम्पर्क नं.'"
                    :value="numberTrans(monitoring.monitoring_data.monitored_person_mobile_no)"/>

                <applicant-preview-data-block
                    :label="'भेट गरिएको परिवार संख्या'"
                    :value="numberTrans(monitoring.monitoring_data.family_count)"/>
            </div>
        </div>

        <!-- on site data-->
        <div class="card">
            <h6 class="heading-primary">
                अनुगमनकर्ताले स्थलगत अनुगमनका क्रममा देखिएका विषयवस्तु
            </h6>
            <div class="data-block !py-0">
                <applicant-preview-data-block
                    :label="'परिवार बसोबास गरेको घरमा मार्करले जनगणना घर तथा घरपरिवार क्रं. सं. लेखेको नलेखेको'"
                    :value="trans(`admin-monitoring.form-options.${monitoring.monitoring_data.onsite_monitoring.marking}`)"/>

                <applicant-preview-data-block
                    :label="'घरपरिवारलाई जनगणनाको वारेमा गणना पुर्व जानकारी भए नभएको'"
                    :value="trans(`admin-monitoring.form-options.${monitoring.monitoring_data.onsite_monitoring.prior_information}`)"/>

                <applicant-preview-data-block
                    :label="'अनुगमन गरिएका क्षेत्रमा जनगणना सम्बन्धी स्थानीय रुपमा प्रचार प्रसार भए नभएको'"
                    :value="trans(`admin-monitoring.form-options.${monitoring.monitoring_data.onsite_monitoring.local_advertisement}`)"/>

                <applicant-preview-data-block
                    :label="'गणना गरिसकिएको गाउँ/टोल/बस्तीमा कुनै घर परिवार गणना गर्न छुट भए नभएको'"
                    :value="trans(`admin-monitoring.form-options.${monitoring.monitoring_data.onsite_monitoring.missed_count}`)"/>

                <applicant-preview-data-block
                    :label="'जनगणनाको स्थलगत गणना कार्य प्रती उत्तरदाताहरुको कुनै गुनासो भए नभएको'"
                    :value="trans(`admin-monitoring.form-options.${monitoring.monitoring_data.onsite_monitoring.complaints}`)"/>

                <applicant-preview-data-block
                    :label="'जनगणनाको स्थलगत कार्यमा बाधा व्यवधान भए नभएकोो'"
                    :value="trans(`admin-monitoring.form-options.${monitoring.monitoring_data.onsite_monitoring.obstacles}`)"/>
            </div>
        </div>

        <!-- obstacles -->
        <div class="card">
            <h2 class="heading-primary">
                अनुगमनका क्रममा देखिएका अन्य समस्याहरु
            </h2>
            <span v-text="monitoring.monitoring_data.monitoring_problems"/>
        </div>

        <!-- suggestions-->
        <div class="card">
            <h2 class="heading-primary">
                अनुगमनकर्ताको सुझावहरु:
            </h2>
            <span v-text="monitoring.monitoring_data.monitoring_suggestions"/>
        </div>
    </admin-layout>
</template>

<script>
    import SecondaryButton           from "../../Components/Buttons/SecondaryButton"
    import Icon                      from "../../Components/General/Icon"
    import AdminLayout               from "../../Layouts/AdminLayout"
    import ApplicantPreviewDataBlock from "../Applicants/Partials/ApplicantPreviewDataBlock"

    export default {
        name: "OverallMonitoringDetail",
        components: { Icon, SecondaryButton, AdminLayout, ApplicantPreviewDataBlock },
        props: {
            monitoring: { type: Object, required: true },
        },
    }
</script>
