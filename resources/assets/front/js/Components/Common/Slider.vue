<template>
    <div>
        <VueSlickCarousel v-if="images && isArray(images) && images.length"
                          :arrows="false"
                          :dots="true"
                          v-bind="settings">
            <template v-for="(file, index) in images">
                <img v-if="isImage(file.filename)"
                     :key="index"
                     :src="`${file.url}?w=200`"
                     :alt="file.filename"
                     class="h-40 w-40 object-cover">

                <a v-else
                   :key="index"
                   :href="file.url"
                   target="_blank"
                   v-text="file.filename"/>
            </template>
        </VueSlickCarousel>
    </div>
</template>
<script>
    import VueSlickCarousel from "vue-slick-carousel"
    import "vue-slick-carousel/dist/vue-slick-carousel.css"

    export default {
        name: "Slider",

        components: { VueSlickCarousel },

        props: {
            images: { type: Array, required: false, default: () => ([]) },
        },

        data: () => ({
            settings: {
                dots: true,
                dotsClass: "slick-dots custom-dot-class",
                edgeFriction: 0.35,
                infinite: false,
                draggable: false,
                speed: 500,
                slidesToShow: 1,
                slidesToScroll: 1,
            },
        }),

        methods: {
            isImage(fileName) {
                return (/\.(?=gif|jpg|png|jpeg)/gi).test(fileName)
            },
        },
    }
</script>
