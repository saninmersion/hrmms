<template>
    <div ref="filters" class="filters">
        <div>
            <label class="form-label !mb-0.5 text-base-3 text-sm">
                Search by Submission no, Name, Phone, Citizenship and DOB
            </label>
            <div class="search-field md:w-1/2">
                <span class="search-icon">
                    <Icon name="search" class="!m-auto !w-6 !h-5"/>
                </span>
                <text-input :value="queries.search"
                            autocomplete="off"
                            name="search"
                            type="search"
                            @input="debounceSearchInput"/>
            </div>
        </div>

        <div class="advanced-filters-wrapper mt-6">
            <div class="advanced-filters flex flex-wrap md:flex-nowrap gap-4">
                <div class="w-full md:w-1/5">
                    <label class="block mb-0.5 text-base-3  text-sm" v-text="trans('application.fields.gender')"/>
                    <drop-down-input v-model="queries.gender" :options="genderOptions"/>
                </div>
                <div v-if="getFromObject($page, 'props.auth.user.role') !== 'district_staffs'" class="w-full md:w-1/5">
                    <label class="block mb-0.5 text-base-3  text-sm" v-text="trans('application.preview.application_locations') + ' ' + trans('application.fields.district')"/>
                    <drop-down-input v-model="queries.district" :options="districtOptions"/>
                </div>
                <div class="w-full md:w-1/5">
                    <label class="block mb-0.5 text-base-3  text-sm" v-text="'फारम स्थिति'"/>
                    <drop-down-input v-model="queries.application_status" :options="statusOptions"/>
                </div>
                <div class="w-full md:w-1/4">
                    <label class="block mb-0.5 text-base-3  text-sm" v-text="trans('application.fields.application_for')"/>
                    <drop-down-input v-model="queries.role" :options="roleOptions"/>
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
    import { debounce }     from "lodash"
    import { CancelButton } from "../../../Components/Buttons"
    import DropDownInput    from "../../../Components/Forms/DropDownInput"
    import TextInput        from "../../../Components/Forms/TextInput"
    import Icon             from "../../../Components/General/Icon"

    export default {
        name: "ApplicantFilters",

        components: {
            Icon,
            CancelButton,
            DropDownInput,
            TextInput,
        },

        inject: ["options"],

        props: {
            query: { required: true, type: Object },
        },

        data() {
            return {
                showReset: false,
                queries: {
                    search: "",
                    gender: "all",
                    district: "all",
                    application_status: "all",
                    role: "all",
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
            genderOptions: function() {
                const options = new Map()
                options.set("all", this.trans("general.All"))

                this.options.genders.forEach(type => {
                    options.set(type, this.trans(`application.gender-${type}`))
                })

                return options
            },
            roleOptions: function() {
                const options = new Map()
                options.set("all", this.trans("general.All"))

                this.options.applicationTypes.forEach(type => {
                    options.set(type, this.trans(`application.success-message-post.${type}`))
                })

                return options
            },
            statusOptions: function() {
                const options = new Map()
                options.set("all", this.trans("general.All"))

                this.options.applicationStatues.forEach(type => {
                    options.set(type, this.trans(`application.application-status.${type}`))
                })

                return options
            },
            districtOptions: function() {
                const options = new Map()
                const key = this.$currentLocale === "en" ? "title_en" : "title_ne"
                options.set("all", this.trans("general.All"))

                this.options.districts.forEach(district => {
                    options.set(parseInt(district.code), district[key])
                })

                return options
            },
        },

        created() {
            const query = this.query

            if (query.district && query.district !== "all") {
                query.district = parseInt(query.district)
            }

            this.$set(this, "queries", { ...this.queries, ...query })
        },

        methods: {
            debounceSearchInput: debounce(function(value) {
                this.$set(this.queries, "search", value)
            }, 500),

            filterReset: function() {
                this.$set(this, "queries", {
                    search: "",
                    gender: "all",
                    district: "all",
                    application_status: "all",
                    role: "all",
                })
            },
        },
    }
</script>
