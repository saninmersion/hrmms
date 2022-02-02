<template>
    <div class="grid md:grid-cols-3 gap-4 radio-btn-group text-base-1">
        <div v-for="(option) in optionsKeys" :key="option" class="flex">
            <input
                :id="name + '_' + option"
                :checked="option === value"
                :name="name"
                :required="required"
                :value="option"
                class=""
                type="radio"
                @change="updateValue(option)">
            <label :for="name+'_' + option">{{ getLabel(option) }}</label>
        </div>
    </div>
</template>

<script>
    export default {
        name: "RadioGroupInput",
        props: {
            name: {
                type: String,
                default: null,
            },
            value: {
                type: [String, Number, Boolean, Object],
                default: null,
            },
            options: {
                type: [Array, Object, Map],
                required: true,
            },
            required: {
                type: Boolean,
                default: false,
            },
        },
        computed: {
            optionsKeys: function() {
                return this.options instanceof Map ? Array.from(this.options.keys()) : Object.keys(this.options)
            },
        },

        methods: {
            getLabel(key) {
                return (this.options instanceof Map ? this.options.get(key) : this.options[key]) || key
            },
            updateValue(value) {
                this.$emit("change", value)
                this.$emit("input", value)
            },
        },
    }
</script>

<style scoped>

</style>
