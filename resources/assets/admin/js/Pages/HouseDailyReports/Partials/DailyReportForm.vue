<template>
    <div>
        <form id="daily-report-form" class="form" @submit.prevent="saveForm">
            <div class="flex justify-between items-baseline">
                <div class="w-full flex justify-between align-center flex-wrap">
                    <div class="w-full px-4 py-2">
                        <label-component :value="trans(`daily_reports.labels.Report Date`)" class="form-label"/>
                        <nepali-date-picker v-model="form.report_date" type="ad" @change="handleOnChange($event, 'report_date')"/>
                        <input-error :message="form.error('report_date')" :v-if="form.error('report_date')"/>
                    </div>
                    <div class="w-1/3 px-4 py-2">
                        <label-component :value="trans(`daily_reports.labels.Number of houses`)" class="form-label"/>
                        <input-component
                            v-model="form.total_surveyed"
                            type="number"
                            class="border form-input rounded-md shadow-sm block mt-1 w-full form-input"/>
                        <input-error :message="form.error('total_surveyed')" :v-if="form.error('total_surveyed')"/>
                    </div>

                    <div class="w-1/3 px-4 py-2">
                        <label-component :value="trans(`daily_reports.labels.Number of houses in census`)" class="form-label"/>
                        <input-component
                            v-model="form.number_of_houses_in_census"
                            type="number"
                            class="border form-input rounded-md shadow-sm block mt-1 w-full form-input"/>
                        <input-error :message="form.error('number_of_houses_in_census')" :v-if="form.error('number_of_houses_in_census')"/>
                    </div>

                    <div class="w-1/3 px-4 py-2">
                        <label-component :value="trans(`daily_reports.labels.Number of families in census`)" class="form-label"/>
                        <input-component
                            v-model="form.number_of_families_in_census"
                            type="number"
                            class="border form-input rounded-md shadow-sm block mt-1 w-full form-input"/>
                        <input-error :message="form.error('number_of_families_in_census')" :v-if="form.error('number_of_families_in_census')"/>
                    </div>
                </div>
            </div>
            <div class="flex items-center bg-white bottom-0 py-4 md:py-6 gap-4 md:gap-6  sticky w-full">
                <cancel-button @click="cancelForm">
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
    </div>
</template>

<script>
    import ActionMessage    from "../../../Components/ActionMessage"
    import CancelButton     from "../../../Components/Buttons/DangerButton"
    import PrimaryButton    from "../../../Components/Buttons/PrimaryButton"
    import InputComponent   from "../../../Components/Forms/Input"
    import InputError       from "../../../Components/Forms/InputError"
    import LabelComponent   from "../../../Components/Forms/Label"
    import NepaliDatePicker from "../../../Components/Forms/NepaliDatePicker"

    export default {
        name: "HouseDailyReportForm",

        components: {
            ActionMessage,
            PrimaryButton,
            CancelButton,
            InputComponent,
            InputError,
            LabelComponent,
            NepaliDatePicker,
        },

        props: {
            formData: { type: Object, required: true },
            isOperator: { type: Boolean, required: false, default: false },
        },

        emits: ["cancel", "save"],

        data() {
            return {
                form: this.formData,
            }
        },

        methods: {
            handleOnChange(value, name) {
                this.$set(this.form, name, value)
            },

            cancelForm() {
                this.$emit("cancel")
            },

            saveForm() {
                this.$emit("save", this.form)
            },
        },
    }
</script>

<style scoped>

</style>
