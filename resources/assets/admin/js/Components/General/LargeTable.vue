<template>
    <div class="-mx-6 large-table table-wrapper overflow-x-auto overflow-y-hidden text-center leading-normal">
        <table>
            <thead class="bg-grey-2 text-left font-bold">
                <tr class="text-center">
                    <th style="min-width: 323px;" class="bg-grey-2  sticky left-0"/>
                    <th style="min-width: 260px;"
                        class="border-none px-3 p   y-4 text-center text-base-1 uppercase tracking-wider">
                        Total
                        <br>
                        <span class="border-r-2 px-1" v-text="'DW'"/>
                        <span class="border-r-2 px-1" v-text="'CDW'"/>
                        <span class="px-1" v-text="'CHH'"/>
                    </th>
                    <th v-for="date in dates"
                        :key="date"
                        style="min-width: 260px"
                        nowrap
                        class="border-none px-3 py-4 text-center text-base-1 uppercase tracking-wider"
                        v-text="date"/>
                </tr>
            </thead>
        </table>
        <Accordion>
            <slot/>
        </Accordion>
    </div>
</template>

<script>
    import Accordion from "../General/Accordion"

    export default {
        name: "Table",
        components: { Accordion },
        props: {
            dates: { type: Array, required: true },
        },
    }
</script>

<style lang="scss">
.large-table {
    tr {
        td:first-child, th:first-child {
            text-align: left;
            position: sticky;
            left: 0;
            min-width: 324px;
            padding-left: 16px;
        }
    }

    td {
        background: #fff;
    }

    tr.bg-primary-50 th {
        //background: #eff6ff;
        background: #fff;
    }

    tr.bg-grey-5 td {
        background: #F1FAFE;
    }

    .accordion__title {
        padding: 0 !important;
    }

    a:hover {
        opacity: unset;
    }
}
</style>
