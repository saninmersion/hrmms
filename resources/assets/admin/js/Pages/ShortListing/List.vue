<template>
    <admin-layout :authorized="isAuthorized($page, 'shortlisting')" :page-title="trans('admin-general.modules.shortlist')">
        <template #breadcrumb>
            <bread-crumb>
                <bread-crumb-item :label="trans('admin-general.modules.shortlist')"/>
            </bread-crumb>
        </template>

        <div v-if="applicants.length > 0" class="downloads flex flex-wrap md:justify-end  gap-4 md:gap-8 mt-1 mb-6">
            <a :href="route('admin.shortlisting.export.scored', {...queries})" class="flex  text-primary-500 hover:opacity-90" target="_blank">
                <Icon name="download"/>
                <span class="block -mt-1">
                    Download Scored List
                </span>
            </a>
            <a :href="route('admin.shortlisting.export.shortlist', {...queries})" class="flex  text-primary-500 hover:opacity-90" target="_blank">
                <Icon name="download"/>
                <span class="block -mt-1">
                    Download Shortlist
                </span>
            </a>
        </div>

        <ShortListFilter :query="query" @filter="filters => loadData(filters)"/>

        <data-table
            :pagination="pagination"
            :queries="query"
            :show-filter="false"
            :table-data="applicants"
            class="applicant-table"
            @loaddata="loadData">
            <template #thead>
                <table-head-col v-text="`Candidate Detail`"/>
                <table-head-col v-text="`Gender`"/>
                <table-head-col v-text="`Age`"/>
                <table-head-col v-text="`Rank`"/>
                <table-head-col v-text="`Priority`"/>
                <table-head-col v-text="`Verified`"/>
                <table-head-col v-text="`Applied For`"/>
                <table-head-col v-text="`Actions`"/>
            </template>

            <template #tbody="{row: applicant}" class="text-base-3">
                <table-body-col no-wrap>
                    <div class="cursor-pointer" @click="showApplicant(applicant)">
                        <p class="whitespace-nowrap " v-text="applicant.name_in_nepali"/>
                        <p class="whitespace-nowrap " v-text="applicant.name_in_english.toUpperCase()"/>
                        <p class="whitespace-nowrap text-xs text-base-3">
                            {{ applicant.citizenship_number }} ({{ applicant.citizenship_issued_district }})
                        </p>
                        <p class="whitespace-nowrap text-xs text-base-3" v-text="formatDob(applicant.dob_bs)"/>
                    </div>
                </table-body-col>

                <table-body-col v-text="applicant.gender"/>
                <table-body-col v-text="applicant.age"/>
                <table-body-col v-text="applicant.rank"/>
                <table-body-col v-text="applicant.priority"/>
                <table-body-col v-text="applicant.verified"/>
                <table-body-col v-text="applicationForTranslated(getFromObject(applicant, 'application_for'))"/>
                <table-body-col>
                    <p>
                        <span v-if="isHired(applicant)" class="text-green-500">Hired</span>
                        <span v-else-if="isRejected(applicant)" class="text-danger-500">Rejected</span>
                        <danger-button v-else-if="isShortListed(applicant)" @click="unShortListApplicant(applicant)" v-text="`Unshortlist`"/>
                        <primary-button v-else-if="!isShortListed(applicant)" @click="shortListApplicant(applicant)" v-text="`ShortList`"/>
                    </p>
                </table-body-col>
            </template>

            <template #empty-message>
                <p>You must select district, local level and role to see the submitted applications for shortlisting.</p>
            </template>
        </data-table>
    </admin-layout>
</template>

<script type="text/ecmascript-6">
    import {
        BreadCrumb,
        BreadCrumbItem,
    }                      from "../../Components/BreadCrumb"
    import {
        DangerButton,
        PrimaryButton,
    }                      from "../../Components/Buttons"
    import {
        DataTable,
        TableBodyCol,
        TableHeadCol,
    }                      from "../../Components/DataTable"
    import Icon            from "../../Components/General/Icon"
    import AdminLayout     from "../../Layouts/AdminLayout"
    import ShortListFilter from "./partials/ShortListFilter"

    const HIRED = "hired"
    const REJECTED = "rejected"

    export default {
        name: "ShortListedApplicantList",

        components: {
            DangerButton,
            PrimaryButton,
            ShortListFilter,
            BreadCrumbItem,
            BreadCrumb,
            AdminLayout,
            DataTable,
            TableHeadCol,
            TableBodyCol,
            Icon,
        },

        props: {
            applicants: { type: Array, required: true },
            pagination: { type: Object, required: false, default: null },
            queries: { type: Object, required: false, default: () => ({}) },
            options: { type: Object, required: true },
        },

        data: () => ({
            query: {},
        }),

        remember: {
            data: ["query"],
            key: "ShortListing/List",
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
                if (query.role !== this.query.role || query.district !== this.query.district || query.municipality !== this.query.municipality) {
                    query.page = "1"
                }
                this.query = { ...this.query, ...query }

                if (!this.query.district || !this.query.municipality || !this.query.role) {
                    return
                }

                this.$inertia.visit(this.route("admin.shortlisting.index"), {
                    method: "get",
                    data: this.query,
                    preserveState: true,
                    preserveScroll: true,
                })
            },

            showApplicant(applicant) {
                if (!this.isShortListed(applicant)) {
                    return
                }
                this.$inertia.visit(this.route("admin.shortlisting.show", [applicant.id, this.query.role]), {
                    preserveState: true,
                    preserveScroll: true,
                })
            },

            unShortListApplicant(applicant) {
                this.$inertia.visit(this.route("admin.shortlisting.applicant.un-shortlist", applicant.id), {
                    method: "post",
                    data: { municipality: this.query.municipality, role: this.query.role },
                    preserveState: true,
                    preserveScroll: true,
                    only: ["applicants", "pagination"],
                })
            },
            shortListApplicant(applicant) {
                this.$inertia.visit(this.route("admin.shortlisting.applicant.shortlist", applicant.id), {
                    method: "post",
                    data: { municipality: this.query.municipality, role: this.query.role },
                    preserveState: true,
                    preserveScroll: true,
                    only: ["applicants", "pagination"],
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

            isShortListed(applicant) {
                return this.getFromObject(applicant, `is_shortlisted.${this.query.role}`, false)
            },
            isHired(applicant) {
                return this.getFromObject(applicant, `hiring_status.${this.query.role}`) === HIRED
            },
            isRejected(applicant) {
                return this.getFromObject(applicant, `hiring_status.${this.query.role}`) === REJECTED
            },
        },
    }
</script>
