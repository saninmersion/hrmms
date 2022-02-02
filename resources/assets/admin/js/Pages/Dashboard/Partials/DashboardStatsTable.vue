<template>
    <div class="relative flex flex-col break-words bg-white w-full shadow-lg rounded">
        <div class="rounded-t mb-0 py-3 px-4 border-0">
            <div class="flex flex-wrap items-center">
                <div class="relative w-full max-w-full flex-grow flex-1">
                    <h3 class="font-semibold text-base text-gray-800" v-text="title"/>
                </div>
            </div>
        </div>
        <div class="block w-full overflow-x-auto">
            <table class="data-table w-full">
                <thead>
                    <slot name="table-head"/>
                </thead>

                <tbody>
                    <slot name="table-body"/>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script type="text/ecmascript-6">
    export default {
        name: "DashboardStatsTable",

        props: {
            title: { type: String, required: true },
        },
    }
</script>
