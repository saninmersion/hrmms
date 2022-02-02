<template>
    <fragment>
        <h6 class="text-gray-500 text-sm mt-3 mb-6 font-bold uppercase">
            Monitoring Location
        </h6>
        <div class="flex flex-wrap">
            <div class="w-full lg:w-6/12 px-4">
                <label-component :value="trans('application.fields.district')"/>
                <drop-down-input
                    :class="{'form-input-error' :validation().has(`${name}.district`)} "
                    :hide-selected="false"
                    :options="districtOptions"
                    :placeholder="trans('application.placeholders.district')"
                    :searchable="true"
                    :show-placeholder="true"
                    :value="district"
                    @input="handleOnChange($event, 'district')"/>
                <input-error :message="validation().get(`${name}.district`)" :v-if="validation().has(`${name}.district`)"/>
            </div>

            <div class="w-full lg:w-6/12 px-4">
                <label-component :value="trans('application.fields.municipality')"/>
                <drop-down-input
                    :class="{'form-input-error' :validation().has(`${name}.municipality`)} "
                    :hide-selected="false"
                    :options="municipalityOptions"
                    :placeholder="trans('application.placeholders.municipality')"
                    :searchable="true"
                    :show-placeholder="true"
                    :value="municipality"
                    @input="handleOnChange($event, 'municipality')"/>
                <input-error :message="validation().get(`${name}.municipality`)"
                             :v-if="validation().has(`${name}.municipality`)"/>
            </div>

            <div class="w-full lg:w-6/12 px-4">
                <label-component :value="trans('application.fields.ward')"/>
                <drop-down-input
                    :class="{'form-input-error' :validation().has(`${name}.ward`)} "
                    :hide-selected="false"
                    :options="wardOptions"
                    :placeholder="trans('application.placeholders.ward')"
                    :show-placeholder="true"
                    :value="ward"
                    @input="handleOnChange($event, 'ward')"/>
                <input-error :message="validation().get(`${name}.ward`)" :v-if="validation().has(`${name}.ward`)"/>
            </div>
            <input-component class="w-full lg:w-6/12 px-4"
                             label="वडा नं."
                             placeholder="census area"
                             :value="census_area"
                             :name="`${name}.census_area`"
                             @input="handleOnChange($event, 'census_area')"/>
        </div>

        <hr class="mt-6 border-b-1 border-gray-400">
    </fragment>
</template>

<script>
    import DropDownInput  from "../../../Components/Forms/DropDownInput"
    import InputError     from "../../../Components/Forms/InputError"
    import LabelComponent from "../../../Components/Forms/Label"
    import InputComponent from "../../../Components/Forms/TextInput"

    export default {
        name: "MonitoringLocation",
        components: { DropDownInput, LabelComponent, InputError, InputComponent },
        inject: ["districts"],
        props: {
            value: { type: Object, required: false, default: () => ({}) },
            name: { type: String, required: true },
        },
        data() {
            return {
                district: null,
                municipality: null,
                ward: null,
                census_area: null,
            }
        },
        watch: {
            value: {
                handler(v) {
                    this.district = v.district || null
                    this.municipality = v.municipality || null
                    this.ward = v.ward || null
                    this.tole = v.tole || null
                },
                immediate: true,
            },
        },
        computed: {
            districtOptions: function() {
                const options = new Map()
                const key = this.$currentLocale === "en" ? "title_en" : "title_ne"

                this.districts.forEach(district => {
                    options.set(parseInt(district.code), district[key])
                })

                return options
            },

            selectedDistrict: function() {
                if (!this.district) {
                    return null
                }

                return this.districts.find(district => {
                    return parseInt(district.code) === parseInt(this.district)
                })
            },
            municipalityOptions: function() {
                if (!this.selectedDistrict || !this.selectedDistrict.municipalities) {
                    return {}
                }

                const options = new Map()
                const key = this.$currentLocale === "en" ? "title_en" : "title_ne"

                this.selectedDistrict.municipalities.forEach(municipality => {
                    if (this.excludedName === "municipality" && this.excludedOptions.includes(municipality.code)) {
                        return
                    }
                    options.set(parseInt(municipality.code), municipality[key])
                })

                return options
            },

            selectedMunicipality: function() {
                if (!this.municipality || !this.selectedDistrict.municipalities) {
                    return null
                }

                return this.selectedDistrict.municipalities.find(municipality => {
                    return parseInt(municipality.code) === parseInt(this.municipality)
                })
            },

            wardOptions: function() {
                if (!this.selectedMunicipality || !this.selectedMunicipality.wards) {
                    return {}
                }

                const options = new Map()

                for (let i = 1; i <= parseInt(this.selectedMunicipality.wards); i++) {
                    options.set(i, this.numberTrans(i))
                }

                return options
            },
        },

        methods: {
            handleOnChange(value, name) {
                this.$set(this, name, value)
                if (name === "district") {
                    this.$set(this, "municipality", null)
                    this.$set(this, "ward", null)
                }

                if (name === "municipality") {
                    this.$set(this, "ward", null)
                }

                this.emitData()
            },

            emitData() {
                const data = {
                    district: this.district,
                    municipality: this.municipality,
                    ward: this.ward,
                    census_area: this.census_area,
                }

                this.$emit("input", data)
            },
        },
    }
</script>

<style scoped>

</style>
