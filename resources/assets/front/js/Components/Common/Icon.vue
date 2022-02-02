<template>
    <svg class="mr-2 w-4 h-4">
        <use :xlink:href="'/images/icons.svg#ic-' + name"/>
    </svg>
</template>

<script type="text/ecmascript-6">
    export default {
        name: "Icon",
        props: {
            name: { type: String, required: true },
        },
    }
</script>
