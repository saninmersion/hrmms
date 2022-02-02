<template>
    <div class="inline-block mb-0">
        <div v-show="image.url" class="w-full h-full">
            <img :src="image.url" alt="" class="w-full h-full object-cover">
        </div>
        <div v-show="!image.url" class="border border-dashed border-black border-2 w-100" style="height: 180px"/>
        <div class="flex">
            <div class="relative">
                <button v-if="isFileInputNew"
                        class="inline-flex items-center justify-center px-4 py-2 bg-gray-600 border border-transparent
                    font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-500
                    focus:outline-none focus:border-gray-700 focus:shadow-outline-gray active:bg-gray-600
                    transition ease-in-out duration-150"
                        type="button">
                    {{ label }}
                </button>
                <button v-if="!isFileInputNew"
                        class="inline-flex items-center justify-center px-4 py-2 bg-success-600 border border-transparent
                    font-semibold text-xs text-white uppercase tracking-widest hover:bg-success-500
                    focus:outline-none focus:border-success-700 focus:shadow-outline-success active:bg-success-600
                    transition ease-in-out duration-150"
                        type="button">
                    Change
                </button>
                <input ref="fileInput"
                       :accept="accept"
                       :name="inputName"
                       class="absolute h-full left-0 m-0 opacity-0 p-0 top-0 w-full"
                       type="file"
                       @input="handleUpload">
            </div>
            <button v-if="removable && !isFileInputNew"
                    class="inline-flex items-center justify-center px-4 py-2 bg-danger-600 border border-transparent
                    font-semibold text-xs text-white uppercase tracking-widest hover:bg-danger-500
                    focus:outline-none focus:border-danger-700 focus:shadow-outline-danger active:bg-danger-600
                    transition ease-in-out duration-150"
                    @click.prevent="handleDelete">
                Remove
            </button>
        </div>
    </div>
</template>

<script>
    export default {
        name: "SimpleImageInput",
        props: {
            inputName: { type: String, required: false, default: "image" },
            label: { type: String, required: false, default: "Upload an image" },
            value: { type: String, required: false, default: null },
            accept: { type: String, default: "image/*", required: false },
            removable: { required: false, type: Boolean, default: true },
        },
        data: () => {
            return {
                image: {
                    url: null,
                },
            }
        },
        watch: {
            value: {
                handler(value) {
                    this.image.url = value

                    if (value instanceof File) {
                        this.image.url = URL.createObjectURL(value)
                    }
                },
                immediate: true,
            },
        },
        computed: {
            isFileInputNew: function() {
                return !this.image.url
            },
        },
        methods: {
            handleUpload(event) {
                const vm = this
                const files = event.currentTarget.files
                const reader = new FileReader()

                reader.readAsDataURL(files[0])
                reader.onload = e => {
                    vm.image = { url: e.target.result, file: files[0] }
                    vm.$emit("change", vm.image.file)
                    vm.$emit("input", vm.image.file)
                }
            },
            handleDelete() {
                this.image = {
                    url: null,
                }
                this.$refs.fileInput.value = null
                this.$emit("change", null)
                this.$emit("input", null)
            },
        },
    }
</script>
