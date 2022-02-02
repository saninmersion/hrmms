<template>
    <div class="card card-js-scroll">
        <h3 class="heading-primary">
            {{ trans("application.sections.position") }}
        </h3>
        <div class="mb-4">
            <label class="mb-2 block">
                {{ trans("application.fields.application_for") }}
            </label>
            <div class="md:w-3/4">
                <radio-group-input v-model="application.application_for"
                                   class="grid md:grid-cols-3 !gap-6"
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

        <div>
            <link-button v-if="application.locations.length < maxLocationCount"
                         class="!border-0 !bg-transparent !shadow-none !px-0 text-primary"
                         @click.prevent="addMoreLocations">
                <Icon name="plus"/>
                {{ trans(`application.application-locations-${application.locations.length + 1}-add`) }}
            </link-button>
        </div>
    </div>
</template>

<script type="text/ecmascript-6">
    import { LinkButton }      from "../../../Components/Buttons"
    import {
        InputError,
        RadioGroupInput,
    }                          from "../../../Components/Forms"
    import Icon                from "../../../Components/General/Icon"
    import ApplicationLocation from "./ApplicationLocation"

    export default {
        name: "ApplicationPositionForm",

        inject: ["applicationTypes"],

        components: { ApplicationLocation, InputError, RadioGroupInput, LinkButton, Icon },

        props: {
            // value: { type: Object, required: true },
            form: { type: Object, required: true },
        },

        data: () => ({
            application: {},
            maxLocationCount: 2,
        }),

        watch: {
            form: {
                handler(val) {
                    this.application = val.application
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
