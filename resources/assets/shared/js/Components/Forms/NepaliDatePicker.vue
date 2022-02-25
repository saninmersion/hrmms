<template>
    <div class="grid grid-cols-3 gap-3">
        <label>
            <label-component v-if="showLabels"
                             :value="trans('application.year')"/>
            <drop-down-input v-model="year"
                             :show-placeholder="true"
                             :placeholder="trans('application.year')"
                             :options="yearOptions"
                             @input="handleOnSelect"/>
        </label>

        <label>
            <label-component v-if="showLabels"
                             :value="trans('application.month')"/>
            <drop-down-input v-model="month"
                             :show-placeholder="true"
                             :placeholder="trans('application.month')"
                             :options="monthOptions"
                             @input="handleOnSelect"/>
        </label>

        <label>
            <label-component v-if="showLabels"
                             :value="trans('application.day')"/>
            <drop-down-input v-model="day"
                             :show-placeholder="true"
                             :placeholder="trans('application.day')"
                             :options="dayOptions"
                             @input="handleOnSelect"/>
        </label>
    </div>
</template>

<script>
    import DropDownInput  from "./DropDownInput"
    import LabelComponent from "./Label"

    export default {
        name: "NepaliDatePicker",

        components: { LabelComponent, DropDownInput },

        props: {
            value: { type: String, required: false, default: null },
            type: { type: String, required: false, default: "bs" },
            showLabels: { type: Boolean, required: false, default: true },
            range: { type: Object, required: false, default: () => ({ ad: [1973, 2021], bs: [2030, 2078] }) },
        },

        data: () => ({
            year: null,
            month: null,
            day: null,
        }),

        watch: {
            value: {
                handler(date) {
                    this.splitDate(date)
                },
                immediate: true,
            },
        },

        computed: {
            yearOptions: function() {
                const options = new Map()
                const start = this.range[this.type][1]
                const end = this.range[this.type][0]

                for (let i = start; i >= end; i--) {
                    options.set(i, this.numberTrans(i))
                }

                return options
            },

            monthOptions: function() {
                const options = new Map()

                for (let i = 1; i <= 12; i++) {
                    options.set(i, this.trans(`general.months.${this.type}-${i}`))
                }

                return options
            },

            dayOptions: function() {
                const options = new Map()

                const max = this.type === "bs" ? 32 : 31

                for (let i = 1; i <= max; i++) {
                    options.set(i, this.numberTrans(i))
                }

                return options
            },
        },

        methods: {
            splitDate(date) {
                if (!date) {
                    this.year = null
                    this.month = null
                    this.day = null

                    return
                }

                const [year, month, day] = date.split("-")

                this.year = parseInt(year)
                this.month = parseInt(month)
                this.day = parseInt(day)
            },

            combineDate() {
                return `${this.year}-${this.month.toString().padStart(2, "0")}-${this.day.toString().padStart(2, "0")}`
            },

            handleOnSelect() {
                if (!this.year || !this.month || !this.day) {
                    return
                }

                this.$emit("input", this.combineDate())
            },
        },
    }
</script>
