<template>
    <div class="grid grid-cols-3 gap-3">
        <label>
            <label-component v-if="showLabels"
                             :value="trans('application.year')"/>
            <span v-text="year"/>
        </label>

        <label>
            <label-component v-if="showLabels"
                             :value="trans('application.month')"/>
            <span v-text="month"/>
        </label>

        <label>
            <label-component v-if="showLabels"
                             :value="trans('application.day')"/>
            <span v-text="day"/>
        </label>
    </div>
</template>

<script>
    import { englishToNepaliNumber } from "nepali-number"
    import LabelComponent            from "./Label"

    export default {
        name: "NepaliDatePickerPreview",

        components: { LabelComponent },

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

        methods: {
            splitDate(date) {
                if (!date) {
                    this.year = null
                    this.month = null
                    this.day = null

                    return
                }

                const [year, month, day] = date.split("-")

                this.year = englishToNepaliNumber(parseInt(year))
                this.month = englishToNepaliNumber(parseInt(month))
                this.day = englishToNepaliNumber(parseInt(day))
            },
        },
    }
</script>
