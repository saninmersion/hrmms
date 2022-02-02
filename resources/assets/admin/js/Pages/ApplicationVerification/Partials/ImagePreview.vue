<template>
    <slider-light-box v-if="images.length"
                      class="mx-auto cursor-pointer"
                      slider-class="slider-doc"
                      :images="images"/>
</template>

<script>
    import SliderLightBox from "../../../Components/SliderLightBox"

    export default {
        name: "ImagePreview",

        components: {
            SliderLightBox,
        },

        props: {
            images: { type: Array, default: () => [] },
        },
    }
</script>

<style scoped>
    .slider-doc {
        width: 800px!important;
    }

    .slider-doc .slick-slide img {
        width: 800px!important;
        height: auto!important;
    }
</style>
