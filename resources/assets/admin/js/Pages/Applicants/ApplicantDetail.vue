<template>
    <admin-layout page-title="Application Detail"
                  :authorized="isAuthorized($page, 'applicants')"
                  :back-url="route('admin.applications.index')">
        <template #breadcrumb>
            <bread-crumb>
                <bread-crumb-item :href="route('admin.applications.index')" label="Applications"/>
                <bread-crumb-item label="Application Detail"/>
            </bread-crumb>
        </template>

        <div class="w-full">
            <applicant-submission :applicant="applicant"/>
            <applicant-position :applicant="applicant"/>
            <applicant-personal :applicant="applicant"/>
            <applicant-qualification :applicant="applicant"/>
        </div>
    </admin-layout>
</template>

<script>
    import {
        BreadCrumb,
        BreadCrumbItem,
    }                             from "../../Components/BreadCrumb"
    import AdminLayout            from "../../Layouts/AdminLayout"
    import ApplicantPersonal      from "./Partials/ApplicantPersonal"
    import ApplicantPosition      from "./Partials/ApplicantPosition"
    import ApplicantQualification from "./Partials/ApplicantQualification"
    import ApplicantSubmission    from "./Partials/ApplicantSubmission"

    export default {
        name: "ShowApplicant",
        components: { ApplicantQualification, ApplicantPersonal, ApplicantPosition, ApplicantSubmission, BreadCrumb, BreadCrumbItem, AdminLayout },
        props: {
            applicant: { type: Object, required: true },
        },
    }
</script>
