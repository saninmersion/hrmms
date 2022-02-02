<template>
    <admin-layout :authorized="isAuthorized($page, 'verification')" page-title="Assigned Applications">
        <template #breadcrumb>
            <bread-crumb>
                <bread-crumb-item :href="route('admin.assigned-applications.index')" label="Assigned Applications"/>
                <bread-crumb-item label="List"/>
            </bread-crumb>
        </template>

        <div class="grid grid-cols-4 gap-8 stats-cards-wrapper">
            <div class="bg-grey-2 rounded  shadow text-center py-6 px-4">
                <h2 class="font-semibold text-3xl text-black">
                    {{ totalVerified }}
                </h2>
                <p>Total Verified</p>
            </div>
            <div class="bg-grey-2 rounded  shadow text-center py-6 px-4">
                <h2 class="font-semibold text-3xl text-black">
                    {{ getFromObject(stats, "okay.count") }}
                </h2>
                <p>Okay</p>
            </div>
            <div class="bg-grey-2 rounded  shadow text-center py-6 px-4">
                <h2 class="font-semibold text-3xl text-black">
                    {{ getFromObject(stats, "correction_needed.count") }}
                </h2>
                <p>Correction Needed</p>
            </div>
            <div class="bg-grey-2 rounded  shadow text-center py-6 px-4">
                <h2 class="font-semibold text-3xl text-black">
                    {{ getFromObject(stats, "recommended_for_rejection.count") }}
                </h2>
                <p>Recommended For Rejection</p>
            </div>
        </div>

        <application-verification-filters :query="query" @filter="filters => loadData(filters)"/>

        <template #actions/>

        <data-table
            :pagination="pagination"
            :queries="query"
            :row-clickable="handleRowClick"
            :show-filter="false"
            :table-data="applicants"
            class="applicant-table"
            @loaddata="loadData">
            <template #thead>
                <table-head-col nowrap>
                    Candidate Detail
                </table-head-col>
                <table-head-col>Gender</table-head-col>
                <table-head-col>Verification Status</table-head-col>
                <table-head-col nowrap>
                    Status
                </table-head-col>
                <table-head-col>Role</table-head-col>
                <table-head-col>Working District</table-head-col>
            </template>
            <template #settings>
                <span class="cursor-pointer" title="Reload" @click.prevent="loadData">
                    <icon name="reload"/>
                </span>
            </template>

            <template #tbody="{row: applicant}">
                <table-body-col no-wrap>
                    <p class="whitespace-nowrap" v-text="applicant.name"/>
                    <p class="whitespace-nowrap" v-text="applicant.name_in_english.toUpperCase()"/>
                    <p class="whitespace-nowrap text-xs text-gray-400">
                        {{ applicant.citizenship_number }} ({{ getFromObject(applicant.citizenship_issued_district, `title_${currentLocale}`) }})
                    </p>
                    <p class="whitespace-nowrap text-xs text-gray-400" v-text="formatDob(applicant.dob)"/>
                </table-body-col>
                <table-body-col v-text="trans(`application.gender-${applicant.gender}`)"/>
                <table-body-col
                    :class="{'text-success font-semibold' : applicant.verification_status === 'okay', 'text-pending font-semibold' : applicant.verification_status === 'correction_needed','text-warning font-semibold' : applicant.verification_status === 'recommended_for_rejection' }"
                    v-text="trans(`admin-application.verification_status-${applicant.verification_status}`)"/>
                <table-body-col nowrap>
                    <span v-if="!applicant.status" class="text-base-3 font-semibold">
                        {{ trans(`application.application-status.form_not_filled`) }}
                    </span>
                    <template v-else>
                        <p>
                            {{ trans(`application.application-status.${applicant.status}`) }}
                        </p>
                        <p class="text-xs">
                            {{ applicant.submission_number }}
                        </p>
                        <p :title="numberTrans(getFromObject(applicant.submitted_at,'raw'))" class="text-xs text-gray-400">
                            ({{ numberTrans(getFromObject(applicant.submitted_at, "diff")) }})
                        </p>
                    </template>
                </table-body-col>
                <table-body-col v-text="applicationForTranslated(getFromObject(applicant, 'application_for'))"/>
                <table-body-col no-wrap>
                    <p v-if="applicant.p1_district">
                        {{ getFromObject(applicant.p1_district, `title_${currentLocale}`) }} <span class="text-gray-400">(P1)</span>
                    </p>
                    <p v-if="applicant.p2_district">
                        {{ getFromObject(applicant.p2_district, `title_${currentLocale}`) }} <span class="text-gray-400">(P2)</span>
                    </p>
                </table-body-col>
            </template>
        </data-table>
    </admin-layout>
</template>

<script>
    import {
        BreadCrumb,
        BreadCrumbItem,
    }                                     from "../../Components/BreadCrumb"
    import {
        DataTable,
        TableBodyCol,
        TableHeadCol,
    }                                     from "../../Components/DataTable"
    import Icon                           from "../../Components/General/Icon"
    import AdminLayout                    from "../../Layouts/AdminLayout"
    import ApplicationVerificationFilters from "./Partials/ApplicationVerificationFilters"

    export default {
        name: "ApplicationVerificationList",
        components: { ApplicationVerificationFilters, AdminLayout, BreadCrumb, BreadCrumbItem, DataTable, TableBodyCol, TableHeadCol, Icon },
        props: {
            applicants: { type: Array, required: true },
            stats: { type: Object, required: true },
            pagination: { type: Object, required: false, default: () => ({}) },
            queries: { type: Object, required: false, default: () => ({}) },
            options: { type: Object, required: true },
        },

        data() {
            return {
                query: {},
            }
        },

        provide() {
            return {
                options: this.options,
            }
        },

        watch: {
            queries: {
                handler(queries) {
                    this.query = { ...queries }
                },
                immediate: true,
                deep: true,
            },
        },
        computed: {
            totalVerified() {
                return Object.values(this.stats).reduce((total, cur) => total + cur.count, 0);
            },
        },
        methods: {
            loadData(query) {
                this.$inertia.visit(this.route("admin.assigned-applications.index"), {
                    method: "get",
                    data: query,
                    preserveState: true,
                    preserveScroll: true,
                    only: ["applicants", "pagination", "queries"],
                })
            },
            applicationForTranslated(applicationFor) {
                if (applicationFor) {
                    return this.trans(`application.success-message-post.${applicationFor}`)
                }
                return ""
            },

            formatDob(dob) {
                return this.trans("application.date_of_birth") + ": " + this.numberTrans(dob) + " (" + this.trans("application.calendar_bs") + ")"
            },

            handleRowClick(applicant) {
                this.$inertia.visit(this.route("admin.assigned-applications.form", applicant.id))
            },
        },
    }
</script>

<style scoped>

</style>
