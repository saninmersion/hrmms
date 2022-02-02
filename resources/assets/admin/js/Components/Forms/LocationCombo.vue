<template>
    <fragment>
        <div>
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

        <div>
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

        <div v-if="hasWard">
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

        <div v-if="hasTole">
            <label-component :value="trans('application.fields.tole')"/>
            <input-component :placeholder="trans('application.placeholders.tole')"
                             :value="tole"
                             :class="{'!border !border-danger-600' :validation().has(`${name}.tole`)} "
                             @input="handleOnChange($event, 'tole')"/>
            <input-error :message="validation().get(`${name}.tole`)" :v-if="validation().has(`${name}.tole`)"/>
        </div>
    </fragment>
</template>

<script type="text/ecmascript-6">
    import DropDownInput  from "./DropDownInput"
    import { InputError } from "./index"
    import InputComponent from "./Input"
    import LabelComponent from "./Label"

    export default {
        name: "LocationCombo",

        components: { InputComponent, DropDownInput, LabelComponent, InputError },

        props: {
            districts: { type: Array, required: true },
            value: { type: Object, required: false, default: () => ({}) },
            name: { type: String, required: true },
            hasTole: { type: Boolean, required: false, default: true },
            hasWard: { type: Boolean, required: false, default: true },
            excludedName: { type: String, required: false, default: null },
            excludedOptions: { type: Array, required: false, default: () => ([]) },
        },

        data: () => ({
            district: null,
            municipality: null,
            ward: null,
            tole: null,
        }),

        watch: {
            value: {
                handler(value) {
                    this.district = value.district || null
                    this.municipality = value.municipality || null
                    this.ward = value.ward || null
                    this.tole = value.tole || null
                },
                immediate: true,
            },
        },

        computed: {
            districtOptions: function() {
                const options = new Map()
                const key = this.$currentLocale === "en" ? "title_en" : "title_ne"

                this.districts.forEach(district => {
                    if (this.excludedName === "district" && this.excludedOptions.includes(district.code)) {
                        return
                    }
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
                    const wardKey = `${this.selectedMunicipality.code}-${i}`
                    if (this.excludedName !== "ward" || !this.excludedOptions.includes(wardKey)) {
                        options.set(i, this.numberTrans(i))
                    }
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
                }

                if (this.hasTole) {
                    data.tole = this.tole
                }

                this.$emit("input", data)
            },
        },
    }
</script>
