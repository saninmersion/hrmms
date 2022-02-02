<template>
    <div class="flex">
        <input :id="targetId"
               :checked="checked"
               type="radio"
               :name="name"
               @input="handleOnChange">
        <label class="cursor-pointer" :for="targetId">
            {{ label }}
        </label>
    </div>
</template>

<script type="text/ecmascript-6">
    export default {
        name: "RadioInput",

        props: {
            label: { type: String, required: false, default: "" },
            checked: { type: Boolean, required: false, default: false },
            name: { type: String, required: true },
            targetId: { type: String, required: true },
        },

        methods: {
            handleOnChange(event) {
                if (event.currentTarget.checked) {
                    this.$emit("checked")
                }
            },
        },
    }
</script>
