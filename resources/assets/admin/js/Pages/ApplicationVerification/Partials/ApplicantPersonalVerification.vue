<template>
    <div class="mb-6 py-2 px-8">
        <div class="font-semibold">
            {{ trans("application.sections.personal") }}
        </div>
        <div class="mt-1 grid grid-cols-6 gap-2">
            <p class="col-span-2 text-l text-black data-block-label" v-text="'नाम थर (देवनागरिमा)'"/>
            <content-editable-input
                :is-editing="isEditing"
                :value="getValue('name_in_nepali')"
                class="col-span-3"
                name="name_in_nepali"
                @editMode="handleClick"
                @modified="handleModified"/>
            <toggle-input v-model="checklist.name_in_nepali"/>
        </div>

        <div class="mt-1 grid grid-cols-6 gap-2">
            <p class="col-span-2 text-l text-black data-block-label" v-text="'नाम थर (अंग्रेजी)'"/>

            <content-editable-input
                :is-editing="isEditing"
                :value="getValue('name_in_english')"
                class="col-span-3"
                name="name_in_english"
                @editMode="handleClick"
                @modified="handleModified">
                <template #input="{value, handler}">
                    <input :value="value" class="bg-primary-100 px-2 py-1 rounded" @input="handler">
                </template>
            </content-editable-input>
            <toggle-input v-model="checklist.name_in_english"/>
        </div>

        <div class="mt-1 grid grid-cols-6 gap-2">
            <p class="col-span-2 text-l text-black data-block-label" v-text="'लिङ्ग'"/>
            <content-editable-input
                :is-editing="isEditing"
                :value="getValue('gender')"
                class="col-span-3"
                name="gender"
                @editMode="handleClick"
                @modified="handleModified">
                <template #show="{value}">
                    <p class="px-2 py-1 rounded" v-text="trans(`application.gender-${value}`)"/>
                </template>
                <template #input="{value, handler}">
                    <radio-group-input :options="genderOptions"
                                       :value="value"
                                       @input="handler"/>
                </template>
            </content-editable-input>
            <toggle-input v-model="checklist.gender"/>
        </div>

        <div class="mt-1 grid grid-cols-6 gap-2">
            <p class="col-span-2 text-l text-black data-block-label" v-text="'जन्म मिति'"/>
            <content-editable-input
                :is-editing="isEditing"
                :value="getValue('dob_bs')"
                class="col-span-3"
                name="dob_bs"
                @editMode="handleClick"
                @modified="handleModified"/>
            <toggle-input v-model="checklist.dob_bs"/>
        </div>

        <div class="mt-1 grid grid-cols-6 gap-2">
            <p class="col-span-2 text-l text-black data-block-label" v-text="'नागरिकता नं'"/>
            <content-editable-input
                :is-editing="isEditing"
                :value="getValue('citizenship_number')"
                class="col-span-3"
                name="citizenship_number"
                @editMode="handleClick"
                @modified="handleModified"/>
            <toggle-input v-model="checklist.citizenship_number"/>
        </div>

        <div class="mt-1 grid grid-cols-6 gap-2">
            <p class="col-span-2 text-l text-black data-block-label" v-text="'जारी गर्ने जिल्ला'"/>
            <content-editable-input
                :is-editing="isEditing"
                :value="getValue('citizenship_issued_district')"
                class="col-span-3"
                name="citizenship_issued_district"
                @editMode="handleClick"
                @modified="handleModified">
                <template #show="{value}">
                    <p class="px-2 py-1 rounded" v-text="getDistrict(value)"/>
                </template>
                <template #input="{value, handler}">
                    <drop-down-input :options="districtOptions"
                                     :value="value"
                                     @input="handler"/>
                </template>
            </content-editable-input>
            <toggle-input v-model="checklist.citizenship_issued_district"/>
        </div>

        <div class="mt-1 grid grid-cols-6 gap-2">
            <p class="col-span-2 text-l text-black data-block-label" v-text="'जारी मिति'"/>
            <content-editable-input
                :is-editing="isEditing"
                :value="getValue('citizenship_issued_date')"
                class="col-span-3"
                name="citizenship_issued_date"
                @editMode="handleClick"
                @modified="handleModified"/>
            <toggle-input v-model="checklist.citizenship_issued_date"/>
        </div>
    </div>
</template>

<script>
    import DropDownInput        from "../../../../../shared/js/Components/Forms/DropDownInput"
    import ContentEditableInput from "../../../Components/Forms/ContentEditableInput"
    import RadioGroupInput      from "../../../Components/Forms/RadioGroupInput"
    import ToggleInput          from "../../../Components/Forms/ToggleInput"

    export default {
        name: "ApplicantPersonalVerification",

        components: { ToggleInput, DropDownInput, ContentEditableInput, RadioGroupInput },

        props: {
            applicant: { type: Object, required: true },
            formData: { type: Object, required: true },
            checkList: { type: Object, required: true },
        },

        inject: ["options"],

        data() {
            return {
                currentEditing: "",
                modified: {},
                checklist: {},
            }
        },
        watch: {
            modified: {
                handler(v) {
                    this.$emit("update", v)
                },
                deep: true,
            },
            checklist: {
                handler(v) {
                    this.$emit("update_checklist", v)
                },
                deep: true,
            },
        },
        computed: {
            genderOptions() {
                const options = new Map()

                this.options.genders.forEach(type => {
                    options.set(type, this.trans(`application.gender-${type}`))
                })

                return options
            },
            districtOptions: function() {
                const options = new Map()
                const key = this.$currentLocale === "en" ? "title_en" : "title_ne"

                this.options.districts.forEach(district => {
                    options.set(parseInt(district.code), district[key])
                })

                return options
            },
        },

        mounted() {
            this.$set(this, "modified", this.formData)
            this.$set(this, "checklist", this.checkList)
        },
        methods: {
            isEditing(name) {
                return name === this.currentEditing
            },

            handleClick(name) {
                this.$set(this, "currentEditing", name)
            },

            handleModified({ name, value }) {
                if (this.getFromObject(this.applicant, name) === value) {
                    this.$set(this.modified, name, null)
                    return
                }
                this.$set(this.modified, name, value)
            },

            getValue(name) {
                return this.getFromObject(this.formData, name) || this.getFromObject(this.modified, name) || this.getFromObject(this.applicant, name)
            },

            getDistrict(code) {
                const key = this.$currentLocale === "en" ? "title_en" : "title_ne"
                const district = this.options.districts.filter(d => parseInt(d.code) === parseInt(code))

                return district[0][key]
            },
        },
    }
</script>

<style scoped>

</style>
