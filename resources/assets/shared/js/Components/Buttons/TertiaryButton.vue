<template>
    <button-component :inertia-link="inertiaLink"
                      :type="type"
                      :method="method"
                      :href="href"
                      class="border-primary-500 !text-primary-500 !font-normal py-2 px-3 text-base
                          bg-white hover:bg-white active:bg-gray-50
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
