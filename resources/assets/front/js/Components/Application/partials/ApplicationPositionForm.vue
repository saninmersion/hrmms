<template>
    <div class="card card-js-scroll">
        <h3 class="heading-primary">
            {{ trans("application.sections.position") }}
        </h3>

        <div class="mb-8">
            <label class="mb-2 block">
                {{ trans("application.fields.application_for") }}
            </label>
            <div>
                <radio-group-input v-model="application.application_for"
                                   class="radio-btn-group radio-btn-group--blue !gap-6 flex-wrap"
                                   :options="applicationForOptions"
                                   @keypress="validation().clear('application.application_for')"/>
                <input-error :message="validation().get('application.application_for')"/>
            </div>
        </div>
        <div>
            <strong class="block text-black-100 mb-2">
                {{ trans("application.fields.application_location") }}
            </strong>
            <div class="block">
                {{ trans("application.form-help-text.application_location") }}
            </div>
        </div>

        <application-location v-for="(location, locationIndex) in application.locations"
                              :key="locationIndex"
                              :location="location"
                              :locations="application.locations"
                              :priority="locationIndex + 1"
                              :name="'application.locations.' + locationIndex "
                              @remove="removeLocation(locationIndex)"
                              @input="application.locations[locationIndex] = $event"/>
    </div>
</template>

<script type="text/ecmascript-6">
    import {
        InputError,
        RadioGroupInput,
    }                          from "../../../../../shared/js/Components/Forms"
    import ApplicationLocation from "./ApplicationLocation"

    export default {
        name: "ApplicationPositionForm",

        inject: ["applicationTypes"],

        components: { ApplicationLocation, InputError, RadioGroupInput },

        props: {
            value: { type: Object, required: true },
        },

        data: () => ({
            application: {},
            maxLocationCount: 2,
        }),

        watch: {
            value: {
                handler(val) {
                    this.application = val
                },
                deep: true,
                immediate: true,
            },
        },

        computed: {
            applicationForOptions: function() {
                const options = new Map()

                this.applicationTypes.forEach(type => {
                    options.set(type, this.trans(`application.application-type-${type}`))
                })

                return options
            },
        },

        methods: {
            addMoreLocations() {
                this.application.locations.push({})
            },

            removeLocation(index) {
                this.application.locations.splice(index, 1)
            },
        },
    }
</script>
