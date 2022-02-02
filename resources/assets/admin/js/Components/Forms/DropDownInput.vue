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
            selectLabel: { type: String, required: false, default: "Press enter to select" },
            deselectLabel: { type: String, required: false, default: "Press enter to remove" },
            selectedLabel: { type: String, required: false, default: "Selected" },
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

<style lang="scss">
    .multiselect {
        border-radius: 4px;

        &__tags {
            border-color: var(--color-border-2);
            display: flex;
            align-items: center;
            cursor: pointer;
        }

        &__input {
            background: transparent;
            padding-left: 0;
            margin: 0;
        }

        &__single {
            background: transparent;
            color: var(--color-base-3);
            border-width: 0;
        }

        &__placeholder {
            font-size: 16px;
            color: var(--color-base-3);
            font-weight: 400;
        }

        &__select {
            position: absolute;
            top: 4px;
            right: 4px;

            &:before {
                border: 5px solid var(--color-border-3);
                border-left-color: transparent;
                border-right-color: transparent;
                border-bottom: 0;
                transform: none;
            }
        }

        &__option {
            white-space: unset;
            word-break: break-all;
        }

        &.multiselect--active {

            .multiselect__select {
                transform: translateY(-8px) rotate(180deg);
            }

            .multiselect__placeholder {
                display: block;
            }

        }
    }
</style>
