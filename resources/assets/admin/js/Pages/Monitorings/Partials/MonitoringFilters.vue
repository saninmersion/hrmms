<template>
    <div ref="filters" class="filters">
        <div class="advanced-filters-wrapper mt-6">
            <div class="advanced-filters flex flex-wrap md:flex-nowrap gap-4">
                <div class="w-full md:w-1/5">
                    <label class="block mb-0.5 text-base-3  text-sm" v-text="`Form Type`"/>
                    <drop-down-input v-model="queries.form_type" :options="formOptions"/>
                </div>
                <div v-if="getFromObject($page, 'props.auth.user.role') !== 'district_staffs'" class="w-full md:w-1/5">
                    <label class="block mb-0.5 text-base-3  text-sm" v-text="trans('application.preview.application_locations') + ' ' + trans('application.fields.district')"/>
                    <drop-down-input :value="queries.district_code" :options="districtOptions" @input="resetMunicipality"/>
                </div>
                <div class="w-full md:w-1/5">
                    <label class="block mb-0.5 text-base-3  text-sm" v-text="trans('application.fields.municipality')"/>
                    <drop-down-input v-model="queries.municipality_code" :options="municipalityOptions"/>
                </div>
                <div class="w-full md:w-auto flex items-end">
                    <cancel-button class="font-medium text-base-1 hover:text-base-2 !border-0 !pl-1 !shadow-none !font-sm tracking-wider reset-btn" @click.prevent="filterReset">
                        <Icon name="reset"/>
                        Reset
                    </cancel-button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { CancelButton } from "../../../Components/Buttons"
    import DropDownInput    from "../../../Components/Forms/DropDownInput"
    import Icon             from "../../../Components/General/Icon"

    export default {
        name: "MonitoringFilters",

        components: {
            Icon,
            CancelButton,
            DropDownInput,
        },

        inject: ["districts"],

        props: {
            query: { required: true, type: Object },
        },

        data() {
            return {
                showReset: false,
                queries: {
                    form_type: "all",
                    district_code: "all",
                    municipality_code: "all",
                },
            }
        },

        watch: {
            queries: {
                handler(queries) {
                    this.$emit("filter", queries)
                },
                deep: true,
            },
        },

        computed: {
            formOptions: function() {
                const options = new Map()
                options.set("all", this.trans("general.All"))
                options.set("overall_monitoring", this.trans("admin-monitoring.form-type.overall_monitoring"))
                options.set("supervisor_monitoring", this.trans("admin-monitoring.form-type.supervisor_monitoring"))
                options.set("enumerator_monitoring", this.trans("admin-monitoring.form-type.enumerator_monitoring"))
                return options
            },
            districtOptions: function() {
                const options = new Map()
                const key = this.$currentLocale === "en" ? "title_en" : "title_ne"
                options.set("all", this.trans("general.All"))

                this.districts.forEach(district => {
                    options.set(parseInt(district.code), district[key])
                })

                return options
            },
            selectedDistrict: function() {
                if (!this.query.district_code) {
                    return null
                }

                return this.districts.find(district => {
                    return parseInt(district.code) === parseInt(this.query.district_code)
                })
            },
            municipalityOptions: function() {
                const options = new Map()
                options.set("all", this.trans("general.All"))

                if (!this.selectedDistrict || !this.selectedDistrict.municipalities) {
                    return options
                }

                const key = this.$currentLocale === "en" ? "title_en" : "title_ne"

                this.selectedDistrict.municipalities.forEach(municipality => {
                    options.set(parseInt(municipality.code), municipality[key])
                })

                return options
            },
        },

        created() {
            const query = this.query

            if (query.district_code && query.district_code !== "all") {
                query.district_code = parseInt(query.district_code)
            }

            if (query.municipality_code && query.municipality_code !== "all") {
                query.municipality_code = parseInt(query.municipality_code)
            }

            this.$set(this, "queries", { ...this.queries, ...query })
        },

        methods: {
            filterReset: function() {
                this.$set(this, "queries", {
                    form_type: "all",
                    district_code: "all",
                    municipality_code: "all",
                })
            },

            resetMunicipality: function(data) {
                this.$set(this.queries, "district_code", data)
                this.$set(this.queries, "municipality_code", "all")
            },
        },
    }
</script>
