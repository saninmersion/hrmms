<template>
    <label class="block text-base-2 mb-2">
        <span v-if="value">{{ value }}</span>
        <span v-else><slot/></span>
    </label>
</template>

<script>
    export default {
        name: "LabelComponent",

        props: {
            value: { type: String, required: false, default: null },
        },
    }
</script>
