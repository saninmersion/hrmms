<template>
    <div>
        <label-component v-if="label" :value="label" class="form-label  !mb-0.5 text-base-3 text-sm"/>
        <input ref="input"
               :class="{ 'border-danger-500': validation().has(arrayToDotNotation(name)) }"
               :name="name"
               :type="type"
               :placeholder="placeholder"
               :value="value"
               class="form-control"
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
            type: { type: String, required: false, default: "text" },
            value: { type: [String, Number], required: false, default: "" },
            label: { type: String, required: false, default: "" },
            name: { type: String, required: true },
            placeholder: { type: String, required: false, default: "" },
            errors: { type: Array, required: false, default: () => [] },
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
