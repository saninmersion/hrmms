<template>
    <button v-if="linkComponent === 'button'"
            :class="classes"
            :type="type"
            v-on="{...$listeners}">
        <slot/>
    </button>

    <component :is="linkComponent"
               v-else
               :as="as"
               :class="classes"
               :data="dataProps"
               :href="href"
               :method="method"
               v-on="{...$listeners}">
        <slot/>
    </component>
</template>

<script>
    export default {
        name: "ButtonComponent",

        props: {
            inertiaLink: { type: Boolean, required: false, default: true },
            type: { type: String, required: false, default: "button" },
            as: { type: String, required: false, default: "a" },
            method: { type: String, required: false, default: "get" },
            href: { type: String, required: false, default: null },
            dataProps: { type: [Object], required: false, default: () => ({}) },
        },

        computed: {
            classes: function() {
                return `active:bg-primary-500 bg-primary-500
                        border border-transparent rounded-md
                        transition duration-150 ease-in-out
                        focus:outline-none focus:shadow-outline-gray
                        hover:opacity-90
                        inline-flex items-center px-4 py-2
                        whitespace-nowrap
                        text-base text-secondary text-white uppercase`
            },

            linkComponent: function() {
                return this.href ? (this.inertiaLink ? "inertia-link" : "a") : "button"
            },
        },
    }
</script>
