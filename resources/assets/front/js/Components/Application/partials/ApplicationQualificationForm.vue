<template>
    <div class="card mt-8 mb-16 card-js-scroll card-qualification">
        <h2 class="heading-primary">
            {{ trans("application.sections.qualification") }}
        </h2>

        <div>
            <div class="mb-8">
                <label class="checkbox-label">
                    <input v-model="qualification.has_education_qualification"
                           class="checkbox"
                           type="checkbox">
                    {{ trans("application.fields.has_education_qualification") }}
                </label>
                <input-error v-if="validation().get('qualification.has_education_qualification')"
                             :message="validation().get('qualification.has_education_qualification')"/>
            </div>

            <div v-if="qualification.has_education_qualification">
                <!-- for supervisor -->
                <fieldset v-if="supervisorSelected">
                    <legend>{{ trans("application.education-for-supervisor") }}</legend>

                    <label-component :value="trans('application.fields.files_for_supervisor_education')"/>
                    <div class="input-file grid md:grid-cols-2 gap-6">
                        <div class="relative">
                            <div class="text-black-200">
                                {{ trans("application.form-help-text.image-format-size") }}
                            </div>
                            <camera-input v-model="qualification.files_for_supervisor_education"
                                          :submit-url="fileUploadUrl"/>
                            <input-error v-if="validation().get('qualification.files_for_supervisor_education')"
                                         :message="validation().get('qualification.files_for_supervisor_education')"/>
                        </div>
                    </div>

                    <div class="mt-8">
                        <div class="grid lg:grid-cols-2 gap-6">
                            <div class="grid sm:grid-cols-2 gap-4">
                                <div>
                                    <label-component
                                        :value="`${trans('application.division')}/${trans('application.fields.cgpa')}`"
                                        class="pb-2"/>
                                    <radio-group-input
                                        v-model="qualification.education.supervisor.grading_system"
                                        :options="gradingSystemsOptions"
                                        class="flex gap-4 radio-btn-group  "
                                        name="qualification.education.supervisor.grading_system"/>
                                    <input-error
                                        :message="validation().get('qualification.education.supervisor.grading_system')"/>
                                </div>
                                <div>
                                    <div v-if="qualification.education.supervisor.grading_system === 'percentage'">
                                        <label-component>
                                            {{ trans("application.fields.percentage") }}
                                        </label-component>
                                        <div class="flex items-center gap-2">
                                            <input-component
                                                v-model="qualification.education.supervisor.percentage"
                                                :class="{'form-input-error' :validation().has('qualification.education.supervisor.percentage')} "
                                                class="!w-12"
                                                @keypress="decimalOnly"/>
                                            <span class="text-black-200">%</span>
                                        </div>

                                        <input-error
                                            v-if="validation().has('qualification.education.supervisor.percentage')"
                                            :message="validation().get('qualification.education.supervisor.percentage')"/>
                                    </div>

                                    <div v-if="qualification.education.supervisor.grading_system === 'grade'">
                                        <label-component>
                                            {{ trans("application.fields.grade") }}
                                        </label-component>

                                        <input-component
                                            v-model="qualification.education.supervisor.grade"
                                            :class="{'form-input-error' :validation().has('qualification.education.supervisor.grade')} "
                                            class="!w-12"
                                            @keypress="decimalOnly"/>
                                        <input-error v-if="validation().has('qualification.education.supervisor.grade')"
                                                     :message="validation().get('qualification.education.supervisor.grade')"/>
                                    </div>
                                </div>
                            </div>
                            <div class="grid sm:grid-cols-2 gap-3">
                                <div>
                                    <label-component :value="trans('application.fields.major_subject')"/>
                                    <drop-down-input
                                        v-model="qualification.education.supervisor.major_subject"
                                        :class="{'form-input-error' :validation().has('qualification.education.supervisor.major_subject')} "
                                        :options="majorSubjectsOptions"
                                        :placeholder="trans('application.placeholders.major_subject')"
                                        :show-placeholder="true"/>

                                    <input-error
                                        :message="validation().get('qualification.education.supervisor.major_subject')"
                                        :v-if="validation().has('qualification.education.supervisor.major_subject')"/>
                                </div>
                                <div>
                                    <div v-if="qualification.education.supervisor.major_subject === 'others'">
                                        <label-component :value="trans('application.fields.major_subject_other')"/>

                                        <input-component
                                            v-model="qualification.education.supervisor.major_subject_other"/>

                                        <input-error
                                            :message="validation().get('qualification.education.supervisor.major_subject_other')"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>

                <!-- for enumerator -->
                <fieldset v-if="enumeratorSelected">
                    <legend>{{ trans("application.education-for-enumerator") }}</legend>

                    <div>
                        <label-component :value="trans('application.fields.files_for_enumerator_education')"/>
                        <div class="input-file grid md:grid-cols-2 gap-6">
                            <div class="relative">
                                <span class="text-black-200">
                                    {{ trans("application.form-help-text.image-format-size") }}
                                </span>
                                <camera-input v-model="qualification.files_for_enumerator_education"
                                              :submit-url="fileUploadUrl"/>
                            </div>
                        </div>
                    </div>
                    <div class="grid md:grid-cols-2 gap-4 mt-8">
                        <div class="grid sm:grid-cols-2 gap-6">
                            <div>
                                <div>
                                    <label-component
                                        :value="`${trans('application.division')}/${trans('application.fields.cgpa')}`"
                                        class="pb-2"/>
                                    <radio-group-input
                                        v-model="qualification.education.enumerator.grading_system"
                                        :options="gradingSystemsOptions"
                                        class="flex gap-4 radio-btn-group "
                                        name="qualification.education.enumerator.grading_system"/>
                                    <input-error
                                        :message="validation().get('qualification.education.enumerator.grading_system')"/>
                                </div>
                            </div>
                            <div>
                                <div v-if="qualification.education.enumerator.grading_system === 'percentage'">
                                    <label-component>
                                        {{ trans("application.fields.percentage") }}
                                    </label-component>

                                    <div class="flex items-center gap-2">
                                        <input-component
                                            v-model="qualification.education.enumerator.percentage"
                                            :class="{'form-input-error' :validation().has('qualification.education.enumerator.percentage')} "
                                            class="!w-12"
                                            @keypress="decimalOnly"/>
                                        <span class="text-black-200">%</span>
                                    </div>
                                    <input-error
                                        v-if="validation().has('qualification.education.enumerator.percentage')"
                                        :message="validation().get('qualification.education.enumerator.percentage')"/>
                                </div>

                                <div v-if="qualification.education.enumerator.grading_system === 'grade'">
                                    <label-component>
                                        {{ trans("application.fields.cgpa") }}
                                    </label-component>

                                    <input-component
                                        v-model="qualification.education.enumerator.grade"
                                        :class="{'form-input-error' :validation().has('qualification.education.enumerator.grade')} "
                                        class="!w-12"
                                        @keypress="decimalOnly"/>

                                    <input-error v-if="validation().has('qualification.education.enumerator.grade')"
                                                 :message="validation().get('qualification.education.enumerator.grade')"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>

                <div class="my-6">
                    <div class="pb-2">
                        {{ trans("application.fields.extra_education") }}
                        <span class="text-black-200">({{ trans("general.optional") }})</span>
                    </div>

                    <div class="input-file grid md:grid-cols-2 gap-6 ">
                        <div class="relative">
                            <div class="text-black-200">
                                {{ trans("application.form-help-text.image-format-size") }}
                            </div>
                            <camera-input v-model="qualification.files_for_extra_education"
                                          :submit-url="fileUploadUrl"/>
                            <input-error v-if="validation().has('qualification.files_for_extra_education')"
                                         :message="validation().get('qualification.files_for_extra_education')"/>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-6 mt-6">
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label-component :value="trans('application.fields.major_subject')"/>
                                <drop-down-input
                                    v-model="qualification.education.extra.major_subject"
                                    :class="{'!form-input-error' :validation().has('qualification.education.extra.major_subject')} "
                                    :options="majorSubjectsOptions"
                                    :hide-selected="false"
                                    :show-labels="true"
                                    :deselect-label="trans('general.Remove')"
                                    :placeholder="trans('application.placeholders.major_subject')"
                                    :show-placeholder="true"/>

                                <input-error
                                    :message="validation().get('qualification.education.extra.major_subject')"
                                    :v-if="validation().has('qualification.education.extra.major_subject')"/>
                            </div>
                            <div>
                                <div v-if="qualification.education.extra.major_subject === 'others'">
                                    <label-component :value="trans('application.fields.major_subject_other')"/>

                                    <input-component
                                        v-model="qualification.education.extra.major_subject_other"/>

                                    <input-error
                                        :message="validation().get('qualification.education.extra.major_subject_other')"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!--has experience-->
        <div class="mt-5 ">
            <div class="my-8">
                <label class="checkbox-label">
                    <input v-model="qualification.has_experience"
                           class="checkbox"
                           type="checkbox">
                    <span>
                        {{ trans("application.fields.has_experience") }}
                        <span class="text-black-200">({{ trans("general.optional") }})</span>
                    </span>
                </label>
                <input-error v-if="validation().get('qualification.has_experience')"
                             :message="validation().get('qualification.has_experience')"/>
            </div>

            <fieldset v-if="qualification.has_experience">
                <legend>
                    {{ trans("application.experience_details") }}
                </legend>
                <div class="grid md:grid-cols-2 gap-6 form-group">
                    <div>
                        <label-component :value="trans('application.fields.experience_organization')"/>
                        <input-component
                            v-model="qualification.experience.organization"
                            :class="{'form-input-error' :validation().has('qualification.experience.organization')} "
                            :placeholder="trans('application.placeholders.experience_organization')"
                            type="text"/>
                        <input-error v-if="validation().get('qualification.experience.organization')"
                                     :message="validation().get('qualification.experience.organization')"/>
                    </div>
                    <div>
                        <label-component :value="trans('application.fields.experience_period')"/>
                        <div class="flex flex-wrap gap-3">
                            <div class="flex items-center gap-3">
                                <input-component
                                    v-model.number="qualification.experience.period_month"
                                    class="!w-12"
                                    @keypress="numberOnly"/>
                                {{ trans("general.month") }}
                            </div>
                            <div class="flex items-center gap-3">
                                <input-component
                                    v-model.number="qualification.experience.period_day"
                                    class="!w-12"
                                    @keypress="numberOnly"/>
                                {{ trans("general.day") }}
                            </div>
                            <div class="w-full">
                                <input-error
                                    :message="validation().get('qualification.experience.period_day') || validation().get('qualification.experience.period_month')"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class=" input-file grid md:grid-cols-2 gap-6 ">
                    <div>
                        <label-component :value="trans('application.fields.experience_document')"/>
                        <div class="relative">
                            <span class="text-black-200">{{ trans("application.form-help-text.image-format-size") }}</span>
                            <camera-input v-model="qualification.experience_documents"
                                          :submit-url="fileUploadUrl"/>
                        </div>
                        <input-error v-if="validation().has('qualification.experience_documents')"
                                     :message="validation().get('qualification.experience_documents')"/>
                    </div>
                </div>
            </fieldset>
        </div>
    </div>
