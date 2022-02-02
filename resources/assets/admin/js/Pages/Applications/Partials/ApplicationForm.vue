<template>
    <form class="flex justify-between align-center flex-wrap"
          @submit.prevent="()=> {return false}">
        <div class="flex items-baseline w-full flex-wrap">
            <div class="flex flex-row-reverse items-baseline justify-between md:flex-row w-full">
                <div class="w-full md:w-3/4 flex flex-wrap items-center">
                    <h2 class="w-full font-bold px-4 py-2">
                        (क) {{ trans("application.candidate_work_details") }}
                    </h2>
                    <div class="w-full md:w-1/2 px-4 py-2">
                        <label-component :value="'१. '+ trans('application.The position you want to work in')"/>
                        <drop-down-input v-model="formData.application_for" :class="{'error': validation().has('application_for')}" :options="postOptions"/>
                        <input-error :message="validation().get('application_for')"/>
                    </div>

                    <div class="w-full md:w-1/2 px-4 py-2">
                        <label-component :value="'२. '+ trans('application.district')"/>
                        <drop-down-input v-model="formData.application_district_code" :class="{'error': validation().has('application_district_code')}" :options="districtOptions"/>
                        <input-error :message="validation().get('application_district_code')"/>
                    </div>
                    <div class="w-full md:w-1/2 px-4 py-2">
                        <text-input v-model="formData.application_municipality_code" label="३. स्थानीय तह" name="application_municipality_code"/>
                    </div>
                    <div class="w-full md:w-1/2 px-4 py-2">
                        <text-input v-model="formData.ward" label="४. वडा नं." name="ward"/>
                    </div>
                </div>
                <div class="w-full md:w-1/4">
                    <div class="w-full px-4 py-2 ml-auto">
                        <image-input v-model="formData.applicant_photo" label="पासपाेर्ट साईजकाे फोटो" name="passport_photo"/>
                    </div>
                </div>
            </div>

            <hr>

            <h2 class="w-full font-bold px-4 py-2">
                (ख) उम्मेदवारकाे व्यक्तिगत विवरणः
            </h2>
            <div class="w-full md:w-1/2 px-4 py-2">
                <text-input v-model="formData.details.name_in_devanagari" label="उम्मेदवारकाे नाम थर (देवनागरिमा)" name="details[name_in_devanagari]"/>
            </div>
            <div class="w-full md:w-1/2 px-4 py-2">
                <text-input v-model="formData.details.name_in_english"
                            label="उम्मेदवारकाे नाम थर अंग्रेजी (ठूलाे अक्षरमा):"
                            name="details[name_in_english]"/>
            </div>
            <div class="w-full md:w-1/3 px-4 py-2">
                <label-component :value="trans('application.gender')"/>
                <drop-down-input v-model="formData.details.gender" :class="{'error': validation().has('details.gender')}" :options="genderOptions"/>
                <input-error :message="validation().get('details.gender')"/>
            </div>
            <div class="w-full md:w-1/3 px-4 py-2">
                <text-input v-model="formData.details.age"
                            label="हालकाे उमेर (पूरा गरेकाे वर्षमा)"
                            name="details[age]"
                            type="number"/>
            </div>
            <div class="w-full md:w-1/3 px-4 py-2">
                <text-input v-model="formData.applicant.dob_bs"
                            :label="trans('application.date_of_birth')"
                            name="applicant[dob_bs]"/>
            </div>
            <div class="w-full md:w-1/3 px-4 py-2">
                <text-input v-model="formData.applicant.citizenship_number"
                            label="नागरिकता नं."
                            name="applicant[citizenship_number]"/>
            </div>
            <div class="w-full md:w-1/3 px-4 py-2">
                <label-component :value="trans('application.citizenship_district')"/>
                <drop-down-input v-model="formData.applicant.citizenship_issued_district_code"
                                 :class="{'error': validation().has('applicant.citizenship_issued_district_code')}"
                                 :options="districtOptions"/>
                <input-error :message="validation().get('applicant.citizenship_issued_district_code')"/>
            </div>
            <div class="w-full md:w-1/3 px-4 py-2">
                <text-input v-model="formData.applicant.citizenship_issued_date"
                            label="जारी मितिः"
                            name="applicant[citizenship_issued_date]"/>
            </div>

            <h2 class="w-full font-bold px-4 py-2">
                स्थायी ठेगानाः
            </h2>
            <div class="w-full md:w-3/12 px-4 py-2">
                <text-input v-model="formData.permanent_address.district"
                            label="क) जिल्ला"
                            name="permanent_address[district]"/>
            </div>
            <div class="w-full md:w-3/12 px-4 py-2">
                <text-input v-model="formData.permanent_address.local_government"
                            label="ख) स्थानीय तह"
                            name="permanent_address[local_government]"/>
            </div>
            <div class="w-full md:w-2/12 px-4 py-2">
                <text-input v-model="formData.permanent_address.ward"
                            label="ग) वडा नं."
                            name="permanent_address[ward]"/>
            </div>
            <div class="w-full md:w-4/12 px-4 py-2">
                <text-input v-model="formData.permanent_address.tole"
                            label="घ) टाेल"
                            name="permanent_address[tole]"/>
            </div>

            <h2 class="w-full font-bold px-4 py-2">
                अस्थायी ठेगानाः
            </h2>
            <div class="w-full md:w-3/12 px-4 py-2">
                <text-input v-model="formData.temporary_address.district"
                            label="क) जिल्ला"
                            name="temporary_address[district]"/>
            </div>
            <div class="w-full md:w-3/12 px-4 py-2">
                <text-input v-model="formData.temporary_address.local_government"
                            label="ख) स्थानीय तह"
                            name="temporary_address[local_government]"/>
            </div>
            <div class="w-full md:w-2/12 px-4 py-2">
                <text-input v-model="formData.temporary_address.ward"
                            label="ग) वडा नं."
                            name="temporary_address[ward]"/>
            </div>
            <div class="w-full md:w-4/12 px-4 py-2">
                <text-input v-model="formData.temporary_address.tole"
                            label="घ) टाेल"
                            name="temporary_address[tole]"/>
            </div>
            <div class="w-full md:w-4/12 px-4 py-2">
                <text-input v-model="formData.applicant.mobile_number"
                            label="ङ) सम्पर्क माेबाइल नं. "
                            name="applicant[mobile_number]"/>
            </div>
            <div class="md:w-2/3"/>
            <div class="w-full md:w-4/12 px-4 py-2">
                <text-input v-model="formData.details.family_details.grandfather"
                            label="बाजेकाे नाम थर"
                            name="details[family_details][grandfather]"/>
            </div>
            <div class="w-full md:w-4/12 px-4 py-2">
                <text-input v-model="formData.details.family_details.father"
                            label="बाबुकाे नाम थर"
                            name="details[family_details][father]"/>
            </div>
            <div class="w-full md:w-4/12 px-4 py-2">
                <text-input v-model="formData.details.family_details.mother"
                            label="आमाकाे नाम थर"
                            name="details[family_details][mother]"/>
            </div>
            <div class="w-full md:w-4/12 px-4 py-2">
                <text-input v-model="formData.details.family_details.spouse"
                            label="पति/पत्नीकाे नाम थर"
                            name="details[family_details][spouse]"/>
            </div>

            <h2 class="w-full font-bold px-4 py-2">
                (ग) उम्मेदवारकाे शैक्षिक याेग्यता/तालिम/अनुभव सम्बन्धी विवरणः
            </h2>
            <p v-if="formData.application_for" class="text-info-500 text-sm w-full px-4 help-text">
                ( आवश्यक न्यूनतम शैक्षिक याेग्यताः {{ formData.application_for === "enumerator" ? "दश जाेड दुई वा साे सरह" : "स्नातक तह वा साे सरह " }})
            </p>
            <div class="w-full md:w-1/3 px-4 py-2">
                <label-component :value="trans('application.academic_degree')"/>
                <drop-down-input v-model="formData.details.education.qualification"
                                 :class="{'error': validation().has('details.education.qualification')}"
                                 :options="academicDegreeOptions"/>
                <input-error :message="validation().get('details.education.qualification')"/>
            </div>
            <div class="w-full md:w-1/3 px-4 py-2">
                <label-component :value="trans('application.stream')"/>
                <drop-down-input v-model="formData.details.education.stream"
                                 :class="{'error': validation().has('details.education.stream')}"
                                 :options="academicStreamOptions"/>
                <input-error :message="validation().get('details.education.stream')"/>
            </div>
            <div class="md:w-1/3"/>
            <div class="w-full md:w-1/3 px-4 py-2">
                <text-input v-model="formData.details.education.division"
                            label="श्रेणी"
                            name="details[education][division]"/>
            </div>
            <div class="w-full md:w-1/3 px-4 py-2">
                <text-input v-model="formData.details.education.percentage"
                            label="प्रतिशतः"
                            name="details[education][percentage]"/>
            </div>
            <div class="w-full md:w-1/3 px-4 py-2">
                <text-input v-model="formData.details.education.major"
                            label="मूल विषय"
                            name="details[education][major]"/>
            </div>

            <h2 class="w-full font-bold px-4 py-2">
                तथ्या‌ंक संकलन सम्बन्धी तालिम (भए उल्लेख गर्ने):
            </h2>
            <div class="w-full md:w-1/3 px-4 py-2">
                <text-input v-model="formData.details.training.institute"
                            label="तालिम प्रदान गर्ने निकाय"
                            name="details[training][institute]"/>
            </div>
            <div class="w-full md:w-1/3 px-4 py-2">
                <text-input v-model="formData.details.training.period"
                            label="तालिम अवधी (दिनमा)"
                            name="details[training][period]"/>
            </div>
            <h2 class="w-full font-bold px-4 py-2">
                तथ्या‌ंक संकलन सम्बन्धी अनुभव (भए उल्लेख गर्ने):
            </h2>
            <div class="w-full md:w-1/3 px-4 py-2">
                <text-input v-model="formData.details.work_experience.institute"
                            label="काम गरेकाे संस्थाकाे नाम"
                            name="details[work_experience][institute]"/>
            </div>
            <div class="w-full md:w-1/3 px-4 py-2">
                <text-input v-model="formData.details.work_experience.period.day"
                            label="काम गरेकाे अवधी"
                            name="details[work_experience][period][day]"/>
            </div>

            <h2 v-if="isEdit" class="w-full font-bold px-4 py-2">
                (घ) कार्यालय प्रयाेजनका लागि
            </h2>
            <div v-if="isEdit" class="w-full flex justify-between items-center">
                <div class="w-1/2">
                    <div class="w-full px-4 py-2">
                        <text-input v-model="formData.official.checker.full_name"
                                    label="दरखास्त फारामकाे रुजु जाँच गर्नेकाे नामथर"
                                    name="official[checker][full_name]"/>
                    </div>
                    <div class="w-full px-4 py-2">
                        <textarea-input v-model="formData.official.checker.full_name" label="दस्तखत" name="official[checker][full_name]"/>
                    </div>
                    <div class="w-full px-4 py-2">
                        <text-input v-model="formData.official.checker.full_name" label="मिति" name="official[checker][full_name]"/>
                    </div>
                </div>
                <div v-if="isEdit" class="w-1/2">
                    <div class="w-full px-4 py-2">
                        <text-input v-model="formData.official.verifier.full_name"
                                    label="दरखास्त फाराम प्रमाणित गर्नेकाे नामथरः"
                                    name="official[verifier][full_name]"/>
                    </div>
                    <div class="w-full px-4 py-2">
                        <textarea-input v-model="formData.official.verifier.full_name" label="दस्तखत" name="official[verifier][full_name]"/>
                    </div>
                    <div class="w-full px-4 py-2">
                        <text-input v-model="formData.official.verifier.full_name" label="मिति" name="official[verifier][full_name]"/>
                    </div>
                </div>
            </div>
            <h2 v-if="isEdit" class="w-full font-bold px-4 py-2">
                दरखास्त फाराम अस्वीकृत भएमा साेकाे कारणः
            </h2>
            <div v-if="isEdit" class="w-full px-4 py-2">
                <textarea-input v-model="formData.metadata.cause_of_rejection" label="" name="metadata[cause_of_rejection]"/>
            </div>
        </div>
        <div class="flex justify-end w-full form-actions">
            <cancel-button :href="route('admin.users.index')" class="mx-4 my-2">
                Cancel
            </cancel-button>
            <success-button class="mx-4 my-2" @mouseup.prevent="$emit('submit', form)">
                Save
            </success-button>
        </div>
    </form>
