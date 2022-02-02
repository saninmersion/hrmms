<template>
    <div>
        <form id="daily-report-form" class="form" @submit.prevent="saveForm">
            <div class="flex justify-between items-baseline">
                <div class="w-full flex justify-between align-center flex-wrap">
                    <div class="w-full px-4 py-2">
                        <label-component :value="trans(`daily_reports.labels.Enumerator`)" class="form-label"/>
                        <drop-down-input
                            :class="{'form-input-error' :form.error('enumerator_id')} "
                            :hide-selected="false"
                            :options="enumeratorOptions"
                            :placeholder="trans(`daily_reports.labels.Enumerator`)"
                            :searchable="true"
                            :show-placeholder="true"
                            :value="form.enumerator_id"
                            @input="handleOnChange($event, 'enumerator_id')"/>
                        <input-error :message="form.error('enumerator_id')" :v-if="form.error('enumerator_id')"/>
                    </div>
                    <div class="w-full px-4 py-2">
                        <label-component :value="trans(`daily_reports.labels.Report Date`)" class="form-label"/>
                        <nepali-date-picker v-model="form.report_date" type="ad" @change="handleOnChange($event, 'report_date')"/>
                        <input-error :message="form.error('report_date')" :v-if="form.error('report_date')"/>
                    </div>
                    <div class="w-1/2 px-4 py-2">
                        <label-component :value="trans(`daily_reports.labels.Total Surveyed`)" class="form-label"/>
                        <input-component
                            v-model="form.total_surveyed"
                            type="number"
                            class="border form-input rounded-md shadow-sm block mt-1 w-full form-input"/>
                        <input-error :message="form.error('total_surveyed')" :v-if="form.error('total_surveyed')"/>
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
    import DropDownInput    from "../../../Components/Forms/DropDownInput"
    import InputComponent   from "../../../Components/Forms/Input"
    import InputError       from "../../../Components/Forms/InputError"
    import LabelComponent   from "../../../Components/Forms/Label"
    import NepaliDatePicker from "../../../Components/Forms/NepaliDatePicker"

    export default {
        name: "DailyReportForm",

        components: { ActionMessage, PrimaryButton, CancelButton, InputComponent, InputError, LabelComponent, NepaliDatePicker, DropDownInput },

        props: {
            formData: { type: Object, required: true },
            isOperator: { type: Boolean, required: false, default: false },
            enumeratorOptions: { type: Map, required: true },
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
