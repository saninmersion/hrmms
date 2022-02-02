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
            गणक सुपरिवेक्षण
            बिबरण
        </h1>

        <!-- location-->
        <div class="card">
            <h2 class="heading-primary">
                अनुगमन गरेको:
            </h2>
            <div class="data-block !py-0">
                <applicant-preview-data-block
                    :label="trans('application.fields.district')"
                    :value="monitoring.monitored_district[this.$currentLocale === 'en' ? 'title_en' : 'title_ne']"/>

                <applicant-preview-data-block
                    :label="trans('application.fields.municipality')"
                    :value="monitoring.monitored_municipality[this.$currentLocale === 'en' ? 'title_en' : 'title_ne']"/>

                <applicant-preview-data-block
                    :label="trans('application.fields.ward')"
                    :value="numberTrans(monitoring.ward.toString())"/>

                <applicant-preview-data-block
                    :label="'गणना छेत्र'"
                    :value="monitoring.census_area"/>

                <applicant-preview-data-block
                    :label="'भेट गरिएको गणकको नाम'"
                    :value="monitoring.monitoring_data.monitored_person_name"/>

                <applicant-preview-data-block
                    :label="'सम्पर्क नं.'"
                    :value="numberTrans(monitoring.monitoring_data.monitored_person_mobile_no)"/>
            </div>
        </div>

        <!--enumerator behaviour-->
        <div class="card">
            <h6 class="heading-primary">
                गणकको शिष्टता र व्यवहार
            </h6>
            <div class="data-block !py-0">
                <applicant-preview-data-block
                    :label="'अन्तर्वार्ता शुरु हुनु भन्दा पहिले गणकले उत्तरदातालाई अभिवादन गरे ?'"
                    :value="trans(`admin-monitoring.form-options.${ monitoring.monitoring_data.politeness_behaviour.greeting}`)"/>

                <applicant-preview-data-block
                    :label="'गणकले आफ्नो परिचय दिएर आफु राष्ट्रिय जनगणनाको तथ्या संकलनका लागि आवश्यक विवरण संकलन गर्न आएको हो भन्ने कुरा बताए ?'"
                    :value="trans(`admin-monitoring.form-options.${ monitoring.monitoring_data.politeness_behaviour.introduction}`)"/>

                <applicant-preview-data-block
                    :label="'गणकले जनगणनाको उद्देश्यको बारेमा जानकारी गराए ?'"
                    :value="trans(`admin-monitoring.form-options.${ monitoring.monitoring_data.politeness_behaviour.objective}`)"/>

                <applicant-preview-data-block
                    :label="'अन्तर्वार्ताको समयमा उत्तरदातासँग गणकको व्यवहार मिजासिलो, नम्र र धैर्यशील रह्यो ?'"
                    :value="trans(`admin-monitoring.form-options.${ monitoring.monitoring_data.politeness_behaviour.demeanor}`)"/>

                <applicant-preview-data-block
                    :label="'अन्तर्वार्ता सकिएपछी गणकले उत्तरदाता र अन्य सम्बिन्धत सबैलाई धन्यवाद ज्ञापन गरे ?'"
                    :value="trans(`admin-monitoring.form-options.${ monitoring.monitoring_data.politeness_behaviour.gratitude}`)"/>
            </div>
        </div>

        <!--interview process-->
        <div class="card">
            <h6 class="heading-primary">
                अन्तर्वार्ता प्रक्रिया
            </h6>
            <div class="data-block">
                <applicant-preview-data-block
                    :label="'गणकले प्रश्नावलिमा लेखिएको प्रश्न जस्ताको तस्तै पढेर उत्तरदातालाई सोधेका थिए ?'"
                    :value="trans(`admin-monitoring.form-options.${ monitoring.monitoring_data.interview_process.question_similarity}`)"/>

                <applicant-preview-data-block
                    :label="'उत्तरदाताले कुनै प्रश्न नबुझेमा गणकले त्यस्ता प्रश्नलाई प्रश्नको आशय नबिग्रने गरी थप स्पष्ट पारेका थिए ?'"
                    :value="trans(`admin-monitoring.form-options.${ monitoring.monitoring_data.interview_process.clarification}`)"/>

                <applicant-preview-data-block
                    :label="'गणकले प्रश्नावली को प्रत्येक खण्डका प्रत्येक प्रश्न सोधेर परिवारमूलीले दिएको उत्तर लेखेका थिए ?'"
                    :value="trans(`admin-monitoring.form-options.${ monitoring.monitoring_data.interview_process.every_section}`)"/>
            </div>
        </div>

        <!--time management-->
        <div class="card">
            <h6 class="heading-primary">
                समय व्यवस्थापन
            </h6>
            <div class="data-block">
                <applicant-preview-data-block
                    :label="'गणकले उत्तरदातासँग कुनै प्रश्नमा लामो बहस गर्ने गरेका थिए ?'"
                    :value="trans(`admin-monitoring.form-options.${ monitoring.monitoring_data.time_management.long_argument}`)"/>

                <applicant-preview-data-block
                    :label="'उत्तरदाताबाट असान्दर्भिक वा जटिल उत्तर पाउने बित्तिकै गणकले बीचमा बोलेर उत्तरदातालाई रोकेका थिए ?'"
                    :value="trans(`admin-monitoring.form-options.${ monitoring.monitoring_data.time_management.interruption}`)"/>

                <applicant-preview-data-block
                    :label="'उत्तरदातालाई छिटो छिटो उत्तर दिन लगाउने वा गणकले अन्तर्वार्ता लिंदा हतार गरेका थिए ?'"
                    :value="trans(`admin-monitoring.form-options.${ monitoring.monitoring_data.time_management.impatience}`)"/>
            </div>
        </div>

        <!--impartiality-->
        <div class="card">
            <h6 class="heading-primary">
                निष्पक्षता
            </h6>
            <div class="data-block">
                <applicant-preview-data-block
                    :label="'अन्तर्वार्ता लिँदा गणकले प्रश्न र उत्तर दुबैमा तटस्थ व्यवहार देखाएका थिए ?'"
                    :value="trans(`admin-monitoring.form-options.${monitoring.monitoring_data.impartiality.neutrality}`)"/>

                <applicant-preview-data-block
                    :label="'उत्तर्दाताबाट प्राप्त कुनै उत्तर सुनेर गणक आश्चर्य वा उत्तर मन नपराएको जस्तो हाउभाउ देखाएका थिए ?'"
                    :value="trans(`admin-monitoring.form-options.${monitoring.monitoring_data.impartiality.reaction}`)"/>

                <applicant-preview-data-block
                    :label="'गणकले कुनै प्रश्नको सन्दर्भमा आफ्नो निजी विचार उत्तरदातालाई सुनाएका थिए ?'"
                    :value="trans(`admin-monitoring.form-options.${monitoring.monitoring_data.impartiality.personal_opinion}`)"/>

                <applicant-preview-data-block
                    :label="'गणकले प्रश्न सोध्दा उत्तर पनि आफैले सुझाएको वा संकेत गरेका थिए ?'"
                    :value="trans(`admin-monitoring.form-options.${monitoring.monitoring_data.impartiality.answer_indication}`)"/>
            </div>
        </div>

        <!-- on site -->
        <div class="card">
            <h2 class="heading-primary">
                मुख्य प्रश्नावली सम्बन्धी विवरणको स्थलगत जाचँ
            </h2>
            <div class="data-block !pb-0">
                <enumerator-onsite-preview
                    :onsite-monitoring="monitoring.monitoring_data.onsite_monitoring"/>
            </div>
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
    import EnumeratorOnsitePreview   from "./Partials/EnumeratorOnsitePreview"

    export default {
        name: "OverallMonitoringDetail",
        components: { Icon, SecondaryButton, AdminLayout, ApplicantPreviewDataBlock, EnumeratorOnsitePreview },
        props: {
            monitoring: { type: Object, required: true },
        },
    }
</script>
