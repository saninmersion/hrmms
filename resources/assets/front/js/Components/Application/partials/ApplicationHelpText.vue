<template>
    <div class="help-text text-black-100 mb-6 sm:pr-12 md:pr-24">
        <!-- eslint-disable-next-line vue/no-v-html -->
        <div class="w-1/10" v-html="text"/>
    </div>
</template>

<script>
    export default {
        name: "ApplicationHelpText",
        props: {
            text: { type: String, required: false, default: "" },
        },
    }
</script>
