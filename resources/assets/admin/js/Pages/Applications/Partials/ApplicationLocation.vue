<template>
    <fieldset class="xl:w-3/5 relative">
        <legend>
            {{ trans(`application.application-locations-${priority}`) }}
        </legend>

        <div class="grid sm:grid-cols-3 gap-3">
            <location-combo :value="value"
                            :districts="districtOptions"
                            :excluded-options="excludedOptions"
                            :has-tole="false"
                            :has-ward="true"
                            excluded-name="ward"
                            :name="name"
                            @input="handleOnChange"/>
        </div>

        <button v-if="priority !== 1"
                class="btn-remove btn-remove--absolute"
                @click.prevent="$emit('remove')">
            <Icon name="close"/>
        </button>
    </fieldset>
</template>

<script type="text/ecmascript-6">
    import { LocationCombo } from "../../../Components/Forms"
    import Icon from "../../../Components/General/Icon";

    export default {
        name: "ApplicationLocation",

        inject: ["newDistricts"],

        components: { Icon, LocationCombo },

        props: {
            name: { type: String, required: true },
            location: { type: Object, required: true },
            locations: { type: Array, required: true },
            priority: { type: Number, required: true },
        },

        data: () => ({
            value: {},
        }),

        watch: {
            location: {
                handler(location) {
                    this.value = location
                },
                immediate: true,
            },
        },

        computed: {
            districtOptions: function() {
                return this.newDistricts
            },

            excludedOptions: function() {
                return this.locations.reduce((excluded, location) => ([
                    ...excluded,
                    `${location.municipality}-${location.ward}`,
                ]), [])
            },
        },

        methods: {
            handleOnChange(data) {
                this.value = { ...this.value, ...data, priority: this.priority }

                this.$emit("input", this.value)
            },
        },
    }
</script>
