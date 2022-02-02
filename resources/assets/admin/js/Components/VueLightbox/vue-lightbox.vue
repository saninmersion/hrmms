<template>
    <transition name="fade">
        <div
            v-if="visible"
            class="img-swiper modal"
            @click.self="closeDialog">
            <div
                class="img-wrapper"
                :class="{transition: imgTransitionStatus}"
                :style="imgStyle">
                <img
                    class="img"
                    :src="visibleImgSrc"
                    alt=""
                    draggable="false"
                    @mousedown="handleMouseDown($event)"
                    @mouseup="handleMouseUp($event)"
                    @mousemove="handleMouseMove($event)">
            </div>

            <!-- btns -->
            <div class="btns">
                <div
                    v-if="imgList.length !== 1"
                    class="btn__prev"
                    :class="{disable: imgIndex === 0}"
                    @click="prev">
                    <svg class="icon" aria-hidden="true">
                        <use xlink:href="#icon-prev"/>
                    </svg>
                </div>

                <div
                    v-if="imgList.length !== 1"
                    class="btn__next"
                    :class="{disable: imgIndex === imgList.length - 1}"
                    @click="next">
                    <svg class="icon" aria-hidden="true">
                        <use xlink:href="#icon-next"/>
                    </svg>
                </div>

                <div
                    class="btn__close"
                    @click="closeDialog">
                    <svg class="icon" aria-hidden="true">
                        <use xlink:href="#icon-close"/>
                    </svg>
                    <!--                    <Icon name="close"/>-->
                </div>

                <div class="toolbar-btns">
                    <!-- zoom-in -->
                    <div
                        class="toobar-btn toolbar-btn__zoomin text-white hover:text-primary-500"
                        @click="zoomIn()">
                        <Icon name="zoom-in" class="!w-8 !h-8 !mr-0"/>
                    </div>

                    <!-- zoom-out -->
                    <div
                        class="toobar-btn toolbar-btn__zoomout text-white hover:text-primary-500"
                        @click="zoomOut()">
                        <Icon name="zoom-out" class="!w-8 !h-8 !mr-0"/>
                    </div>

                    <!-- rotate -->
                    <div
                        class="toobar-btn toolbar-btn__rotate"
                        @click="rotate()">
                        <svg class="icon" aria-hidden="true">
                            <use xlink:href="#icon-rotate"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- total -->
            <div
                v-if="imgList.length !== 1"
                class="pagination-total">
                {{ imgIndex + 1 }}/{{ imgTotal }}
            </div>
        </div>
    </transition>
</template>

