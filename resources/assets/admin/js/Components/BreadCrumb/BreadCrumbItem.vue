<template>
    <fragment>
        <li>
            <inertia-link v-if="href" :href="href" v-text="label"/>
            <span v-else v-text="label"/>
        </li>

        <li v-if="href">
            <span class="mx-2">/</span>
        </li>
    </fragment>
</template>

<script type="text/ecmascript-6">
    export default {
        name: "BreadCrumbItem",

        props: {
            label: { type: String, required: true },
            href: { type: String, required: false, default: "" },
        },
    }
</script>
