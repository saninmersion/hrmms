<template>
    <div class="mb-6 py-2 px-8">
        <div v-if="getFromObject(applicant, 'application_for') === 'supervisor' || getFromObject(applicant, 'application_for') === 'enumerator_supervisor'">
            <div class="heading-primary heading-primary--sm">
                {{ trans("application.education-for-supervisor") }}
            </div>
            <div class="mt-1 grid grid-cols-6 gap-2">
                <p class="col-span-2 text-l text-black data-block-label" v-text="'मूल विषय लिएकाे'"/>

                <content-editable-input
                    :is-editing="isEditing"
                    :value="getValue('education_supervisor_major_subject')"
                    class="col-span-3"
                    name="education_supervisor_major_subject"
                    @editMode="handleClick"
                    @modified="handleModified">
                    <template #show="{value}">
                        <p class="px-2 py-1 rounded" v-text="trans(`application.major_subjects.${value}`)"/>
                    </template>
                    <template #input="{value, handler}">
                        <drop-down-input :options="majorSubjectsOptions"
                                         :value="value"
                                         @input="handler"/>
                    </template>
                </content-editable-input>
                <toggle-input v-model="checklist.education_supervisor_major_subject"/>
            </div>
            <div v-if="getFromObject(applicant,'education_supervisor_grading_system') === 'grade'" class="mt-1 grid grid-cols-6 gap-2">
                <p class="col-span-2 text-l text-black data-block-label" v-text="'CGPA'"/>
                <content-editable-input
                    :is-editing="isEditing"
                    :value="getValue('education_supervisor_grade')"
                    class="col-span-3"
                    name="education_supervisor_grade"
                    @editMode="handleClick"
                    @modified="handleModified"/>
                <toggle-input v-model="checklist.education_supervisor_grade"/>
            </div>
            <div v-if="getFromObject(applicant,'education_supervisor_grading_system') === 'percentage'" class="mt-1 grid grid-cols-6 gap-2">
                <p class="col-span-2 text-l text-black data-block-label" v-text="'Percentage'"/>
                <content-editable-input
                    :is-editing="isEditing"
                    :value="getValue('education_supervisor_percentage')"
                    class="col-span-3"
                    name="education.supervisor.percentage"
                    @editMode="handleClick"
                    @modified="handleModified"/>
                <toggle-input v-model="checklist.education_supervisor_percentage"/>
            </div>
        </div>

        <div v-if="getFromObject(applicant, 'application_for') === 'enumerator' || getFromObject(applicant, 'application_for') === 'enumerator_supervisor'">
            <div class="heading-primary heading-primary--sm">
                {{ trans("application.education-for-enumerator") }}
            </div>
            <div class="mt-1 grid grid-cols-6 gap-2">
                <p class="col-span-2 text-l text-black data-block-label" v-text="'मूल विषय लिएकाे'"/>
                <content-editable-input
                    :is-editing="isEditing"
                    :value="getValue('education_supervisor_major_subject')"
                    class="col-span-3"
                    name="education_supervisor_major_subject"
                    @editMode="handleClick"
                    @modified="handleModified">
                    <template #show="{value}">
                        <p class="px-2 py-1 rounded" v-text="trans(`application.major_subjects.${value}`)"/>
                    </template>
                    <template #input="{value, handler}">
                        <drop-down-input :options="majorSubjectsOptions"
                                         :value="value"
                                         @input="handler"/>
                    </template>
                </content-editable-input>
                <toggle-input v-model="checklist.education_enumerator_major_subject"/>
            </div>
            <div v-if="getFromObject(applicant,'education_enumerator_grading_system') === 'grade'" class="mt-1 grid grid-cols-6 gap-2">
                <p class="col-span-2 text-l text-black data-block-label" v-text="'CGPA'"/>
                <content-editable-input
                    :is-editing="isEditing"
                    :value="getValue('education_enumerator_grade')"
                    class="col-span-3"
                    name="education_enumerator_grade"
                    @editMode="handleClick"
                    @modified="handleModified"/>
                <toggle-input v-model="checklist.education_enumerator_grade"/>
            </div>
            <div v-if="getFromObject(applicant,'education_enumerator_grading_system') === 'percentage'" class="mt-1 grid grid-cols-6 gap-2">
                <p class="col-span-2 text-l text-black data-block-label" v-text="'CGPA'"/>
                <content-editable-input
                    :is-editing="isEditing"
                    :value="getValue('education_enumerator_percentage')"
                    class="col-span-3"
                    name="education_enumerator_percentage"
                    @editMode="handleClick"
                    @modified="handleModified"/>
                <toggle-input v-model="checklist.education_enumerator_percentage"/>
            </div>
        </div>
        <div v-if="getFromObject(applicant, 'has_training', false)">
            <div class="heading-primary heading-primary--sm">
                {{ trans("application.preview.training") }}
            </div>
            <div class="mt-1 grid grid-cols-6 gap-2">
                <p class="col-span-2 text-l text-black data-block-label" v-text="'तालिम प्रदान गर्ने निकाय'"/>
                <content-editable-input
                    :is-editing="isEditing"
                    :value="getValue('training_institute')"
                    class="col-span-3"
                    name="training_institute"
                    @editMode="handleClick"
                    @modified="handleModified"/>
                <toggle-input v-model="checklist.training_institute"/>
            </div>
            <div class="mt-1 grid grid-cols-6 gap-2">
                <p class="col-span-2 text-l text-black data-block-label" v-text="'तालिम अवधी (दिनमा)'"/>
                <content-editable-input
                    :is-editing="isEditing"
                    :value="getValue('training_period')"
                    class="col-span-3"
                    name="training_period"
                    @editMode="handleClick"
                    @modified="handleModified"/>
                <toggle-input v-model="checklist.training_period"/>
            </div>
        </div>
        <div v-if="getFromObject(applicant, 'has_experience', false)">
            <div class="heading-primary heading-primary--sm">
                {{ trans("application.preview.experience") }}
            </div>
            <div class="mt-1 grid grid-cols-6 gap-2">
                <p class="col-span-2 text-l text-black data-block-label" v-text="'काम गरेकाे संस्थाकाे नाम'"/>
                <content-editable-input
                    :is-editing="isEditing"
                    :value="getValue('experience_institute')"
                    class="col-span-3"
                    name="experience_institute"
                    @editMode="handleClick"
                    @modified="handleModified"/>
                <toggle-input v-model="checklist.experience_institute"/>
            </div>
            <div class="mt-1 grid grid-cols-6 gap-2">
                <p class="col-span-2 text-l text-black data-block-label" v-text="'काम गरेकाे अवधी'"/>
                <content-editable-input
                    :is-editing="isEditing"
                    :value="getValue('experience_period')"
                    class="col-span-3"
                    name="experience_period"
                    @editMode="handleClick"
                    @modified="handleModified"/>
                <toggle-input v-model="checklist.experience_period"/>
            </div>
        </div>
    </div>
</template>

<script>
    import DropDownInput        from "../../../../../shared/js/Components/Forms/DropDownInput"
    import ContentEditableInput from "../../../Components/Forms/ContentEditableInput"
    import ToggleInput          from "../../../Components/Forms/ToggleInput"

    export default {
        name: "QualificationVerification",

        components: { ToggleInput, DropDownInput, ContentEditableInput },

        props: {
            checkList: { type: Object, required: true },
            formData: { type: Object, required: true },
            applicant: { type: Object, required: true },
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
            majorSubjectsOptions: function() {
                const options = new Map()

                this.options.majorSubjects.forEach(subject => {
                    options.set(subject, this.trans(`application.major_subjects.${subject}`))
                })

                return options
            },
        },
        mounted() {
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
                return this.getFromObject(this.modified, name) || this.getFromObject(this.applicant, name)
            },
        },
    }
</script>

<style scoped>

</style>
