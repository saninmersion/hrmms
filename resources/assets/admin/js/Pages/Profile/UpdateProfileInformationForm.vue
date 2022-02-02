<template>
    <form-section @submitted="updateProfileInformation">
        <template #title>
            {{ trans("user.Profile Information") }}
        </template>

        <template #description>
            {{ trans("user.profile_info_help_text") }}
        </template>

        <template #form>
            <!-- Profile Photo -->
            <div v-if="$page.props.jetstream.managesProfilePhotos" class="col-span-6 sm:col-span-4">
                <!-- Profile Photo File Input -->
                <input ref="photo"
                       type="file"
                       accept="image/png,image/jpeg"
                       class="hidden"
                       @change="updatePhotoPreview">

                <label-component for="photo" :value="trans('user.Photo')"/>

                <div class="flex items-center gap-4">
                    <!-- Current Profile Photo -->
                    <div v-show="! photoPreview">
                        <img :src="user.profile_photo_url"
                             :alt="user.username"
                             class="rounded-full w-20 object-cover">
                    </div>

                    <!-- New Profile Photo Preview.vue -->
                    <div v-show="photoPreview">
                        <span class="block rounded-full w-20 h-20"
                              :style="'background-size: cover; background-repeat: no-repeat; background-position: center center; background-image: url(\'' + photoPreview + '\');'"/>
                    </div>

                    <tertiary-button type="button" @click.native.prevent="selectNewPhoto">
                        {{ trans("user.Select A New Photo") }}
                    </tertiary-button>

                    <danger-button v-if="user.profile_photo_path"
                                   type="button"
                                   @click.native.prevent="deletePhoto">
                        {{ trans("user.Remove Photo") }}
                    </danger-button>
                </div>

                <input-error :message="form.error('photo')"/>
            </div>

            <!-- Name -->
            <div class="col-span-6 sm:col-span-4">
                <label-component for="name" :value="trans('user.Name')"/>
                <input-component id="name"
                                 v-model="form.name"
                                 type="text"
                                 class="form-control"
                                 autocomplete="name"/>
                <input-error :message="form.error('name')"/>
            </div>

            <!-- Email -->
            <div class="col-span-6 sm:col-span-4">
                <label-component for="email" :value="trans('user.Email')"/>
                <input-component id="email" v-model="form.email" type="email" class="form-control"/>
                <input-error :message="form.error('email')"/>
            </div>

            <!-- Username -->
            <div class="col-span-6 sm:col-span-4">
                <label-component for="username" :value="trans('user.Username')"/>
                <input-component id="username" v-model="form.username" type="text" class="form-control"/>
                <input-error :message="form.error('username')"/>
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
    import ActionMessage   from "../../../../shared/js/Components/ActionMessage"
    import {
        DangerButton,
        PrimaryButton,
        TertiaryButton,
    }                      from "../../../../shared/js/Components/Buttons"
    import { FormSection } from "../../../../shared/js/Components/Containers"
    import {
        InputComponent,
        LabelComponent,
    }                      from "../../../../shared/js/Components/Forms"

    import Icon            from "../../../js/Components/General/Icon"
    import InputError from "../../Components/Forms/InputError"

    export default {
        name: "UpdateProfileInformationForm",

        components: {
            Icon,
            PrimaryButton,
            TertiaryButton,
            ActionMessage,
            FormSection,
            InputComponent,
            InputError,
            LabelComponent,
            DangerButton,
        },

        props: {
            user: { type: Object, required: true },
        },

        data() {
            return {
                form: this.$inertia.form({
                    _method: "PUT",
                    name: this.user.name,
                    email: this.user.email,
                    username: this.user.username,
                    photo: null,
                }, {
                    bag: "updateProfileInformation",
                    resetOnSuccess: false,
                }),

                photoPreview: null,
            }
        },

        methods: {
            updateProfileInformation() {
                if (this.$refs.photo) {
                    this.form.photo = this.$refs.photo.files[0]
                }

                this.form.post(this.route("user-profile-information.update"), {
                    preserveScroll: true,
                })
            },

            selectNewPhoto() {
                this.$refs.photo.click()
            },

            updatePhotoPreview() {
                const reader = new FileReader()

                reader.onload = (e) => {
                    this.photoPreview = e.target.result
                }

                reader.readAsDataURL(this.$refs.photo.files[0])
            },

            deletePhoto() {
                this.$inertia.delete(this.route("current-user-photo.destroy"), {
                    preserveScroll: true,
                }).then(() => {
                    this.photoPreview = null
                })
            },
        },
    }
</script>
