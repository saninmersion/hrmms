<template>
    <div class="help-text text-black-100 mb-6 sm:pr-12 md:pr-24">
        <div class="w-1/10">
            {{ text }}
        </div>
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
