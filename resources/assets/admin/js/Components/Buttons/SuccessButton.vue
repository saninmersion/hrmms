<template>
    <button :type="type"
            class="inline-flex items-center justify-center px-4 py-2 bg-success-600 border border-transparent
                    rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-success-500
                    focus:outline-none focus:border-success-700 focus:shadow-outline-success active:bg-success-600
                    transition ease-in-out duration-150"
            v-on="$listeners">
        <slot/>
    </button>
</template>

<script>
    export default {
        name: "SuccessButton",

        props: {
            type: { type: String, default: "button" },
        },
    }
</script>
