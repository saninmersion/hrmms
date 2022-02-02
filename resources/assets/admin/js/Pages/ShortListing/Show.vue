<template>
    <admin-layout page-title="Shortlisted Applicant Detail"
                  :authorized="isAuthorized($page, 'district_shortlisting')"
                  :back-url="route('admin.shortlisting.index')">
        <template #breadcrumb>
            <bread-crumb>
                <bread-crumb-item :href="route('admin.shortlisting.index')" label="Shortlist"/>
                <bread-crumb-item label="Application Detail"/>
            </bread-crumb>
        </template>

        <div class="hiring-preview md:flex gap-6">
            <div class="md:w-3/5">
                <shortlisting-status :applicant="applicant"/>
                <applicant-submission :applicant="applicant"/>
                <div class="data-block-position">
                    <applicant-position :applicant="applicant"/>
                </div>
                <applicant-personal :applicant="applicant"/>
                <short-list-detail-qualification :applicant="applicant"/>
            </div>
            <div class="mt-6 md:mt-0 md:w-2/5">
                <hiring-status-form class="md:sticky top-6" :applicant="applicant"/>
            </div>
        </div>
    </admin-layout>
</template>

<script>
    import {
        BreadCrumb,
        BreadCrumbItem,
    }                                   from "../../Components/BreadCrumb"
    import AdminLayout                  from "../../Layouts/AdminLayout"
    import ApplicantPersonal            from "../Applicants/Partials/ApplicantPersonal"
    import ApplicantPosition            from "../Applicants/Partials/ApplicantPosition"
    import ApplicantSubmission          from "../Applicants/Partials/ApplicantSubmission"
    import HiringStatusForm             from "./partials/HiringStatusForm"
    import ShortListDetailQualification from "./partials/ShortListDetailQualification"
    import ShortlistingStatus           from "./partials/ShortlistingStatus"

    export default {
        name: "ShortlistedApplicantDetail",
        components: { ShortListDetailQualification, HiringStatusForm, ShortlistingStatus, ApplicantPersonal, ApplicantPosition, ApplicantSubmission, BreadCrumb, BreadCrumbItem, AdminLayout },
        props: {
            applicant: { type: Object, required: true },
            options: { type: Object, required: true },
            role: { type: String, required: true },
        },
        provide() {
            return {
                options: this.options,
                role: this.role,
            }
        },
    }
</script>
