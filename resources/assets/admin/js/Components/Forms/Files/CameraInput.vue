<template>
    <Fragment>
        <div>
            <div class="flex items-center flex-wrap gap-4 lg:gap-16 mt-2">
                <div class="input-file-btn-wrap overflow-hidden flex flex-col sm:flex-row flex-wrap gap-2 sm:gap-0 lg:flex-row w-full gap-4 py-2 pb-4 bg-blue-100 rounded-md">
                    <div class="input-file-btn sm:w-1/2 flex flex-col items-center cursor-pointer" @click="openCameraModal">
                        <span class="block my-2" v-text="trans('general.take_photo')"/>
                        <div class="input-file-box  p-4 flex bg-blue-100 w-16 h-16 rounded cursor-pointer">
                            <Icon class="m-auto !mr-auto w-8 h-6"
                                  name="camera"/>
                        </div>
                    </div>
                    <div class="input-file-btn sm:w-1/2 flex flex-col items-center relative pt-4 sm:pt-0">
                        <span class="block my-2" v-text="trans('general.browse_photo')"/>
                        <div class="input-file-box  h-16 w-16  flex flex cursor-pointer">
                            <Icon name="upload-1" class="!w-5 !h-5 !m-auto"/>
                            <input ref="fileInput"
                                   :multiple="multiple"
                                   :placeholder="placeholder"
                                   accept="image/x-png,image/jpg,image/jpeg"
                                   type="file"
                                   class="cursor-pointer"
                                   @click="()=> {errorMessage = null}"
                                   @input="handleFileSelect">
                        </div>
                        <camera :is-modal-open="showCameraModal" @image-captured="uploadCapturedPhoto" @close="closeCameraModal"/>
                    </div>
                </div>
            </div>
            <span v-if="errorMessage" class="block text-danger-50 text-sm my-4" v-text="errorMessage"/>
            <ul v-if="uploadedFiles.length" class="flex flex-1 flex-wrap preview mt-4 p-4 rounded bg-grey-5 gap-4">
                <li v-for="(file,index) in uploadedFiles"
                    :key="index"
                    class="file__item block w-20 h-20 rounded relative overflow-hidden">
                    <img :alt="file.filename"
                         :src="getFileImage(file)"
                         class="img-preview w-full h-full object-cover">
                    <span class="btn-remove btn-remove--absolute !top-0.5 !right-0.5" @click.prevent="removeFile(index)">
                        <Icon name="close"/>
                    </span>
                    <span class="file__name" v-text="file.name"/>
                </li>
            </ul>
        </div>
    </Fragment>
</template>

<script>
    import { Fragment } from "vue-fragment"
    import Icon         from "../../../Components/General/Icon"
    import Camera       from "./Camera"

    export default {
        name: "ImageUploadWithCamera",
        components: {
            Camera,
            Icon,
            Fragment,
        },
        props: {
            value: { type: Array, required: false, default: () => ([]) },
            placeholder: { type: String, default: "Click to select files" },
            multiple: { type: Boolean, default: true },
            submitUrl: { type: String, required: true },
        },
        data() {
            return {
                showCameraModal: false,
                uploadedFiles: [],
                fileIds: [],
                errorMessage: null,
            }
        },
        computed: {
            defaultImage() {
                return "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAABHNCSVQICAgIfAhkiAAAAAlwSFlzAAABGAAAARgBIE5viAAAABl0RVh0U29mdHdhcmUAd3d3Lmlua3NjYXBlLm9yZ5vuPBoAAAKYSURBVFiF1ZdBaNNgGIbf78/Spu222qZ1TjqxDoQx9NLJ3EGcolPHEGEiYo962MGTepl6EUTRo+wg3hQd7OJAhijCnCI4h8OjoqBFxDGnOyhzadLk86AdW0uaNEuFvaf8yfv97wP58pGfmBkAkJ7IKUY+chEWToHQBF/E4yBx+cuBxFM7hyhe6FokC8YF/8IBgPaC+V7HNGRHAAKf8C94hZpnfnzrdgQAiUiNAAATtnvXOZRaqsxvdkSsX8xMXvN7G81DHz7mdhIwKxEPp9Pp2eIzKjZh6vH3STB3Li88vyH/en/U7PAabCNDWNTV2rppGlj+CkokE3+qQTgAyBbhdHFhC6DW8VwNwv+KuM0R4H/JqQnLZFoWFrW8u80lCUow4C/A0PAoxiZeuvIqwQDuXB1ErLHBP4CBY4dx/OAeV15ZliuGewIIBmQ0JeLVlvkHwMwwCqYrrxCEOknyF+DG3fsYe+a+B25fGUQ86mMPnOzvRV93lzuAQKBiuCeA+nAI9eFQtWW2WnuDaGh4FA+fv3LlDStB3Lp0zt8eyPbtw67MdlfeULAGPRBrbHAcLtVo7fXAzZEHePRiquy+JAlcPzuA1paNtQXo79mNTPvWcgAhYUuqudrtqgdIxqJIxqJVBzkDML8F0Glv9SZJkqAEVh4LCqb1uwzAEjQkLM4C/w4RRLza8Eg4hIaSqakXCnPWwsLR4nrpK/jak5g2ZG4B6AwTrrUr5uRqwglARFFW3DOMwmdd0zanUqn5JV/xt7xU73O5I8Q06hVAkgSSsXVL67xuvEuo8W0ACst9tnOAWCx4DS9VXtenEmq8rTS8IsDP+bkJADOrDV/U9CcJVbVtbluATCZjCIEsEcY9JTNY0/Ij65NqTyXbH/tYuDy65habAAAAAElFTkSuQmCC"
            },
        },
        mounted() {
            if (this.value.length > 0) {
                this.uploadedFiles = [...this.uploadedFiles, ...this.value]
            }
        },
        methods: {
            openCameraModal() {
                this.showCameraModal = true
                document.querySelector("body").classList.add("overflow-hidden")
            },

            closeCameraModal() {
                this.showCameraModal = false
            },

            handleFileSelect(event) {
                const files = event.target.files

                for (const file of files) {
                    this.uploadFile(file)
                }
                this.$emit("input", this.uploadedFiles)
                this.$refs.fileInput.value = ""
            },

            uploadCapturedPhoto(file) {
                this.uploadFile(file)
                this.closeCameraModal()
                this.$emit("input", this.uploadedFiles)
                this.$refs.fileInput.value = ""
            },

            uploadFile(file) {
                const vm = this
                const formData = new FormData()
                formData.append("media", file)
                formData.append("field_name", this.name)

                this.$http.post(this.submitUrl, formData).then(response => {
                    if (response.status === 201) {
                        vm.uploadedFiles.push(response.data.data)
                    }
                }).catch(err => {
                    if (err.response.status === 422) {
                        this.$set(this, "errorMessage", err.response.data.message)
                    }
                })
            },

            getFileImage(file) {
                if (!(file instanceof File || file instanceof Blob)) {
                    if (this.isImage(file.filename)) {
                        return `${file.url}?w=150`
                    }
                }

                if (this.isImage(file.name)) {
                    if (file instanceof File) {
                        return URL.createObjectURL(file)
                    }
                }

                return this.defaultImage
            },

            removeFile(fileIndex) {
                this.uploadedFiles.splice(fileIndex, 1)
                this.$emit("input", this.uploadedFiles)
            },

            isImage(fileName) {
                return (/\.(?=gif|jpg|png|jpeg)/gi).test(fileName)
            },
        },
    }
</script>
