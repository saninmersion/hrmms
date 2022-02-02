<template>
    <div class="filters">
        <div class=" flex flex-wrap md:flex-nowrap gap-4">
            <div class="w-full md:w-1/4">
                <label class="block mb-0.5 text-base-3  text-sm" v-text="trans('application.fields.district')"/>
                <drop-down-input :value="queries.district" :options="districtOptions" @input="handleOnSelect($event, 'district')"/>
            </div>

            <div class="w-full md:w-1/4">
                <label class="block mb-0.5 text-base-3  text-sm" v-text="trans('application.fields.municipality')"/>
                <drop-down-input :value="queries.municipality" :options="municipalitiesOptions" @input="handleOnSelect($event, 'municipality')"/>
            </div>

            <div class="w-full md:w-1/4">
                <label class="block mb-0.5 text-base-3 text-sm" v-text="trans('application.fields.application_for')"/>
                <drop-down-input :value="queries.role" :options="roleOptions" @input="handleOnSelect($event, 'role')"/>
            </div>

            <div class="w-full md:w-2/5">
                <label class="form-label !mb-0.5 text-base-3 text-sm">
                    Search by Name
                </label>
                <div class="search-field w-full">
                    <span class="search-icon">
                        <Icon class="!m-auto !w-6 !h-5" name="search"/>
                    </span>
                    <text-input :value="queries.search"
                                autocomplete="off"
                                name="search"
                                type="search"
                                @input="debounceSearchInput"/>
                </div>
            </div>
        </div>
    </div>
</template>

<script type="text/ecmascript-6">
    import { debounce }  from "lodash"
    import DropDownInput from "../../../Components/Forms/DropDownInput"
    import TextInput     from "../../../Components/Forms/TextInput"
    import Icon          from "../../../Components/General/Icon"

    export default {
        name: "ShortListFilter",

        components: {
            DropDownInput,
            Icon,
            TextInput,
        },

        inject: ["options"],

        props: {
            query: { required: true, type: Object },
        },

        data: () => ({
            queries: {
                search: "",
                district: null,
                municipality: null,
                role: null,
            },
        }),

        watch: {
            query: {
                handler(query) {
                    this.queries = {
                        ...query,
                        district: query.district ? parseInt(query.district) : null,
                        municipality: query.municipality ? parseInt(query.municipality) : null,
                    }
                },
                deep: true,
                immediate: true,
            },
        },

        computed: {
            districtOptions: function() {
                const options = new Map()
                const key = this.$currentLocale === "en" ? "title_en" : "title_ne"

                this.options.districts.forEach(district => {
                    options.set(parseInt(district.code), district[key])
                })

                return options
            },

            selectedDistrict: function() {
                const selected = this.queries.district
                if (!selected) {
                    return null
                }

                return this.options.districts.find(district => parseInt(district.code) === parseInt(selected))
            },

            municipalitiesOptions: function() {
                const options = new Map()

                const district = this.selectedDistrict
                if (!district || !district.municipalities) {
                    return options
                }

                const key = this.$currentLocale === "en" ? "title_en" : "title_ne"

                district.municipalities.forEach(district => {
                    options.set(parseInt(district.code), district[key])
                })

                return options
            },

            roleOptions: function() {
                const options = new Map()

                this.options.applicationTypes.forEach(type => {
                    options.set(type, this.trans(`application.success-message-post.${type}`))
                })

                return options
            },
        },

        mounted() {},

        methods: {
            debounceSearchInput: debounce(function(value) {
                this.$set(this.queries, "search", value)
                this.$emit("filter", this.queries)
            }, 500),

            handleOnSelect(value, name) {
                this.queries[name] = value
                if (name === "district") {
                    this.queries.municipality = null
                }

                this.$emit("filter", this.queries)
            },
        },
    }
</script>

<style scoped>

</style>
