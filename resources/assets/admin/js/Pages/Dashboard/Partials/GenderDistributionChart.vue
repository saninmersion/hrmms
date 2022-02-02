<template>
    <chart-wrapper title="Gender Distribution of Submitted Applicants">
        <bar-chart v-if="genderDistribution"
                   :chart-data="stats"
                   :labels="labels"
                   :stacked="true"/>
    </chart-wrapper>
</template>

<script>
    import {
        BarChart,
        ChartWrapper,
    } from "../../../Components/Charts"

    export default {
        name: "GenderDistributionChart",

        components: { BarChart, ChartWrapper },

        props: {
            genderDistribution: { type: Object, required: false, default: () => ({}) },
        },
        computed: {
            labels() {
                return [
                    this.trans("application.success-message-post.enumerator"),
                    this.trans("application.success-message-post.supervisor"),
                    this.trans("application.success-message-post.enumerator_supervisor"),
                ]
            },
            stats() {
                const stats = []
                Object.entries(this.genderDistribution).forEach(([key, value]) => {
                    stats.push({
                        name: this.trans(`application.gender-${key}`),
                        data: [value.enumerator, value.supervisor, value.enumerator_supervisor],
                    })
                })
                return stats
            },
        },
    }
</script>
