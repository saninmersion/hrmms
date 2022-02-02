<template>
    <fragment>
        <h6 class="text-gray-500 text-sm mt-3 mb-6 font-bold uppercase">
            २. अनगमनकता ु लर् े ःथलगत अनगमनका ु बममा देिखएका िवषयबःतु
        </h6>
        <div class="flex flex-wrap">
            <div class="w-full px-4">
                <div class="relative w-full mb-3">
                    <label-component class="block uppercase text-gray-700 text-xs font-bold mb-2" htmlfor="grid-password">
                        परिवार बसोबास गरेको घरमा माकर् रले जनगणना घर तथा घरपरिवार ब.सं. लेखेको नलेखेको
                    </label-component>
                    <div class="">
                        <radio-group-input id="marking"
                                           v-model="marking"
                                           :options="markingOptions"
                                           class="pt-2 pb-4"
                                           name="marking"
                                           @change="handleOnChange($event, 'marking')"/>
                    </div>
                </div>
            </div>
            <div class="w-full px-4">
                <div class="relative w-full mb-3">
                    <label-component class="block uppercase text-gray-700 text-xs font-bold mb-2" htmlfor="grid-password">
                        घरपरिवारलाई जनगणनाको वारेमा गणना पूवर् जानकारी भए नभएको
                    </label-component>
                    <div class="">
                        <radio-group-input id="prior_information"
                                           v-model="prior_information"
                                           :options="priorOptions"
                                           class="pt-2 pb-4"
                                           name="prior_information"
                                           @change="handleOnChange($event, 'prior_information')"/>
                    </div>
                </div>
            </div>
            <div class="w-full px-4">
                <div class="relative w-full mb-3">
                    <label-component class="block uppercase text-gray-700 text-xs font-bold mb-2" htmlfor="grid-password">
                        अनगमन ु गिरएका क्षेऽमा जनगणना सम्बन्धी ःथानीय ǽपमा ूचार ूसार भए नभएको
                    </label-component>
                    <div class="">
                        <radio-group-input id="local_advertisement"
                                           v-model="local_advertisement"
                                           :options="localAdvertisementOptions"
                                           class="pt-2 pb-4"
                                           name="local_advertisement"
                                           @change="handleOnChange($event, 'local_advertisement')"/>
                    </div>
                </div>
            </div>
            <div class="w-full px-4">
                <div class="relative w-full mb-3">
                    <label-component class="block uppercase text-gray-700 text-xs font-bold mb-2" htmlfor="grid-password">
                        गणना गिरसिकएको गाउँ÷टोल÷बःतीमा कु नै घर परिवार गणना गनर् छुट भए नभएको
                    </label-component>
                    <div class="">
                        <radio-group-input id="missed_count"
                                           v-model="missed_count"
                                           :options="missedCountOptions"
                                           class="pt-2 pb-4"
                                           name="missed_count"
                                           @change="handleOnChange($event, 'missed_count')"/>
                    </div>
                </div>
            </div>
            <div class="w-full px-4">
                <div class="relative w-full mb-3">
                    <label-component class="block uppercase text-gray-700 text-xs font-bold mb-2" htmlfor="grid-password">
                        जनगणनाको ःथलगत गणना कायर् ूित उƣरदाताहǽको कु नै गनासो ु भए नभएको
                    </label-component>
                    <div class="">
                        <radio-group-input id="complaints"
                                           v-model="complaints"
                                           :options="complaintsOptions"
                                           class="pt-2 pb-4"
                                           name="complaints"
                                           @change="handleOnChange($event, 'complaints')"/>
                    </div>
                </div>
            </div>
            <div class="w-full px-4">
                <div class="relative w-full mb-3">
                    <label-component class="block uppercase text-gray-700 text-xs font-bold mb-2" htmlfor="grid-password">
                        पजनगणनाको ःथलगत कायमा र् बाधा व्यवधान भए नभएको
                    </label-component>
                    <div class="">
                        <radio-group-input id="obstacles"
                                           v-model="obstacles"
                                           :options="obstaclesOptions"
                                           class="pt-2 pb-4"
                                           name="obstacles"
                                           @change="handleOnChange($event, 'obstacles')"/>
                    </div>
                </div>
            </div>
        </div>
    </fragment>
</template>

<script>
    import LabelComponent  from "../../../Components/Forms/Label"
    import RadioGroupInput from "../../../Components/Forms/RadioGroupInput"

    export default {
        name: "MonitoringOnsite",
        components: { RadioGroupInput, LabelComponent },
        props: {
            value: { type: Object, required: false, default: () => ({}) },
            name: { type: String, required: true },
        },
        data() {
            return {
                marking: null,
                prior_information: null,
                local_advertisement: null,
                missed_count: null,
                complaints: null,
                obstacles: null,
            }
        },
        watch: {
            value: {
                handler(v) {
                    this.marking = v.marking || null
                    this.prior_information = v.prior_information || null
                    this.local_advertisement = v.local_advertisement || null
                    this.missed_count = v.missed_count || null
                    this.complaints = v.complaints || null
                    this.obstacles = v.obstacles || null
                },
                immediate: true,
            },
        },
        computed: {
            markingOptions() {
                const options = new Map()

                options.set("written", "ेखेको")
                options.set("not_written", "नलेखेकोे")
                options.set("partially_written", "आंिशक ǽपमा लेखेको")
                return options
            },
            priorOptions() {
                const options = new Map()

                options.set("informed", "जानकारी भएको")
                options.set("not_informed", "जानकारी नभएको")
                options.set("partially_informed", "आंिशक घर परिवारमा जानकारी भएको")
                return options
            },
            localAdvertisementOptions() {
                const options = new Map()

                options.set("has", "भएको")
                options.set("doesnt_have", "नभएको")
                return options
            },
            missedCountOptions() {
                const options = new Map()

                options.set("not_missed", "भएको")
                options.set("missed", "भएको (छुट भएको घर परिवारको गाउँ÷टोल÷बःती खलाउन")
                return options
            },
            complaintsOptions() {
                const options = new Map()

                options.set("has", "भएको")
                options.set("doesnt_have", "नभएको")
                return options
            },
            obstaclesOptions() {
                const options = new Map()

                options.set("has", "भएको")
                options.set("doesnt_have", "नभएको")
                return options
            },
        },
        methods: {
            handleOnChange(value, name) {
                this.$set(this, name, value)
                this.emitData()
            },

            emitData() {
                const data = {
                    marking: this.marking,
                    prior_information: this.prior_information,
                    local_advertisement: this.local_advertisement,
                    missed_count: this.missed_count,
                    complaints: this.complaints,
                    obstacles: this.obstacles,
                }

                this.$emit("input", data)
            },
        },
    }
</script>

<style scoped>

</style>
