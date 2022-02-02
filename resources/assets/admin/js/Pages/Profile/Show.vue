<template>
    <admin-layout :page-title="trans('user.Account Settings')" :authorized="true">
        <div class="py-4 px-4">
            <update-profile-information-form :user="$page.props.user"/>

            <section-border/>

            <update-password-form class="mt-10 sm:mt-0"/>
        </div>
    </admin-layout>
</template>

<script>
    import { SectionBorder }            from "../../../../shared/js/Components/Containers"
    import AdminLayout                  from "../../Layouts/AdminLayout"
    import UpdatePasswordForm           from "./UpdatePasswordForm"
    import UpdateProfileInformationForm from "./UpdateProfileInformationForm"

    export default {
        name: "AccountSetting",

        components: {
            AdminLayout,
            SectionBorder,
            UpdatePasswordForm,
            UpdateProfileInformationForm,
        },
    }
</script>
