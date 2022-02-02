<template>
    <div class="cursor-pointer w-14 h-8 flex items-center bg-grey-2 rounded-full p-1 duration-300 ease-in-out"
         @click.prevent="onClick">
        <div class="bg-white w-6 h-6 rounded-full shadow-md transform duration-300 ease-in-out flex justify-center items-center"
             :class="{ 'translate-x-6 bg-success-500': toggleActive,}">
            <icon v-if="toggleActive" name="success" style="margin-right: 0"/>
            <icon v-if="!toggleActive" name="close" style="margin-right: 0"/>
        </div>
    </div>
</template>

<script>
    import Icon from "../General/Icon"
    export default {
        name: "ToggleInput",
        components: { Icon },
        props: {
            value: { type: Boolean, default: false },
            disabled: { type: Boolean, default: false },
        },

        data() {
            return {
                toggleActive: false,
            };
        },

        watch: {
            value: {
                handler(v) {
                    this.$set(this, "toggleActive", v)
                },
                immediate: true,
            },
        },

        methods: {
            onClick() {
                if (this.disabled) {
                    return
                }
                this.toggleActive = !this.toggleActive

                this.$emit("input", this.toggleActive)
            },
        },

    }
</script>

<style scoped>

</style>
