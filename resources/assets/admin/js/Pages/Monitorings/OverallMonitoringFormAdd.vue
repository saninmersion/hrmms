<template>
    <admin-layout page-title="Overall Monitoring Form" :authorized="isAuthorized($page, 'monitoring_general')">
        <form id="overall-monitoring-form"
              class="form"
              @submit.prevent="submitForm">
            <div v-if="isOperator" class="md:w-1/2 mb-6">
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
                अनुगमन फाराम
            </h6>
            <!-- monitoring location-->
            <div class="card">
                <h6 class="heading-primary">
                    अनुगमन गरेको:
                </h6>
                <div class="grid md:grid-cols-3 gap-4">
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
                        <label-component value="भेट गरिएको सुपरिवेक्षक/गणक को नाम "/>
                        <input-component v-model="form.monitoring_data.monitored_person_name" name="monitored_person_name" class="block w-full"/>
                        <input-error :message="form.error('monitoring_data.monitored_person_name')"/>
                    </div>
                    <div>
                        <label-component value="सम्पर्क नं."/>
                        <input-component v-model="form.monitoring_data.monitored_person_mobile_no" name="monitored_person_mobile_no" class="block w-full"/>
                        <input-error :message="form.error('monitoring_data.monitored_person_mobile_no')"/>
                    </div>
                    <div>
                        <label-component value="भेट गरिएको परिवार संख्या"/>
                        <input-component v-model="form.monitoring_data.family_count" name="family_count" class="block w-full"/>
                        <input-error :message="form.error('monitoring_data.family_count')"/>
                    </div>
                </div>
            </div>
            <!-- on site data-->
            <div class="card">
                <h6 class="heading-primary">
                    अनुगमनकर्ताले स्थलगत अनुगमनका क्रममा देखिएका विषयवस्तु
                </h6>
                <div>
                    <div class="form-input-block py-4">
                        <div class="flex flex-wrap md:flex-nowrap  gap-4 md:gap-8">
                            <label-component class="w-full md:w-2/5 !mb-0 !text-base-2">
                                परिवार बसोबास गरेको घरमा मार्करले जनगणना घर तथा घरपरिवार क्रं. सं. लेखेको नलेखेको
                            </label-component>
                            <radio-group-input id="marking"
                                               v-model="form.monitoring_data.onsite_monitoring.marking"
                                               class="w-full md:w-3/5 md:!gap-0"
                                               :options="markingOptions"
                                               name="marking"
                                               @change="handleOnChange($event, 'monitoring_data.onsite_monitoring.marking')"/>
                        </div>
                        <input-error :message="form.error('monitoring_data.onsite_monitoring.marking')"/>
                    </div>
                    <div class="form-input-block py-4">
                        <div class="flex flex-wrap md:flex-nowrap  gap-4 md:gap-8">
                            <label-component class="w-full md:w-2/5 !mb-0 !text-base-2">
                                घरपरिवारलाई जनगणनाको वारेमा गणना पुर्व जानकारी भए नभएको
                            </label-component>
                            <radio-group-input id="prior_information"
                                               v-model="form.monitoring_data.onsite_monitoring.prior_information"
                                               class="w-full md:w-3/5 md:!gap-0"
                                               :options="priorOptions"
                                               name="prior_information"
                                               @change="handleOnChange($event, 'monitoring_data.onsite_monitoring.prior_information')"/>
                        </div>
                        <input-error :message="form.error('monitoring_data.onsite_monitoring.prior_information')"/>
                    </div>
                    <div class="form-input-block py-4">
                        <div class="flex flex-wrap md:flex-nowrap  gap-4 md:gap-8">
                            <label-component class="w-full md:w-2/5 !mb-0 !text-base-2">
                                अनुगमन गरिएका क्षेत्रमा जनगणना सम्बन्धी स्थानीय रुपमा प्रचार प्रसार भए नभएको
                            </label-component>
                            <radio-group-input id="local_advertisement"
                                               v-model="form.monitoring_data.onsite_monitoring.local_advertisement"
                                               class="w-full md:w-3/5"
                                               :options="localAdvertisementOptions"
                                               name="local_advertisement"
                                               @change="handleOnChange($event, 'monitoring_data.onsite_monitoring.local_advertisement')"/>
                        </div>
                        <input-error :message="form.error('monitoring_data.onsite_monitoring.local_advertisement')"/>
                    </div>
                    <div class="form-input-block py-4">
                        <div class="flex flex-wrap md:flex-nowrap  gap-4 md:gap-8">
                            <label-component class="w-full md:w-2/5 !mb-0 !text-base-2">
                                गणना गरिसकिएको गाउँ/टोल/बस्तीमा कुनै घर परिवार गणना गर्न छुट भए नभएको
                            </label-component>
                            <radio-group-input id="missed_count"
                                               v-model="form.monitoring_data.onsite_monitoring.missed_count"
                                               class="w-full md:w-3/5"
                                               :options="missedCountOptions"
                                               name="missed_count"
                                               @change="handleOnChange($event, 'monitoring_data.onsite_monitoring.missed_count')"/>
                        </div>
                        <input-error :message="form.error('monitoring_data.onsite_monitoring.missed_count')"/>
                    </div>

                    <div class="form-input-block py-4">
                        <div class="flex flex-wrap md:flex-nowrap  gap-4 md:gap-8">
                            <label-component class="w-full md:w-2/5 !mb-0 !text-base-2">
                                जनगणनाको स्थलगत गणना कार्य प्रती उत्तरदाताहरुको कुनै गुनासो भए नभएको
                            </label-component>
                            <radio-group-input id="complaints"
                                               v-model="form.monitoring_data.onsite_monitoring.complaints"
                                               class="w-full md:w-3/5"
                                               :options="complaintsOptions"

                                               name="complaints"
                                               @change="handleOnChange($event, 'monitoring_data.onsite_monitoring.complaints')"/>
                        </div>
                        <input-error :message="form.error('monitoring_data.onsite_monitoring.complaints')"/>
                    </div>
                    <div class="form-input-block py-4">
                        <div class="flex flex-wrap md:flex-nowrap  gap-4 md:gap-8">
                            <label-component class="w-full md:w-2/5 !mb-0 !text-base-2">
                                जनगणनाको स्थलगत कार्यमा बाधा व्यवधान भए नभएको
                            </label-component>
                            <radio-group-input id="obstacles"
                                               v-model="form.monitoring_data.onsite_monitoring.obstacles"
                                               class="w-full md:w-3/5"
                                               :options="obstaclesOptions"
                                               name="obstacles"
                                               @change="handleOnChange($event, 'monitoring_data.onsite_monitoring.obstacles')"/>
                        </div>
                        <input-error :message="form.error('monitoring_data.onsite_monitoring.obstacles')"/>
                    </div>
                </div>
            </div>
            <!--obstacles and suggestions-->
            <div class="card">
                <h2 class="heading-primary">
                    अनुगमनका क्रममा देखिएका अन्य समस्याहरु
                </h2>
                <textarea id="monitoring_problems"
                          v-model="form.monitoring_data.monitoring_problems"
                          placeholder="अनुगमनका क्रममा देखिएका अन्य समस्याहरु"
                          class="border rounded-md shadow-sm block w-full form-control md:w-2/3"/>
                <input-error :message="form.error('monitoring_data.monitoring_problems')"/>
            </div>
            <div class="card">
                <h2 class="heading-primary">
                    अनुगमनकर्ताको सुझावहरु:
                </h2>
                <textarea id="monitoring_suggestions"
                          v-model="form.monitoring_data.monitoring_suggestions"
                          placeholder="अनुगमनकर्ताको  सुझावहरु"
                          class="border rounded-md shadow-sm block w-full form-control md:w-2/3"/>
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
                    Saved.
                </action-message>
            </div>
        </form>
    </admin-layout>
