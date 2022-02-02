<template>
    <div
        v-if="show"
        class="transition duration-500 ease-in-out fixed inset-0 w-full h-screen flex items-center justify-center bg-semi-75 z-50 modal "
        @click.self="close">
        <div class="relative w-full bg-white shadow-lg rounded-lg px-8 py-12" style="max-width: 500px;">
            <button
                aria-label="close"
                class="ic-close ic-close--modal"
                @click.prevent="close"/>
            <slot/>
        </div>
    </div>
</template>

<script>
    export default {
        name: "CardModal",

        props: {
            show: {
                required: true,
                type: Boolean,
            },
        },

        watch: {
            show(value) {
                if (value) {
                    return document.querySelector("body").classList.add("overflow-hidden")
                }

                document.querySelector("body").classList.remove("overflow-hidden")
            },
        },

        methods: {
            close() {
                this.$emit("close")
            },
        },
    }
</script>

<style scoped>
    .modal {
        background: rgba(0, 192, 255, 0.2);
    }
</style>
