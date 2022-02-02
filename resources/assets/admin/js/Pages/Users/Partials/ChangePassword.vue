<template>
    <form class="mt-8 bg-white max-w-2xl shadow overflow-hidden sm:rounded-lg mx-auto" @submit.prevent="updatePassword">
        <div class="bg-white max-w-2xl shadow overflow-hidden sm:rounded-lg mx-auto">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900" v-text="trans('user.Change Password')"/>
            </div>

            <div class="bg-gray-50 border-t border-gray-200">
                <dl>
                    <div class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm text-base-3">
                            {{ trans("user.change_password_help_text") }}
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <label-component for="password" class="!text-base-1" :value="'नयाँ पासवर्ड'"/>
                            <input-component id="password"
                                             ref="password"
                                             v-model="form.password"
                                             class="border form-input rounded-md shadow-sm block mt-1 w-full form-input"
                                             autocomplete="new-password"/>
                            <input-error :message="form.error('password')" class="mt-2"/>
                        </dd>
                    </div>

                    <div class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500"/>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <action-message :on="form.recentlySuccessful" class="mr-3">
                                {{ trans("general.Saved") }}
                            </action-message>

                            <primary-button type="submit">
                                {{ trans("general.Save") }}
                            </primary-button>
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
    </form>
</template>

<script>
    import ActionMessage     from "../../../../../shared/js/Components/ActionMessage"
    import InputComponent    from "../../../../../shared/js/Components/Forms/Input"
    import InputError        from "../../../../../shared/js/Components/Forms/InputError"
    import LabelComponent    from "../../../../../shared/js/Components/Forms/Label"
    import { PrimaryButton } from "../../../Components/Buttons"

    export default {
        name: "ChangePassword",

        components: {
            ActionMessage,
            InputError,
            InputComponent,
            LabelComponent,
            PrimaryButton,
        },

        props: {
            userDetail: { type: Object, required: true },
        },

        data() {
            return {
                form: this.$inertia.form({
                    password: "",
                }, {
                    bag: "updatePassword",
                }),
            }
        },

        methods: {
            updatePassword() {
                this.form.put(this.route("admin.users.change-password", this.userDetail.id), {
                    preserveScroll: true,

                    onSuccess: () => {
                        this.$refs.password.focus()
                    },
                })
            },
        },
    }
</script>
