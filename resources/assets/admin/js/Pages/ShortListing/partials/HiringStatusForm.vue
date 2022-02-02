<template>
    <div>
        <form id="hiring-status-form"
              class="form"
              @submit.prevent="submitForm">
            <div class="card !mb-0">
                <div class="form-input-block py-4">
                    <div class="flex flex-wrap md:flex-nowrap gap-12">
                        <label-component>
                            Status
                        </label-component>
                        <radio-group-input id="status"
                                           v-model="form.status"
                                           class="ml-auto md:!grid-cols-2 capitalize"
                                           :options="hiringOptions"
                                           name="status"
                                           @change="handleOnChange($event, 'status')"/>
                    </div>
                    <input-error :message="form.error('status')"/>
                </div>
                <div>
                    <h2 class="heading-primary--sm py-4">
                        Comments
                    </h2>
                    <textarea id="comments"
                              v-model="form.comments"
                              placeholder="comments"
                              class="form-control md:w-2/3"/>
                    <input-error :message="form.error('comments')"/>
                </div>
            </div>
        </form>
        <div class="flex items-center justify-end py-8 gap-6">
            <action-message :on="form.recentlySuccessful">
                Saved.
            </action-message>
            <cancel-button @click="goToIndex">
                Cancel
            </cancel-button>
            <primary-button type="submit" @click.prevent="submitForm">
                Save
            </primary-button>
        </div>
    </div>
</template>
<script>
    import ActionMessage   from "../../../Components/ActionMessage"
    import PrimaryButton   from "../../../Components/Buttons/PrimaryButton"
    import CancelButton    from "../../../Components/Buttons/TertiaryButton"
    import InputError      from "../../../Components/Forms/InputError"
    import LabelComponent  from "../../../Components/Forms/Label"
    import RadioGroupInput from "../../../Components/Forms/RadioGroupInput"

    export default {
        name: "HiringStatusForm",
        components: { RadioGroupInput, InputError, LabelComponent, ActionMessage, CancelButton, PrimaryButton },
        props: {
            applicant: { type: Object, required: true },
        },
        inject: ["options", "role"],
        data() {
            return {
                form: this.$inertia.form({
                    status: this.getFromObject(this.applicant, `hiring.${this.role}.status`, ""),
                    comments: this.getFromObject(this.applicant, `hiring.${this.role}.comments`, ""),
                }, {
                    bag: "hiringStatusForm",
                    resetOnSuccess: true,
                }),
            }
        },
        computed: {
            hiringOptions: function() {
                const hOptions = new Map()
                Object.values(this.getFromObject(this.options, "hiringOptions", [])).forEach(value => {
                    hOptions.set(value, value)
                })
                return hOptions
            },
        },
        methods: {
            submitForm() {
                this.form.post(this.route("admin.shortlisting.applicant.hire", [this.applicant.id, this.role]))
            },
            handleOnChange(value, name) {
                this.$set(this.form, name, value)
            },
            goToIndex() {
                this.$inertia.visit(this.route("admin.shortlisting.index"), {
                    data: {
                        district: this.getFromObject(this.applicant, `hiring.${this.role}.district`),
                        municipality: this.getFromObject(this.applicant, `hiring.${this.role}.municipality`),
                        role: this.role,
                    },
                })
            },
        },
    }
</script>
