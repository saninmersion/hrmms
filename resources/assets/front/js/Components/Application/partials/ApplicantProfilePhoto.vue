<template>
    <div>
        <div class="flex items-center" @click="() => {isModalOpen = !isModalOpen}">
            <div class="p-4 flex bg-blue-100 w-16 h-16 mr-2 rounded">
                <Icon class="m-auto !mr-auto w-8 h-6"
                      name="camera"/>
            </div>
            <span class="text-black-200">
                {{ trans("application.placeholders.photo") }}
                <span>{{ trans("application.form-help-text.image-format-size") }}</span>
            </span>
        </div>
        <webcam-capture :is-modal-open="isModalOpen"
                        :submit-url="mediaUploadUrl"
                        name="personal.applicant_photo"/>
    </div>
</template>

<script>
    import { WebcamCapture } from "../../../../../shared/js/Components/Forms"
    import Icon              from "../../Common/Icon"

    export default {
        name: "ApplicantProfilePhoto",

        inject: ["mediaUploadUrl"],

        components: {
            WebcamCapture,
            Icon,
        },

        data() {
            return {
                isModalOpen: false,
            }
        },
    }
</script>

<style scoped>

</style>
