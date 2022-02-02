<template>
    <div ref="filters" class="filters">
        <div class="advanced-filters-wrapper mt-6">
            <div class="advanced-filters flex flex-wrap md:flex-nowrap gap-4">
                <text-input :value="queries.search"
                            autocomplete="off"
                            class="w-full md:w-1/3"
                            :label="trans('admin-users.search-users')"
                            name="search"
                            :placeholder="trans('admin-users.enter-username')"
                            type="search"
                            @input="debounceSearchInput"/>
                <div class="w-full md:w-1/4">
                    <label class="block mb-0.5 text-base-3  text-sm" v-text="trans('admin-users.role')"/>
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
        name: "UserFilters",

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
            roleOptions: function() {
                const options = new Map()
                options.set("all", this.trans("general.All"))

                this.options.roles.forEach(role => {
                    options.set(role, this.trans(`admin-users.roles.${role}`))
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
                    role: "all",
                })
            },
        },
    }
</script>
