<template>
    <div v-show="message">
        <p class="font-medium text-danger-50 text-sm mt-2">
            {{ message }}
        </p>
    </div>
</template>

<script>
    export default {
        name: "InputError",

        props: {
            message: { type: String, required: false, default: null },
        },
    }
</script>
