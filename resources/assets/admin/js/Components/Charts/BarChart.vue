<template>
    <apex-chart :options="chartOptions" :series="chartData" type="bar" class="-mx-4"/>
</template>

<script>
    import VueApexCharts from "vue-apexcharts"

    export default {
        name: "BarChart",

        components: {
            apexChart: VueApexCharts,
        },

        props: {
            title: { type: String, required: false, default: "" },
            height: { type: Number, required: false, default: 300 },
            labels: { type: Array, required: true },
            colors: { type: Array, required: false, default: () => ["#3b82f6"] },
            chartData: { type: Array, required: true },
            stacked: { type: Boolean, default: false },
        },

        computed: {
            chartOptions() {
                return {
                    chart: {
                        fontFamily: "inherit",
                        stacked: this.stacked,
                    },
                    xaxis: {
                        categories: this.labels,
                        labels: {
                            style: {
                                fontSize: "14px",
                            },
                        },
                    },
                    yaxis: {
                        labels: {
                            style: {
                                fontSize: "14px",
                            },
                        },
                    },
                    legend: {
                        fontSize: "13px",
                        itemMargin: {
                            horizontal: 8,
                        },
                    },
                }
            },
        },
    }
</script>
