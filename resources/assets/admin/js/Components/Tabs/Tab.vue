<template lang="html">
    <div v-show="isActive">
        <slot/>
    </div>
</template>

<script>
    export default {
        props: {
            title: { type: String, default: "Tab" },
            name: { type: String, required: true },
        },
        data() {
            return {
                isActive: true,
            }
        },
    }
</script>
