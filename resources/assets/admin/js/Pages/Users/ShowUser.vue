<template>
    <admin-layout page-title="User Detail"
                  :authorized="isAuthorized($page, 'user')"
                  :back-url="route('admin.users.index')">
        <template #breadcrumb>
            <bread-crumb>
                <bread-crumb-item :href="route('admin.users.index')" :label="trans('admin-general.modules.user-mgmt')"/>
                <bread-crumb-item label="User Detail"/>
            </bread-crumb>
        </template>

        <template #actions>
            <secondary-button v-if="isAuthorized($page, 'user')"
                              :href="route('admin.users.edit', userDetail.id)"
                              class="!text-primary-500 uppercase py-2 px-4 bg-white flex items-center rounded !font-normal cursor-pointer hover:bg-white">
                <Icon name="edit"/>
                Edit
            </secondary-button>
            <danger-button v-if="isAuthorized($page, 'user')"
                           class="uppercase py-2 px-4 !bg-white text-warning flex  items-center rounded ml-4 !font-normal"
                           @click.prevent="handleUserDelete">
                <Icon name="delete"/>
                Deactivate
            </danger-button>
        </template>

        <div class="w-full py-2">
            <user-detail :user-detail="userDetail"/>

            <change-password :user-detail="userDetail"/>
        </div>
    </admin-layout>
</template>

<script>
    import BreadCrumb     from "../../Components/BreadCrumb/BreadCrumb"
    import BreadCrumbItem from "../../Components/BreadCrumb/BreadCrumbItem"
    import {
        DangerButton,
        SecondaryButton,
    }                     from "../../Components/Buttons"
    import AdminLayout    from "../../Layouts/AdminLayout"
    import Icon           from "./../../Components/General/Icon"
    import ChangePassword from "./Partials/ChangePassword"
    import UserDetail     from "./Partials/UserDetail"

    export default {
        name: "ShowUser",

        components: {
            DangerButton,
            SecondaryButton,
            ChangePassword,
            UserDetail,
            BreadCrumbItem,
            BreadCrumb,
            AdminLayout,
            Icon,
        },

        props: {
            userDetail: { type: Object, required: true },
        },

        methods: {
            handleUserDelete() {
                this.$inertia.delete(this.route("admin.users.destroy", this.userDetail.id))
            },
        },
    }
</script>
