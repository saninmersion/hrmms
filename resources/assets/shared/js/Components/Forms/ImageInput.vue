<template>
    <div>
        <label-component v-if="label" :value="label" class="form-label"/>
        <simple-image-input
            v-model="image"
            :accept="accept"
            :input-name="name"
            label="Upload Image"
            @change="(image)=> change(image)"/>
        <div v-if="errors.length" class="form-error">
            {{ errors[0] }}
        </div>
    </div>
</template>

<script>
    import { LabelComponent } from "./index"
    import SimpleImageInput   from "./Partials/SimpleImageInput"

    export default {
        name: "ImageInput",
        components: {
            SimpleImageInput,
            LabelComponent,
        },
        props: {
            value: {
                type: [File, String],
                default: null,
            },
            label: { type: String, default: "" },
            name: { type: String, default: "" },
            accept: { type: String, default: "image/*" },
            errors: {
                type: Array,
                default: () => [],
            },
        },
        data() {
            return {
                image: this.value,
            }
        },
        watch: {
            image(value) {
                this.$emit("input", value)
            }
            ,
        },
        methods: {
            filesize(size) {
                const i = Math.floor(Math.log(size) / Math.log(1024))
                return (size / Math.pow(1024, i)).toFixed(2) * 1 + " " + ["B", "kB", "MB", "GB", "TB"][i]
            },
            change(image) {
                this.$emit("input", image)
            },
        },
    }
</script>

<style scoped>

</style>
