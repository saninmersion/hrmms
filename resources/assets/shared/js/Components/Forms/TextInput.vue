<template>
    <div>
        <label-component v-if="label" :value="label" class="form-label"/>
        <input ref="input"
               :class="{ 'border-danger-500': validation().has(arrayToDotNotation(name)) }"
               :name="name"
               :type="type"
               :value="value"
               class="border form-input rounded-md shadow-sm block mt-1 w-full form-input"
               v-bind="$attrs"
               @input="$emit('input', $event.target.value)">
        <input-error v-if="validation().has(arrayToDotNotation(name))" :message="validation().get(arrayToDotNotation(name))"/>
    </div>
</template>

<script>
    import {
        InputError,
        LabelComponent,
    } from "./index"

    export default {
        components: {
            LabelComponent,
            InputError,
        },
        inheritAttrs: false,
        props: {
            type: {
                type: String,
                default: "text",
            },
            value: { type: [String, Number], default: "" },
            label: { type: String, default: "" },
            name: { type: String, required: true },
            errors: {
                type: Array,
                default: () => [],
            },
        },
        methods: {
            focus() {
                this.$refs.input.focus()
            },
            select() {
                this.$refs.input.select()
            },
            arrayToDotNotation(str) {
                return str.replace(/\[(.*?)\]/g, (m, s) => `.${s}`)
            },
        },
    }
</script>
