<template>
    <modal :show="show" :max-width="maxWidth" :closeable="closeable" @close="close">
        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <div class="sm:flex sm:items-start">
                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                    <h3 class="text-lg">
                        <slot name="title"/>
                    </h3>

                    <div class="mt-2">
                        <slot name="content"/>
                    </div>
                </div>
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
        name: "ConfirmationModal",

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
