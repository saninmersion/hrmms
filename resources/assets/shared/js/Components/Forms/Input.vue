<template>
    <!--suppress HtmlFormInputWithoutLabel -->
    <input ref="input"
           class="form-control"
           :value="value"
           :type="type"
           v-on="{
               ...$listeners,
               input: event => $emit('input', event.target.value)
           }">
</template>

<script>
    export default {
        name: "InputComponent",

        props: {
            value: { type: [Number, String], required: false, default: null },
            type: { type: String, required: false, default: "text" },
        },

        methods: {
            focus() {
                this.$refs.input.focus()
            },
        },
    }
</script>
