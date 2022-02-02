<template>
    <form-section @submitted="updatePassword">
        <template #title>
            {{ trans("user.Change Password") }}
        </template>

        <template #description>
            {{ trans("user.change_password_help_text") }}
        </template>

        <template #form>
            <div class="col-span-6 sm:col-span-4">
                <label-component for="current_password" :value="trans('user.Current Password')"/>
                <input-component id="current_password"
                                 ref="current_password"
                                 v-model="form.current_password"
                                 type="password"
                                 class="form-control"
                                 autocomplete="current-password"/>
                <input-error :message="form.error('current_password')"/>
            </div>

            <div class="col-span-6 sm:col-span-4">
                <label-component for="password" :value="trans('user.New Password')"/>
                <input-component id="password"
                                 v-model="form.password"
                                 type="password"
                                 class="form-control"
                                 autocomplete="new-password"/>
                <input-error :message="form.error('password')"/>
            </div>

            <div class="col-span-6 sm:col-span-4">
                <label-component for="password_confirmation"
                                 :value="trans('validation.attributes.password_confirmation')"/>
                <input-component id="password_confirmation"
                                 v-model="form.password_confirmation"
                                 type="password"
                                 class="form-control"
                                 autocomplete="new-password"/>
                <input-error :message="form.error('password_confirmation')"/>
            </div>
        </template>

        <template #actions>
            <action-message :on="form.recentlySuccessful" class="mr-3">
                <div class="flex items-center">
                    <Icon name="success"/>
                    {{ trans("general.Saved") }}
                </div>
            </action-message>

            <primary-button type="submit" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                {{ trans("general.Save") }}
            </primary-button>
        </template>
    </form-section>
</template>

<script>
    import ActionMessage     from "../../../../shared/js/Components/ActionMessage"
    import { PrimaryButton } from "../../../../shared/js/Components/Buttons"
    import { FormSection }   from "../../../../shared/js/Components/Containers"
    import {
        InputComponent,
        LabelComponent,
    }                        from "../../../../shared/js/Components/Forms"

    import Icon            from "../../../js/Components/General/Icon"
    import InputError from "../../Components/Forms/InputError"

    export default {
        components: {
            Icon,
            PrimaryButton,
            ActionMessage,
            FormSection,
            InputComponent,
            InputError,
            LabelComponent,
        },

        data() {
            return {
                form: this.$inertia.form({
                    current_password: "",
                    password: "",
                    password_confirmation: "",
                }, {
                    bag: "updatePassword",
                }),
            }
        },

        methods: {
            updatePassword() {
                this.form.put(this.route("user-password.update"), {
                    preserveScroll: true,

                    onSuccess: () => {
                        this.$refs.current_password.focus()
                    },
                })
            },
        },
    }
</script>
