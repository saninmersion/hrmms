<template>
    <chart-wrapper title="Applications Submission Trend">
        <line-chart v-if="dailyTrend"
                    :chart-data="stats"
                    :labels="labels"
                    class="-mx-4"
                    :tick-amount="10"/>
    </chart-wrapper>
</template>

<script>

    import {
        ChartWrapper,
        LineChart,
    } from "../../../Components/Charts"

    export default {
        name: "ApplicationsSubmittedTrend",

        components: {
            ChartWrapper,
            LineChart,
        },

        props: {
            dailyTrend: { type: Object, required: false, default: () => ({}) },
        },

        computed: {
            formattedDailyTrend() {
                return Object.keys(this.dailyTrend).reduce((stats, date) => {
                    const cur = {
                        enumerator: this.getFromObject(this.dailyTrend[date], "enumerator", 0),
                        supervisor: this.getFromObject(this.dailyTrend[date], "supervisor", 0),
                        enumerator_supervisor: this.getFromObject(this.dailyTrend[date], "enumerator_supervisor", 0),
                    }
                    return { ...stats, [date]: cur }
                }, {})
            },

            labels() {
                return Object.keys(this.formattedDailyTrend)
            },
            stats() {
                return [
                    { name: this.trans("application.success-message-post.enumerator"), data: this.getValuesByType("enumerator") },
                    { name: this.trans("application.success-message-post.supervisor"), data: this.getValuesByType("supervisor") },
                    { name: this.trans("application.success-message-post.enumerator_supervisor"), data: this.getValuesByType("enumerator_supervisor") },
                    { name: "Total", data: this.getTotalValues() },
                ]
            },
        },
        methods: {
            getValuesByType(type) {
                return Object.values(this.formattedDailyTrend).map(a => a[type])
            },

            getTotalValues() {
                return Object.values(this.formattedDailyTrend).map(a => this.getRowTotal(a))
            },

            getRowTotal: function(row) {
                return (row.enumerator || 0) + (row.supervisor || 0) + (row.enumerator_supervisor || 0)
            },

            getTotalForPost: function(post) {
                return Object.values(this.formattedDailyTrend).reduce((acc, row) => {
                    return acc + this.getFromObject(row, post, 0)
                }, 0)
            },
        },
    }
</script>
