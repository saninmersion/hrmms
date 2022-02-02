<template>
    <div class="card card-js-scroll my-8">
        <h3 class="heading-primary">
            {{ trans("application.sections.personal") }}
        </h3>

        <!--   applicant's full name - nepali & english -->
        <div class="grid xl:grid-cols-2 gap-6">
            <div>
                <label-component :value="trans('application.fields.name_in_nepali')"/>
                <div class="grid sm:grid-cols-3 gap-3">
                    <full-name-input v-model="personal.name_in_nepali" name="personal.name_in_nepali" :form="form"/>
                </div>
            </div>

            <div>
                <label-component :value="trans('application.fields.name_in_english')"/>
                <div class="grid sm:grid-cols-3 gap-3">
                    <full-name-input v-model="personal.name_in_english" locale="en" name="personal.name_in_english" :form="form"/>
                </div>
            </div>
        </div>

        <!--    photo & gender     -->
        <div class="grid lg:grid-cols-2 gap-6 my-6">
            <div class="input-group">
                <label-component :value="trans('application.fields.applicant_photo')" class="block"/>
                <span class="text-base-3">
                    {{ trans("application.form-help-text.image-format-size") }}
                </span>
                <camera-input v-model="personal.applicant_photo"
                              :submit-url="fileUploadUrl"/>
                <input-error v-if="form.error('personal.applicant_photo')"
                             :message="form.error('personal.applicant_photo')"/>
            </div>
            <div>
                <label-component :value="trans('application.fields.gender')"/>
                <div class="w-full">
                    <gender-input v-model="personal.gender" class="flex gap-4" name="gender"/>
                </div>
                <input-error v-if="form.error('personal.gender')"
                             :message="form.error('personal.gender')"/>
            </div>
        </div>
        <!--    caste & language     -->
        <div class="grid md:grid-cols-2 gap-6">
            <div>
                <label-component :value="trans('application.fields.ethnicity')" class="block mb-2"/>
                <div class="gap-2 grid grid-cols-2">
                    <div>
                        <drop-down-input v-model="personal.ethnicity"
                                         :options="ethnicityOptions"
                                         :placeholder="trans('application.placeholders.ethnicity')"
                                         :show-placeholder="true"/>
                    </div>
                    <input-component v-if="personal.ethnicity === 'other'"
                                     v-model="personal.ethnicity_other"
                                     placeholder="अन्य खुलाउनुहाेस्"
                                     class="col-span-2 md:col-span-1"/>
                </div>

                <input-error :message="form.error('personal.ethnicity')"/>
                <input-error :message="form.error('personal.ethnicity_other')"/>
            </div>
            <div>
                <label-component :value="trans('application.fields.mother_tongue')" class="block mb-2"/>
                <div class="gap-2 grid grid-cols-2">
                    <div class="col-span-2 md:col-span-1">
                        <drop-down-input v-model="personal.mother_tongue"
                                         :options="motherTongueOptions"
                                         :placeholder="trans('application.placeholders.mother_tongue')"
                                         :show-placeholder="true"
                                         class="w-50"/>
                    </div>
                    <input-component v-if="personal.mother_tongue === 'other'"
                                     v-model="personal.mother_tongue_other"
                                     placeholder="अन्य खुलाउनुहाेस्"
                                     class="col-span-2 md:col-span-1"/>
                </div>
                <input-error :message="form.error('personal.mother_tongue')"/>
                <input-error :message="form.error('personal.mother_tongue_other')"/>
            </div>
        </div>

        <!--   disability    -->
        <div class="lg:grid lg:grid-cols-2 gap-4 my-6">
            <div>
                <label-component :value="trans('application.fields.disability')"/>
                <div class="w-full pt-2">
                    <radio-group-input v-model="personal.disability"
                                       :options="disabilityOptions"
                                       class="flex gap-4 radio-btn-group"
                                       name="personal.disability"/>
                </div>
                <input-error :message="form.error('personal.disability')"/>
            </div>
        </div>

        <!-- DOB -->
        <fieldset>
            <legend>
                {{ trans("application.fields.dob") }}
            </legend>

            <div>
                <div class="sm:flex gap-2.5">
                    <div class="date-btn-wrap border rounded-md border-base-2 overflow-hidden flex self-end mb-4 sm:mb-0">
                        <button
                            :class="(dob_type === 'ad') ? ' py-2 px-4 !bg-base-2 !text-white' : ' py-2 px-4 !text-base-2 '"
                            @click="dob_type = 'ad'">
                            {{ trans("application.calendar_ad") }}
                        </button>
                        <button
                            :class="(dob_type === 'bs') ? ' py-2 px-4 !bg-base-2 !text-white':' py-2 px-4 text-base-2 '"
                            @click="dob_type = 'bs'">
                            {{ trans("application.calendar_bs") }}
                        </button>
                    </div>
                    <div class="md:w-1/2">
                        <nepali-date-picker v-if="dob_type === 'bs'"
                                            :value="personal.dob_bs"
                                            type="bs"
                                            @input="handleDobChange"/>
                        <nepali-date-picker v-if="dob_type === 'ad'"
                                            :value="personal.dob_ad"
                                            type="ad"
                                            @input="handleDobChange"/>
                    </div>
                </div>
                <div class="sm:pl-28 pt-2">
                    <input-error v-if="form.error('personal.dob_bs')"
                                 :message="form.error('personal.dob_bs')"/>
                    <input-error v-if="form.error('personal.dob_ad')"
                                 :message="form.error('personal.dob_ad')"/>
                </div>
            </div>
        </fieldset>

        <!-- citizenship num.  -->
        <fieldset class="!my-6">
            <legend>{{ trans("application.fields.citizenship") }}</legend>
            <div class="flex flex-wrap gap-4 md:gap-3">
                <div class="grid sm:grid-cols-2 gap-4 md:gap-3 md:w-1/3">
                    <div>
                        <label-component :value="trans('application.fields.citizenship_no')"/>
                        <input-component v-model="personal.citizenship_number" type="text"/>
                        <input-error v-if="form.error('personal.citizenship_number')"
                                     :message="form.error('personal.citizenship_number')"/>
                    </div>
                    <div>
                        <label-component :value="trans('application.fields.citizenship_issued_district')"/>
                        <drop-down-input
                            v-model="personal.citizenship_issued_district"
                            :options="oldDistrictOptions"
                            @keypress="validation().clear('personal.citizenship_issued_district')"/>
                        <input-error v-if="form.error('personal.citizenship_issued_district')"
                                     :message="form.error('personal.citizenship_issued_district')"/>
                    </div>
                </div>
                <div class="w-full md:w-1/2">
                    <label-component :value="trans('application.fields.citizenship_issued_date')"/>
                    <nepali-date-picker v-model="personal.citizenship_issued_date"
                                        :show-labels="false"/>
                    <input-error v-if="form.error('personal.citizenship_issued_date')"
                                 :message="form.error('personal.citizenship_issued_date')"/>
                </div>
            </div>
            <div class="mt-6 grid md:grid-cols-2 gap-6">
                <div class="input-file">
                    <label-component :value="trans('application.fields.citizenship_uploads') "/>
                    <div class="relative">
                        <span class="text-base-3">
                            {{ trans("application.form-help-text.image-format-size") }}</span>
                        <camera-input v-model="personal.citizenship_files"
                                      :submit-url="fileUploadUrl"
                                      :multiple="false"/>

                        <input-error v-if="form.error('personal.citizenship_files')"
                                     :message="form.error('personal.citizenship_files')"/>
                    </div>
                </div>
            </div>
        </fieldset>

        <!-- permanent address       -->
        <fieldset>
            <legend>{{ trans("application.fields.permanent_address") }}</legend>
            <div class="lg:w-3/4">
                <div class="grid sm:grid-cols-3 lg:grid-cols-4 gap-3 ">
                    <location-combo v-model="personal.permanent_address"
                                    :districts="newDistrictOptions"
                                    :has-tole="false"
                                    name="personal.permanent_address"/>
                </div>
                <div class="mt-4">
                    {{ trans("application.form-help-text.permanent_address") }}
                </div>
            </div>
        </fieldset>

        <!-- current address       -->
        <div class="my-6">
            <label class="checkbox-label">
                <input v-model="personal.has_current_address"
                       class="checkbox"
                       type="checkbox">
                {{ trans("application.fields.has_current_address") }}
            </label>
        </div>

        <fieldset v-if="personal.has_current_address" class="!mb-6">
            <legend>{{ trans("application.fields.current_address") }}</legend>
            <div class="lg:w-3/4">
                <div class="grid sm:grid-cols-3 lg:grid-cols-4 gap-3">
                    <location-combo v-model="personal.current_address"
                                    :districts="newDistrictOptions"
                                    :has-tole="false"
                                    name="personal.current_address"/>
                </div>
            </div>
        </fieldset>

        <div class="mb-4">
            <div class="grid xl:grid-cols-2 gap-6">
                <div>
                    <label-component :value="trans('application.fields.mobile_number')"/>
                    <div class="grid sm:grid-cols-3 gap-3">
                        <input-component v-model="personal.mobile_number" type="number"/>
                    </div>
                    <input-error :message="form.error('personal.mobile_number')"/>
                </div>
            </div>
        </div>

        <div class="grid gap-6">
            <div class="grid xl:grid-cols-2 gap-6">
                <div>
                    <div>
                        <h4>{{ trans("application.fields.mother_name") }}</h4>
                    </div>

                    <div class="grid sm:grid-cols-3 gap-3">
                        <full-name-input v-model="personal.mother_name"
                                         class="my-4 lg:my-0"
                                         name="personal.mother_name"
                                         :form="form"/>
                    </div>
                </div>
                <div>
                    <div>
                        <h4>{{ trans("application.fields.father_name") }}</h4>
                    </div>

                    <div class="grid sm:grid-cols-3 gap-3">
                        <full-name-input v-model="personal.father_name"
                                         class="my-4 lg:my-0"
                                         name="personal.father_name"
                                         :form="form"/>
                    </div>
                </div>
            </div>
            <div class="grid xl:grid-cols-2 gap-6">
                <div>
                    <div>
                        <h4>{{ trans("application.fields.grand_father_name") }}</h4>
                    </div>

                    <div class="grid sm:grid-cols-3 gap-3">
                        <full-name-input v-model="personal.grand_father_name"
                                         class="my-4 lg:my-auto"
                                         name="personal.grand_father_name"
                                         :form="form"/>
                    </div>
                </div>
                <div class="flex-grow">
                    <div>
                        <h4>
                            {{ trans("application.fields.spouse_name") }}
                            <span class="text-base-3">( {{ trans("application.form-help-text.spouse") }} )</span>
                        </h4>
                    </div>

                    <div class="grid sm:grid-cols-3 gap-3">
                        <full-name-input v-model="personal.spouse_name"
                                         class="my-4 lg:my-0"
                                         name="personal.spouse_name"
                                         :form="form"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script type="text/ecmascript-6">
    import {
        ADToBS,
        BSToAD,
    }                      from "bikram-sambat-js"
    import {
        CameraInput,
        DropDownInput,
        FullNameInput,
        GenderInput,
        InputComponent,
        InputError,
        LabelComponent,
        LocationCombo,
        NepaliDatePicker,
    }                      from "../../../Components/Forms"
    import RadioGroupInput from "../../../Components/Forms/RadioGroupInput"

    export default {
        name: "ApplicationPersonalForm",

        inject: ["mediaUploadUrl", "oldDistricts", "newDistricts", "ethnicities", "motherTongues", "disabilities"],

        components: {
            CameraInput,
            RadioGroupInput,
            LocationCombo,
            DropDownInput,
            InputComponent,
            NepaliDatePicker,
            InputError,
            GenderInput,
            FullNameInput,
            LabelComponent,
        },

        props: {
            form: { type: Object, required: true },
            value: { type: Object, required: true },
        },

        data: () => ({
            personal: {},

            dob_type: "bs",
        }),

        watch: {
            value: {
                handler(value) {
                    this.personal = value
                    this.handleDobChange(this.dob_type === "bs" ? value.dob_bs : value.dob_ad)
                },
                immediate: true,
                deep: true,
            },
        },

        computed: {
            fileUploadUrl: function() {
                return this.mediaUploadUrl
            },

            oldDistrictOptions: function() {
                const options = new Map()
                const key = this.$currentLocale === "en" ? "title_en" : "title_ne"

                this.oldDistricts.forEach(district => {
                    options.set(parseInt(district.code), district[key])
                })

                return options
            },

            newDistrictOptions: function() {
                return this.newDistricts
            },

            ethnicityOptions: function() {
                const options = new Map()

                this.ethnicities.forEach(caste => {
                    options.set(caste, this.trans(`caste.${caste}`))
                })

                return options
            },

            motherTongueOptions: function() {
                const options = new Map()

                this.motherTongues.forEach(language => {
                    options.set(language, this.trans(`mother_tongue.${language}`))
                })

                return options
            },

            disabilityOptions: function() {
                const options = new Map()

                this.disabilities.forEach(language => {
                    options.set(language, this.trans(`application.disabilities.${language}`))
                })

                return options
            },
        },

        mounted() {},

        methods: {
            handleDobChange(date) {
                const dateConverter = this.dob_type === "bs" ? BSToAD : ADToBS
                const dobKey = this.dob_type === "bs" ? "dob_bs" : "dob_ad"
                const dobKeyConverted = this.dob_type === "bs" ? "dob_ad" : "dob_bs"

                const dateConverted = dateConverter(date)

                this.$set(this.personal, dobKey, date)
                this.$set(this.personal, dobKeyConverted, dateConverted)
            },
        },
    }
</script>