<script>
    import "./assets/svg/iconfont"
    import Icon from "../General/Icon"

    export default {
        name: "VueLightbox",
        components: { Icon },
        props: {
            imgs: {
                type: [Array, String],
                default: () => [],
            },
            visible: {
                type: Boolean,
            },
            index: {
                type: Number,
                default: 0,
            },
        },
        data() {
            return {
                scale: 1,
                rotateDeg: 0,
                imgIndex: 0,
                imgTransitionStatus: true,
                top: 0,
                left: 0,
                lastX: 0,
                lastY: 0,
                isDraging: false,
            }
        },
        watch: {
            visible(visible) {
                if (visible === true) {
                    this.init()
                }
            },
        },
        computed: {
            imgList() {
                if (Array.isArray(this.imgs)) {
                    return this.imgs
                } else {
                    return [this.imgs]
                }
            },
            visibleImgSrc() {
                return this.imgList[this.imgIndex]
            },
            imgTotal() {
                return this.imgList.length || 0
            },
            imgStyle: {
                get() {
                    return {
                        transform: `translate(-50%, -50%)
              scale(${this.scale})
              rotate(-${this.rotateDeg}deg)`,
                        top: `calc(50% + ${this.top}px)`,
                        left: `calc(50% + ${this.left}px)`,
                    }
                },
            },
        },
        methods: {
            checkBtn(btn) {
                if (btn === 0) return true
                return false
            },
            handleMouseDown(e) {
                if (!this.checkBtn(e.button)) return
                this.lastX = e.clientX
                this.lastY = e.clientY
                this.isDraging = true
                e.stopPropagation()
            },
            handleMouseUp(e) {
                if (!this.checkBtn(e.button)) return
                this.isDraging = false
                this.lastX = this.lastY = 0
            },
            handleMouseMove(e) {
                if (!this.checkBtn(e.button)) return
                if (this.isDraging) {
                    this.top = this.top - this.lastY + e.clientY
                    this.left = this.left - this.lastX + e.clientX
                    this.lastX = e.clientX
                    this.lastY = e.clientY
                }
                e.stopPropagation()
            },
            zoomIn(e) {
                this.scale += 0.25
            },
            zoomOut() {
                if (this.scale !== 0) {
                    this.scale -= 0.25
                }
            },
            rotate() {
                this.rotateDeg += 90
            },
            next() {
                if (this.imgIndex === this.imgList.length - 1) return
                this.reset()
                this.imgIndex += 1
                setTimeout(() => {
                    this.imgTransitionStatus = !this.imgTransitionStatus
                }, 0)
            },
            prev() {
                if (this.imgIndex === 0) return
                this.reset()
                this.imgIndex -= 1
                setTimeout(() => {
                    this.imgTransitionStatus = !this.imgTransitionStatus
                }, 0)
            },
            reset() {
                this.imgTransitionStatus = !this.imgTransitionStatus
                this.scale = 1
                this.rotateDeg = 0
            },
            closeDialog() {
                this.$emit("hide")
                document.querySelector("body").classList.remove("overflow-hidden");
            },
            init() {
                this.imgIndex = this.index
                this.imgTransitionStatus = true
                this.scale = 1
                this.rotateDeg = 0
                this.top = 0
                this.left = 0
                this.isDraging = false
            },
        },
    }
</script>

<style scoped lang="scss">
    .btn__close .icon {
        width: 25px;
        height: 25px;
    }

    .icon {
        width: 18px;
        height: 18px;
        vertical-align: -0.15em;
        fill: currentColor;
        overflow: hidden;
    }

    .fade-enter-active,
    .fade-leave-active {
        transition: all 0.3s ease;
    }

    .fade-enter,
    .fade-leave-to {
        opacity: 0;
    }

    .img-swiper {
        position: relative;
    }

    .modal {
        z-index: 9998;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.7);
    }

    .img-wrapper {
        margin: 0;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50% -50%);
        box-shadow: #555 0px 5px 20px 2px;
        cursor: move;
    }

    .img-wrapper.transition {
        transition: transform 0.3s ease-in-out;
    }

    .img {
        max-width: 80vw;
        max-height: 80vh;
        vertical-align: middle;
        position: relative;
    }

    .btn__prev,
    .btn__next,
    .btn__close{
        position: absolute;
        cursor: pointer;
        font-size: 60px;
        color: #fff;
        transition: 0.15s linear;
        opacity:  .8;

        &:hover{
            opacity: 1;
        }

        &.disable,&.disable:hover{
            cursor: default;
            opacity: 0.3;
        }
    }

    .btn__prev,
    .btn__next {
        display: flex;
        align-items: center;
        justify-content: center;
        top:  50%;
        width:  24px;
        height:  24px;
    }

    .btn__next,  .btn__close {
        right: 24px;
    }

    .btn__prev {
        left: 24px;
    }

    .btn__close {
        top: 24px;
    }

    .pagination-total {
        position: absolute;
        font-size: 16px;
        top: 16px;
        left: 16px;
        color: #fff;
    }

    .toolbar-btns {
        user-select: none;
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translate(-50%);
        background: rgba(45, 45, 44, .8);
        border-radius: 4px;
        border-bottom-right-radius: 0;
        border-bottom-left-radius: 0;
        padding: 6px 24px 0;
    }

    .toobar-btn {
        cursor: pointer;
        display: inline-block;
        padding: 6px;
    }

    .toobar-btn .icon {
        width: 32px;
        height: 32px;
        fill: #fff;
    }

    .toobar-btn:hover .icon {
        fill: #54b4ee;
    }
</style>
