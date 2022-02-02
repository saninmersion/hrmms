<template>
    <div class="card card-js-scroll mb-6">
        <div class="heading-primary">
            {{ trans("application.sections.position") }}
        </div>
        <div class="data-block">
            <div class="data-block-item">
                <div class="data-block-label">
                    {{ trans("application.preview.application_position") }}
                </div>
                <div class="data-block-value">
                    <span> {{ trans(`application.preview.position-${applicant.application.application_for}`) }}</span>
                </div>
            </div>

            <div class="data-block-item">
                <div class="data-block-label">
                    {{ trans("application.preview.application_locations") }}
                </div>
                <div class="data-block-value">
                    <ul>
                        <li v-for="(location, index) in applicant.application.locations"
                            :key="index">
                            {{ formatLocation(location) }}
                            <span class="text-black-200">
                                ({{ trans(`application.preview.location-priority-${location.priority}`) }})
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>

<script type="text/ecmascript-6">
    export default {
        name: "ApplicationPosition",

        inject: ["newDistricts"],

        props: {
            applicant: { type: Object, required: true },
        },

        methods: {
            formatLocation(location) {
                if (!location.district) {
                    return ""
                }

                const formatted = []

                const district = this.newDistricts.find(dist => parseInt(dist.code) === parseInt(location.district))

                if (district) {
                    formatted.unshift(this.$currentLocale === "en" ? district.title_en : district.title_ne)
                }

                if (!location.municipality) {
                    return formatted.join(", ")
                }

                const municipality = district.municipalities.find(
                    mun => parseInt(mun.code) === parseInt(location.municipality))

                if (municipality) {
                    formatted.unshift(this.$currentLocale === "en" ? municipality.title_en : municipality.title_ne)
                }

                if (location.ward) {
                    formatted.unshift(this.trans("application.preview.ward_no_", {
                        ":attr": this.numberTrans(location.ward),
                    }))
                }

                return formatted.join(", ")
            },
        },
    }
</script>
