<template>
    <div ref="filters" class="filters">
        <div class="advanced-filters-wrapper mt-6">
            <div class="advanced-filters flex flex-wrap gap-4">
                <div class="w-full md:w-2/5">
                    <label class="form-label !mb-0.5 text-base-3 text-sm">
                        Search by Citizenship and DOB
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
                <div class="w-full md:w-1/5">
                    <label class="block mb-0.5 text-base-3  text-sm" v-text="'Verification Status'"/>
                    <drop-down-input v-model="queries.status" :options="statusOptions"/>
                </div>
                <div class="flex items-end">
                    <cancel-button class="text-base-1 hover:text-base-2 !border-0 !pl-1 !shadow-none !font-sm tracking-wider reset-btn" @click.prevent="filterReset">
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
        name: "ApplicationVerificationFilters",

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
                    status: "not_verified",
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
            statusOptions: function() {
                const options = new Map()
                options.set("all", this.trans("general.All"))

                this.options.verificationTypes.forEach(type => {
                    options.set(type, this.trans(`admin-application.verification_status-${type}`))
                })

                return options
            },
        },

        created() {
            this.$set(this, "queries", { ...this.queries, ...this.query })
        },

        methods: {
            debounceSearchInput: debounce(function(value) {
                this.$set(this.queries, "search", value)
            }, 500),

            filterReset: function() {
                this.$set(this, "queries", {
                    search: "",
                    status: "not_verified",
                })
            },
        },
    }
</script>
