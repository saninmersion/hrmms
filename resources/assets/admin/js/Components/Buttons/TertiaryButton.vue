<template>
    <button-component :inertia-link="inertiaLink"
                      :type="type"
                      :method="method"
                      :href="href"
                      class="bg-white hover:bg-white active:bg-gray-50
                          text-base-3 hover:text-base-1 hover:border-base-3
                          border border-gray-300
                          shadow-sm"
                      v-on="{...$listeners}">
        <slot/>
    </button-component>
</template>

<script>
    import ButtonComponent from "./Button"

    export default {
        name: "TertiaryButton",

        components: { ButtonComponent },

        props: {
            inertiaLink: { type: Boolean, required: false, default: true },
            type: { type: String, required: false, default: "button" },
            method: { type: String, required: false, default: "get" },
            href: { type: String, required: false, default: null },
        },
    }
</script>
