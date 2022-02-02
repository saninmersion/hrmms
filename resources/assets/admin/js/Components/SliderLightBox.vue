<template>
    <div class="relative">
        <slider :images="images" :class="sliderClass" @imageClicked="showLightBox"/>
        <vue-light-box :imgs="lightBoxImage.urls" :visible="lightBoxImage.visible" :index="lightBoxImage.index" @hide="hideLightBox"/>
    </div>
</template>

<script>
    import Slider      from "../Components/Slider"
    import VueLightBox from "../Components/VueLightbox"

    export default {
        name: "SliderLightBox",
        components: { Slider, VueLightBox },
        props: {
            images: { type: Array, required: false, default: () => ([]) },
            sliderClass: { type: String, required: false, default: "" },
        },
        data() {
            return {
                lightBoxImage: {
                    visible: false,
                    index: 0,
                    urls: [],
                },
            }
        },
        watch: {
            images: {
                handler(currentImages) {
                    if (!this.isArray(currentImages)) {
                        return
                    }

                    const urls = Object.values(currentImages).map(file => `${file.url}?w=1000`)
                    this.$set(this.lightBoxImage, "urls", urls)
                },
                immediate: true,
                deep: true,
            },
        },
        methods: {
            showLightBox(index) {
                this.$set(this.lightBoxImage, "visible", true)
                this.$set(this.lightBoxImage, "index", index)
            },
            hideLightBox() {
                this.$set(this.lightBoxImage, "visible", false)
                this.$set(this.lightBoxImage, "index", 0)
            },
        },
    }
</script>

<style scoped>

</style>
