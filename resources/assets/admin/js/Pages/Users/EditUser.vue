<template>
    <admin-layout :page-title="'Update User'"
                  :authorized="isAuthorized($page, 'user')"
                  :back-url="route('admin.users.show', userDetail.id)">
        <template #breadcrumb>
            <bread-crumb>
                <bread-crumb-item :href="route('admin.users.index')" :label="trans('admin-general.modules.user-mgmt')"/>
                <bread-crumb-item :label="'Update User'"/>
            </bread-crumb>
        </template>

        <form-view @submitted="updateUser">
            <user-form :form="form" :is-edit="true"/>

            <template #actions>
                <div class="flex gap-8">
                    <cancel-button :href="route('admin.users.show', userDetail.id)">
                        Cancel
                    </cancel-button>

                    <primary-button type="submit">
                        Update
                    </primary-button>

                    <action-message :on="form.recentlySuccessful">
                        Updated.
                    </action-message>
                </div>
            </template>
        </form-view>
    </admin-layout>
</template>

<script>
    import ActionMessage from "../../Components/ActionMessage"
    import {
        BreadCrumb,
        BreadCrumbItem,
    }                    from "../../Components/BreadCrumb"
    import {
        CancelButton,
        PrimaryButton,
    }                    from "../../Components/Buttons"
    import FormView      from "../../Components/Containers/FormView"
    import AdminLayout   from "../../Layouts/AdminLayout"
    import UserForm      from "./Partials/UserForm"

    export default {
        name: "EditUser",

        components: {
            ActionMessage,
            PrimaryButton,
            CancelButton,
            FormView,
            AdminLayout,
            BreadCrumb,
            BreadCrumbItem,
            UserForm,
        },

        props: {
            userDetail: { type: Object, required: true },
            roles: { type: Array, required: true },
            districts: { type: Array, required: true },
            censusOffices: { type: Array, required: true },
        },

        data() {
            return {
                form: this.$inertia.form({
                    name: this.userDetail.name,
                    email: this.userDetail.email,
                    username: this.userDetail.username,
                    role: this.userDetail.role,
                    district: this.userDetail.district_code,
                    census_office: this.userDetail.census_office_id,
                }, {
                    bag: "userUpdateForm",
                    resetOnSuccess: false,
                }),
            }
        },

        provide() {
            return {
                districts: this.districts,
                roles: this.roles,
                censusOffices: this.censusOffices,
            }
        },

        methods: {
            updateUser() {
                this.form.put(this.route("admin.users.update", this.userDetail.id))
            },
        },
    }
</script>
