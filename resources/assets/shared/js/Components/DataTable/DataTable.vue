<template>
    <div class="datatable w-full user-list-page">
        <div class="my-2 flex sm:flex-row flex-col justify-between">
            <div v-if="showFilter" class="block relative">
                <span class="h-full absolute inset-y-0 left-0 flex items-center pl-2">
                    <svg class="h-4 w-4 fill-current text-gray-500" viewBox="0 0 24 24">
                        <path
                            d="M10 4a6 6 0 100 12 6 6 0 000-12zm-8 6a8 8 0 1114.32 4.906l5.387 5.387a1 1 0 01-1.414
                                1.414l-5.387-5.387A8 8 0 012 10z"/>
                    </svg>
                </span>
                <label>
                    <input v-model="query.search"
                           class="appearance-none rounded bg-white border border-black block pl-8 pr-6 py-2
                                w-full bg-white text-sm placeholder-gray-400 text-gray-700 focus:bg-white
                                focus:placeholder-gray-600 focus:text-gray-700 focus:outline-none  "
                           placeholder="Search"
                           type="search"
                           @input="handleOnChange">
                </label>
            </div>
        </div>

        <div class="-mx-4 sm:-mx-8 px-4 sm:px-8 py-4">
            <div class="block min-w-full shadow rounded-lg">
                <div class="flex gap-2 bg-white border-t pr-5  items-center  py-2">
                    <div v-if="showPaginationTop && paginationData"
                         class="px-5 flex flex-col xs:flex-row items-center xs:justify-between
                            rounded-t-md flex-1">
                        <pagination
                            :current-page="query.page"
                            :pagination="paginationData"
                            @pagechanged="onPageChange"/>
                    </div>
                    <div v-if="showLimitOption && rows.length" class="flex flex-row mb-1 sm:mb-0 h-10">
                        <div v-if="paginationData" class="relative">
                            <label>
                                <select
                                    v-model="query.per_page"
                                    class="cursor-pointer appearance-none h-full rounded   block appearance-none
                                w-full bg-white border border-black text-gray-700 py-2 px-4 pr-8 leading-tight
                                focus:outline-none focus:bg-white focus:border-gray-500 shadow"
                                    @change="handleOnChange">
                                    <option v-for="size in $page.props.staticData.paginate_sizes"
                                            :key="size"
                                            :selected="size === paginationData.per_page"
                                            :value="size"
                                            v-text="size"/>
                                </select>
                            </label>
                            <div
                                class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                <svg class="fill-current h-4 w-4"
                                     viewBox="0 0 20 20"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <slot v-if="rows.length" name="settings"/>
                </div>

                <div class="table-wrapper overflow-x-auto">
                    <table v-if="rows.length" class="w-full leading-normal text-gray-900 ">
                        <thead>
                            <tr>
                                <th v-if="hasSnRow"
                                    class="px-4 py-3 text-left bg-gray-300 text-xs
                                    font-bold text-secondary uppercase tracking-wider"
                                    v-text="'S.N.'"/>

                                <slot :column="tableColumns" name="thead">
                                    <th v-for="(label, key) in tableColumns"
                                        :key="`datatable-thead-th-${key}`"
                                        class="px-4 py-3 bg-gray-300 text-left  text-xs
                                        font-bold text-secondary uppercase tracking-wider"
                                        v-text="label"/>
                                </slot>
                            </tr>
                        </thead>

                        <tbody>
                            <tr v-for="(row, index) in rows"
                                :key="`datatable-tbody-${new Date().getDate() + index}`">
                                <td v-if="hasSnRow"
                                    class="px-4 py-3 border-b border-gray-200   text-sm"
                                    v-text="index + 1 + (paginationData && (paginationData.per_page * (paginationData.current_page - 1)))"/>

                                <slot :index="index" :row="row" name="tbody">
                                    <td v-for="(label_, key) in tableColumns"
                                        :key="`datatable-tbody-td-${new Date().getDate() + key}`"
                                        class="px-4 py-3 border-b border-gray-200   text-sm"
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
                     class="px-5   bg-white border-t flex flex-col xs:flex-row items-center xs:justify-between
                            rounded-b-md">
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
    import {
        isArray,
        isObject,
    }                  from "./../../../../shared/js/Helpers"
    import EmptyData   from "./EmptyData"
    import Pagination  from "./Pagination"

    export default {
        name: "DataTable",

        components: { EmptyData, Pagination },

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

            "queries.page": {
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
                this.$set(this.queries, "page", page)

                this.fireDataReload()
            },

            handleOnChange() {
                this.$set(this.queries, "page", 1)

                this.fireDataReload()
            },

            fireDataReload() {
                this.$emit("loaddata", this.queries)
            },
        },
    }
</script>

<style lang="scss">
    .table-wrapper {
        min-height: calc(100vh - 390px);
        background: #ffffff;
    }

    .datatable table tr th:first-child {
        width: 70px;
    }

    .datatable table tr th:nth-child(2) {
        width: 250px;
    }

    tr td ul.multiselect__content li.multiselect__element span {
        font-size: 12px;
    }

    .user-list-page {
        table tr td {
            border-bottom: 1px solid #e0e0e0;
            border-right: 1px solid #e0e0e0;

        }

        table th {
            border-bottom: 1px solid #e0e0e0;
            border-right: 1px solid #cccccc;
        }
    }
</style>
