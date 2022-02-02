<template>
    <div ref="filters" class="filters">
        <div class="advanced-filters-wrapper mt-6">
            <div class="advanced-filters flex flex-wrap md:flex-nowrap gap-4">
                <div v-if="getFromObject($page, 'props.auth.user.role') !== 'district_staffs'" class="w-full md:w-1/5">
                    <label class="font-bold text-black-500 block mb-0.5 text-base-3  text-sm" v-text="trans('application.preview.application_locations') + ' ' + trans('application.fields.district')"/>
                    <drop-down-input
                        :value="queries.district_code"
                        :options="districtOptions"
                        :hide-selected="false"
                        :placeholder="trans('application.district')"
                        :show-placeholder="true"
                        @input="updateDistrict"/>
                </div>
                <div class="w-full md:w-1/5">
                    <label class="font-bold text-black-500 block mb-0.5 text-base-3  text-sm" v-text="trans('application.census_office')"/>
                    <drop-down-input
                        v-model="queries.census_office_id"
                        :options="censusOfficesOptions"
                        :hide-selected="false"
                        :placeholder="trans('application.census_office')"
                        :show-placeholder="true"/>
                </div>

                <div class="w-full md:w-1/5">
                    <label class="font-bold text-black-500 block mb-0.5 text-base-3  text-sm" v-text="trans('application.supervisor')"/>
                    <input-component
                        v-model="queries.supervisor"
                        :show-placeholder="true"/>
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
    import { CancelButton }   from "../../../Components/Buttons"
    import { InputComponent } from "../../../Components/Forms"
    import DropDownInput      from "../../../Components/Forms/DropDownInput"
    import Icon               from "../../../Components/General/Icon"

    export default {
        name: "DailyReportFilters",

        components: {
            Icon,
            CancelButton,
            DropDownInput,
            InputComponent,
        },

        inject: ["districts", "censusOffices"],

        props: {
            query: { required: true, type: Object },
        },

        data() {
            return {
                showReset: false,
                queries: {
                    district_code: "all",
                    census_office_id: "all",
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
            districtOptions: function() {
                const options = new Map()
                options.set("all", this.trans("general.All"))
                const key = this.$currentLocale === "en" ? "title_en" : "title_ne"

                this.districts.forEach(district => {
                    options.set(parseInt(district.code), district[key])
                })

                return options
            },

            censusOfficesOptions: function() {
                const options = new Map()
                options.set("all", this.trans("general.All"))

                this.censusOffices.forEach(office => {
                    if (this.queries.district_code && this.queries.district_code !== office.district_code) {
                        return
                    }
                    options.set(parseInt(office.id), office.office_name)
                })

                return options
            },
        },

        created() {
            const query = this.query

            if (query.district_code && query.district_code !== "all") {
                query.district_code = parseInt(query.district_code)
            }

            if (query.census_office_id && query.census_office_id !== "all") {
                query.census_office_id = parseInt(query.census_office_id)
            }

            this.$set(this, "queries", { ...this.queries, ...query })
        },

        methods: {
            filterReset: function() {
                this.$set(this, "queries", {
                    district_code: "all",
                    census_office_id: "all",
                })
            },

            updateDistrict: function(data) {
                this.$set(this.queries, "district_code", data)
                this.$set(this.queries, "census_office_id", "all")
            },
        },
    }
</script>
