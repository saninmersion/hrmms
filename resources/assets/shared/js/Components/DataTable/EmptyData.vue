<template>
    <div class="h-56 flex justify-center items-center">
        <div class="flex flex-col gap-2">
            <p v-if="message" class="text-xl text-gray-700" v-text="message"/>
            <slot v-else/>
        </div>
    </div>
</template>

<script type="text/ecmascript-6">
    export default {
        name: "EmptyData",

        props: {
            message: { type: String, required: false, default: null },
        },
    }
</script>
