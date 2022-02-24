<template>
    <chart-wrapper title="Overall Application Status">
        <bar-chart v-if="overallStats"
                   :chart-data="stats"
                   :colors="['#00E396', '#FEB019']"
                   :labels="labels"/>
    </chart-wrapper>
</template>

<script>
    import {
        BarChart,
        ChartWrapper,
    } from "../../../Components/Charts"

    export default {
        name: "OverallApplicationStatusChart",
        components: {
            BarChart,
            ChartWrapper,
        },
        props: {
            overallStats: { type: Object, required: false, default: () => ({}) },
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
                Object.entries(this.overallStats).forEach(([key, value]) => {
                    delete value.na
                    stats.push({
                        name: this.trans(`application.application-status.${key}`),
                        data: [value.enumerator, value.supervisor, value.enumerator_supervisor],
                    })
                })

                return stats
            },
        },
    }
</script>

<style scoped>

</style>
