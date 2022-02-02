<template>
    <admin-layout page-title="Enumerator Monitoring Form" :authorized="isAuthorized($page, 'monitoring_enumerator')">
        <form id="overall-monitoring-form"
              class="form"
              @submit.prevent="submitForm">
            <div v-if="isOperator" class="w-full lg:w-1/2 mb-6">
                <label-component value="Monitored By"/>
                <drop-down-input
                    :class="{'form-input-error' :form.error('monitored_by')} "
                    :hide-selected="false"
                    :options="monitorOptions"
                    placeholder="Monitored By"
                    :searchable="true"
                    :show-placeholder="true"
                    :value="form.monitored_by"
                    @input="handleOnChange($event, 'monitored_by')"/>
                <input-error :message="form.error('monitored_by')" :v-if="form.error('monitored_by')"/>
            </div>
            <h6 class="heading-primary heading-primary--lg !pb-6">
                {{ trans(`admin-monitoring.form-type.enumerator_monitoring`) }}
            </h6>
            <div>
                <!-- monitoring location-->
                <div class="card mb-6">
                    <h6 class="heading-primary">
                        अनुगमन गरेको:
                    </h6>
                    <div class="grid md:grid-cols-3 gap-6">
                        <div>
                            <label-component :value="trans('application.fields.district')"/>
                            <drop-down-input
                                :class="{'form-input-error' :form.error('district')} "
                                :hide-selected="false"
                                :options="districtOptions"
                                :placeholder="trans('application.placeholders.district')"
                                :searchable="true"
                                :show-placeholder="true"
                                :value="form.district"
                                @input="handleOnChange($event, 'district')"/>
                            <input-error :message="form.error('district')" :v-if="form.error('district')"/>
                        </div>
                        <div>
                            <label-component :value="trans('application.fields.municipality')"/>
                            <drop-down-input
                                :class="{'form-input-error' :form.error('municipality')} "
                                :hide-selected="false"
                                :options="municipalityOptions"
                                :placeholder="trans('application.placeholders.municipality')"
                                :searchable="true"
                                :show-placeholder="true"
                                :value="form.municipality"
                                @input="handleOnChange($event, 'municipality')"/>
                            <input-error :message="form.error('municipality')"
                                         :v-if="form.error('municipality')"/>
                        </div>
                        <div>
                            <label-component :value="trans('application.fields.ward')"/>
                            <drop-down-input
                                :class="{'form-input-error' :form.error('ward')} "
                                :hide-selected="false"
                                :options="wardOptions"
                                :placeholder="trans('application.placeholders.ward')"
                                :show-placeholder="true"
                                :value="form.ward"
                                @input="handleOnChange($event, 'ward')"/>
                            <input-error :message="form.error('ward')" :v-if="form.error('ward')"/>
                        </div>
                        <div>
                            <label-component value="गणना छेत्र"/>
                            <input-component v-model="form.census_area" name="census_area" class="block w-full"/>
                            <input-error :message="form.error('census_area')"/>
                        </div>
                        <div>
                            <label-component value="भेट गरिएको गणक को नाम "/>
                            <input-component v-model="form.monitoring_data.monitored_person_name" name="monitored_person_name" class="block w-full"/>
                            <input-error :message="form.error('monitoring_data.monitored_person_name')"/>
                        </div>
                        <div>
                            <label-component value="सम्पर्क नं."/>
                            <input-component v-model="form.monitoring_data.monitored_person_mobile_no" name="monitored_person_mobile_no" class="block w-full"/>
                            <input-error :message="form.error('monitoring_data.monitored_person_mobile_no')"/>
                        </div>
                    </div>
                </div>
                <!--enumerator behaviour-->
                <div class="card mb-6">
                    <h2 class="heading-primary">
                        गणकको शिष्टता र व्यवहार
                    </h2>
                    <div>
                        <div class="form-input-block py-4">
                            <div class="grid gap-4 md:grid-cols-2 md:gap-24">
                                <label-component class="!mb-0">
                                    अन्तर्वार्ता शुरु हुनु भन्दा पहिले गणकले उत्तरदातालाई अभिवादन गरे ?
                                </label-component>
                                <radio-group-input id="greeting"
                                                   v-model="form.monitoring_data.politeness_behaviour.greeting"
                                                   :options="didDidNotOptions"
                                                   name="greeting"/>
                            </div>
                            <input-error :message="form.error('monitoring_data.politeness_behaviour.greeting')"/>
                        </div>

                        <div class="form-input-block py-4">
                            <div class="grid gap-4 md:grid-cols-2 md:gap-24">
                                <label-component class="!mb-0">
                                    गणकले आफ्नो परिचय दिएर आफु राष्ट्रिय जनगणनाको तथ्या संकलनका
                                    लागि आवश्यक विवरण संकलन गर्न आएको हो भन्ने कुरा बताए ?
                                </label-component>
                                <radio-group-input id="introduction"
                                                   v-model="form.monitoring_data.politeness_behaviour.introduction"
                                                   :options="explainOptions"
                                                   name="introduction"/>
                            </div>
                            <input-error :message="form.error('monitoring_data.politeness_behaviour.introduction')"/>
                        </div>

                        <div class="form-input-block py-4">
                            <div class="grid gap-4 md:grid-cols-2 md:gap-24">
                                <label-component class="!mb-0">
                                    गणकले जनगणनाको उद्देश्यको बारेमा जानकारी गराए ?
                                </label-component>
                                <radio-group-input id="objective"
                                                   v-model="form.monitoring_data.politeness_behaviour.objective"
                                                   :options="doneNotDoneOptions"
                                                   name="objective"/>
                            </div>
                            <input-error :message="form.error('monitoring_data.politeness_behaviour.objective')"/>
                        </div>

                        <div class="form-input-block py-4">
                            <div class="grid gap-4 md:grid-cols-2 md:gap-24">
                                <label-component class="!mb-0">
                                    अन्तर्वार्ताको समयमा उत्तरदातासँग गणकको व्यवहार मिजासिलो, नम्र र धैर्यशील रह्यो ?
                                </label-component>
                                <radio-group-input id="demeanor"
                                                   v-model="form.monitoring_data.politeness_behaviour.demeanor"
                                                   :options="remainedOptions"
                                                   name="demeanor"/>
                            </div>
                            <input-error :message="form.error('monitoring_data.politeness_behaviour.demeanor')"/>
                        </div>

                        <div class="form-input-block py-4">
                            <div class="grid gap-4 md:grid-cols-2 md:gap-24">
                                <label-component class="!mb-0">
                                    अन्तर्वार्ता सकिएपछी गणकले उत्तरदाता र अन्य सम्बिन्धत सबैलाई धन्यवाद ज्ञापन गरे ?
                                </label-component>
                                <radio-group-input id="gratitude"
                                                   v-model="form.monitoring_data.politeness_behaviour.gratitude"
                                                   :options="didDidNotOptions"
                                                   name="gratitude"/>
                            </div>
                            <input-error :message="form.error('monitoring_data.politeness_behaviour.gratitude')"/>
                        </div>
                    </div>
                </div>
                <!--interview process-->
                <div class="card mb-6">
                    <h2 class="heading-primary">
                        अन्तर्वार्ता प्रक्रिया
                    </h2>
                    <div>
                        <div class="form-input-block py-4">
                            <div class="grid gap-4 md:grid-cols-2 md:gap-24">
                                <label-component class="!mb-0">
                                    गणकले प्रश्नावलिमा लेखिएको प्रश्न जस्ताको तस्तै पढेर उत्तरदातालाई सोधेका थिए ?
                                </label-component>
                                <radio-group-input id="question_similarity"
                                                   v-model="form.monitoring_data.interview_process.question_similarity"
                                                   :options="wereWereNotOptions"
                                                   name="question_similarity"/>
                            </div>
                            <input-error :message="form.error('monitoring_data.interview_process.question_similarity')"/>
                        </div>

                        <div class="form-input-block py-4">
                            <div class="grid gap-4 md:grid-cols-2 md:gap-24">
                                <label-component class="!mb-0">
                                    उत्तरदाताले कुनै प्रश्न नबुझेमा गणकले त्यस्ता प्रश्नलाई प्रश्नको आशय नबिग्रने गरी थप स्पष्ट पारेका थिए ?
                                </label-component>
                                <radio-group-input id="clarification"
                                                   v-model="form.monitoring_data.interview_process.clarification"
                                                   :options="wereWereNotOptions"
                                                   name="clarification"/>
                            </div>
                            <input-error :message="form.error('monitoring_data.interview_process.clarification')"/>
                        </div>

                        <div class="form-input-block py-4">
                            <div class="grid gap-4 md:grid-cols-2 md:gap-24">
                                <label-component class="!mb-0">
                                    गणकले प्रश्नावली को प्रत्येक खण्डका प्रत्येक प्रश्न सोधेर परिवारमूलीले दिएको उत्तर लेखेका थिए ?
                                </label-component>
                                <radio-group-input id="every_section"
                                                   v-model="form.monitoring_data.interview_process.every_section"
                                                   :options="wereWereNotOptions"
                                                   name="every_section"/>
                            </div>
                            <input-error :message="form.error('monitoring_data.interview_process.every_section')"/>
                        </div>
                    </div>
                </div>

                <!--time management-->
                <div class="card mb-6">
                    <h2 class="heading-primary">
                        समय व्यवस्थापन
                    </h2>
                    <div>
                        <div class="form-input-block py-4">
                            <div class="grid gap-4 md:grid-cols-2 md:gap-24">
                                <label-component class="!mb-0">
                                    गणकले उत्तरदातासँग कुनै प्रश्नमा लामो बहस गर्ने गरेका थिए ?
                                </label-component>
                                <radio-group-input id="long_argument"
                                                   v-model="form.monitoring_data.time_management.long_argument"
                                                   :options="wereWereNotOptions"
                                                   name="long_argument"/>
                            </div>
                            <input-error :message="form.error('monitoring_data.time_management.long_argument')"/>
                        </div>

                        <div class="form-input-block py-4">
                            <div class="grid gap-4 md:grid-cols-2 md:gap-24">
                                <label-component class="!mb-0">
                                    उत्तरदाताबाट असान्दर्भिक वा जटिल उत्तर पाउने बित्तिकै गणकले बीचमा बोलेर उत्तरदातालाई रोकेका थिए ?
                                </label-component>
                                <radio-group-input id="interruption"
                                                   v-model="form.monitoring_data.time_management.interruption"
                                                   :options="wereWereNotOptions"
                                                   name="interruption"/>
                            </div>
                            <input-error :message="form.error('monitoring_data.time_management.interruption')"/>
                        </div>

                        <div class="form-input-block py-4">
                            <div class="grid gap-4 md:grid-cols-2 md:gap-24">
                                <label-component class="!mb-0">
                                    उत्तरदातालाई छिटो छिटो उत्तर दिन लगाउने वा गणकले अन्तर्वार्ता लिंदा हतार गरेका थिए ?
                                </label-component>
                                <radio-group-input id="impatience"
                                                   v-model="form.monitoring_data.time_management.impatience"
                                                   :options="wereWereNotOptions"
                                                   name="impatience"/>
                            </div>
                            <input-error :message="form.error('monitoring_data.time_management.impatience')"/>
                        </div>
                    </div>
                </div>

                <!--impartiality-->
                <div class="card mb-6">
                    <h2 class="heading-primary">
                        निष्पक्षता
                    </h2>
                    <div>
                        <div class="form-input-block py-4">
                            <div class="grid gap-4 md:grid-cols-2 md:gap-24">
                                <label-component class="!mb-0">
                                    अन्तर्वार्ता लिँदा गणकले प्रश्न र उत्तर दुबैमा तटस्थ व्यवहार देखाएका थिए ?
                                </label-component>
                                <radio-group-input id="neutrality"
                                                   v-model="form.monitoring_data.impartiality.neutrality"
                                                   :options="wereWereNotOptions"
                                                   name="neutrality"/>
                            </div>
                            <input-error :message="form.error('monitoring_data.impartiality.neutrality')"/>
                        </div>

                        <div class="form-input-block py-4">
                            <div class="grid gap-4 md:grid-cols-2 md:gap-24">
                                <label-component class="!mb-0">
                                    उत्तर्दाताबाट प्राप्त कुनै उत्तर सुनेर गणक आश्चर्य वा उत्तर मन नपराएको जस्तो हाउभाउ देखाएका थिए ?
                                </label-component>
                                <radio-group-input id="reaction"
                                                   v-model="form.monitoring_data.impartiality.reaction"
                                                   :options="wereWereNotOptions"
                                                   name="reaction"/>
                            </div>
                            <input-error :message="form.error('monitoring_data.impartiality.reaction')"/>
                        </div>

                        <div class="form-input-block py-4">
                            <div class="grid gap-4 md:grid-cols-2 md:gap-24">
                                <label-component class="!mb-0">
                                    गणकले कुनै प्रश्नको सन्दर्भमा आफ्नो निजी विचार उत्तरदातालाई सुनाएका थिए ?
                                </label-component>
                                <radio-group-input id="personal_opinion"
                                                   v-model="form.monitoring_data.impartiality.personal_opinion"
                                                   :options="wereWereNotOptions"
                                                   name="personal_opinion"/>
                            </div>
                            <input-error :message="form.error('monitoring_data.impartiality.personal_opinion')"/>
                        </div>

                        <div class="form-input-block py-4">
                            <div class="grid gap-4 md:grid-cols-2 md:gap-24">
                                <label-component class="!mb-0">
                                    गणकले प्रश्न सोध्दा उत्तर पनि आफैले सुझाएको वा संकेत गरेका थिए ?
                                </label-component>
                                <radio-group-input id="answer_indication"
                                                   v-model="form.monitoring_data.impartiality.answer_indication"
                                                   :options="wereWereNotOptions"
                                                   name="answer_indication"/>
                            </div>
                            <input-error :message="form.error('monitoring_data.impartiality.answer_indication')"/>
                        </div>
                    </div>
                </div>

                <div class="card  text-base-2 mb-6">
                    <h2 class="heading-primary">
                        मुख्य प्रश्नावली सम्बन्धी विवरणको स्थलगत जाचँ
                    </h2>
                    <p class="mb-6">
                        (नोटः सुपरिवेक्षकले संकलन गरेको सूचीकरणको विवरण भन्दा सुपरिवेक्षणकर्ताले भरेको विवरण फरक भएमा यकिन गरी सही विवरण भर्न लगाउने)
                    </p>
                    <enumerator-onsite-monitoring
                        name="monitoring_data.onsite_monitoring"
                        :onsite-monitoring="form.monitoring_data.onsite_monitoring"
                        :form="form"
                        @input="form.monitoring_data.onsite_monitoring = $event"/>
                </div>

                <!--suggestions-->
                <div class="card mb-6">
                    <h2 class="heading-primary">
                        अनुगमनकर्ताको सुझावहरु
                    </h2>

                    <textarea id="monitoring_suggestions"
                              v-model="form.monitoring_data.monitoring_suggestions"
                              placeholder="अनुगमनकर्ताको सुझावहरु"
                              class="border form-control rounded-md shadow-sm block mt-1 w-full form-textarea md:w-2/3"/>
                    <input-error :message="form.error('monitoring_data.monitoring_suggestions')"/>
                </div>
                <div class="flex items-center bg-white bottom-0 py-4 md:py-6 gap-4 md:gap-6  sticky w-full">
                    <cancel-button :href="route('admin.monitorings.index')">
                        Cancel
                    </cancel-button>
                    <primary-button type="submit">
                        Save
                    </primary-button>
                    <action-message :on="form.recentlySuccessful">
                        Saved
                    </action-message>
                </div>
            </div>
        </form>
    </admin-layout>