</template>

<script type="text/ecmascript-6">
    import DropDownInput   from "../../../../../shared/js/Components/Forms/DropDownInput"
    import CameraInput     from "../../../../../shared/js/Components/Forms/Files/CameraInput"
    import InputComponent  from "../../../../../shared/js/Components/Forms/Input"
    import InputError      from "../../../../../shared/js/Components/Forms/InputError"
    import LabelComponent  from "../../../../../shared/js/Components/Forms/Label"
    import RadioGroupInput from "../../../../../shared/js/Components/Forms/RadioGroupInput"

    export default {
        name: "ApplicationQualificationForm",

        inject: ["mediaUploadUrl", "gradingSystems", "majorSubjects"],

        components: {
            CameraInput,
            DropDownInput,
            InputComponent,
            InputError,
            RadioGroupInput,
            LabelComponent,
        },

        props: {
            value: { type: Object, required: true },
            application: { type: Object, required: true },
        },

        data: () => ({
            qualification: {},
        }),

        watch: {
            value: {
                handler(value) {
                    this.qualification = value
                },
                immediate: true,
                deep: true,
            },
        },

        computed: {
            supervisorSelected: function() {
                return ["supervisor", "enumerator_supervisor"].includes(this.application.application_for || "")
            },

            enumeratorSelected: function() {
                return ["enumerator", "enumerator_supervisor"].includes(this.application.application_for || "")
            },

            fileUploadUrl: function() {
                return this.mediaUploadUrl
            },

            gradingSystemsOptions: function() {
                const options = new Map()

                this.gradingSystems.forEach(gradingSystem => {
                    options.set(gradingSystem, this.trans(`application.grading_system.${gradingSystem}`))
                })

                return options
            },

            majorSubjectsOptions: function() {
                const options = new Map()

                this.majorSubjects.forEach(subject => {
                    options.set(subject, this.trans(`application.major_subjects.${subject}`))
                })

                return options
            },
        },
    }
</script>
