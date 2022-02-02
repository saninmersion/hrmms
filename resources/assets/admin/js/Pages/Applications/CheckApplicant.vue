<template>
    <admin-layout page-title="Create New Application" :authorized="isAuthorized($page, 'offline_application')">
        <template #breadcrumb/>
        <div class="form md:w-1/2 shadow p-8 rounded-md mx-auto my-4">
            <div class="flex justify-between items-center flex-wrap gap-4">
                <h3 class="heading-primary">
                    {{ trans("application.Online Application") }}
                </h3>
            </div>
            <div class="form-group mb-4">
                <div class="flex pb-1 items-center relative">
                    <label-component class="leading-none " :value="trans('application.date_of_birth')"/>
                </div>
                <nepali-date-picker id="dob"
                                    v-model="form.dob"
                                    name="dob"
                                    :range="dobRange"
                                    :show-labels="false"
                                    class="grid grid-gap-3 grid-cols-3"/>
                <input-error :message="form.error('dob')"/>
            </div>

            <div class="form-group mb-4">
                <label-component :value="trans('application.Nepali Citizenship No')"/>
                <input-component
                    id="citizenship_number"
                    v-model="form.citizenship_number"
                    class=" form-control block border rounded h-10 w-full px-3"
                    name="citizenship_number"
                    :placeholder="trans('application.Nepali Citizenship No')"/>
                <input-error :message="form.error('citizenship_number')"/>
            </div>

            <div class="form-group mb-4">
                <label-component :value="trans('application.citizenship_district')"/>
                <drop-down-input id="citizenship_district"
                                 v-model="form.citizenship_district"
                                 name="citizenship_district"
                                 :options="districtOptions"
                                 :searchable="true"
                                 :show-placeholder="true"
                                 :placeholder="trans('application.citizenship_district')"/>
                <input-error :message="form.error('citizenship_district')"/>
            </div>

            <div class="form-group mb-4">
                <label-component :value="trans('application.mobile_number')"/>
                <input-component
                    id="mobile_number"
                    v-model="form.mobile_number"
                    class=" form-control block border rounded h-10 w-full px-3"
                    name="mobile_number"
                    type="number"
                    :placeholder="trans('application.mobile_number')"/>
                <input-error :message="form.error('mobile_number')"/>
            </div>

            <div class="form-group mb-4">
                <label-component :value="trans('application.gender')"/>
                <gender-input id="gender" v-model="form.gender" name="gender"/>
                <input-error class="!mt-0" :message="form.error('gender')"/>
            </div>

            <div class="form-group mb-4">
                <primary-button class="py-3 w-full justify-center mt-2" @click.prevent="handleOnSubmit">
                    Check Applicant
                </primary-button>
            </div>
        </div>
    </admin-layout>
</template>

<script type="text/ecmascript-6">
    import { PrimaryButton } from "../../Components/Buttons"
    import {
        DropDownInput,
        GenderInput,
        InputComponent,
        InputError,
        LabelComponent,
        NepaliDatePicker,
    }                        from "../../Components/Forms"
    import AdminLayout       from "../../Layouts/AdminLayout"

    export default {
        name: "CheckApplicant",

        components: {
            AdminLayout,
            DropDownInput,
            PrimaryButton,
            InputError,
            LabelComponent,
            GenderInput,
            InputComponent,
            NepaliDatePicker,
        },

        props: {
            value: { type: Object, required: false, default: () => ({}) },
            iconLink: { type: String, required: false, default: "" },
            districts: { type: Array, required: true },
        },

        data() {
            return {
                form: this.$inertia.form(
                    {
                        dob: null,
                        citizenship_number: null,
                        citizenship_district: null,
                        mobile_number: null,
                        gender: null,
                    },
                    {
                        bag: "checkApplicantForm",
                        resetOnSuccess: true,
                    },
                ),
                dobRange: { ad: [1975, 2004], bs: [2032, 2060] },
            }
        },

        watch: {
            value: {
                handler(val) {
                    this.dob = val.dob
                    this.citizenship_number = val.citizenship_number
                    this.citizenship_district = val.citizenship_district
                    this.mobile_number = val.mobile_number
                    this.gender = val.gender
                },
                immediate: true,
            }
            ,
        },

        computed: {
            districtOptions: function() {
                const options = new Map()
                const key = this.$currentLocale === "en" ? "title_en" : "title_ne"

                this.districts.forEach(district => {
                    options.set(district.code, district[key])
                })

                return options
            }
            ,
        },

        methods: {
            handleOnSubmit() {
                this.form.post(this.route("admin.applications.offline-form.check"))
            }
            ,
        }
        ,
    }
</script>
