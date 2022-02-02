<template>
    <div>
        <div v-if="title" class="py-3">
            <h3 class="heading-primary heading-primary--sm !text-base-1 !pb-0" style="font-size: 18px;" v-text="title"/>
        </div>
        <slot/>
    </div>
</template>

<script>
    export default {
        name: "ChartWrapper",

        props: { title: { type: String, required: false, default: null } },
    }
</script>

<style scoped>

</style>
