<template>
    <div>
        <label-component v-if="label" :value="label" class="form-label"/>
        <select ref="input"
                v-model="selected"
                :class="{ error: errors.length }"
                class="border form-input rounded-md shadow-sm block mt-1 w-full form-select">
            <option disabled value="">
                Please select one
            </option>
            <option v-for="option in options"
                    :key="'option_' + option"
                    :value="option"
                    v-text="option"/>
        </select>
        <div v-if="errors.length" class="form-error">
            {{ errors[0] }}
        </div>
    </div>
</template>

<script>
    import { LabelComponent } from "./index"

    export default {
        components: {
            LabelComponent,
        },
        inheritAttrs: false,
        props: {
            value: { type: [String, Number, Boolean], default: "" },
            label: { type: String, default: "" },
            errors: {
                type: Array,
                default: () => [],
            },
            options: { type: Array, required: true, default: () => [] },
        },
        data() {
            return {
                selected: this.value,
            }
        },
        watch: {
            selected(selected) {
                this.$emit("input", selected)
            },
            value: {
                handler(currentValue) {
                    this.$set(this, "selected", currentValue)
                },
                immediate: true,
            },
        },
        methods: {
            focus() {
                this.$refs.input.focus()
            },
            select() {
                this.$refs.input.select()
            },
        },
    }
</script>
