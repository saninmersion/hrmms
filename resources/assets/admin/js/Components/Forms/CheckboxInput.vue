<template>
    <label>
        <input :name="name" :checked="value" type="checkbox" @change="updateInput">
        <span class="ml-2">{{ label }}</span>
    </label>
</template>

<script>
    export default {

        inheritAttrs: false,

        props: {
            type: { type: String, required: false, default: "text" },
            label: { type: String, required: true },
            name: { type: String, required: true },
            value: { type: Boolean, default: false },
        },

        methods: {
            updateInput(e) {
                const isChecked = e.target.checked
                this.$emit("input", isChecked)
            },
        },
    }
</script>
