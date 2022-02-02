<template>
    <div>
        <form id="enumerator-form" class="form" @submit.prevent="saveForm">
            <div class="flex justify-between items-baseline">
                <div class="w-full flex justify-between align-center flex-wrap">
                    <div class="w-full px-4 py-2">
                        <label-component :value="`Name`" class="form-label"/>
                        <label-component v-if="isEdit" :value="form.name"/>
                        <input-component
                            v-else
                            v-model="form.name"
                            type="text"
                            class="border form-input rounded-md shadow-sm block mt-1 w-full form-input"/>
                        <input-error :message="form.error('name')" :v-if="form.error('name')"/>
                    </div>
                    <div class="w-full px-4 py-2">
                        <label-component :value="`Target`" class="form-label"/>
                        <input-component
                            v-model="form.target"
                            type="number"
                            class="border form-input rounded-md shadow-sm block mt-1 w-full form-input"/>
                        <input-error :message="form.error('target')" :v-if="form.error('target')"/>
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
    import ActionMessage from "../../../Components/ActionMessage"
    import CancelButton from "../../../Components/Buttons/DangerButton"
    import PrimaryButton from "../../../Components/Buttons/PrimaryButton"
    import InputComponent from "../../../Components/Forms/Input"
    import InputError from "../../../Components/Forms/InputError"
    import LabelComponent from "../../../Components/Forms/Label"

    export default {
        name: "EnumeratorForm",
        components: {
            ActionMessage, CancelButton, PrimaryButton, InputComponent, InputError, LabelComponent,
        },
        props: {
            formData: { type: Object, required: true },
            isOperator: { type: Boolean, required: false, default: false },
            isEdit: { type: Boolean, required: false, default: false },
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
                this.$emit("cancel");
            },
            saveForm() {
                this.$emit("save", this.form);
            },
        },
    }
</script>

<style scoped>

</style>
