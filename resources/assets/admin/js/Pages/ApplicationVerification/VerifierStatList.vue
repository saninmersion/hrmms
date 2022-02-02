<template>
    <admin-layout :authorized="isAuthorized($page, 'user')"
                  :back-url="route('admin.dashboard')"
                  :page-title="'Verifiers Stats'">
        <div class="downloads flex flex-wrap md:justify-end  gap-4 md:gap-8 mt-1 ">
            <a :href="downloads.correction_needed_export_url" class="flex  text-primary-500 hover:opacity-90" target="_blank">
                <Icon name="download"/>
                <span class="block -mt-1">
                    Download Correction Needed List
                </span>
            </a>
            <a :href="downloads.rejection_export_url" class="flex  text-primary-500 hover:opacity-90" target="_blank">
                <Icon name="download"/>
                <span class="block -mt-1">
                    Download Recommended For Rejection List
                </span>
            </a>
        </div>
        <div class="grid md:grid-cols-4 gap-8 mt-7">
            <div class="bg-grey-2 rounded  shadow text-center py-6 px-4">
                <h2 class="font-semibold text-3xl text-black">
                    {{ totalVerified }}
                </h2>
                <p>Total</p>
            </div>
            <div class="bg-grey-2 rounded  shadow text-center py-6 px-4">
                <h2 class="font-semibold text-3xl text-black">
                    {{ totalOkay }}
                </h2>
                <p>Okay</p>
            </div>
            <div class="bg-grey-2 rounded  shadow text-center py-6 px-4">
                <h2 class="font-semibold text-3xl text-black">
                    {{ totalCorrectionNeeded }}
                </h2>
                <p>Correction Needed</p>
            </div>
            <div class="bg-grey-2 rounded  shadow text-center py-6 px-4">
                <h2 class="font-semibold text-3xl text-black">
                    {{ totalRFR }}
                </h2>
                <p>Recommended For Rejection</p>
            </div>
        </div>
        <div class="table-wrapper overflow-x-auto mt-6 shadow-md rounded-md">
            <table class="w-full leading-normal  ">
                <thead class="bg-grey-2 text-left font-bold">
                    <tr>
                        <th class="bg-grey-2 sticky left-0"/>
                        <th class="px-3 py-4 text-center text-base-1 uppercase tracking-wider">
                            Total Assigned
                        </th>
                        <th class="px-3 py-4 text-center text-base-1 uppercase tracking-wider">
                            Ok
                        </th>
                        <th class="px-3 py-4 text-center text-base-1 uppercase tracking-wider">
                            Correction needed
                        </th>
                        <th class="px-3 py-4 text-center text-base-1 uppercase tracking-wider">
                            Recommended for rejection
                        </th>
                        <th class="px-3 py-4 text-center text-base-1 uppercase tracking-wider">
                            Total Verified
                        </th>
                        <th v-for="date in dateLabels" :key="'heading_'+date" class="px-3 py-4 text-base-1 text-sm uppercase tracking-wider">
                            {{ date }}
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(statByUser, userId) in stats"
                        :key="userId"
                        class="text-center overflow-x-scroll cursor-pointer"
                        @click.prevent="handleRowClick(userId)">
                        <td class="px-4 py-3 border-b text-left sticky left-0 bg-white" nowrap>
                            {{ getVerifierName(userId) }}
                        </td>
                        <td class="px-4 py-3 border-b ">
                            {{ getAssignedTotal(userId) }}
                        </td>
                        <td class="px-4 py-3 border-b ">
                            {{ getStatusTotal(userId, "okay") }}
                        </td>
                        <td class="px-4 py-3 border-b ">
                            {{ getStatusTotal(userId, "correction_needed") }}
                        </td>
                        <td class="px-4 py-3 border-b ">
                            {{ getStatusTotal(userId, "recommended_for_rejection") }}
                        </td>
                        <td class="px-4 py-3 border-b ">
                            {{ getUserTotal(userId) }}
                        </td>
                        <td v-for="(date) in dateLabels"
                            :key="date"
                            class="px-4 py-3 border-b ">
                            {{ getTotal(getFromObject(statByUser, date, {})) }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </admin-layout>
</template>

<script>
    import Icon        from "../../../../admin/js/Components/General/Icon"
    import AdminLayout from "../../Layouts/AdminLayout"

    export default {
        name: "VerifierStatList",
        components: { Icon, AdminLayout },

        props: {
            stats: { type: Object, required: true, default: () => {} },
            totalAssigned: { type: Array, required: true, default: () => [] },
            verifiers: { type: Array, required: true, default: () => [] },
            downloads: { type: Object, required: true, default: () => {} },
        },

        computed: {
            dateLabels() {
                const labels = []
                Object.values(this.stats).forEach((statByUser) => {
                    Object.keys(statByUser).forEach(date => {
                        if (!labels.includes(date)) {
                            labels.push(String(date))
                        }
                    })
                })

                return labels.sort()
            },
            totalVerified() {
                return this.totalOkay + this.totalCorrectionNeeded + this.totalRFR
            },
            totalOkay() {
                return Object.keys(this.stats).reduce((total, userId) => {
                    return total + this.getStatusTotal(userId, "okay")
                }, 0)
            },
            totalCorrectionNeeded() {
                return Object.keys(this.stats).reduce((total, userId) => {
                    return total + this.getStatusTotal(userId, "correction_needed")
                }, 0)
            },
            totalRFR() {
                return Object.keys(this.stats).reduce((total, userId) => {
                    return total + this.getStatusTotal(userId, "recommended_for_rejection")
                }, 0)
            },
        },
        methods: {
            getTotal(object) {
                return Object.values(object).reduce((total, num) => {
                    return total + num
                }, 0)
            },
            getAssignedTotal(userId) {
                const curUser = this.totalAssigned.find(x => parseInt(x.verifier_id) === parseInt(userId))

                return curUser ? curUser.verification_count : 0
            },
            getStatusTotal(userId, status) {
                const stat = this.stats[userId] || {}

                return Object.values(stat).reduce((total, statByDate) => {
                    return total + this.getFromObject(statByDate, status, 0)
                }, 0)
            },
            getUserTotal(userId) {
                const stat = this.stats[userId] || {}

                return Object.values(stat).reduce((total, statByDate) => {
                    return total + this.getTotal(statByDate)
                }, 0)
            },
            getVerifierName(userId) {
                const user = this.verifiers.find(v => v.id === parseInt(userId))
                return user ? user.name : ""
            },
            handleRowClick(userId) {
                this.$inertia.visit(this.route("admin.verifications.user", userId))
            },
        },
    }
</script>

<style scoped>

</style>
