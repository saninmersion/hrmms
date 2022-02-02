<template>
    <div class="accordion">
        <header class="bg-grey-2 accordion__title !px-3"
                :class="{'accordion__title accordion--active accordion__trigger_active': visible}"
                @click="open">
            <slot name="accordion-trigger"/>
        </header>

        <transition
            name="accordion"
            @enter="start"
            @after-enter="end"
            @before-leave="start"
            @after-leave="end">
            <div v-show="visible" class="accordion__content !p-0">
                <slot name="accordion-content"/>
            </div>
        </transition>
    </div>
</template>

<script>
    export default {
        name: "AccordionItem",
        props: {},
        inject: ["Accordion"],
        data() {
            return {
                index: null,
            }
        },
        computed: {
            visible() {
                return this.index === this.Accordion.active
            },
        },
        created() {
            this.index = this.Accordion.count++
        },
        methods: {
            open() {
                if (this.visible) {
                    this.Accordion.active = null
                } else {
                    this.Accordion.active = this.index
                }
            },
            start(el) {
                el.style.height = el.scrollHeight + "px"
            },
            end(el) {
                el.style.height = ""
            },
        },
    }
</script>

<style lang="scss" scoped>
    .accordion__item {
        cursor: pointer;
        padding: 10px 20px 10px 40px;
        border-bottom: 1px solid #ebebeb;
        position: relative;
    }

    .accordion__trigger {
        display: flex;
        justify-content: space-between;
    }

    .accordion-enter-active,
    .accordion-leave-active {
        will-change: height, opacity;
        transition: height 0.3s ease, opacity 0.3s ease;
        overflow: hidden;
    }

    .accordion-enter,
    .accordion-leave-to {
        height: 0 !important;
        opacity: 0;
    }
</style>
