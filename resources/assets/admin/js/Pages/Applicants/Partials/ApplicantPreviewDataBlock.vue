<template>
    <div class="data-block-item flex flex-wrap py-2 gap-8">
        <div class="text-base-1 data-block-label">
            <slot name="label">
                {{ label }}
            </slot>
        </div>
        <div class="text-base-2">
            <slot name="value">
                {{ value ? value : '-' }}
            </slot>
        </div>
    </div>
</template>

<script>
    export default {
        name: "ApplicantPreviewDataBlock",
        props: {
            label: { type: String, required: false, default: "" },
            value: { type: String, required: false, default: "" },
        },
    }
</script>
