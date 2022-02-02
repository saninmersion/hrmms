<template>
    <div ref="inputWrapper" @click.stop="handleClick">
        <slot v-if="!isEditing(name)" name="show" :value="value">
            <p class="px-2 py-1 rounded" v-text="value"/>
        </slot>

        <slot v-if="isEditing(name)" :handler="handleInput" :value="value" name="input">
            <input ref="input"
                   :value="value"
                   class="bg-primary-100 px-2 py-1 rounded"
                   type="text"
                   @input="handleInput">
        </slot>
    </div>
</template>

<script>
    export default {
        name: "ContentEditableInput",

        props: {
            value: { type: [String, Number], default: "" },
            name: { type: String, default: "" },
            isEditing: { type: Function, default: () => {} },
        },
        mounted() {
            this.$refs.inputWrapper.addEventListener("focusout", () => {
                this.$emit("editMode", "")
            })
        },
        methods: {
            handleClick() {
                this.$emit("editMode", this.name)
            },
            handleInput(e) {
                const value = e.currentTarget ? e.currentTarget.value : e
                this.$emit("modified", { name: this.name, value: value })
            },
        },
    }
</script>

<style scoped>

</style>
