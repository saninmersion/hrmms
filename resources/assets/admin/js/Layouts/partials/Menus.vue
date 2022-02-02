<template>
    <fragment>
        <fragment v-for="(menu, index) in menus" :key="index">
            <template v-if="isAuthorized(menu)">
                <!-- Divider -->
                <hr class="my-4 md:min-w-full border-gray-700">

                <!-- Heading -->
                <h6
                    class="md:min-w-full text-gray-400  font-medium block pt-4 pb-2 no-underline"
                    v-text="trans(menu.group)"/>

                <!-- Navigation -->
                <ul class="md:flex-col md:min-w-full flex flex-col list-none">
                    <li v-for="(item, itemIndex) in menu.items" :key="itemIndex" class="items-center">
                        <inertia-link
                            v-if="isAuthorized(item)"
                            :class="route().current(item.pattern) ? 'text-primary-400' : 'text-gray-200 hover:text-gray-300'"
                            :href="route(item.route)"
                            class="flex text-sm uppercase py-3 font-medium block">
                            <i :class="item.icon" class="fas mr-2 text-sm opacity-75"/>
                            <span class="inline-block -mt-1">{{ trans(item.label) }}</span>
                        </inertia-link>
                    </li>
                </ul>
            </template>
        </fragment>
    </fragment>
</template>

<script type="text/ecmascript-6">
    export default {
        name: "Menus",

        data: () => ({
            menus: [
                {
                    group: "admin-general.modules.hr-module",
                    can: "hr_module",
                    items: [
                        {
                            label: "admin-general.modules.dashboard",
                            icon: "fa-tv",
                            route: "admin.dashboard",
                            pattern: "admin.dashboard",
                            can: "dashboard",
                        },
                        {
                            label: "admin-general.modules.district-dashboard",
                            icon: "fa-table",
                            route: "admin.dashboard.districts",
                            pattern: "admin.dashboard.districts",
                            can: "district_dashboard",
                        },
                        {
                            label: "admin-general.modules.applications",
                            icon: "fa-users",
                            route: "admin.applications.index",
                            pattern: "admin.applications.index",
                            can: "applicants",
                        },
                        {
                            label: "admin-general.modules.offline-application",
                            icon: "fa-users",
                            route: "admin.applications.offline-form.check.show",
                            pattern: "admin.applications.offline-form.*",
                            can: "offline_application",
                        },
                        {
                            label: "admin-general.modules.offline-list",
                            icon: "fa-users",
                            route: "admin.applications.offline.index",
                            pattern: "admin.applications.offline.index",
                            can: "offline_index",
                        },
                        {
                            label: "admin-general.modules.export-applications",
                            icon: "fa-file-excel",
                            route: "admin.applications.exports.index",
                            pattern: "admin.applications.exports.index",
                            can: "export",
                        },
                        // {
                        //     label: "admin-general.modules.verifications",
                        //     icon: "fa-user-check",
                        //     route: "admin.verifications.stats",
                        //     pattern: "admin.verifications.stats",
                        //     can: "user",
                        // },
                        {
                            label: "admin-general.modules.shortlist",
                            icon: "fa-clipboard-list",
                            route: "admin.shortlisting.index",
                            pattern: "admin.shortlisting.index",
                            can: "shortlisting",
                        },
                        {
                            label: "admin-general.modules.shortlisted-applicants",
                            icon: "fa-clipboard-list",
                            route: "admin.shortlisting.district.index",
                            pattern: "admin.shortlisting.district.index",
                            can: "district_shortlisting",
                        },
                    ],
                },
                {
                    group: "admin-general.modules.verifications",
                    can: "verification",
                    items: [
                        {
                            label: "Assigned Applications",
                            icon: "fa-users",
                            route: "admin.assigned-applications.index",
                            pattern: "admin.assigned-applications.index",
                            can: "verification",
                        },
                    ],
                }, {
                    group: "Administration",
                    can: "user",
                    items: [
                        {
                            label: "admin-general.modules.user-management",
                            icon: "fa-users",
                            route: "admin.users.index",
                            pattern: "admin.users.index",
                            can: "user",
                        },
                        {
                            label: "admin-general.modules.activate-monitor",
                            icon: "fa-align-left",
                            route: "admin.users.index.inactive-monitors",
                            pattern: "admin.users.index.inactive-monitors",
                            can: "activate_monitor",
                        },
                    ],
                },
                // {
                //     group: "admin-general.modules.monitoring",
                //     can: "monitoring",
                //     items: [
                //         {
                //             label: "admin-general.modules.monitoring-dashboard",
                //             icon: "fa-tv",
                //             route: "admin.monitorings.dashboard",
                //             pattern: "admin.monitorings.dashboard",
                //             can: "monitoring_dashboard",
                //         },
                //         {
                //             label: "admin-general.modules.monitoring-list",
                //             icon: "fa-th-list",
                //             route: "admin.monitorings.index",
                //             pattern: "admin.monitorings.index",
                //             can: "monitoring_list",
                //         },
                //         {
                //             label: "admin-general.modules.house-daily-report-dashboard",
                //             icon: "fa-laptop-house",
                //             route: "admin.house-daily-reports.dashboard",
                //             pattern: "admin.house-daily-reports.dashboard",
                //             can: "daily_report_dashboard",
                //         },
                //         {
                //             label: "admin-general.modules.house-daily-report-list",
                //             icon: "fa-home",
                //             route: "admin.house-daily-reports.index",
                //             pattern: "admin.house-daily-reports.index",
                //             can: "daily_report",
                //         },
                //         {
                //             label: "admin-general.modules.daily-report-dashboard",
                //             icon: "fa-tv",
                //             route: "admin.daily-reports.dashboard",
                //             pattern: "admin.daily-reports.dashboard",
                //             can: "daily_report_dashboard",
                //         },
                //         {
                //             label: "admin-general.modules.daily-report-list",
                //             icon: "fa-calendar-day",
                //             route: "admin.daily-reports.index",
                //             pattern: "admin.daily-reports.index",
                //             can: "daily_report",
                //         },
                //         {
                //             label: "admin-general.modules.enumerator-list",
                //             icon: "fa-calendar-day",
                //             route: "admin.enumerators.index",
                //             pattern: "admin.enumerators.index",
                //             can: "enumerator",
                //         },
                //     ],
                // },
            ],
        }),
        methods: {
            isAuthorized: function(item) {
                const can = this.getFromObject(item, "can")
                return this.getFromObject(this.$page, `props.can.${can}`, true)
            },
        },
    }
</script>
