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
            colors: {
                type: Array,
                required: false,
                default: () => [
                    "#008FFB", "#00E396", "#FEB019", "#FF4560", "#775DD0",
                    "#3F51B5", "#546E7A", "#D4526E", "#8D5B4C", "#F86624",
                    "#D7263D", "#1B998B", "#2E294E", "#F46036", "#E2C044",
                ],
            },
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
                    colors: this.colors,
                }
            },
        },
    }
</script>
