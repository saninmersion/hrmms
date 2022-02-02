<template>
    <multi-select v-model="selectedValue"
                  :allow-empty="allowEmpty"
                  :custom-label="getLabel"
                  :deselect-label="deselectLabel"
                  :hide-selected="hideSelected"
                  :multiple="multiple"
                  :options="optionsKeys"
                  :placeholder="showPlaceholder ? placeholder : ''"
                  :searchable="searchable"
                  :select-label="selectLabel"
                  :selected-label="selectedLabel"
                  :show-labels="showLabels"
                  :show-no-results="true"
                  @close="$emit('close')"
                  @input="handleOnInput"
                  @select="handleOnSelect"/>
</template>

<script type="text/ecmascript-6">
    import MultiSelect from "vue-multiselect"

    export default {
        name: "DropDownInput",

        components: { MultiSelect },

        props: {
            options: { type: [Object, Map], required: true },
            value: { required: false, default: "" }, /* eslint-disable-line vue/require-prop-types */
            placeholder: { type: String, required: false, default: "Please select an option" },
            showPlaceholder: { type: Boolean, required: false, default: false },
            hideSelected: { type: Boolean, required: false, default: true },
            allowEmpty: { type: Boolean, required: false, default: true },
            multiple: { type: Boolean, required: false, default: false },
            searchable: { type: Boolean, required: false, default: false },
            showLabels: { type: Boolean, required: false, default: false },
            selectLabel: { type: String, required: false, default: "" },
            deselectLabel: { type: String, required: false, default: "" },
            selectedLabel: { type: String, required: false, default: "" },
            unselectOption: { type: Boolean, required: false, default: true },
        },

        data: () => ({
            selectedValue: "",
        }),

        watch: {
            value: {
                handler: function(value) {
                    this.selectedValue = value
                },
                immediate: true,
                deep: true,
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

            handleOnInput() {
                this.$emit("input", this.selectedValue)
            },

            handleOnSelect() {
                this.$emit("select", this.selectedValue)
            },
        },
    }
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