</template>

<script>
    import ActionMessage              from "../../Components/ActionMessage"
    import PrimaryButton              from "../../Components/Buttons/PrimaryButton"
    import CancelButton               from "../../Components/Buttons/TertiaryButton"
    import DropDownInput              from "../../Components/Forms/DropDownInput"
    import InputError                 from "../../Components/Forms/InputError"
    import LabelComponent             from "../../Components/Forms/Label"
    import RadioGroupInput            from "../../Components/Forms/RadioGroupInput"
    import InputComponent             from "../../Components/Forms/TextInput"
    import AdminLayout                from "../../Layouts/AdminLayout"
    import EnumeratorOnsiteMonitoring from "./Partials/EnumeratorOnsiteMonitoring"

    const OPERATOR = "operators"

    export default {
        name: "EnumeratorMonitoringFormAdd",
        components: { EnumeratorOnsiteMonitoring, RadioGroupInput, InputComponent, DropDownInput, InputError, LabelComponent, ActionMessage, CancelButton, PrimaryButton, AdminLayout },
        props: {
            districts: { type: Array, required: true },
            monitors: { type: Array, required: false, default: () => ([]) },
            options: { type: Object, required: true },
        },
        provide() {
            return {
                districts: this.districts,
                genderOptions: this.genderOptions,
                accuracyOptions: this.accuracyOptions,
                wereWereNotOptions: this.wereWereNotOptions,
                homeOwnerOptions: this.homeOwnerOptions,
                roofMaterialOptions: this.roofMaterialOptions,
                yesNoOptions: this.yesNoOptions,
            }
        },
        data() {
            return {
                form: this.$inertia.form({
                    monitored_by: null,
                    district: "",
                    municipality: "",
                    ward: "",
                    census_area: "",
                    monitoring_data: {
                        monitored_person_name: "",
                        monitored_person_mobile_no: "",
                        politeness_behaviour: {
                            greeting: "",
                            introduction: "",
                            objective: "",
                            demeanor: "",
                            gratitude: "",
                        },
                        interview_process: {
                            question_similarity: "",
                            clarification: "",
                            every_section: "",
                        },
                        time_management: {
                            long_argument: "",
                            interruption: "",
                            impatience: "",
                        },
                        impartiality: {
                            neutrality: "",
                            reaction: "",
                            personal_opinion: "",
                            answer_indication: "",
                        },
                        onsite_monitoring: {
                            house_sn: "",
                            family_sn: "",
                            head_of_household_name: "",
                            head_of_household_age: "",
                            head_of_household_gender: "",
                            resident_family_total: "",
                            resident_family_male: "",
                            resident_family_female: "",
                            resident_family_check: "",
                            absent_family_total: "",
                            absent_family_male: "",
                            absent_family_female: "",
                            absent_family_check: "",
                            current_house_owner: "",
                            current_house_check: "",
                            current_house_roof: "",
                            current_house_roof_check: "",
                            facility_television_code: "",
                            facility_television_check: "",
                            facility_computer_code: "",
                            facility_computer_check: "",
                            facility_refrigerator_code: "",
                            facility_refrigerator_check: "",
                            family_death: "",
                            family_death_check: "",
                            child_less_than_one_count: "",
                            child_less_than_one_check: "",
                            elderly_more_than_60_count: "",
                            elderly_more_than_60_check: "",
                            literate_read_write_count: "",
                            literate_read_write_check: "",
                            disabled_count: "",
                            disabled_check: "",
                        },
                        monitoring_suggestions: "",
                    },
                }, {
                    bag: "enumeratorMonitoringForm",
                    resetOnSuccess: true,
                }),
            }
        },
        computed: {
            isOperator: function() {
                return this.getFromObject(this.$page, "props.auth.user.role") === OPERATOR
            },
            monitorOptions: function() {
                const options = new Map()
                if (this.isOperator) {
                    this.monitors.forEach(monitor => {
                        options.set(monitor.id, monitor.name)
                    })
                }
                return options
            },
            districtOptions: function() {
                const options = new Map()
                const key = this.$currentLocale === "en" ? "title_en" : "title_ne"

                this.districts.forEach(district => {
                    options.set(parseInt(district.code), district[key])
                })

                return options
            },
            selectedDistrict: function() {
                if (!this.form.district) {
                    return null
                }

                return this.districts.find(district => {
                    return parseInt(district.code) === parseInt(this.form.district)
                })
            },
            municipalityOptions: function() {
                if (!this.selectedDistrict || !this.selectedDistrict.municipalities) {
                    return {}
                }

                const options = new Map()
                const key = this.$currentLocale === "en" ? "title_en" : "title_ne"

                this.selectedDistrict.municipalities.forEach(municipality => {
                    options.set(parseInt(municipality.code), municipality[key])
                })

                return options
            },

            selectedMunicipality: function() {
                if (!this.form.municipality || !this.selectedDistrict.municipalities) {
                    return null
                }

                return this.selectedDistrict.municipalities.find(municipality => {
                    return parseInt(municipality.code) === parseInt(this.form.municipality)
                })
            },

            wardOptions: function() {
                if (!this.selectedMunicipality || !this.selectedMunicipality.wards) {
                    return {}
                }

                const options = new Map()

                for (let i = 1; i <= parseInt(this.selectedMunicipality.wards); i++) {
                    options.set(i, this.numberTrans(i))
                }

                return options
            },
            genderOptions: function() {
                const options = new Map()

                this.options.genderOptions.forEach(option => {
                    options.set(option, this.trans(`application.gender-${option}`))
                })
                return options
            },
            accuracyOptions: function() {
                const options = new Map()
                this.options.accurateOptions.forEach(option => {
                    options.set(option, this.trans(`admin-monitoring.form-options.${option}`))
                })
                return options
            },
            explainOptions: function() {
                const options = new Map()

                this.options.explainedOptions.forEach(option => {
                    options.set(option, this.trans(`admin-monitoring.form-options.${option}`))
                })

                return options
            },
            yesNoOptions: function() {
                const options = new Map()
                this.options.yesNoOptions.forEach(option => {
                    options.set(option, this.trans(`admin-monitoring.form-options.${option}`))
                })
                return options
            },
            remainedOptions: function() {
                const options = new Map()
                this.options.remainedOptions.forEach(option => {
                    options.set(option, this.trans(`admin-monitoring.form-options.${option}`))
                })
                return options
            },
            didDidNotOptions: function() {
                const options = new Map()
                this.options.didOptions.forEach(option => {
                    options.set(option, this.trans(`admin-monitoring.form-options.${option}`))
                })
                return options
            },
            doneNotDoneOptions: function() {
                const options = new Map()
                this.options.doneOptions.forEach(option => {
                    options.set(option, this.trans(`admin-monitoring.form-options.${option}`))
                })
                return options
            },
            wereWereNotOptions: function() {
                const options = new Map()
                this.options.wereOptions.forEach(option => {
                    options.set(option, this.trans(`admin-monitoring.form-options.${option}`))
                })
                return options
            },

            homeOwnerOptions: function() {
                const options = new Map()
                this.options.homeOwnershipOptions.forEach(option => {
                    options.set(option, this.trans(`admin-monitoring.form-options.home_ownership.${option}`))
                })
                return options
            },
            roofMaterialOptions: function() {
                const options = new Map()
                this.options.roofOptions.forEach(option => {
                    options.set(option, this.trans(`admin-monitoring.form-options.roof_material.${option}`))
                })
                return options
            },
        },
        mounted() {
            this.$set(this.form, "district", this.getInitialDistrict())
            this.$set(this.form, "municipality", this.getInitialMunicipality())
            this.$set(this.form, "ward", this.getInitialWard())
        },
        methods: {
            getInitialDistrict: function() {
                return this.getFromObject(this.$page, "props.auth.user.district_code")
            },
            getInitialMunicipality: function() {
                if (!this.selectedDistrict || !this.selectedDistrict.municipalities) {
                    return ""
                }
                return this.municipalityOptions.keys().next().value ?? ""
            },
            getInitialWard: function() {
                if (!this.selectedMunicipality || !this.selectedMunicipality.wards) {
                    return ""
                }
                return this.wardOptions.keys().next().value ?? ""
            },
            handleOnChange(value, name) {
                this.$set(this.form, name, value)
            },
            submitForm() {
                this.form.post(this.route("admin.monitorings.form.enumerator.store"))
            },
        },
    }
</script>
