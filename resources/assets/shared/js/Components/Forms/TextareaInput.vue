<template>
    <div>
        <label-component v-if="label" :value="label" class="form-label"/>
        <p v-if="hint" class="text-sm text-info-600" v-text="hint"/>
        <textarea ref="input"
                  :class="{'border-danger-500': validation().has(arrayToDotNotation(name))}"
                  :name="name"
                  :value="value"
                  class="border form-input rounded-md shadow-sm block mt-1 w-full form-textarea"
                  v-bind="$attrs"
                  @input="$emit('input', $event.target.value)"/>
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
            value: { type: String, default: "" },
            label: { type: String, default: "" },
            name: { type: String, required: true },
            hint: { type: String, default: null },
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
