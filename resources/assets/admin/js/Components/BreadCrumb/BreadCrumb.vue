<template>
    <ol class="flex list-reset text-gray-300 breadcrumb flex-wrap">
        <bread-crumb-item :href="route('admin.dashboard')" :label="trans('admin-general.modules.dashboard')"/>
        <slot/>
    </ol>
</template>

<script type="text/ecmascript-6">
    import BreadCrumbItem from "../../../../shared/js/Components/BreadCrumb/BreadCrumbItem"

    export default {
        name: "BreadCrumb",

        components: { BreadCrumbItem },
    }
</script>
