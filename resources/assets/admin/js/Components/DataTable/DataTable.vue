<template>
    <div class="datatable w-full user-list-page">
        <div v-if="showFilter">
            <div class="search-field md:w-2/5">
                <span class="search-icon">
                    <Icon name="search" class="!m-auto !w-6 !h-5"/>
                </span>
                <label>
                    <input v-model="query.search"
                           :placeholder="trans('admin-general.search')"
                           class="form-control"
                           type="search"
                           @input="handleOnChange">
                </label>
            </div>
        </div>
        <div class="mt-6">
            <div class="block py-6 bg-white w-full shadow rounded-lg">
                <div class="flex flex-wrap gap-6 pl-5 pr-1">
                    <pagination v-if="showPaginationTop && paginationData"
                                :current-page="query.page"
                                :pagination="paginationData"
                                class="flex-grow"
                                @pagechanged="onPageChange"/>
                    <div class="flex gap-2 w-32">
                        <div v-if="showLimitOption && rows.length" class="flex flex-row h-10">
                            <div v-if="paginationData" class="relative">
                                <label>
                                    <select
                                        v-model="query.per_page"
                                        class="w-18 px-3 cursor-pointer appearance-none h-full rounded block
                                            border focus:outline-none focus:bg-white focus:border-primary-100"
                                        @change="handleOnChange">
                                        <option v-for="size in $page.props.staticData.paginate_sizes"
                                                :key="size"
                                                :selected="size === paginationData.per_page"
                                                :value="size"
                                                v-text="size"/>
                                    </select>
                                </label>
                                <div
                                    class="pointer-events-none flex items-center absolute right-0 top-0 h-full pr-3">
                                    <Icon name="caret-down"/>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center p-3 hover:text-base-2">
                            <slot v-if="rows.length" name="settings"/>
                        </div>
                    </div>
                </div>
                <div class="table-wrapper data-table overflow-x-auto my-6">
                    <table v-if="rows.length">
                        <thead>
                            <tr>
                                <th v-if="hasSnRow"
                                    class="px-3 py-4 bg-grey-2 text-sm font-semibold text-base-1 text-left tracking-wider uppercase"
                                    v-text="trans('admin-general.sn')"/>

                                <slot :column="tableColumns" name="thead">
                                    <th v-for="(label, key) in tableColumns"
                                        :key="`datatable-thead-th-${key}`"
                                        class="px-3 py-4 bg-grey-2 text-sm font-semibold text-base-1 text-left tracking-wider uppercase"
                                        v-text="label"/>
                                </slot>
                            </tr>
                        </thead>

                        <tbody>
                            <tr v-for="(row, index) in rows"
                                :key="`datatable-tbody-${new Date().getDate() + index}`"
                                :class="{ 'cursor-pointer': rowClickable }"
                                @click.prevent="handleOnRowClick(row)">
                                <td v-if="hasSnRow"
                                    class="p-3 text-sm  whitespace-no-wrap text-base-3"
                                    v-text="numberTrans(index + 1 + (paginationData && (paginationData.per_page * (paginationData.current_page - 1))))"/>

                                <slot :index="index" :row="row" name="tbody">
                                    <td v-for="(_, key) in tableColumns"
                                        :key="`datatable-tbody-td-${new Date().getDate() + key}`"
                                        v-text="row[key]"/>
                                </slot>
                            </tr>
                        </tbody>
                    </table>
                    <empty-data v-else class="bg-white  ">
                        <slot name="empty-message">
                            <p class="text-xl text-gray-700">
                                No records found.
                            </p>
                        </slot>
                    </empty-data>
                </div>
                <div v-if="showPaginationBottom && paginationData"
                     class="px-5">
                    <pagination
                        :current-page="query.page"
                        :pagination="paginationData"
                        @pagechanged="onPageChange"/>
                </div>
            </div>
        </div>
    </div>
</template>

<script type="text/ecmascript-6">
    import { isEmpty } from "lodash"
    import Icon        from "../General/Icon"
    import {
        isArray,
        isObject,
    }                  from "./../../../../shared/js/Helpers"
    import EmptyData   from "./EmptyData"
    import Pagination  from "./Pagination"

    export default {
        name: "DataTable",

        components: { Icon, EmptyData, Pagination },

        props: {
            tableData: { type: [Array, Object], required: true },
            columns: { type: Object, required: false, default: null },
            queries: { type: [Object], required: false, default: () => ({}) },
            hasSnRow: { type: Boolean, required: false, default: true },
            showFilter: { type: Boolean, required: false, default: true },
            showLimitOption: { type: Boolean, required: false, default: true },
            pagination: { type: Object, required: false, default: () => ({}) },
            showPaginationTop: { type: Boolean, required: false, default: true },
            showPaginationBottom: { type: Boolean, required: false, default: true },
            rowClickable: { type: Function, required: false, default: null },
        },

        data: () => ({
            query: {
                page: 1,
                search: "",
                per_page: 10,
            },
        }),

        watch: {
            queries: {
                handler(queries) {
                    this.$set(this, "query", { ...this.query, ...queries })
                },
                immediate: true,
            },

            paginationData: {
                handler(pagination) {
                    if (pagination) {
                        this.$set(this.query, "per_page", pagination.per_page)
                    }
                },
                immediate: true,
            },

            "query.page": {
                handler(page) {
                    this.$set(this.query, "page", parseInt(page))
                },
                immediate: true,
            },
        },

        computed: {
            tableColumns: function() {
                if (this.columns) {
                    return this.columns
                }

                if (this.rows.length === 0) {
                    return {}
                }

                return Object.entries(this.rows[0]).reduce((cols, [key, _]) => ({ ...cols, [key]: key }), {})
            },

            rows: function() {
                if (isArray(this.tableData)) {
                    return this.tableData
                }

                return Object.entries(this.tableData).reduce((rows, [key, d]) => {
                    if (key === "meta") {
                        return [...rows]
                    }

                    return [...rows, d]
                }, [])
            },

            paginationData: function() {
                if (!isEmpty(this.pagination)) {
                    return this.pagination
                }

                if (isObject(this.tableData) && this.tableData.meta) {
                    return this.tableData.meta.pagination || null
                }

                return null
            },

            totalColumns: function() {
                return (this.hasSnRow ? 1 : 0) + Object.keys(this.tableColumns).length
            },
        },

        methods: {
            onPageChange(page) {
                this.$set(this.query, "page", page)

                this.fireDataReload()
            },

            handleOnChange() {
                this.$set(this.query, "page", 1)

                this.fireDataReload()
            },

            fireDataReload() {
                this.$emit("loaddata", this.query)
            },

            handleOnRowClick(row) {
                if (this.rowClickable) {
                    this.rowClickable(row)
                }
            },
        },
    }
</script>
