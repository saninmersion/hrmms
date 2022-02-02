<template>
    <div>
        <div class="flex justify-between items-center flex-wrap gap-4">
            <h3 class="heading-primary">
                {{ trans("application.Online Application") }}
            </h3>

            <tertiary-button class="!px-3 !text-sm !m-0"
                             @click.prevent="$emit('edit')">
                {{ trans("application.Edit Application") }}
            </tertiary-button>
        </div>

        <div class="form-group mt-8">
            <div class="flex pb-1">
                <label-component class="leading-none " :value="trans('application.date_of_birth')"/>
                <div class="custom-tooltip">
                    <svg class="ml-1 mt-1">
                        <use :xlink:href="`${iconLink}ic-info`"/>
                    </svg>
                    <span class="tooltip" v-text="trans('application.form-help-text.dob')"/>
                </div>
            </div>
            <nepali-date-picker v-model="dob" :range="dobRange" :show-labels="false" class="grid grid-gap-3 grid-cols-3"/>
            <input-error :message="validation().get('dob')"/>
        </div>

        <div class="form-group">
            <label-component :value="trans('application.Nepali Citizenship No')"/>
            <input-component
                v-model="citizenship_number"
                :placeholder="trans('application.Nepali Citizenship No')"/>
            <input-error :message="validation().get('citizenship_number')"/>
        </div>

        <div class="form-group">
            <label-component :value="trans('application.citizenship_district')"/>
            <drop-down-input v-model="citizenship_district"
                             :options="districtOptions"
                             :searchable="true"
                             :show-placeholder="true"
                             :placeholder="trans('application.citizenship_district')"/>
            <input-error :message="validation().get('citizenship_district')"/>
        </div>

        <div class="form-group">
            <label-component :value="trans('application.mobile_number')"/>
            <input-component v-model="mobile_number"
                             type="number"
                             :placeholder="trans('application.mobile_number')"/>
            <input-error :message="validation().get('mobile_number')"/>
        </div>

        <div class="form-group">
            <label-component :value="trans('application.gender')"/>
            <gender-input v-model="gender" class="flex flex-wrap gap-4 sm:gap-6" name="gender"/>
            <input-error class="!mt-0" :message="validation().get('gender')"/>
        </div>

        <div class="form-group">
            <primary-button class="py-3 w-full justify-center mt-2" :class="{'pointer-events-none' : isSaving}" @click.prevent="handleOnSubmit">
                {{ trans("application.start-application") }}
            </primary-button>

            <link-button class="w-full justify-center !text-sm whitespace-pre-line" @click.prevent="$emit('check')">
                {{ trans("application.check_application") }}
            </link-button>
        </div>
    </div>
</template>

<script type="text/ecmascript-6">
    import {
        LinkButton,
        PrimaryButton,
        TertiaryButton,
    } from "../../../../shared/js/Components/Buttons"
    import {
        DropDownInput,
        GenderInput,
        InputComponent,
        InputError,
        LabelComponent,
        NepaliDatePicker,
    } from "../../../../shared/js/Components/Forms"

    export default {
        name: "LoginNew",

        components: {
            DropDownInput,
            PrimaryButton,
            TertiaryButton,
            LinkButton,
            InputError,
            LabelComponent,
            GenderInput,
            InputComponent,
            NepaliDatePicker,
        },

        props: {
            value: { type: Object, required: true },
            isSaving: { type: Boolean, required: true },
            iconLink: { type: String, required: true },
            districtOptions: { type: Map, required: true },
        },

        data: () => ({
            dob: null,
            citizenship_number: null,
            citizenship_district: null,
            mobile_number: null,
            gender: null,
            dobRange: { ad: [1975, 2004], bs: [2032, 2060] },
        }),

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
            },
        },

        methods: {
            handleOnSubmit() {
                this.$emit("submit", {
                    dob: this.dob,
                    citizenship_number: this.citizenship_number,
                    citizenship_district: this.citizenship_district,
                    mobile_number: this.mobile_number,
                    gender: this.gender,
                })
            },
        },
    }
</script>
