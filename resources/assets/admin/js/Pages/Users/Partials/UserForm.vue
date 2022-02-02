<template>
    <div class="flex justify-between items-baseline">
        <div class="w-full flex justify-between align-center flex-wrap">
            <div class="w-full md:w-1/2 px-4 py-2">
                <label-component :value="trans('admin-users.full-name')" class="form-label"/>
                <input-component v-model="formData.name" class="border form-input rounded-md shadow-sm block mt-1 w-full form-input"/>
                <input-error :message="formData.error('name')"/>
            </div>

            <div class="w-full md:w-1/2 px-4 py-2" @focusout="generateUsername">
                <label-component :value="trans('admin-users.email')" class="form-label"/>
                <input-component v-model="formData.email" type="email" class="border form-input rounded-md shadow-sm block mt-1 w-full form-input"/>
                <input-error :message="formData.error('email')"/>
            </div>

            <div class="w-full md:w-1/2 px-4 py-2">
                <label-component :value="trans('admin-users.username')" class="form-label"/>
                <input-component v-model="formData.username" class="border form-input rounded-md shadow-sm block mt-1 w-full form-input"/>
                <input-error :message="formData.error('username')"/>
            </div>

            <div v-if="!isEdit" class="w-full md:w-1/2 px-4 py-2">
                <label-component :value="trans('admin-users.password')" class="form-label"/>
                <input-component v-model="formData.password" class="border form-input rounded-md shadow-sm block mt-1 w-full form-input"/>
                <input-error :message="formData.error('password')"/>
            </div>

            <div class="w-full md:w-1/2 px-4 py-2">
                <label-component :value="trans('admin-users.role')"/>
                <drop-down-input
                    v-model="formData.role"
                    :class="{'!border !border-red-600' :validation().has('role')} "
                    :options="roleOptions"
                    :hide-selected="false"
                    :placeholder="trans('admin-users.role')"
                    name="role"
                    :show-placeholder="true"/>
                <input-error :message="formData.error('role')"/>
            </div>

            <div v-if="showDistricts" class="w-full md:w-1/2 px-4 py-2">
                <label-component :value="trans('application.district')"/>
                <drop-down-input
                    :value="formData.district"
                    :class="{'!border !border-red-600' :validation().has('district')} "
                    :options="districtOptions"
                    :hide-selected="false"
                    :placeholder="trans('application.district')"
                    name="role"
                    :show-placeholder="true"
                    @input="updateDistrict"/>
                <input-error :message="formData.error('district')"/>
            </div>

            <div v-if="showCensusOffices" class="w-full md:w-1/2 px-4 py-2">
                <label-component :value="trans('application.census_office')"/>
                <drop-down-input
                    v-model="formData.census_office"
                    :class="{'!border !border-red-600' :validation().has('census_office')} "
                    :options="censusOfficesOptions"
                    :hide-selected="false"
                    :placeholder="trans('application.census_office')"
                    name="role"
                    :show-placeholder="true"/>
                <input-error :message="formData.error('census_office')"/>
            </div>
        </div>
    </div>
</template>

<script>
    import DropDownInput  from "../../../../../shared/js/Components/Forms/DropDownInput"
    import InputComponent from "../../../../../shared/js/Components/Forms/Input"
    import LabelComponent from "../../../../../shared/js/Components/Forms/Label"
    import InputError     from "../../../Components/Forms/InputError"

    const ROLE_DISTRICT = "district_staffs"
    const ROLE_SUPERVISOR = "supervisors"

    export default {
        name: "UserForm",

        components: {
            InputComponent,
            InputError,
            LabelComponent,
            DropDownInput,
        },

        inject: ["districts", "roles", "censusOffices"],

        props: {
            form: { type: Object, required: true },
            isEdit: { type: Boolean, required: false, default: false },
        },

        data: () => ({
            formData: {},
        }),

        watch: {
            form: {
                handler(form) {
                    this.formData = form
                },
                immediate: true,
            },
        },

        computed: {
            roleOptions: function() {
                const options = new Map()

                this.roles.forEach(role => {
                    options.set(role, this.trans(`admin-users.roles.${role}`))
                })

                return options
            },

            showDistricts: function() {
                return (this.formData.role && (this.formData.role === ROLE_DISTRICT || this.formData.role === ROLE_SUPERVISOR))
            },

            showCensusOffices: function() {
                return (this.formData.role && this.formData.role === ROLE_SUPERVISOR)
            },

            districtOptions: function() {
                const options = new Map()
                const key = this.$currentLocale === "en" ? "title_en" : "title_ne"

                this.districts.forEach(district => {
                    options.set(parseInt(district.code), district[key])
                })

                return options
            },

            censusOfficesOptions: function() {
                const options = new Map()
                this.censusOffices.forEach(office => {
                    if (this.formData.district && this.formData.district !== office.district_code) {
                        return
                    }
                    options.set(parseInt(office.id), office.office_name)
                })

                return options
            },
        },

        methods: {
            generateUsername: function() {
                if (this.formData.username) {
                    return
                }

                const username = this.formData.email.split("@")
                this.$set(this.formData, "username", username[0])
            },
            updateDistrict(district) {
                this.$set(this.formData, "district", district)
                this.$set(this.formData, "census_office", null)
            },
        },
    }
</script>
