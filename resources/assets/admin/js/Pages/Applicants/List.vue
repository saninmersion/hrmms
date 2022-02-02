<template>
    <admin-layout page-title="Applications" :authorized="isAuthorized($page, 'applicants')">
        <template #breadcrumb>
            <bread-crumb>
                <bread-crumb-item :href="route('admin.applications.index')" label="Applications"/>
                <bread-crumb-item label="List"/>
            </bread-crumb>
        </template>

        <applicant-filters :query="query" @filter="filters => loadData(filters)"/>

        <template #actions/>

        <data-table :pagination="pagination"
                    :queries="query"
                    :show-filter="false"
                    :table-data="applicants"
                    :row-clickable="handleRowClick"
                    class="applicant-table"
                    @loaddata="loadData">
            <template #thead>
                <table-head-col nowrap>
                    Candidate Detail
                </table-head-col>
                <table-head-col>Gender</table-head-col>
                <table-head-col>Mobile Number</table-head-col>
                <table-head-col nowrap>
                    Status
                </table-head-col>
                <table-head-col>Role</table-head-col>
                <table-head-col>Working District</table-head-col>
            </template>
            <template #settings>
                <span class="cursor-pointer" title="Reload" @click.prevent="loadData">
                    <icon name="reload" class="!mr-0 fill-current"/>
                </span>
            </template>
            <template #tbody="{row: applicant}">
                <table-body-col no-wrap>
                    <p v-text="applicant.name"/>
                    <p v-text="applicant.name_in_english.toUpperCase()"/>
                    <p class="text-xs text-base-3">
                        {{ applicant.citizenship_number }} ({{ getFromObject(applicant.citizenship_issued_district, `title_${currentLocale}`) }})
                    </p>
                    <p class="text-xs text-base-3" v-text="formatDob(applicant.dob)"/>
                </table-body-col>
                <table-body-col v-text="trans(`application.gender-${applicant.gender}`)"/>
                <table-body-col class="text-base-3" v-text="numberTrans(applicant.mobile_number)"/>
                <table-body-col nowrap>
                    <span v-if="!applicant.status" class="text-base-3 font-semibold">
                        {{ trans(`application.application-status.form_not_filled`) }}
                    </span>
                    <span v-else-if="isDraft(applicant.status)">
                        <p class="text-pending font-semibold"> {{ trans(`application.application-status.draft`) }} </p>
                        <p class="text-xs text-base-3" :title="numberTrans(getFromObject(applicant.created_at,'raw'))">({{ numberTrans(getFromObject(applicant.created_at,"diff")) }})</p>
                    </span>
                    <span v-else>
                        <p class="text-success font-semibold"> {{ trans(`application.application-status.${applicant.status}`) }}</p>
                        <p class="text-xs">{{ applicant.submission_number }}</p>
                        <p class="text-xs text-base-3" :title="numberTrans(getFromObject(applicant.submitted_at,'raw'))">({{ numberTrans(getFromObject(applicant.submitted_at,"diff")) }})</p>
                    </span>
                    <span v-if="applicant.is_offline" class="text-xs inline-block py-1 px-2 rounded-full text-gray-600 bg-gray-200 uppercase">
                        offline
                    </span>
                </table-body-col>
                <table-body-col v-text="applicationForTranslated(getFromObject(applicant, 'application_for'))"/>
                <table-body-col no-wrap>
                    <p v-if="applicant.p1_district">
                        {{ getFromObject(applicant.p1_district, `title_${currentLocale}`) }} <span class="text-base-3">(P1)</span>
                    </p>
                    <p v-if="applicant.p2_district">
                        {{ getFromObject(applicant.p2_district, `title_${currentLocale}`) }} <span class="text-base-3">(P2)</span>
                    </p>
                </table-body-col>
            </template>
        </data-table>
    </admin-layout>
</template>

<script type="text/ecmascript-6">
    import {
        BreadCrumb,
        BreadCrumbItem,
    }                       from "../../Components/BreadCrumb"
    import {
        DataTable,
        TableBodyCol,
        TableHeadCol,
    }                       from "../../Components/DataTable"
    import Icon             from "../../Components/General/Icon"
    import AdminLayout      from "../../Layouts/AdminLayout"
    import ApplicantFilters from "./Partials/ApplicantFilters"

    export default {
        name: "List",

        components: {
            Icon,
            ApplicantFilters,
            DataTable,
            TableHeadCol,
            TableBodyCol,
            AdminLayout,
            BreadCrumbItem,
            BreadCrumb,
        },
        props: {
            applicants: { type: Array, required: true },
            pagination: { type: Object, required: true },
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

        methods: {
            loadData(query) {
                this.$inertia.visit(this.route("admin.applications.index"), {
                    method: "get",
                    data: query,
                    preserveState: true,
                    preserveScroll: true,
                    only: ["applicants", "pagination", "queries"],
                })
            },

            isDraft(status) {
                return (status === "draft")
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
                this.$inertia.visit(this.route("admin.applications.show", applicant.id))
            },
        },
    }
</script>
