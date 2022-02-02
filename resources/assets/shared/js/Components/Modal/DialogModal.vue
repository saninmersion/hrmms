<template>
    <modal :show="show" :max-width="maxWidth" :closeable="closeable" @close="close">
        <div class="px-6 py-4">
            <div class="text-lg">
                <slot name="title"/>
            </div>

            <div class="mt-4">
                <slot name="content"/>
            </div>
        </div>

        <div class="px-6 py-4 bg-gray-100 text-right">
            <slot name="footer"/>
        </div>
    </modal>
</template>

<script>
    import Modal from "./Modal"

    export default {
        name: "DialogModal",

        components: {
            Modal,
        },

        props: {
            show: { type: Boolean, required: false, default: false },
            maxWidth: { type: String, required: false, default: "2xl" },
            closeable: { type: Boolean, required: false, default: true },
        },

        methods: {
            close() {
                this.$emit("close")
            },
        },
    }
</script>
