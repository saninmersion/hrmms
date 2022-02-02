<template>
    <tertiary-button :inertia-link="inertiaLink"
                     :type="type"
                     :method="method"
                     class="bg-danger-500"
                     :href="href">
        <slot/>
    </tertiary-button>
</template>

<script type="text/ecmascript-6">
    import TertiaryButton from "./PrimaryButton"

    export default {
        name: "CancelButton",

        components: { TertiaryButton },

        props: {
            inertiaLink: { type: Boolean, required: false, default: true },
            type: { type: String, required: false, default: "button" },
            method: { type: String, required: false, default: "get" },
            href: { type: String, required: false, default: null },
        },
    }
</script>
