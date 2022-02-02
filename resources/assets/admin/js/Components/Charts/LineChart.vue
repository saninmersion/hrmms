<template>
    <apex-chart :options="chartOptions" :series="chartData" height="350" type="line"/>
</template>

<script>
    import VueApexCharts from "vue-apexcharts"

    export default {
        name: "LineChart",

        components: {
            apexChart: VueApexCharts,
        },

        props: {
            title: { type: String, required: false, default: "" },
            height: { type: Number, required: false, default: 300 },
            labels: { type: Array, required: true },
            colors: { type: Array, required: false, default: () => ["#3b82f6"] },
            chartData: { type: Array, required: true },
            tickAmount: { type: Number, default: null },
        },

        computed: {
            chartOptions() {
                return {
                    chart: {
                        fontFamily: "inherit",
                        toolbar: {
                            tools: {
                                download: true,
                                selection: false,
                                zoom: false,
                                zoomin: false,
                                zoomout: false,
                                pan: false,
                                reset: false,
                            },
                        },
                    },
                    xaxis: {
                        categories: this.labels,
                        tickAmount: this.tickAmount,
                        labels: {
                            style: {
                                fontSize: "13px",
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