</template>

<script>
    import ActionMessage   from "../../Components/ActionMessage"
    import PrimaryButton   from "../../Components/Buttons/PrimaryButton"
    import CancelButton    from "../../Components/Buttons/TertiaryButton"
    import DropDownInput   from "../../Components/Forms/DropDownInput"
    import InputError      from "../../Components/Forms/InputError"
    import LabelComponent  from "../../Components/Forms/Label"
    import RadioGroupInput from "../../Components/Forms/RadioGroupInput"
    import InputComponent  from "../../Components/Forms/TextInput"
    import AdminLayout     from "../../Layouts/AdminLayout"

    const OPERATOR = "operators"

    export default {
        name: "OverallMonitoringFormAdd",
        components: { RadioGroupInput, InputComponent, DropDownInput, InputError, LabelComponent, ActionMessage, CancelButton, PrimaryButton, AdminLayout },
        props: {
            districts: { type: Array, required: true },
            monitors: { type: Array, required: false, default: () => ([]) },
            options: { type: Object, required: true },
        },
        provide() {
            return {
                districts: this.districts,
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
                        family_count: "",
                        onsite_monitoring: {
                            marking: "",
                            prior_information: "",
                            local_advertisement: "",
                            missed_count: "",
                            complaints: "",
                            obstacles: "",
                        },
                        monitoring_problems: "",
                        monitoring_suggestions: "",
                    },
                }, {
                    bag: "overallMonitoringForm",
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
            markingOptions() {
                const options = new Map()

                this.options.writtenOptions.forEach(option => {
                    options.set(option, this.trans(`admin-monitoring.form-options.${option}`))
                })

                return options
            },
            priorOptions() {
                const options = new Map()

                this.options.informedOptions.forEach(option => {
                    options.set(option, this.trans(`admin-monitoring.form-options.${option}`))
                })
                return options
            },
            localAdvertisementOptions() {
                const options = new Map()

                this.options.hasOptions.forEach(option => {
                    options.set(option, this.trans(`admin-monitoring.form-options.${option}`))
                })
                return options
            },
            missedCountOptions() {
                const options = new Map()

                this.options.missedOptions.forEach(option => {
                    options.set(option, this.trans(`admin-monitoring.form-options.${option}`))
                })
                return options
            },
            complaintsOptions() {
                const options = new Map()

                this.options.hasOptions.forEach(option => {
                    options.set(option, this.trans(`admin-monitoring.form-options.${option}`))
                })
                return options
            },
            obstaclesOptions() {
                const options = new Map()

                this.options.hasOptions.forEach(option => {
                    options.set(option, this.trans(`admin-monitoring.form-options.${option}`))
                })
                return options
            },
        },
        methods: {
            handleOnChange(value, name) {
                if (name === "district") {
                    this.$set(this.form, "municipality", null)
                    this.$set(this.form, "ward", null)
                }

                if (name === "municipality") {
                    this.$set(this.form, "ward", null)
                }
                this.$set(this.form, name, value)
            },
            submitForm() {
                this.form.post(this.route("admin.monitorings.form.overall.store"))
            },
        },
    }
</script>
