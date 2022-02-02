<template>
    <fragment>
        <div>
            <input-component :class="{'form-input-error' : hasError(`${name}.first_name`)}"
                             :placeholder="firstNamePlaceholder"
                             :value="first_name"
                             type="text"
                             @input="handleOnChange('first_name', $event)"/>
            <input-error v-if="form.error(`${name}.first_name`)" :message="form.error(`${name}.first_name`)"/>
        </div>

        <div>
            <input-component :class="{'form-input-error' : hasError(`${name}.middle_name`)}"
                             :placeholder="middleNamePlaceholder"
                             :value="middle_name"
                             type="text"
                             @input="handleOnChange('middle_name', $event)"/>
            <input-error v-if="form.error(`${name}.middle_name`)" :message="form.error(`${name}.middle_name`)"/>
        </div>
        <div>
            <input-component
                :class="{'form-input-error' : hasError(`${name}.last_name`)}"
                :placeholder="lastNamePlaceholder"
                :value="last_name"
                type="text"
                @input="handleOnChange('last_name', $event)"/>
            <input-error v-if="form.error(`${name}.last_name`)" :message="form.error(`${name}.last_name`)"/>
        </div>
    </fragment>
</template>

<script type="text/ecmascript-6">
    import { InputError } from "./index"
    import InputComponent from "./Input"

    export default {
        name: "FullNameInput",

        components: { InputComponent, InputError },

        props: {
            value: { type: Object, required: false, default: () => ({}) },
            form: { type: Object, required: false, default: () => ({}) },
            name: { type: String, required: true },
            locale: { type: String, required: false, default: () => "ne" },
        },

        data: () => ({
            first_name: null,
            middle_name: null,
            last_name: null,
        }),

        watch: {
            value: {
                handler(value) {
                    this.first_name = value.first_name
                    this.middle_name = value.middle_name
                    this.last_name = value.last_name
                },
                immediate: true,
                deep: true,
            },
        },

        computed: {
            firstNamePlaceholder: function() {
                return this.locale === "ne" ? "पहिलो नाम" : "First Name"
            },

            middleNamePlaceholder: function() {
                return this.locale === "ne" ? "बीचको नाम" : "Middle Name"
            },

            lastNamePlaceholder: function() {
                return this.locale === "ne" ? "थर" : "Last Name"
            },
        },

        methods: {
            handleOnChange(name, value) {
                this.$set(this, name, value)

                this.$emit("input", {
                    first_name: this.first_name,
                    middle_name: this.middle_name,
                    last_name: this.last_name,
                })
            },

            hasError(name) {
                return !!this.form.error(name)
            },
        },
    }
</script>
