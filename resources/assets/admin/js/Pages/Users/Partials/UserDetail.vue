<template>
    <div class="bg-white max-w-2xl shadow overflow-hidden sm:rounded-lg mx-auto">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-base-2" v-text="userDetail.name"/>
        </div>

        <div class="border-t border-gray-200">
            <dl class="bg-gray-50">
                <div class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-base-3">
                        Email:
                    </dt>
                    <dd class="mt-1 text-sm text-base-2 sm:mt-0 sm:col-span-2">
                        {{ userDetail.email }}
                    </dd>
                </div>

                <div class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-base-3">
                        Username:
                    </dt>
                    <dd class="mt-1 text-sm text-base-2 sm:mt-0 sm:col-span-2">
                        {{ userDetail.username }}
                    </dd>
                </div>

                <div class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-base-3">
                        Role
                    </dt>
                    <dd class="mt-1 text-sm text-base-2 sm:mt-0 sm:col-span-2">
                        {{ trans(`admin-users.roles.${userDetail.role}`) }}
                    </dd>
                </div>

                <div v-if="isDistrictStaff" class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-base-3">
                        {{ trans("application.district") }}
                    </dt>
                    <dd class="mt-1 text-sm text-base-2 sm:mt-0 sm:col-span-2">
                        {{ formatDistrict(userDetail.district) }}
                    </dd>
                </div>

                <div v-if="isSupervisor" class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-base-3">
                        {{ trans("application.census_office") }}
                    </dt>
                    <dd class="mt-1 text-sm text-base-2 sm:mt-0 sm:col-span-2">
                        {{ getFromObject(userDetail, "censusOffice.office_name") }}
                    </dd>
                </div>
            </dl>
        </div>
    </div>
</template>

<script>
    const ROLE_DISTRICT = "district_staffs"
    const ROLE_SUPERVISOR = "supervisors"

    export default {
        name: "UserDetail",

        props: {
            userDetail: { type: Object, required: true },
        },

        computed: {
            isDistrictStaff: function() {
                return this.userDetail.role === ROLE_DISTRICT
            },

            isSupervisor: function() {
                return this.userDetail.role === ROLE_SUPERVISOR
            },
        },

        methods: {
            formatDistrict(district) {
                if (!district) {
                    return ""
                }

                return district[this.$currentLocale === "en" ? "title_en" : "title_ne"]
            },
        },
    }
</script>
