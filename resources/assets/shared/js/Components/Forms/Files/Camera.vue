<template>
    <div v-if="isModalOpen" class="modal file-modal fixed w-full h-full top-0 left-0 flex items-center justify-center">
        <span class="btn-remove btn-remove--absolute !w-8 !h-8 !right-4 !top-4 sm:!top-8 sm:!right-8" style="z-index: 999;" @click="closeModal()">
            <Icon name="close" class="!w-5 !h-5"/>
        </span>
        <div class="modal-container bg-black-100  w-11/12 md:max-w-md mx-auto  shadow-lg z-50 overflow-y-auto flex rounded-md">
            <div class="video-preview mt-auto w-full">
                <video v-show="isCameraOpen && !isPhotoTaken"
                       id="video"
                       ref="video"
                       :height="height"
                       :width="width"
                       autoplay
                       playsinline/>

                <canvas v-show="isPhotoTaken"
                        id="canvas"
                        ref="canvas"
                        :height="height"
                        :width="width"
                        class="w-full"/>

                <div v-if="!isPhotoTaken" class="camera-buttons flex">
                    <button
                        class="flex justify-center items-center font-medium text-black h-full  p-4 cursor-pointer w-1/2"
                        @click.prevent="capture()">
                        <Icon class="!w-8 !h-8" name="camera"/>
                    </button>
                    <button class="flex justify-center items-center font-medium text-black h-full  p-4 cursor-pointer w-1/2"
                            @click.prevent="toggleFacingMode()">
                        <Icon class="!w-8 !h-8" name="switchcamera"/>
                    </button>
                </div>
                <div v-if="isPhotoTaken" class="camera-buttons flex">
                    <button class="flex justify-center items-center font-medium text-black h-full  p-4 cursor-pointer w-1/2"
                            @click.prevent="uploadPhoto()">
                        <Icon name="upload-1" class="!w-5 !h-5"/>
                        <span v-text="trans('general.upload')"/>
                    </button>
                    <button
                        class="flex justify-center items-center font-medium text-black h-full  p-4 cursor-pointer w-1/2"
                        @click.prevent="resetCamera()">
                        <Icon name="camera" class="!w-6 !h-5"/>
                        <span v-text="trans('general.take_again')"/>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Icon from "../../../../../front/js/Components/Common/Icon"

    export default {
        name: "Camera",
        components: {
            Icon,
        },
        props: {
            autoplay: { type: Boolean, default: true },
            width: { type: Number, default: 640 },
            height: { type: Number, default: 480 },
            mirror: { type: Boolean, default: true },
            imageFormat: { type: String, default: "image/jpeg" },
            isModalOpen: { type: Boolean, default: false },
        },
        data() {
            return {
                isCameraOpen: false,
                isPhotoTaken: false,
                file: null,
                fileSize: 0,
                facingMode: "user",
                video: null,
                canvasElement: null,
            }
        },
        watch: {
            isModalOpen: {
                handler() {
                    this.isModalOpen ? this.startStream() : this.stopStream()
                },
            },
        },
        methods: {
            closeModal() {
                this.$emit("close")
                document.querySelector("body").classList.remove("overflow-hidden")
            },
            startStream() {
                if (navigator.mediaDevices === undefined) {
                    navigator.mediaDevices = {}
                }
                if (navigator.mediaDevices.getUserMedia === undefined) {
                    navigator.mediaDevices.getUserMedia = this.legacyGetUserMediaSupport()
                }

                const constraints = (window.constraints = {
                    audio: false,
                    video: {
                        width: { ideal: this.width, max: this.width },
                        height: { ideal: this.height, max: this.height },
                        facingMode: this.facingMode,
                    },
                    aspectRatio: 1,
                })

                navigator
                    .mediaDevices
                    .getUserMedia(constraints)
                    .then(stream => (this.$refs.video.srcObject = stream))
                    .then(() => (this.isCameraOpen = true))
                    .catch((err) => {
                        console.error(err)
                        alert("The browser isn't supported.")
                    })
            },

            stopStream() {
                const tracks = this.$refs.video.srcObject.getTracks()

                tracks.forEach(track => {
                    track.stop()
                })
                this.isCameraOpen = false
                this.isPhotoTaken = false
            },

            toggleCamera() {
                if (this.isCameraOpen) {
                    this.stopStream()
                } else {
                    this.startStream()
                }
            },

            resetCamera() {
                this.stopStream()
                this.startStream()
            },

            toggleFacingMode() {
                this.stopStream()

                this.facingMode = this.facingMode === "user" ? "environment" : "user"

                this.startStream()
            },

            dataURItoFile(dataURI) {
                // convert base64/URLEncoded data component to raw binary data held in a string
                let byteString
                if (dataURI.split(",")[0].indexOf("base64") >= 0) { byteString = atob(dataURI.split(",")[1]) } else {
                    byteString = unescape(dataURI.split(",")[1])
                }

                // separate out the mime component
                const mimeString = dataURI.split(",")[0].split(":")[1].split(";")[0]

                // write the bytes of the string to a typed array
                const ia = new Uint8Array(byteString.length)
                for (let i = 0; i < byteString.length; i++) {
                    ia[i] = byteString.charCodeAt(i)
                }

                return new File([ia], "photo.jpeg", { type: mimeString })
            },

            capture() {
                if (!this.isCameraOpen) {
                    return;
                }
                const context = this.$refs.canvas.getContext("2d")
                context.drawImage(this.$refs.video, 0, 0, this.width, this.height)
                this.previewImage = this.$refs.canvas.toDataURL(this.imageFormat)
                this.file = this.dataURItoFile(this.previewImage)
                this.fileSize = this.file.size
                this.isPhotoTaken = true
                this.isCameraOpen = false
            },

            legacyGetUserMediaSupport() {
                return constraints => {
                    // First get ahold of the legacy getUserMedia, if present
                    const getUserMedia =
                        navigator.getUserMedia ||
                        navigator.webkitGetUserMedia ||
                        navigator.mozGetUserMedia ||
                        navigator.msGetUserMedia ||
                        navigator.oGetUserMedia
                    // Some browsers just don't implement it - return a rejected promise with an error
                    // to keep a consistent interface
                    if (!getUserMedia) {
                        return Promise.reject(
                            new Error("getUserMedia is not implemented in this browser"),
                        )
                    }
                    // Otherwise, wrap the call to the old navigator.getUserMedia with a Promise
                    return new Promise(function(resolve, reject) {
                        getUserMedia.call(navigator, constraints, resolve, reject)
                    })
                }
            },

            uploadPhoto() {
                this.$emit("image-captured", this.file)
                document.querySelector("body").classList.remove("overflow-hidden")
            },
        },
    }
</script>

<style lang="scss">
    .file-modal {
        z-index: 999;
        background: rgba(0, 0, 0, .2);

        .btn {
            z-index: 1000;
        }
    }

    .camera-buttons {
        background: var(--color-white);

        button {
            opacity: .9;

            &:hover {
                opacity: 1;
                color: var(--color-base-2);
            }

            &:last-child {
                background: #e5e5e5;
            }
        }
    }

    .modal-container {
        box-shadow: 0 2px 4px 0 rgba(46, 49, 51, .24);
        min-height: 408px;
    }

    .camera-buttons {
        height: 72px;
    }

    @media (max-width: 768px) {
        .modal-container {
            min-height: 282px;

        }

        .camera-buttons {
            height: 64px;
        }
    }

</style>
