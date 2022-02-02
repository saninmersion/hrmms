<template>
    <button-component :inertia-link="inertiaLink"
                      :type="type"
                      :method="method"
                      :href="href"
                      v-on="{...$listeners}">
        <slot/>
    </button-component>
</template>

<script>
    import ButtonComponent from "./Button"

    export default {
        name: "PrimaryButton",

        components: { ButtonComponent },

        props: {
            inertiaLink: { type: Boolean, required: false, default: true },
            type: { type: String, required: false, default: "button" },
            method: { type: String, required: false, default: "get" },
            href: { type: String, required: false, default: null },
        },
    }
</script>

<style>

</style>
