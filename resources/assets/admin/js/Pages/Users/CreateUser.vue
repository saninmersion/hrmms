<template>
    <admin-layout :page-title="trans('admin-users.create-user')"
                  :authorized="isAuthorized($page, 'user')"
                  :back-url="route('admin.users.index')">
        <template #breadcrumb>
            <bread-crumb>
                <bread-crumb-item :href="route('admin.users.index')" :label="trans('admin-general.modules.user-mgmt')"/>
                <bread-crumb-item :label="trans('admin-users.create-user')"/>
            </bread-crumb>
        </template>

        <form-view @submitted="createUser">
            <user-form :form="form"/>

            <template #actions>
                <div>
                    <cancel-button :href="route('admin.users.index')">
                        Cancel
                    </cancel-button>

                    <primary-button type="submit" class="mx-4">
                        Create
                    </primary-button>

                    <action-message :on="form.recentlySuccessful" class="mr-3">
                        Created.
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
        name: "CreateUser",

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
            roles: { type: Array, required: true },
            districts: { type: Array, required: true },
            censusOffices: { type: Array, required: true },
        },

        data() {
            return {
                form: this.$inertia.form({
                    name: "",
                    email: "",
                    username: "",
                    password: "",
                    role: "",
                    district: null,
                    census_office: null,
                }, {
                    bag: "userCreateForm",
                    resetOnSuccess: true,
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
            createUser() {
                this.form.post(this.route("admin.users.store"))
            },
        },
    }
</script>