</template>

<script>
    import {
        CancelButton,
        SuccessButton,
    } from "../../../../../shared/js/Components/Buttons"
    import {
        DropDownInput,
        ImageInput,
        InputError,
        LabelComponent,
        TextareaInput,
        TextInput,
    } from "../../../../../shared/js/Components/Forms"

    export default {
        name: "ApplicationForm",
        components: {
            TextInput,
            TextareaInput,
            SuccessButton,
            CancelButton,
            ImageInput,
            LabelComponent,
            DropDownInput,
            InputError,
        },
        props: {
            form: {
                type: Object,
                required: true,
            },
            isEdit: { type: Boolean, default: false },
            districts: { type: Array, required: true },
        },
        data() {
            return {
                formData: {},
            }
        },
        watch: {
            form: {
                handler(currentForm) {
                    this.$set(this, "formData", currentForm)
                },
                immediate: true,
                deep: true,
            },
        },
        computed: {
            districtOptions: function() {
                const options = new Map()

                this.districts.forEach(district => {
                    options.set(district.code, district[this.$currentLocale === "en" ? "title_en" : "title_ne"])
                })

                return options
            },
            postOptions() {
                const options = new Map()

                options.set("enumerator", this.trans("application.enumerator"), this.$currentLocale)
                options.set("supervisor", this.trans("application.supervisor"), this.$currentLocale)

                return options
            },
            genderOptions() {
                const options = new Map()

                options.set("male", this.trans("application.male"), this.$currentLocale)
                options.set("female", this.trans("application.female"), this.$currentLocale)
                options.set("other", this.trans("application.other"), this.$currentLocale)

                return options
            },
            academicDegreeOptions() {
                const options = new Map()

                options.set("master", this.trans("application.education_level.master"), this.$currentLocale)
                options.set("bachelor", this.trans("application.education_level.bachelor"), this.$currentLocale)
                options.set("plus_two", this.trans("application.education_level.plus_two"), this.$currentLocale)

                return options
            },
            academicStreamOptions() {
                const options = new Map()

                options.set("science", this.trans("application.academic_stream.science"), this.$currentLocale)
                options.set("management", this.trans("application.academic_stream.management"), this.$currentLocale)
                options.set("humanities", this.trans("application.academic_stream.humanities"), this.$currentLocale)

                return options
            },
        },
        mounted() {
            console.log(this.$formErrors)
        },
    }
</script>

<style scoped>

</style>
