<template>
    <div v-if="remainingTime !== 0" class="border-r pr-4" style="border-color: #ffffff33;">
        <!-- eslint-disable vue/no-v-html -->
        <p class="flex items-center justify-between text-days-remaining text-black-200 text-semibold md:text-white" v-html="remainingTime"/>
        <!--eslint-enable-->
    </div>
</template>

<script>
    export default {
        name: "DaysRemaining",
        props: {
            deadline: { type: String, required: true },
        },
        computed: {
            remainingTime() {
                const date1 = new Date(this.deadline).setHours(24)
                const date2 = new Date()

                const { days, hours, minutes } = this.diffInDays(date1, date2)

                if (days < 0 && hours < 0 && minutes < 0) {
                    return 0
                }

                if (days > 0) {
                    return `<span class="font-bold mr-2 text-3xl">${this.numberTrans(days)}</span> ${this.trans("general.Days Remaining")}`
                }

                if (hours > 0) {
                    return `<span class="font-bold mr-2 text-3xl">${this.numberTrans(hours)}</span> ${this.trans("general.Hours Remaining")}`
                }

                return `<span class="font-bold mr-2 text-3xl">${this.numberTrans(minutes)}</span> ${this.trans("general.Minutes Remaining")}`
            },
        },
        methods: {
            diffInDays(date1, date2) {
                const distance = date1 - date2

                const days = Math.floor(distance / (1000 * 60 * 60 * 24))
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60))
                const minutes = Math.ceil(distance / 1000 / 60)

                return {
                    days: days,
                    hours: hours,
                    minutes: minutes,
                }
            },
        },
    }
</script>

<style scoped>

</style>
